<?php

namespace App\Http\Controllers;

use App\Helpers\Tri;
use App\Models\Symptome;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;


class FormulesController extends Controller
{
    public function index() {
        return view('formules.index');
    }

    public function liste(string $selectedLang) {
        $lang = App::getLocale();
        $formules = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_formules = DB::select("SELECT * FROM formule ORDER BY nom");
        foreach ($all_formules as $formule) {
            $default_traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0]->nom_langue;
            $formule->nom_langue = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($formule->id == $traduction->idFormule) $formule->nom_langue = $traduction->nom_langue;
            }

            array_push($formules, $formule);
        }

        // Retourne la vue liste.blade.php du dossier formules avec comme arguments les variables lang et formules
        return view('formules.liste', compact( 'selectedLang', 'formules','all_formules'));
    }

    public function search(string $selectedLang, Request $request) {
        $lang = App::getLocale();
        // Construit une variable search avec la valeur inscrite dans le input search
        $search = $request->input('search');
        $search = $this->retirerAccents( $search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('formules.liste', [$lang, $selectedLang]));

        // Sélectionne toutes les données des lignes où il y a la valeur inscrite dans le input search de la table formule
        $formules = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_formules = DB::select("SELECT * FROM formule ORDER BY nom");
        foreach ($all_formules as $formule) {
            $default_traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0]->nom_langue;
            $formule->nom_langue = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($formule->id == $traduction->idFormule) $formule->nom_langue = $traduction->nom_langue;
            }

            if (stristr($this->retirerAccents($formule->nom), $search) || stristr($this->retirerAccents($formule->nom_chinois), $search) || stristr($this->retirerAccents($formule->nom_langue), $search)) array_push($formules, $formule);
        }

        // Retourne la vue liste.blade.php du dossier formules avec comme arguments les variables lang et formules
        return view('formules.liste', compact( 'selectedLang', 'formules','all_formules'));
    }

    public function show(string $selectedLang, int $id_formule) {
        $lang = App::getLocale();

        // Sélectionne toutes les données de la ligne où l'id est égale à la variable id_formule de la table formule
        $traduction = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
        else $traduction = $traduction[0];

        $formule = DB::select('select * from formule where id = ?', [$traduction->idFormule])[0];
        $formule->nom_langue = $traduction->nom_langue;
        $formule->conseil = $traduction->conseil;
        $formule->pharmacologie = $traduction->pharmacologie;
        $formule->toxicologie = $traduction->toxicologie;
        $formule->actions = $traduction->actions;

        /*********************
         * INGREDIENTS
         ********************/


        $ingredient_detail = DB::select("SELECT idIngredient, ponderation, quantite FROM ingredient_detail WHERE idFormule = ? AND ponderation > 0", [$id_formule]);
        $ingredients = [];
        foreach ($ingredient_detail as $key => $value) {
            $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$value->idIngredient])[0];
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $ingredient->nom = $traduction_ingredient->nom;

            $ponderation = DB::select("SELECT * FROM ponderation WHERE id = ?", [$value->ponderation])[0];


            $traduction_ponderation = $this->getTraductionById($lang, 'ponderation_traduction', 'idPonderation', $ponderation->id);
            if (empty($traduction_ponderation)) $traduction_ponderation = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
            else $traduction_ponderation = $ponderation->nom;



            $ingredient->quantite = $value->quantite;
            $ingredient->ponderation = $traduction_ponderation;
            $ingredient->ponderation_id = $value->ponderation;

            array_push($ingredients,$ingredient);
        }
        /* Tri des ingrédients en fonction de leur pondération : empereur, ministre ect... */
        $ingredients = collect($ingredients)->sortBy('ponderation_id')->toArray();

        $total=0;
        foreach ($ingredients as $ingredient){
            $total+=$ingredient->quantite;
        }
        if($total!==0){
            foreach ($ingredients as $ingredient){
                $ingredient->quantite=number_format((($ingredient->quantite/$total)*100), 1, '.', "");
            }
        }



        /*********************
         * SYMPTOMES
         ********************/


        // Sélectionne toutes les données des lignes où idFormule est égale à la variable id_formule de la table formule_detail
        $detail_formules = DB::select('select * from formule_detail where idFormule = ?', [$id_formule]);

        // Pour chaque ligne récupérée de la requete contenu dans detail_formules
        $id_symptomes = collect($detail_formules)->map(function($detail){
            if($detail->score > 0)return $detail->idSymptome;
        })->reject(function ($element) {
            return empty($element);
        });

        // Pour chaque valeur contenu dans le tableau id_symptomes
        $symptomes = $id_symptomes->map(function($id){
            return Symptome::find($id);
        });
        $symptomes = Tri::symptomes($symptomes);

        $ingredients = collect($ingredients)->sortBy('nom')->toArray();

        // Retourne la vue show.blade.php du dossier formules avec comme arguments les variables lang, formule, ingredients, et symptomes
        return view('formules.show', compact( 'selectedLang', 'formule', 'ingredients', 'symptomes'));
    }

    public function symptomeSearch( string $selectedLang, int $id_formule, Request $request) {
        $lang = App::getLocale();

        $search = $request->input('Symptomesearch');

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('formules.show', [$lang, $selectedLang, $id_formule]));

        // Sélectionne toutes les données de la ligne où l'id est égale à la variable id_formule de la table formule
        $traduction = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);
        $formule = DB::select('select * from formule where id = ?', [$id_formule])[0];

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
        else $traduction = $traduction[0];

        $formule->nom_langue = $traduction->nom_langue;


        /*********************
         * INGREDIENTS
         ********************/


        // Sélectionne toutes les données des lignes où idFormule est égale à la variable id_formule de la table ingredient_detail
        $detail_ingredients = DB::select('select * from ingredient_detail where idFormule = ?', [$id_formule]);

        // Pour chaque ligne récupérée de la requete contenu dans detail_ingredients
        $id_ingredients = [];
        foreach($detail_ingredients as $detail_ingredient) {
            // Si la pondération de cette ingrédient pour cette formule est supérieur à 0
            if ($detail_ingredient->ponderation > 0) {
                // On ajoute au tableau id_ingredients, l'id de l'ingrédient
                array_push($id_ingredients, $detail_ingredient->idIngredient);
            }
        }


        // Pour chaque valeur contenu dans le tableau id_ingredients
        $ingredients = [];
        foreach($id_ingredients as $id_ingredient) {
            // Sélectionne toutes les données de la ligne où l'id est égale à id_ingredient de la table ingredient
            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $id_ingredient);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $id_ingredient)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $ingredient = DB::select('select id, status from ingredient where id = ?', [$id_ingredient])[0];
            $ingredient->nom = $traduction_ingredient->nom;

            // On ajoute au tableau ingredients, l'array contenant toutes les données récupérées de la requête contenu dans ingredient
            array_push($ingredients, $ingredient);
        }


        /*********************
         * SYMPTOMES
         ********************/


        // Sélectionne toutes les données des lignes où idFormule est égale à la variable id_formule de la table formule_detail
        $detail_formules = DB::select('select * from formule_detail where idFormule = ?', [$id_formule]);

        // Pour chaque ligne récupérée de la requete contenu dans detail_formules
        $id_symptomes = [];
        foreach($detail_formules as $detail_formule) {
            // Si le score de cette formule pour ce symptôme est supérieur à 0
            if ($detail_formule->score > 0) {
                // On ajoute au tableau id_symptomes, l'id du symptôme
                array_push($id_symptomes, $detail_formule->idSymptome);
            }
        }


        // Pour chaque valeur contenu dans le tableau id_symptomes
        $symptomes = [];
        foreach($id_symptomes as $id_symptome) {
            // Sélectionne toutes les données de la ligne où l'id est égale à id_symptome de la table symptome
            $traduction_symptome = $this->getTraductionById($lang, 'symptome_traduction', 'idSymptome', $id_symptome);

            if (empty($traduction_symptome)) $traduction_symptome = $this->getTraductionById('fr', 'symptome_traduction', 'idSymptome', $id_symptome)[0];
            else $traduction_symptome = $traduction_symptome[0];

            if (stristr($traduction_symptome->nom, $search)) {
                $symptome = Symptome::find($id_symptome);
                $symptome->nom = $traduction_symptome->nom;

                // On ajoute au tableau symptomes, l'array contenant toutes les données récupérées de la requête contenu dans symptome
                array_push($symptomes, $symptome);
            }
        }

        // Retourne la vue show.blade.php du dossier formules avec comme arguments les variables lang, formule, ingredients, et symptomes
        return view('formules.show', compact( 'selectedLang', 'formule', 'ingredients', 'symptomes'));
    }

    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM formule_traduction WHERE langue = ?", [$lang]);
    }

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
