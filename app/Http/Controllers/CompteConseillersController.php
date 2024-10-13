<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CompteConseillersController extends Controller
{
    public function index(){
        return redirect(route('compte.conseillers.create'));
    }

    // Fonction qui ramène le praticien vers la page de création de sa fiche praticien
    public function create()
    {
        $userId = Auth::user()->id;
        $praticienId = DB::select('SELECT praticien_id FROM users WHERE id = ?', [$userId]);
        $praticienId = $praticienId[0]->praticien_id;

        if ($praticienId) {
            return redirect(route('compte.conseillers.show'));
        } else {
            return view('compte.conseillers.create');
        }
    }

    public function store(Request $request)
    {
        $userId = Auth::user()->id;
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

        $insertId = DB::table('praticiens')->insertGetId(['code' => $code, 'nom' => $nom, 'mobile' =>  $donnees['tel'], 'email' => $donnees['email'], 'adresse' => $donnees['adresse'], 'ville' => $donnees['ville'], 'CP' => (int)$donnees['cp']?:$donnees['cp'], 'departement' => $donnees['departement'], 'region' => $donnees['region'], 'coordonnees' => $coordonnees_json, 'horaires' => $donnees['horaires'], 'adresse2' => $donnees['adresse2'], 'ville2' => $donnees['ville2'], 'CP2' => (int)$donnees['cp2']?:$donnees['cp2'], 'departement2' => $donnees['departement2'], 'region2' => $donnees['region2']]);
        DB::update('UPDATE users SET praticien_id = ? WHERE id = ?', [$insertId, $userId]);

        return redirect(route('compte.conseillers.show'));
    }

    public function show() {
        // Récupère la fiche praticiens correspondant au praticiens courant
        $userId = Auth::user()->id;
        $praticienId = DB::select('SELECT praticien_id FROM users WHERE id = ?', [$userId]);
        $praticienId = $praticienId[0]->praticien_id;

        if ($praticienId) {
            $praticien = DB::select('SELECT * FROM praticiens WHERE id = ?', [$praticienId]);
            $praticien = $praticien[0];

            $coordonnees = json_decode($praticien->coordonnees);
            $praticien->coordonnees = $coordonnees;

            return view('compte.conseillers.show', compact( 'praticien'));
        } else {
            return redirect(route('compte.conseillers.create'));
        }
    }

    public function edit() {
        App::setLocale(Session::get('locale'));

        $userId = Auth::user()->id;
        $praticienId = DB::select('SELECT praticien_id FROM users WHERE id = ?', [$userId]);
        $praticienId = $praticienId[0]->praticien_id;

        if ($praticienId) {
            $praticien = DB::select('SELECT * FROM praticiens WHERE id = ?', [$praticienId]);
            $praticien = $praticien[0];

            $coordonnees = json_decode($praticien->coordonnees);
            $praticien->coordonnees = $coordonnees;

            return view('compte.conseillers.edit', compact('praticien'));
        } else {
            return redirect(route('compte.conseillers.create'));
        }
    }

    public function update(Request $request) {
        $userId = Auth::user()->id;
        $praticienId = DB::select('SELECT praticien_id FROM users WHERE id = ?', [$userId]);
        $praticienId = $praticienId[0]->praticien_id;

        $isOnRDV = $request->has('rdv');

        $donnees = $request->input();

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

        DB::update('UPDATE praticiens SET nom = ?, mobile = ?, email = ?, adresse = ?, ville = ?, CP = ?, departement = ?, region = ?, adresse2 = ?, ville2 = ?, CP2 = ?, departement2 = ?, region2 = ?, horaires = ? WHERE id = ?', [$donnees['nom'], $donnees['tel'], $donnees['email'], $donnees['adresse'], $donnees['ville'], (int)$donnees['cp']?:$donnees['cp'], $donnees['departement'], $donnees['region'], $donnees['adresse2'], $donnees['ville2'], (int)$donnees['cp2']?:$donnees['cp2'], $donnees['departement2'], $donnees['region2'], $donnees['horaires'], $praticienId]);

        return redirect()->route("compte.conseillers.show");
    }

    // Fonction qui détruit la fiche praticiens et toutes les liaisons avec ce dernier
    public function destroy() {
        App::setLocale(Session::get('locale'));

        $userId = Auth::user()->id;
        $praticienId = DB::select('SELECT praticien_id FROM users WHERE id = ?', [$userId]);
        $praticienId = $praticienId[0]->praticien_id;
        DB::delete('DELETE FROM praticiens WHERE id = ?', [$praticienId]);
        DB::update('UPDATE users SET praticien_id = ? WHERE id = ?', [null, $userId]);

        return redirect(route('compte.index'));
    }
}
