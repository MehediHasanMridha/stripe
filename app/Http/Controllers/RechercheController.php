<?php

namespace App\Http\Controllers;

use App\Models\Symptome;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use function GuzzleHttp\Psr7\str;

class RechercheController extends Controller
{

    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue index.blade.php du dossier admin/recherche
     *
     * @return Application|Factory|View
     */
    public function index() {
        return view('admin.recherche.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function search(Request $request) {
        $lang=App::getLocale(); // Change la langue du site avec celle donnée en paramètre
        $search = $request->input('search');

        //if (stristr($search, 'œ')) $search = stristr($search, 'œ', true) . 'oe' . substr(strstr($search, 'œ'), 2);
        if(strpos($search, "coeur") !== false){
            $search = str_replace("coeur", "cœur", $search);
        }

        $search = $this->retirerAccents($search);



        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('recherche.index'));

        $liste_symptomes = array();

        $is_symptome = [];
        $all_symptomes = Symptome::getAllSymptomes();


        $is_formule = [];
        $all_formules = DB::select("SELECT * from formule");
        foreach ($all_formules as $one_formule) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$one_formule->id])[0];

            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $one_formule->id);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $one_formule->id)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;

            //Retire les accents
            $formule->nom_langue = $this->retirerAccents( $formule->nom_langue);

            if (stristr($formule->nom, $search) || stristr($formule->nom_chinois, $search) || stristr($formule->nom_langue, $search)) {
                array_push($is_formule, $formule);
            }
        }

        $is_syndrome = [];
        $all_syndromes = DB::select("SELECT * FROM syndrome");
        foreach ($all_syndromes as $one_syndrome) {
            $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $one_syndrome->id);

            if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $one_syndrome->id)[0];
            else $traduction_syndrome = $traduction_syndrome[0];

            //Retire les accents
            $traduction_syndrome->nom = $this->retirerAccents( $traduction_syndrome->nom);

            if (stristr($traduction_syndrome->nom, $search)) {
                $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$one_syndrome->id])[0];
                $syndrome->nom = $traduction_syndrome->nom;

                array_push($is_syndrome, $syndrome);
            }
        }

        $is_ingredient = [];
        $all_ingredients = DB::select("SELECT * FROM ingredient");
        foreach ($all_ingredients as $one_ingredient) {
            $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$one_ingredient->id])[0];

            $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $one_ingredient->id);

            if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $one_ingredient->id)[0];
            else $traduction_ingredient = $traduction_ingredient[0];

            $ingredient->nom_langue = $traduction_ingredient->nom;

            //Retire les accents
            $ingredient->nom_latin = ($this->retirerAccents($ingredient->nom_latin));
            $ingredient->nom_chinois = ($this->retirerAccents($ingredient->nom_chinois));
            $ingredient->nom_langue = ($this->retirerAccents($ingredient->nom_langue));


            if (stristr($ingredient->nom_latin, $search) || stristr($ingredient->nom_chinois, $search) || stristr($ingredient->nom_langue, $search)) {
                array_push($is_ingredient, $ingredient);
            }
        }

        $search_response = array();
        foreach ($is_symptome as $key => $symptome) $search_response['symptomes'][$key] = $symptome;
        foreach ($is_formule as $key => $formule) $search_response['formules'][$key] = $formule;
        foreach ($is_syndrome as $key => $syndrome) $search_response['syndromes'][$key] = $syndrome;
        foreach ($is_ingredient as $key => $ingredients) $search_response['ingredients'][$key] = $ingredients;

        /***************
         * SYMPTOMES
        ***************/

        if (isset($search_response['symptomes'])) {
            foreach ($search_response['symptomes'] as $symptome_found) {
                $id_symptome_cle = $symptome_found->id;
                $liste_symptomes[$id_symptome_cle] = $this->setSymptomeTab($lang, $id_symptome_cle, null, null);
            }
        }

        /***************
         * SYNDROMES
         ***************/

        if (isset($search_response['syndromes'])) {
            foreach ($search_response['syndromes'] as $syndrome_found) {
                $syndromes_details = DB::select("SELECT idSymptome FROM syndrome_detail WHERE idSyndrome = ? AND score > 0", [$syndrome_found->id]);
                foreach ($syndromes_details as $id_symptome) {
                    if (!key_exists($id_symptome->idSymptome, $liste_symptomes)) {
                        $symptome =Symptome::find($id_symptome->idSymptome);
                        $id_symptome_cle = $symptome->id;
                        $liste_symptomes[$id_symptome_cle] = $this->setSymptomeTab($lang, $id_symptome_cle, null, null);
                    }
                }
            }
        }

        /***************
         * FORMULES
         ***************/

        if (isset($search_response['formules'])) {
            foreach ($search_response['formules'] as $formule_found) {
                $formules_details = DB::select("SELECT idSymptome FROM formule_detail WHERE idFormule = ? AND score > 0", [$formule_found->id]);
                foreach ($formules_details as $id_symptome) {
                    if (!key_exists($id_symptome->idSymptome, $liste_symptomes)) {
                        $symptome =Symptome::find($id_symptome->idSymptome);
                        $id_symptome_cle = $symptome->id;
                        $liste_symptomes[$id_symptome_cle] = $this->setSymptomeTab($lang, $id_symptome_cle, 'formule', $search);
                    }
                }
            }
        }

        /***************
         * INGREDIENTS
         ***************/

        if (isset($search_response['ingredients'])) {
            foreach ($search_response['ingredients'] as $ingredient) {
                $ingredients_details = DB::select("SELECT idFormule FROM ingredient_detail WHERE idIngredient = ? AND ponderation > 0", [$ingredient->id]);
                foreach ($ingredients_details as $id_formule) {
                    $formules_details = DB::select("SELECT idSymptome FROM formule_detail WHERE idFormule = ? AND score > 0", [$id_formule->idFormule]);
                    foreach ($formules_details as $id_symptome) {
                        if (!key_exists($id_symptome->idSymptome, $liste_symptomes)) {
                            $symptome =Symptome::find($id_symptome->idSymptome);
                            $id_symptome_cle = $symptome->id;
                            $liste_symptomes[$id_symptome_cle] = $this->setSymptomeTab($lang, $id_symptome_cle, 'ingredient', $search);
                        }
                    }
                }
            }
        }

        ksort($liste_symptomes);

        return view('admin.recherche.index', compact('liste_symptomes'));
    }

    /**
     * @param string $lang
     * @param int $id_symptome_cle
     * @param $type
     * @param $search
     * @return array
     */
    private function setSymptomeTab(string $lang, int $id_symptome_cle, $type, $search): array {
        $tab = [];

        $nom_symptome = DB::select("SELECT nom FROM symptome_traduction WHERE idSymptome = ? AND langue = ?", [$id_symptome_cle, $lang]);

        if (empty($nom_symptome)) $nom_symptome = "NULL";
        else $nom_symptome = $nom_symptome[0]->nom;

        $tab['nom'] = $nom_symptome;

        $count = 'COUNT(*)';

        $nb_syndromes = DB::select("SELECT COUNT(*) FROM syndrome_detail WHERE idSymptome = ? AND score > 0", [$id_symptome_cle])[0]->$count;
        $tab['nb_syndromes'] = $nb_syndromes;

        $nb_formules = 0;
        $nb_ingredients = 0;

        /**************************
         * SYMPTOMES - SYNDROMES
         *************************/


        $tab['syndromes'] = [];
        $syndromes_details = DB::select("SELECT idSyndrome FROM syndrome_detail WHERE idSymptome = ? AND score > 0", [$id_symptome_cle]);
        foreach ($syndromes_details as $id_syndrome) {
            $syndromes = DB::select("SELECT * FROM syndrome WHERE id = ?", [$id_syndrome->idSyndrome]);
            foreach ($syndromes as $syndrome) {
                $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $syndrome->id);

                if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0];
                else $traduction_syndrome = $traduction_syndrome[0];

                $tab['syndromes'][$syndrome->id] = $traduction_syndrome->nom;
            }
        }


        /**************************
         * SYMPTOMES - FORMULES
         *************************/

        $tab['formules'] = [];
        $formules_details = DB::select("SELECT idFormule FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome_cle]);
        foreach ($formules_details as $id_formule) {
            $formules = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule->idFormule]);
            foreach ($formules as $formule) {
                $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $formule->id);

                if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0];
                else $traduction_formule = $traduction_formule[0];

                $model = ["nom" => $formule->nom, "nom_chinois" => $formule->nom_chinois, "nom_langue" => $traduction_formule->nom_langue, "ingredients" => []];


                /**************************
                 * SYMPTOMES - INGREDIENTS
                 *************************/

                $ingredients_details = DB::select("SELECT idIngredient FROM ingredient_detail WHERE idFormule = ? AND ponderation > 0", [$id_formule->idFormule]);
                foreach ($ingredients_details as $id_ingredient) {
                    $ingredients = DB::select("SELECT * FROM ingredient WHERE id = ?", [$id_ingredient->idIngredient]);
                    foreach ($ingredients as $ingredient) {
                        $traduction_ingredient = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $ingredient->id);

                        if (empty($traduction_ingredient)) $traduction_ingredient = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0];
                        else $traduction_ingredient = $traduction_ingredient[0];

                        if (($type == 'ingredient' && stristr($traduction_ingredient->nom, $search)) || ($type != 'ingredient')) {
                            $model["ingredients"][$ingredient->id] = $traduction_ingredient->nom;
                        }
                    }
                }

                if (($type == 'formule' && (stristr($formule->nom, $search) || stristr($formule->nom_chinois, $search) || stristr($traduction_formule->nom_langue, $search))) || ($type != 'formule')) {
                    if (($type == 'ingredient' && !empty($model["ingredients"])) || $type != 'ingredient') {
                        $tab['formules'][$formule->id] = $model;
                        $nb_formules++;
                    }
                }
            }
        }
        $tab['nb_formules'] = $nb_formules;
        foreach ($tab['formules'] as $id => $formule) {
            $nb_ingredients += sizeof($tab['formules'][$id]['ingredients']);
        }
        $tab['nb_ingredients'] = $nb_ingredients;

        return $tab;
    }

    private function getTraductionByLang(string $lang, string $table): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ?", [$lang]);
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
