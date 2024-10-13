<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Translation\Translator;

class ConseillersAdminController extends Controller
{
    // Fonction qui permet l'affichage de l'index quand on clique sur le bouton conseillers
    public function index()
    {
        $conseillers = DB::select("SELECT * FROM praticiens ORDER BY nom");
        return view("admin.conseillersAdmin.index", compact( "conseillers"));
    }

    // Fonction permettant d'effectuer une recherche dans la listes
    public function search(Request $request)
    {
        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = '%' . $request->input('search') . '%';

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($request->input('search')) == 0) return redirect(route('conseillersAdmin.index'));

        // Sélectionne toutes les données des lignes où il y a la valeur inscrite dans le input search de la table praticiens
        $conseillers = DB::select("SELECT DISTINCT * FROM praticiens WHERE nom LIKE ? OR code LIKE ?", [$search, $search]);

        // Retourne la vue liste.blade.php du dossier praticiens avec comme arguments les praticiens
        return view('admin.conseillersAdmin.index', compact( 'conseillers'));
    }

    // Fonction qui ramène l'utilisateur vers la page de création d'un praticiens quand on clique sur le +
    public function create()
    {
        return view('admin.conseillersAdmin.create');
    }

    public function store(Request $request)
    {
        $isOnRDV = $request->has('rdv');

        $donnees = $request->input();

        $code = substr($donnees['cp'], 0, 2) . substr($donnees['nom'], 0, 1) . substr($donnees['prenom'], 0, 1) . substr($donnees['cp'], -2);
        $nom = $donnees['nom'] . ' ' . $donnees['prenom'];

        // Appelle l'API geocoder en appelant une requête qui retournera les coordonnées du département recherché
        $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=AIzaSyCdWATFoJQKVh-hGD_BFYEYJ3Fy9_CoVcE";
        $adresse = $donnees['adresse'] . ', ' . $donnees['cp'] . ', ' . $donnees['ville'] . ', France';

        // Récupère la réponse de la requête
        $query = sprintf($geocoder, urlencode(utf8_encode($adresse)));
        $result = json_decode(file_get_contents($query));


        if(isset($result->results[0])){
            $json = $result->results[0];

            // Défini les coordonnées du département
            $coordonnees_json = [
                'latitude' => (string) $json->geometry->location->lat,
                'longitude' => (string) $json->geometry->location->lng
            ];

            $coordonnees_json = json_encode($coordonnees_json);
        }else{
            $coordonnees_json = null;
        }

        // Si on est sur RDV supprime la valeur précédente stocker dans horaires
        if ($isOnRDV) {
            $donnees['horaires'] = "<p>Sur rendez-vous uniquement.</p>";
        }

        DB::insert("INSERT INTO praticiens (code, nom, mobile, email, adresse, ville, CP, departement, region, coordonnees, horaires, adresse2, ville2, CP2, departement2, region2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$code, $nom, $donnees['tel'], $donnees['email'], $donnees['adresse'], $donnees['ville'], (int)$donnees['cp'], $donnees['departement'], $donnees['region'], $coordonnees_json, $donnees['horaires'], $donnees['adresse2'], $donnees['ville2'], (int)$donnees['cp2'], $donnees['departement2'], $donnees['region2']]);

        return redirect(route('conseillersAdmin.index'));
    }

    // Fonction permettant l'affichage d'un conseillers
    public function show(int $id_praticien) {
        $praticien = DB::select('SELECT * FROM praticiens WHERE id = ?', [$id_praticien]);

        $coordonnees = json_decode($praticien[0]->coordonnees);
        $praticien[0]->coordonnees = $coordonnees;

        // Retourne la vue show.blade.php du dossier conseillers
        return view('admin.conseillersAdmin.show', compact( 'praticien'));
    }

    public function edit(int $id_praticien) {
        App::setLocale(Session::get('locale'));

        $praticien = DB::select("SELECT * FROM praticiens WHERE id = ?", [$id_praticien])[0];

        return view('admin.conseillersAdmin.edit', compact('praticien'));
    }

    public function update($id_praticien, Request $request) {
        App::setLocale(Session::get('locale'));

        $isOnRDV = $request->has('rdv');

        $donnees = $request->input();
        /*$request->validate([
            'nouvelIngredient' => 'required',
            'nouvelIngredientChinois' => 'required',
            'nouvelIngredientLatin' => 'required',
            'imageIngredient' => 'sometimes|image|max:8000'
        ]);*/

        // Si on est sur RDV supprime la valeur précédente stocker dans horaires
        if ($isOnRDV) {
            $donnees['horaires'] = "<p>Sur rendez-vous uniquement.</p>";
        }

        DB::update('UPDATE praticiens SET nom = ?, mobile = ?, email = ?, adresse = ?, ville = ?, CP = ?, departement = ?, region = ?, adresse2 = ?, ville2 = ?, CP2 = ?, departement2 = ?, region2 = ?, horaires = ? WHERE id = ?', [$donnees['nom'], $donnees['tel'], $donnees['email'], $donnees['adresse'], $donnees['ville'], (int)$donnees['cp'], $donnees['departement'], $donnees['region'], $donnees['adresse2'], $donnees['ville2'], (int)$donnees['cp2'], $donnees['departement2'], $donnees['region2'], $donnees['horaires'], $id_praticien]);
        return redirect(route('conseillersAdmin.show', [$id_praticien]));
    }

    // Fonction qui détruit l'ingrédient et toutes les liaisons avec ce dernier
    public function destroy($id_praticien) {
        // On cherche l'utilisateur liée au praticien dont ont veut supprimer la fiche
        $userId = DB::select('SELECT id FROM users WHERE praticien_id = ?', [$id_praticien]);

        // On met a jour la base de données
        DB::delete('DELETE FROM praticiens WHERE id = ?', [$id_praticien]);

        // Supprime le numéro de fiche praticien liée à un praticien
        // Ne fait rien dans le cas d'une fiche créer par un administrateur
        if ($userId) {
            $userId = $userId[0]->id;
            DB::update('UPDATE users SET praticien_id = ? WHERE id = ?', [null, $userId]);
        }

        return redirect(route('conseillersAdmin.index'));
    }
}
