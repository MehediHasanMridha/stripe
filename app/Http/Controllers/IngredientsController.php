<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class IngredientsController extends Controller
{
    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue index.blade.php du dossier ingredients
     *
     * @return Application|Factory|View
     */
    public function index() {
        return view('ingredients.index');
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère le choix de l'utilisateur sur l'affichage du nom des ingrédients
     * Récupère la liste des ingrédients de la base de données
     * Retourne la vue liste.blade.php du dossier ingredients
     *
     * @param string $selectedLang
     * @return Application|Factory|View
     */
    public function liste(string $selectedLang) {
        $lang=App::getLocale();
        // Récupère la traduction de chaque ingrédient dans la langue demandé
        $traductions = $this->getTraductionByLang($lang);

        // Récupère tous les ingrédients stocké dans la table ingredient
        $all_ingredients = DB::select("SELECT * FROM ingredient");

        // Pour chaque ingredient, change le nom par celui par défaut puis s'il existe par la langue du site demandé
        $ingredients = [];
        foreach ($all_ingredients as $ingredient) {
            $default_traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0]->nom;
            $ingredient->nom_langue = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($ingredient->id == $traduction->idIngredient) $ingredient->nom_langue = $traduction->nom;
            }

            // Ajoute l'ingrédient dans l'array ingredients
            array_push($ingredients, $ingredient);
        }

        return view('ingredients.liste', compact( 'selectedLang', 'ingredients'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère le choix de l'utilisateur sur l'affichage du nom des ingrédients
     * Récupère la liste des ingrédients qui ont dans leur nom la chaîne de caractères écrit par l'utilisateur
     * Retourne la vue liste.blade.php du dossier ingredients
     *
     * @param string $selectedLang
     * @param Request $request
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function search( string $selectedLang, Request $request) {
        $lang=App::getLocale();
        // Récupère la valeur du champs search
        $search = $request->input('search');
        $search = $this->retirerAccents( $search);

        // Si la recherche est vide, on redirige vers la page de la liste complète
        if (strlen($search) == 0) return redirect(route('ingredients.liste', [$selectedLang]));

        $ingredients = [];
        // Récupère la traduction de chaque ingrédient dans la langue demandé
        $traductions = $this->getTraductionByLang($lang);

        // Récupère tous les ingrédients stocké dans la table ingredient trié par nom
        $all_ingredients = DB::select("SELECT * FROM ingredient");
        Log::info($selectedLang);
        if($selectedLang==="cn"){
            foreach ($all_ingredients as $ingredient) {
                // si dans le nom de l'ingrédient se trouve la valeur cherché, ajoute cet ingrédient dans l'array ingredients
                if (stristr($ingredient->nom_chinois, $search)) array_push($ingredients, $ingredient);
            }
        }
        else if($selectedLang==="latin"){
            foreach ($all_ingredients as $ingredient) {
                // si dans le nom de l'ingrédient se trouve la valeur cherché, ajoute cet ingrédient dans l'array ingredients
                if (stristr($ingredient->nom_latin, $search)) array_push($ingredients, $ingredient);
            }
        }
        else{
            // Pour chaque ingredient, change le nom par celui par défaut puis s'il existe par la langue du site demandé
            foreach ($all_ingredients as $ingredient) {
                $default_traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0]->nom;
                $ingredient->nom_langue = $default_traduction;

                foreach ($traductions as $traduction) {
                    if ($ingredient->id == $traduction->idIngredient) $ingredient->nom_langue = $traduction->nom;
                }

                // si dans le nom de l'ingrédient se trouve la valeur cherché, ajoute cet ingrédient dans l'array ingredients
                if (stristr($this->retirerAccents($ingredient->nom_langue), $search)) array_push($ingredients, $ingredient);
            }
        }


        return view('ingredients.liste', compact( 'selectedLang', 'ingredients'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère les informations de l'ingrédient où son id est égale à celui demandé
     * Récupère les informations de toutes les formules lié à cet ingrédient
     * Retourne la vue show.blade.php du dossier ingredient
     *
     * @param string $selectedLang
     * @param int $id_ingredient
     * @return Application|Factory|View
     */
    public function show(string $selectedLang, int $id_ingredient) {
        $lang=App::getLocale();
        // Récupère toutes les informations de l'ingrédient qui possède un id égale à celui donnée en paramètre
        $ingredient = DB::select('select * from ingredient where id = ?', [$id_ingredient])[0];
        // Récupère sa traduction dans la langue du site demandé
        $traduction = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $id_ingredient);

        // Si sa traduction n'existe pas, on récupère la traduction de l'ingrédient en français (langue par défaut)
        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $id_ingredient)[0];
        // Sinon on garde cette traduction
        else $traduction = $traduction[0];

        // Change le nom de l'ingrédient et son tropisme par ceux récupéré de la traduction
        $ingredient->nom_langue = $traduction->nom;
        $ingredient->tropisme = $traduction->tropisme;
        $ingredient->nature = $traduction->nature;
        $ingredient->saveur = $traduction->saveur;
        $ingredient->action = $traduction->action;
        $ingredient->partie = $traduction->partie;

        /* Sélectionne l'idFormule de chaque ingrédient contenu dans ingredient_detail où leur idIngredient est égale à celui donnée en paramètre
        et où la pondération est strictement supérieur à 0 */
        $detail_ingredients = DB::select('select idFormule from ingredient_detail where idIngredient = ? AND ponderation > 0', [$id_ingredient]);

        // Pour chaque donnée récupéré de la requete, ajoute l'idFormule dans l'array id_formules
        $id_formules = [];
        foreach($detail_ingredients as $detail_ingredient) array_push($id_formules, $detail_ingredient->idFormule);


        // Pour chaque valeur contenu dans le tableau id_formules
        $formules = [];
        foreach($id_formules as $id_formule) {
            // Sélectionne toutes les informations de la formule qui possède un id égale à celui stocké dans id_formule
            $formule = DB::select('select * from formule where id = ?', [$id_formule])[0];

            // Récupère sa traduction dans la langue du site demandé
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

            // Si sa traduction n'existe pas, on récupère la traduction de la formule en français (langue par défaut)
            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
            // Sinon on garde cette traduction
            else $traduction_formule = $traduction_formule[0];

            // Change le nom de la formule par celui récupéré de la traduction
            $formule->nom_langue = $traduction_formule->nom_langue;

            // On ajoute la formule à l'array formules
            array_push($formules, $formule);
        }

        $formules = collect($formules)->sortBy('nom_langue')->toArray();

        return view('ingredients.show', compact( 'selectedLang', 'ingredient', 'formules'));
    }


    /**
     * Retourne une array contenant toutes les traductions existantes se trouvant dans la table ingredient_traduction
     * et où la langue est égale à celle donnée en paramètre
     *
     * @param string $lang
     * @return array
     */
    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM ingredient_traduction WHERE langue = ?", [$lang]);
    }


    /**
     * Retourne une array contenant la traduction contenu dans la table donnée en paramètre et où la langue est
     * égale à celle donnée en paramètre et où la colonne donnée en paramètre est égale à l'élément donnée en paramètre
     *
     * @param string $lang
     * @param string $table
     * @param string $colonne
     * @param int $id
     * @return array
     */
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
