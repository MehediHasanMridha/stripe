<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ConseillersController extends Controller
{

    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue index.blade.php du dossier conseillers
     *
     * @return Application|Factory|View
     */
    public function index() {
        return view('conseillers.index');
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère la liste de tous les départements différents, et de toutes les régions différentes de la base de données
     * Retourne la vue listeForm.blade.php du dossier conseillers
     *
     * @return Application|Factory|View
     */
    public function listeForm() {
        // Récupère tous les département différents stocké dans la table praticiens
        $departements = DB::select('SELECT DISTINCT departement FROM praticiens');
        // Récupère toutes les régions différentes stocké dans la table praticien
        $regions = DB::select('SELECT DISTINCT region FROM praticiens');

        return view('conseillers.listeForm', compact('departements', 'regions'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère la liste des praticiens qui sont dans la zone recherché
     * Retourne la vue liste.blade.php du dossier conseillers
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function liste(Request $request) {
        // Récupère la valeur du champs autre
        $autre = $request->input('autre');
        // Construit la variable '%(valeur de autre)%' pour la requete sql
        $search = '%' . $autre . '%';

        // Récupère la valeur du champs departement
        $departement = $request->input('departement');
        // Récupère la valeur du champs region
        $region = $request->input('region');

        $zones = array();
        /* Si la valeur de autre n'est pas null
        on recherche les praticiens qui sont dans la zone indiqué dans autre */
        if ($autre != null) {
            $zones_by_categories = DB::select('SELECT DISTINCT ville, CP FROM praticiens WHERE nom LIKE ? OR adresse LIKE ? OR ville LIKE ? OR CP LIKE ?', [$search, $search, $search, $search]);
            foreach ($zones_by_categories as $ville) {
                $key = $ville->CP . ' - ' . $ville->ville;
                $ville = $ville->ville;
                $zones[$key] = DB::select("SELECT * FROM praticiens WHERE ville = ?", [$ville]);
            }

            $zone_searched = $autre;
        }

        /* Sinon si la valeur de departement n'est pas null
        on recherche les praticiens qui sont dans le département indiqué */
        elseif ($departement != 'Département') {
            $zones_by_categories = DB::select('SELECT DISTINCT CP, ville FROM praticiens WHERE departement = ? ORDER BY CP', [$departement]);
            foreach ($zones_by_categories as $CP) {
                $key = $CP->CP . ' - ' . $CP->ville;
                $CP = $CP->CP;
                $zones[$key] = DB::select("SELECT * FROM praticiens WHERE CP = ?", [$CP]);
            }

            $zone_searched = $departement;
        }

        /* Sinon si la valeur de region n'est pas null
        on recherche les praticiens qui sont dans la région indiqué */
        elseif ($region != 'Région') {
            $zones_by_categories = DB::select('SELECT DISTINCT departement, CP FROM praticiens WHERE region = ? ORDER BY departement', [$region]);
            foreach ($zones_by_categories as $departement) {
                $key = substr($departement->CP, 0, 2) . ' - ' . $departement->departement;
                $departement = $departement->departement;
                $zones[$key] = DB::select("SELECT * FROM praticiens WHERE departement = ?", [$departement]);
            }

            $zone_searched = $region;
        }

        // Sinon on retourne tous les praticiens
        else {
            $zones_by_categories = DB::select('SELECT DISTINCT region, departement, CP FROM praticiens ORDER BY region');
            foreach ($zones_by_categories as $departement) {
                $key = $departement->region . ' - ' . $departement->departement . ' (' . substr($departement->CP, 0, 2) . ')';
                $departement = $departement->departement;
                $zones[$key] = DB::select("SELECT * FROM praticiens WHERE departement = ?", [$departement]);
            }

            $zone_searched = "Dans toute la France";
        }

        return view('conseillers.liste', compact('zones', 'zone_searched'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère la localisation de tous les praticiens
     * Retourne la vue localisation.blade.php du dossier conseillers
     *
     * @return Application|Factory|View
     */
    public function localisation() {
        // Défini les coordonnées du centre de la France
        $coordonnees = [46.227638, 2.213749];
        // Initialise le zoom à 5.7 pour y voir toute la France lors de la génération de la map
        $zoom = 5.7;

        $markers = DB::select('SELECT id, nom, coordonnees FROM praticiens');
        foreach ($markers as $marker) {
            $marker_coordonnees = json_decode($marker->coordonnees);
            $marker->coordonnees = $marker_coordonnees;
        }

        return view('conseillers.localisation', compact( 'coordonnees', 'zoom', 'markers'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère les coordonnées du département recherché
     * Récupère la localisation de tous les praticiens
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function localisationCP(Request $request) {
        // test si la valeur du champ codePostal n'est pas vide, et possède strictement 5 caractères
        $code_postal = $request->validate([
            'codePostal' => 'required|min:5|max:5'
        ]);

        // Appelle l'API geocoder en appelant une requête qui retournera les coordonnées du département recherché
        $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=AIzaSyD982868RehTHPFdeV_g2tRmoL3f4IYPOs";
        $adresse = ', ' . $code_postal['codePostal'] . ', France';

        // Récupère la réponse de la requête
        $query = sprintf($geocoder, urlencode(utf8_encode($adresse)));
        $result = json_decode(file_get_contents($query));
        $json = $result->results[0];

        // Défini les coordonnées du département
        $coordonnees = [
            (string) $json->geometry->location->lat,
            (string) $json->geometry->location->lng
        ];
        // Initialise le zoom à 10 pour y voir un zoom sur le département en question lors de la génération de la map
        $zoom = 10;

        $markers = DB::select('SELECT id, nom, coordonnees FROM praticiens');
        foreach ($markers as $marker) {
            $marker_coordonnees = json_decode($marker->coordonnees);
            $marker->coordonnees = $marker_coordonnees;
        }

        return view('conseillers.localisation', compact( 'coordonnees', 'zoom', 'markers'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère le code et l'id de tous les praticiens
     * Retourne la vue code.blade.php du dossier conseillers
     *
     * @return Application|Factory|View
     */
    public function code() {
        $codes = DB::select("SELECT code, id FROM praticiens");

        return view('conseillers.code', compact( 'codes'));
    }


    /**
     * Récupère l'id choisi par l'utilisateur et redirige vers la fonction show
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function codeSent(Request $request) {
        // test si la valuer de code n'est pas vide
        $id = $request->validate([
            'code' => 'required'
        ]);

        $id = $id['code'];

        return redirect(route('conseillers.show', [$id]));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère les informations sur le praticien qui possède un id égale à celui donnée
     * Retourne la vue show.blade.php du dossier conseillers
     *

     * @param int $id_praticien
     * @return Application|Factory|View
     */
    public function show(int $id_praticien) {
        // Sélectionne toutes les informations du conseillers stocké dans la table praticiens où l'id est égale à celui donnée en paramètre
        $praticien = DB::select('SELECT * FROM praticiens WHERE id = ?', [$id_praticien]);

        $coordonnees = json_decode($praticien[0]->coordonnees);
        $praticien[0]->coordonnees = $coordonnees;

        // Retourne la vue show.blade.php du dossier conseillers
        return view('conseillers.show', compact('praticien'));
    }
}
