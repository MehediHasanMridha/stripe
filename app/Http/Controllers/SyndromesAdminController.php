<?php

namespace App\Http\Controllers;

use App\Helpers\Tri;
use App\Models\Symptome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyndromesAdminController extends Controller
{

    /* Liste des syndromes */
    public function index(){
        $lang=App::getLocale();
        $syndromes = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_syndromes = DB::select("SELECT * FROM syndrome");
        foreach ($all_syndromes as $syndrome) {
            $default_traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0]->nom;
            $syndrome->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($syndrome->id == $traduction->idSyndrome) $syndrome->nom = $traduction->nom;
            }

            array_push($syndromes, $syndrome);
        }
        $syndromes = collect($syndromes)->sortBy('nom')->toArray();
        return view("admin.syndromes.index", compact("syndromes"));
    }


    /* Recherche dans les syndromes */
    public function search(Request $request) {
        $lang=App::getLocale();

        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = $request->input('search');
        $search = $this->retirerAccents($search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('syndromesAdmin.index', [$lang]));

        $syndromes = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_syndromes = DB::select("SELECT * FROM syndrome");
        foreach ($all_syndromes as $syndrome) {
            $default_traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idsyndrome', $syndrome->id)[0]->nom;
            $syndrome->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($syndrome->id == $traduction->idSyndrome) $syndrome->nom = $traduction->nom;
            }

            $nom_sans_accents = $this->retirerAccents( $syndrome->nom);
            if (stristr($nom_sans_accents, $search)) array_push($syndromes, $syndrome);
        }

        // Retourne la vue liste.blade.php du dossier ingrédients avec comme arguments les ingrédients
        return view('admin.syndromes.index', compact('syndromes'));
    }



    // Ensemble des fonctions utilisées dans show permettant d'afficher les éléments
    public function show(int $id_syndrome){
        $lang=App::getLocale();

        // On récupère toutes les information dans la BDD grace a l'ID du symptome choisis
        $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$id_syndrome])[0];
        $traduction = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $id_syndrome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $id_syndrome)[0];
        else $traduction = $traduction[0];

        $syndrome->nom = $traduction->nom;

        $syndrome_detail = DB::select("SELECT idSymptome, score FROM syndrome_detail WHERE idSyndrome = ? AND score > 0", [$id_syndrome]);

        $symptomes = collect($syndrome_detail)->map(function($detail){
            $symptome=Symptome::find($detail->idSymptome);
            $symptome->score=$detail->score;
            return $symptome;
        });
        $symptomes = Tri::symptomes($symptomes);
        return view('admin.syndromes.show', compact('syndrome', "symptomes") );
    }

    // Fonction permettant d'éditer un syndrome déja existant
    public function edit($id_syndrome) {
        $lang=App::getLocale();

        $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$id_syndrome])[0];
        $traduction = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $id_syndrome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $id_syndrome)[0];
        else $traduction = $traduction[0];

        $syndrome->nom = $traduction->nom;



        $syndrome_detail = DB::select("SELECT idSymptome, score FROM syndrome_detail WHERE idSyndrome = ? AND score > 0", [$id_syndrome]);
        $symptomes = collect($syndrome_detail)->map(function($detail){
            $symptome=Symptome::find($detail->idSymptome);
            $symptome->score=$detail->score;
            return $symptome;
        });




        /* Tableau contenant tous les symptômes */
        $donnees_symptomes = [];
        $donnees_symptomes = DB::select("SELECT * FROM symptome");
        foreach ($donnees_symptomes as $key => $value) {
            $donnees_symptome = DB::select("SELECT * FROM symptome WHERE id = ?", [$value->id])[0];
            $traduction_donnees_symptome = $this->getTraductionById($lang, 'symptome_traduction', 'idSymptome', $donnees_symptome->id);

            if (empty($traduction_donnees_symptome)) $traduction_donnees_symptome = $this->getTraductionById('fr', 'symptome_traduction', 'idSymptome', $donnees_symptome->id)[0];
            else $traduction_donnees_symptome = $traduction_donnees_symptome[0];

            if(isset($traduction_symptome)){
                $donnees_symptome->nom = $traduction_symptome->nom;
            }
            array_push($donnees_symptomes,$donnees_symptome);
        }
        $donnees_symptomes = Symptome::getAllSymptomes();

         $symptomes = Tri::symptomes($symptomes);
        return view('admin.syndromes.edit', compact("syndrome", "symptomes", "donnees_symptomes"));
    }








    public function update($id_syndrome, Request $request) {
        $lang=App::getLocale();

        // On demande a l'utilisateur d'entrer le nom du nouvel ingrédient
        $data = $request->input();
        $request->validate([
            'editSyndrome' => 'required',
            'imageSyndrome' => 'sometimes|image|max:8000'
        ]);

        $nom_syndrome = $request->input("editSyndrome");

        DB::update('UPDATE syndrome_traduction SET nom = ? WHERE idSyndrome = ? AND langue = ?', [$nom_syndrome, $id_syndrome, $lang]);

        // Modification de l'image
        $image_existante = DB::select("SELECT image FROM syndrome WHERE id = ?", [$id_syndrome])[0]->image;

        // On envoie l'image le ou elle doit être stockée
        if ($request->hasFile('imageSyndrome')) {
            $image = $request->file('imageSyndrome');
            $imageName = time(). '.' . $image->getClientOriginalExtension();
            $destinationImage = public_path('storage/images/syndromes', 'public');
            $image->move($destinationImage, $imageName);
            $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
            $imagePath = str_replace("\\","/",$imagePath);

            if(file_exists($image_existante))
                unlink($image_existante);
        }
        else {
            $imagePath = $image_existante;
        }

        DB::update('UPDATE syndrome SET image = ? WHERE id = ?', [$imagePath, $id_syndrome]);

        // Modification des symptômes
        $donnees = $request->input();
        unset($donnees['editSyndrome']);
        unset($donnees['_token']);
        unset($donnees['_method']);

        $symptomes_existants = DB::select("SELECT idSymptome FROM syndrome_detail WHERE idSyndrome = ?", [$id_syndrome]);

        foreach ($symptomes_existants as $symptome) {
            if (!array_key_exists('editSymptome__' . $symptome->idSymptome, $donnees)) {
                DB::delete("DELETE FROM syndrome_detail WHERE idSyndrome = ? AND idSymptome = ?", [$id_syndrome, $symptome->idSymptome]);
            }
        }





        foreach ($donnees as $key => $donnee) {
            $donnees_symptomes[$key] = $donnee;
        }
        if(isset($donnees_symptomes)){
        foreach ($donnees_symptomes as $key => $symptome) {
            if (stristr($key, 'editSymptome__')) {
                $id_symptome = explode('__', $key)[1];
                $score_symptome = $donnees['editSymptomeScore__' . $id_symptome];

                DB::update('UPDATE syndrome_detail SET score = ? WHERE idSymptome = ? AND idSyndrome = ?', [$score_symptome, $id_symptome, $id_syndrome]);
            }else if (stristr($key, 'addSymptome_')) {
                $id_addSymptome = explode('_', $key)[1];
                $id_symptome = $symptome;
                $score_addSymptome = $donnees['addSymptomeScore_' . $id_addSymptome];

                unset($donnees['addSymptomeScore_' . $id_addSymptome]);

                if($id_symptome != null){
                DB::insert("INSERT INTO syndrome_detail (idSyndrome, idSymptome, score) VALUES (?, ?, ?)", [$id_syndrome, $id_symptome, $score_addSymptome]);
            }
            }
        }
        }



        return redirect(route('syndromesAdmin.show', [$id_syndrome]));
    }

    /* Récupère les traduction par langue */
    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM syndrome_traduction WHERE langue = ?", [$lang]);
    }

    /* Récupère les traduction par ID */
    private function getTraductionById(string $lang, string $table, string $colonne, int $id): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ? AND ".$colonne." = ?", [$lang, $id]);
    }

    public function create(){
        $donnees_symptomes =Symptome::getAllSymptomes();
        return view('admin.syndromes.create', compact('donnees_symptomes'));
    }



    public function store(Request $request){
        $lang=App::getLocale();

        // On demande à l'utilisateur d'entrer le nom du nouveau symptome
        $data = $request->input();
        $request->validate([
            'nouveauSyndrome' => 'required',
            'imageSyndrome' => 'sometimes|image|max:8000'
        ]);

        if(DB::select('SELECT * FROM syndrome WHERE nom = ?', [$data['nouveauSyndrome']]) != null ){
            return back()->with('erreur', 'syndromes_create_erreur_doublon');
        }else{
            // On envoie l'image le ou elle doit être stockée
            if ($request->hasFile('imageSyndrome')) {
                $image = $request->file('imageSyndrome');
                $imageName = time(). '.' . $image->getClientOriginalExtension();
                $destinationImage = public_path('storage/images/syndromes', 'public');
                $image->move($destinationImage, $imageName);
                $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
                $imagePath = str_replace("\\","/",$imagePath);
             }
             else {
                 $imagePath = null;
             }

            // On l'insert dans la table symptome grace a ce nom
            DB::insert("INSERT INTO syndrome (nom, image) VALUES (?, ?)", [$data['nouveauSyndrome'], $imagePath]);

            $id_nouveau_syndrome = DB::select("SElECT id FROM syndrome WHERE nom = ?", [$data['nouveauSyndrome']])[0]->id;
            DB::insert("INSERT INTO syndrome_traduction (langue, idSyndrome, nom) VALUES (?, ?, ?)", [$lang, $id_nouveau_syndrome, $data['nouveauSyndrome']]);

            unset($data["nouveauSyndrome"]);
            unset($data["_token"]);


              foreach ($data as $key => $donnee) {
                $donnees_symptomes[$key] = $donnee;
            }

            if(isset($donnees_symptomes)){
            foreach ($donnees_symptomes as $key => $symptome) {
                if (stristr($key, 'addSymptome_')) {
                    $id_addSymptome = explode('_', $key)[1];
                    $id_symptome = $symptome;
                    $score_addSymptome = $data['addSymptomeScore_' . $id_addSymptome];

                    unset($data['addSymptomeScore_' . $id_addSymptome]);
                    if($id_symptome != null){
                    DB::insert("INSERT INTO syndrome_detail (idSyndrome, idSymptome, score) VALUES (?, ?, ?)", [$id_nouveau_syndrome, $id_symptome, $score_addSymptome]);
                    }
                }
            }}



            return redirect(route('syndromesAdmin.index'));
        }
    }


    public function destroy($id_syndrome){
        $chemin = DB::select('SELECT image FROM syndrome WHERE id = ?', [$id_syndrome])[0]->image;
        File::delete($chemin);

        DB::delete('DELETE FROM syndrome_detail WHERE idSyndrome = ?', [$id_syndrome]);
        DB::delete('DELETE FROM syndrome_traduction WHERE idSyndrome = ?', [$id_syndrome]);
        DB::delete('DELETE FROM syndrome WHERE id = ?', [$id_syndrome]);

        return redirect(route('syndromesAdmin.index'));
    }

    /* Retire les accents d'une chaîne de caractère */
    public function retirerAccents($varMaChaine)
        {
            $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'œ');
            //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
            $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'oe');

            $varMaChaine = str_replace($search, $replace, $varMaChaine);
            return $varMaChaine; //On retourne le résultat
        }
}
