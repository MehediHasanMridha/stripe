<?php

namespace App\Http\Controllers;

use App\Helpers\Tri;
use App\Models\Symptome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;


class FormulesAdminController extends Controller
{

    /* Liste des formules */
    public function index( $tri = null){
        $lang=App::getLocale();
        $formules = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_formules = DB::select("SELECT * FROM formule");
        foreach ($all_formules as $formule) {
            $default_traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0]->nom_langue;
            $formule->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($formule->id == $traduction->idFormule) $formule->nom = $traduction->nom_langue;
            }

            array_push($formules, $formule);
        }
        if($tri == "nom_chinois"){
            $formules = collect($formules)->sortBy('nom_chinois')->toArray();
        }else if($tri == "nom"){
            $formules = collect($formules)->sortBy('nom')->toArray();
        }
        else
        {
            $formules = collect($formules)->sortBy('id')->toArray();
        }

        return view("admin.formules.index", compact("formules"));
    }

    /* Recherche dans les formules */
    public function search(Request $request) {
        $lang=App::getLocale();

        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = $request->input('search');
        $search = $this->retirerAccents($search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('formulesAdmin.index'));

        $formules = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_formules = DB::select("SELECT * FROM formule");
        foreach ($all_formules as $formule) {
            $default_traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0]->nom_langue;
            $formule->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($formule->id == $traduction->idFormule) $formule->nom = $traduction->nom_langue;
            }

            $formule->nom_sans_accents = $this->retirerAccents( $formule->nom);
            $formule->nom_chinois_sans_accents = $this->retirerAccents( $formule->nom_chinois);

            if (stristr($formule->nom_sans_accents, $search)){
                array_push($formules, $formule);
            }else if(stristr($formule->nom_chinois_sans_accents, $search)){
                array_push($formules, $formule);
            }
        }

        // Retourne la vue liste.blade.php du dossier ingrédients avec comme arguments les ingrédients
        return view('admin.formules.index', compact( 'formules'));
    }




    // Ensemble des fonctions utilisées dans show permettant d'afficher les éléments
    public function show(int $id_formule){
        $lang=App::getLocale();

        // On récupère toutes les information dans la BDD grace a l'ID du symptome choisis
        $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule])[0];
        $traduction = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
        else $traduction = $traduction[0];
        $formule->code = $formule->nom;
        $formule->nom = $traduction->nom_langue;
        $formule->conseil = $traduction->conseil;
        $formule->pharmacologie = $traduction->pharmacologie;
        $formule->toxicologie = $traduction->toxicologie;
        $formule->actions = $traduction->actions;

        $formule_detail = DB::select("SELECT idSymptome, score FROM formule_detail WHERE idFormule = ? AND score > 0", [$id_formule]);
        $symptomes = collect($formule_detail)->map(function($detail){
            $symptome = Symptome::find($detail->idSymptome);
            $symptome->score = $detail->score;
            return $symptome;
        });
        $symptomes = Tri::symptomes($symptomes);

        $ingredient_detail = DB::select("SELECT idIngredient, ponderation, quantite FROM ingredient_detail WHERE idFormule = ? AND ponderation > 0", [$id_formule]);
        $ingredients = [];
        foreach ($ingredient_detail as $key => $value) {
            $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$value->idIngredient])[0];
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $ingredient->nom = $traduction_ingredient->nom;
            $ingredient->ponderation = $value->ponderation;
            $ingredient->quantite = $value->quantite;

            array_push($ingredients,$ingredient);
        }

        $ingredients = collect($ingredients)->sortBy('ponderation')->toArray();

        return view('admin.formules.show', compact('formule', "symptomes", "ingredients") );
    }




    // Fonction permettant d'éditer une formule déja existant
    public function edit($id_formule) {
        $lang=App::getLocale();

        $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule])[0];
        $traduction = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_syndrome)[0];
        else $traduction = $traduction[0];

        $formule->code = $formule->nom;
        $formule->nom = $traduction->nom_langue;
        $formule->conseil = $traduction->conseil;
        $formule->pharmacologie = $traduction->pharmacologie;
        $formule->toxicologie = $traduction->toxicologie;
        $formule->actions = $traduction->actions;



        $formule_detail = DB::select("SELECT idSymptome, score FROM formule_detail WHERE idFormule = ? AND score > 0", [$id_formule]);
        $symptomes = collect($formule_detail)->map(function($detail){
            $symptome = Symptome::find($detail->idSymptome);
            $symptome->score = $detail->score;
            return $symptome;
        });
        $symptomes = Tri::symptomes($symptomes);

        /* Tableau contenant tous les symptômes */
        $donnees_symptomes = Symptome::getAllSymptomes();

        $ingredients_detail = DB::select("SELECT idIngredient, ponderation, quantite FROM ingredient_detail WHERE idFormule = ? AND ponderation > 0", [$id_formule]);
        $ingredients = [];
        foreach ($ingredients_detail as $key => $value) {
            $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$value->idIngredient])[0];
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $ingredient->nom = $traduction_ingredient->nom;
            $ingredient->ponderation = $value->ponderation;
            $ingredient->quantite = $value->quantite;

            array_push($ingredients,$ingredient);
        }




        $donnees_ingredients_detail = DB::select("SELECT id FROM ingredient");
        $donnees_ingredients = [];
        foreach ($donnees_ingredients_detail as $key => $value) {
            $donnees_ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$value->id])[0];
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $donnees_ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $donnees_ingredient->nom = $traduction_ingredient->nom;

            array_push($donnees_ingredients,$donnees_ingredient);
        }

        $ingredients = collect($ingredients)->sortBy('ponderation')->toArray();
        return view('admin.formules.edit', compact("formule", "symptomes", "donnees_symptomes", "ingredients", "donnees_ingredients"));
    }










    public function update($id_formule, Request $request) {
        $lang=App::getLocale();

        // On demande a l'utilisateur d'entrer le nom du nouvel ingrédient
        $data = $request->input();
        $nom_formule = $request->input("editFormule");
        $nom_chinois_formule = $request->input("editFormuleChinois");
        $code_formule = $request->input("editFormuleCode");
        $conseil_formule = $data['conseilFormule'];
        $pharmacologie_formule = $data['pharmacologieFormule'];
        $toxicologie_formule = $data['toxicologieFormule'];
        $actions_formule = $data['actionsFormule'];

         $image_existante = DB::select("SELECT image FROM formule WHERE id = ?", [$id_formule])[0]->image;

        // On envoie l'image le ou elle doit être stockée
        if ($request->hasFile('imageIngredient')) {
            $image = $request->file('imageIngredient');
            $imageName = time(). '.' . $image->getClientOriginalExtension();
            $destinationImage = public_path('storage/images/formules', 'public');
            $image->move($destinationImage, $imageName);
            $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
            $imagePath = str_replace("\\","/",$imagePath);

            if(file_exists($image_existante)) unlink($image_existante);
        }
        else {
            $imagePath = $image_existante;
        }





        DB::update('UPDATE formule_traduction SET nom_langue = ?, conseil = ?, pharmacologie = ?, toxicologie = ?, actions = ? WHERE idFormule = ? AND langue = ?', [$nom_formule, $conseil_formule, $pharmacologie_formule, $toxicologie_formule, $actions_formule ,$id_formule, $lang]);

        DB::update('UPDATE formule SET nom = ?, nom_chinois = ?, image = ? WHERE id = ?', [$code_formule, $nom_chinois_formule, $imagePath, $id_formule, $lang]);

        $donnees = $request->input();
        unset($donnees['editFormule']);
        unset($donnees['_token']);
        unset($donnees['_method']);

        $symptomes_existants = DB::select("SELECT idSymptome FROM formule_detail WHERE idFormule = ?", [$id_formule]);

        foreach ($symptomes_existants as $symptome) {
            if (!array_key_exists('editSymptome__' . $symptome->idSymptome, $donnees)) {
                DB::delete("DELETE FROM formule_detail WHERE idFormule = ? AND idSymptome = ?", [$id_formule, $symptome->idSymptome]);
            }
        }

        foreach ($donnees as $key => $donnee) {
            $donnees_symptomes[$key] = $donnee;
        }
        if(isset($donnees_symptomes)){
        foreach ($donnees_symptomes as $key => $symptome) {
            if (stristr($key, 'editSymptome__')) {
                $id_symptome = explode('editSymptome__', $key)[1];
                $score_symptome = $donnees['editSymptomeScore__' . $id_symptome];

                DB::update('UPDATE formule_detail SET score = ? WHERE idSymptome = ? AND idFormule = ?', [$score_symptome, $id_symptome, $id_formule]);
            }else if (stristr($key, 'addSymptome_')) {
                $id_addSymptome = explode('addSymptome_', $key)[1];
                $id_symptome = $symptome;
                $score_addSymptome = $donnees['addSymptomeScore_' . $id_addSymptome];

                unset($donnees['addSymptomeScore_' . $id_addSymptome]);
                if($id_symptome != null){
                    DB::insert("INSERT INTO formule_detail (idFormule, idSymptome, score) VALUES (?, ?, ?)", [$id_formule, $id_symptome, $score_addSymptome]);
                }

            }
        }
        }




        $ingredients_existants = DB::select("SELECT idIngredient FROM ingredient_detail WHERE idFormule = ?", [$id_formule]);

        foreach ($ingredients_existants as $ingredient) {
            if (!array_key_exists('editIngredient__' . $ingredient->idIngredient, $donnees)) {
                DB::delete("DELETE FROM ingredient_detail WHERE idFormule = ? AND idIngredient = ?", [$id_formule, $ingredient->idIngredient]);
            }
        }

        foreach ($donnees as $key => $donnee) {
            $donnees_ingredients[$key] = $donnee;
        }
        if(isset($donnees_ingredients)){
        foreach ($donnees_ingredients as $key => $ingredient) {
            if (stristr($key, 'editIngredient__')) {
                $id_ingredient = explode('editIngredient__', $key)[1];
                $score_ingredient = $donnees['editIngredientScore__' . $id_ingredient];
                $quantite_ingredient = $donnees['editIngredientQuantite__' . $id_ingredient];
                DB::update('UPDATE ingredient_detail SET ponderation = ? , quantite = ? WHERE idIngredient = ? AND idFormule = ?', [$score_ingredient, $quantite_ingredient, $id_ingredient, $id_formule]);
            }else if (stristr($key, 'addIngredient_')) {
                $id_addIngredient = explode('addIngredient_', $key)[1];
                $id_ingredient = $ingredient;
                $score_addIngredient = $donnees['addIngredientScore_' . $id_addIngredient];
                unset($donnees['addIngredientScore_' . $id_addIngredient]);
                $quantite_addIngredient = $donnees['addIngredientQuantite_' . $id_addIngredient];
                unset($donnees['addIngredientQuantite_' . $id_addIngredient]);
                if($id_ingredient != null){
                    DB::insert("INSERT INTO ingredient_detail (idFormule, idIngredient, ponderation, quantite) VALUES (?, ?, ?, ?)", [$id_formule, $id_ingredient, $score_addIngredient, $quantite_addIngredient]);
                }
            }
        }
        }



        return redirect(route('formulesAdmin.show', [$id_formule]));
    }





    public function create(){
        $lang=App::getLocale();
        $donnees_symptomes = Symptome::getAllSymptomes();

        $donnees_ingredients_detail = DB::select("SELECT id FROM ingredient");
        $donnees_ingredients = [];
        foreach ($donnees_ingredients_detail as $key => $value) {
            $donnees_ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$value->id])[0];
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $donnees_ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $donnees_ingredient->nom = $traduction_ingredient->nom;

            array_push($donnees_ingredients,$donnees_ingredient);
        }

        return view('admin.formules.create', compact('donnees_symptomes', 'donnees_ingredients'));
    }

    public function store(Request $request){
        $lang=App::getLocale();

        // On demande à l'utilisateur d'entrer le nom du nouveau symptome
        $data = $request->input();
        $request->validate([
            'nouveauFormuleCode' => 'required',
            'imageIngredient' => 'sometimes|image|max:8000'
        ]);

        if(DB::select('SELECT * FROM formule WHERE nom = ?', [$data['nouveauFormuleCode']]) != null ){
            return back()->with('erreur', 'formules_create_erreur_doublon');
        }else{

            // On envoie l'image le ou elle doit être stockée
            if ($request->hasFile('imageIngredient')) {
               $image = $request->file('imageIngredient');
               $imageName = time(). '.' . $image->getClientOriginalExtension();
               $destinationImage = public_path('storage/images/formules', 'public');
               $image->move($destinationImage, $imageName);
               $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
               $imagePath = str_replace("\\","/",$imagePath);
            }
            else {
                $imagePath = null;
            }

            // On l'insert dans la table symptome grace a ce nom
            DB::insert("INSERT INTO formule (nom, nom_chinois, image) VALUES (?, ?, ?)", [$data['nouveauFormuleCode'], $data['nouveauFormuleCode'], $imagePath]);

            $id_nouveau_formule = DB::select("SElECT id FROM formule WHERE nom = ?", [$data['nouveauFormuleCode']])[0]->id;
            DB::insert("INSERT INTO formule_traduction (langue, idFormule, nom_langue, conseil, pharmacologie, toxicologie, actions) VALUES (?, ?, ?, ?, ?, ?, ?)", [$lang, $id_nouveau_formule, $data['nouveauFormule'], $data['nouveauConseilFormule'], $data['nouveauPharmacologieFormule'], $data['nouveauToxicologieFormule'], $data['nouveauActionsFormule'], $data['nouveauConseilFormule']]);

            unset($data["nouveauFormuleCode"]);
            unset($data["nouveauFormule"]);
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
                        DB::insert("INSERT INTO formule_detail (idFormule, idSymptome, score) VALUES (?, ?, ?)", [$id_nouveau_formule, $id_symptome, $score_addSymptome]);
                    }
                }
            }}

            foreach ($data as $key => $donnee) {
                $donnees_ingredients[$key] = $donnee;
            }

            if(isset($donnees_ingredients)){
            foreach ($donnees_ingredients as $key => $ingredient) {
                if (stristr($key, 'addIngredient_')) {
                    $id_addIngredient = explode('_', $key)[1];
                    $id_ingredient = $ingredient;
                    $score_addIngredient = $data['addIngredientScore_' . $id_addIngredient];
                    unset($data['addIngredientScore_' . $id_addIngredient]);

                    $quantite_addIngredient = $data['addIngredientQuantite_' . $id_addIngredient];
                    unset($data['addIngredientQuantite_' . $id_addIngredient]);
                    if($id_ingredient != null){
                        DB::insert("INSERT INTO ingredient_detail (idFormule, idIngredient, ponderation, quantite) VALUES (?, ?, ?, ?)", [$id_nouveau_formule, $id_ingredient, $score_addIngredient, $quantite_addIngredient]);
                    }
                }
            }}
            return redirect(route('formulesAdmin.index'));
        }
    }

    /* Supprime une formule dans toutes les tables où elle est utilisé */
    public function destroy($id_formule){
        DB::delete('DELETE FROM formule_detail WHERE idFormule = ?', [$id_formule]);
        DB::delete('DELETE FROM ingredient_detail WHERE idFormule = ?', [$id_formule]);
        DB::delete('DELETE FROM formule_traduction WHERE idFormule = ?', [$id_formule]);
        DB::delete('DELETE FROM formule WHERE id = ?', [$id_formule]);
        return redirect(route('formulesAdmin.index'));
    }

    /* Récupère les traduction par langue */
    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM formule_traduction WHERE langue = ?", [$lang]);
    }

    /* Récupère les traduction par ID */
    private function getTraductionById(string $lang, string $table, string $colonne, int $id): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ? AND ".$colonne." = ?", [$lang, $id]);
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
