<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class IngredientsAdminController extends Controller
{
    private $iso;

    public function __construct()
    {
        $this->iso = Config::get("iso");
    }


    // Fonction qui permet d'afficher tous les ingrédients dans une liste index
    public function index() {
        $lang=App::getLocale();

        $ingredients = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_ingredients = DB::select("SELECT * FROM ingredient");
        foreach ($all_ingredients as $ingredient) {
            $default_traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0]->nom;
            $ingredient->nom_langue = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($ingredient->id == $traduction->idIngredient) $ingredient->nom_langue = $traduction->nom;
            }

            array_push($ingredients, $ingredient);
        }

        $ingredients = collect($ingredients)->sortBy('nom_langue')->toArray();
        return view("admin.ingredientsAdmin.index", compact("ingredients"));
    }

    // Fonction permettant d'effectuer une recherche dans la listes
    public function search(Request $request) {
        $lang=App::getLocale();

        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = $request->input('search');
        $search = $this->retirerAccents($search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('ingredientsAdmin.index'));


        $ingredients = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_ingredients = DB::select("SELECT * FROM ingredient");
        foreach ($all_ingredients as $ingredient) {
            $default_traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $ingredient->id)[0]->nom;
            $ingredient->nom_langue = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($ingredient->id == $traduction->idIngredient) $ingredient->nom_langue = $traduction->nom;
            }

            $nom_sans_accents = $this->retirerAccents( $ingredient->nom_langue);
            if (stristr($nom_sans_accents, $search)) array_push($ingredients, $ingredient);

            //array_push($ingredients, $ingredient);
        }


        // Retourne la vue liste.blade.php du dossier ingrédients avec comme arguments les ingrédients
        return view('admin.ingredientsAdmin.index', compact( 'ingredients'));
    }

    // Fonction qui ramène l'utilisateur vers la page de création d'un ingrédient quand on clique sur le +
    public function create() {
        return view('admin.ingredientsAdmin.create');
    }

    // Fonction qui permet d'enregistrer les modification ou la création d'un ingrédients
    public function store(Request $request) {
        $lang=App::getLocale();

        // On demande a l'utilisateur d'entrer le nom du nouvel ingrédient
        $data = $request->input();
        $request->validate([
            'nouvelIngredient' => 'required',
            'nouvelIngredientChinois' => 'required',
            'nouvelIngredientLatin' => 'required',
            'imageIngredient' => 'sometimes|image|max:8000'
        ]);

        //[$data['nouvelIngredientChinois']
        if(DB::select('SELECT * FROM ingredient WHERE nom_chinois = ?', [$data['nouvelIngredientChinois']]) != null ){
            return back()->with('erreur', 'ingredients_create_erreur_doublon');
        }else{

            // On check le status de  l'ingrédient
            if(isset($data['statusIngredient'])) $status = 1;
            else $status = 0;


            // On envoie l'image le ou elle doit être stockée
            if ($request->hasFile('imageIngredient')) {
               $image = $request->file('imageIngredient');
               $imageName = time(). '.' . $image->getClientOriginalExtension();
               $destinationImage = public_path('storage/images/ingredients', 'public');
               $image->move($destinationImage, $imageName);
               $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
               $imagePath = str_replace("\\","/",$imagePath);
            }
            else {
                $imagePath = null;
            }

            // On l'insert dans la table ingrédients grace à ce nom
            DB::insert('INSERT INTO ingredient (nom_chinois,nom_latin,tropisme,status,image) VALUES (?,?,?,?,?)', [$data['nouvelIngredientChinois'],$data['nouvelIngredientLatin'],$data['tropismeIngredient'],$status,$imagePath]);

            $id_nouvelle_ingredient = DB::select("SELECT id FROM ingredient WHERE nom_chinois = ?", [$data['nouvelIngredientChinois']])[0]->id;


            DB::insert("INSERT INTO ingredient_traduction (langue, idIngredient, nom, tropisme, nature, saveur, action, partie) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$lang, $id_nouvelle_ingredient, $data['nouvelIngredient'], $data['tropismeIngredient'], $data['natureIngredient'], $data['saveurIngredient'], $data['actionIngredient'], $data['partieIngredient']]);

            return redirect(route('ingredientsAdmin.index'));
        }
    }

    // Fonction permettant l'affichage d'un ingrédients
    public function show(int $id_ingredient) {
        $lang=App::getLocale();

        // On récupère toutes les informations nécsessaire à l'affichage des données du symptôme
        $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$id_ingredient])[0];
        $traduction = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $id_ingredient);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $id_ingredient)[0];
        else $traduction = $traduction[0];

        $ingredient->nom = $traduction->nom;
        $ingredient->tropisme = $traduction->tropisme;

        $ingredient->nature = $traduction->nature;
        $ingredient->saveur = $traduction->saveur;
        $ingredient->action = $traduction->action;
        $ingredient->partie = $traduction->partie;

        return view('admin.ingredientsAdmin.show', compact('ingredient'));
    }

    public function edit(int $id_ingredient) {
        $lang=App::getLocale();

        $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$id_ingredient])[0];
        $traduction = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $id_ingredient);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $id_ingredient)[0];
        else $traduction = $traduction[0];

        $ingredient->nom = $traduction->nom;
        $ingredient->tropisme = $traduction->tropisme;

        $ingredient->nature = $traduction->nature;
        $ingredient->saveur = $traduction->saveur;
        $ingredient->action = $traduction->action;
        $ingredient->partie = $traduction->partie;

        return view('admin.ingredientsAdmin.edit', compact('ingredient'));
    }

    public function update($id_ingredient, Request $request) {
        $lang=App::getLocale();

        // On demande a l'utilisateur d'entrer le nom du nouvel ingrédient
        $data = $request->input();
        $request->validate([
            'nouvelIngredient' => 'required',
            'nouvelIngredientChinois' => 'required',
            'nouvelIngredientLatin' => 'required',
            'imageIngredient' => 'sometimes|image|max:8000'
        ]);

        // On check le status de  l'ingrédient
        if (isset($data['statusIngredient'])) $status = 1;
        else $status = 0;

        $image_existante = DB::select("SELECT image FROM ingredient WHERE id = ?", [$id_ingredient])[0]->image;

        // On envoie l'image le ou elle doit être stockée
        if ($request->hasFile('imageIngredient')) {
            $image = $request->file('imageIngredient');
            $imageName = time(). '.' . $image->getClientOriginalExtension();
            $destinationImage = public_path('storage/images/ingredients', 'public');
            $image->move($destinationImage, $imageName);
            $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
            $imagePath = str_replace("\\","/",$imagePath);

            if(file_exists($image_existante)) unlink($image_existante);
        }
        else {
            $imagePath = $image_existante;
        }


        // On l'insert dans la table ingrédients grace a ce nom
        DB::update('UPDATE ingredient SET nom_chinois = ?, nom_latin = ?, status = ?, image = ? WHERE id = ?', [$data['nouvelIngredientChinois'], $data['nouvelIngredientLatin'], $status, $imagePath, $id_ingredient]);
        DB::update('UPDATE ingredient_traduction SET nom = ?, tropisme = ?, nature = ?, saveur = ?, action = ?, partie = ? WHERE idIngredient = ? AND langue = ?', [$data['nouvelIngredient'], $data['tropismeIngredient'], $data['natureIngredient'], $data['saveurIngredient'], $data['actionIngredient'], $data['partieIngredient'], $id_ingredient, $lang]);

        return redirect(route('ingredientsAdmin.show', [$id_ingredient]));
    }

    public function traduction($id_ingredient) {
        $lang=App::getLocale();

        $ingredient = DB::select("SELECT * FROM ingredient WHERE id = ?", [$id_ingredient])[0];
        $traduction = $this->getTraductionById($lang, 'ingredient_traduction', 'idIngredient', $id_ingredient);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'ingredient_traduction', 'idIngredient', $id_ingredient)[0];
        else $traduction = $traduction[0];

        $ingredient->nom = $traduction->nom;
        $ingredient->tropisme = $traduction->tropisme;

        $traductions_restantes = $this->iso;

        $traductions_existantes = DB::select("SELECT langue FROM ingredient_traduction WHERE idIngredient = ?", [$id_ingredient]);
        $references = array();
        foreach ($traductions_existantes as $traductions_existante) {
            $reference = DB::select("SELECT nom, tropisme FROM ingredient_traduction WHERE idIngredient = ? AND langue = ?", [$id_ingredient, $traductions_existante->langue])[0];
            $nom_ingredient_traduction = $reference->nom;
            $tropisme_ingredient_traduction = $reference->tropisme;

            $references['nom'][$this->iso[$traductions_existante->langue]] = $nom_ingredient_traduction;

            if ($tropisme_ingredient_traduction != null) $references['tropisme'][$this->iso[$traductions_existante->langue]] = $tropisme_ingredient_traduction;

            if (array_search($traductions_existante->langue, $traductions_restantes)) unset($traductions_restantes[$traductions_existante->langue]);
        }

        return view('admin.ingredientsAdmin.traduction', compact('ingredient', 'references', 'traductions_restantes'));
    }

    public function updateTraduction($id_ingredient, Request $request) {
        $donnees = $request->input();
        unset($donnees['_token']);
        unset($donnees['_method']);

        $donnees_ingredient = array();

        foreach ($donnees as $key => $donnee) {
            if (stristr($key, 'Ingredient')) $donnees_ingredient['nom'][$key] = $donnee;
            else if (stristr($key, 'Tropisme')) $donnees_ingredient['tropisme'][$key] = $donnee;
        }

        /**************
         * NOM
         **************/

        foreach ($donnees_ingredient['nom'] as $key => $nom_ingredient) {
            if (stristr($key, 'editIngredient__')) {
                $id_ingredient_edit = explode('-', explode('__', $key)[1])[0];
                $langue_ingredient_edit = array_search(explode('-', explode('__', $key)[1])[1], $this->iso);

                DB::update("UPDATE ingredient_traduction SET nom = ? WHERE idIngredient = ? AND langue = ?", [$nom_ingredient, $id_ingredient_edit, $langue_ingredient_edit]);
            } else if (stristr($key, 'addIngredient_')) {
                $langue_ingredient = $donnees_ingredient['nom']['langueIngredient_'.explode('_', $key)[1]];

                $traduction_existante = DB::select("SELECT * FROM ingredient_traduction WHERE idIngredient = ? AND langue = ?", [$id_ingredient, $langue_ingredient]);

                if ($traduction_existante) DB::update("UPDATE ingredient_traduction SET nom = ? WHERE idIngredient = ? AND langue = ?", [$nom_ingredient, $id_ingredient, $langue_ingredient]);
                else DB::insert("INSERT INTO ingredient_traduction (langue, idIngredient, nom) VALUES (?, ?, ?)", [$langue_ingredient, $id_ingredient, $nom_ingredient]);
            }
        }

        /**************
         * TROPISME
         **************/

        if (!empty($donnees_ingredient['tropisme'])) {
            foreach ($donnees_ingredient['tropisme'] as $key => $tropisme_ingredient) {
                if (stristr($key, 'editTropisme__')) {
                    $langue_ingredient_edit = array_search(explode('-', explode('__', $key)[1])[1], $this->iso);

                    DB::update('UPDATE ingredient_traduction SET tropisme = ? WHERE idIngredient = ? AND langue = ?', [$tropisme_ingredient, $id_ingredient, $langue_ingredient_edit]);
                } else if (stristr($key, 'addTropisme')) {
                    $langue_ingredient = $donnees_ingredient['tropisme']['langueTropisme_'.explode('_', $key)[1]];

                    $traduction_existante = DB::select("SELECT * FROM ingredient_traduction WHERE idIngredient = ? AND langue = ?", [$id_ingredient, $langue_ingredient]);

                    if ($traduction_existante) DB::update("UPDATE ingredient_traduction SET tropisme = ? WHERE idIngredient = ? AND langue = ?", [$tropisme_ingredient, $id_ingredient, $langue_ingredient]);
                    else DB::insert("INSERT INTO ingredient_traduction (langue, idIngredient, tropisme) VALUES (?, ?, ?)", [$langue_ingredient, $id_ingredient, $tropisme_ingredient]);
                }
            }
        }

        return redirect(route("ingredientsAdmin.show", [$id_ingredient]));
    }

    // Fonction qui détruit l'ingrédient et toutes les liaisons avec ce dernier
    public function destroy($id_ingredient) {
        $chemin = DB::select('SELECT image FROM ingredient WHERE id = ?', [$id_ingredient])[0]->image;
        File::delete($chemin);

        DB::delete('DELETE FROM ingredient_traduction WHERE idIngredient = ?', [$id_ingredient]);
        DB::delete('DELETE FROM ingredient_detail WHERE idIngredient = ?', [$id_ingredient]);
        DB::delete('DELETE FROM ingredient WHERE id = ?', [$id_ingredient]);

        //$count = 'COUNT(*)';
//
        //$nb_ingredient = DB::select('SELECT COUNT(*) FROM ingredient')[0]->$count;
        //$nb_ingredient_traduction = DB::select('SELECT COUNT(*) FROM ingredient_traduction')[0]->$count;
//
        //DB::update('ALTER TABLE ingredient AUTO_INCREMENT=' . $nb_ingredient);
        //DB::update('ALTER TABLE ingredient_traduction AUTO_INCREMENT=' . $nb_ingredient_traduction);

        return redirect(route('ingredientsAdmin.index'));
    }

    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM ingredient_traduction WHERE langue = ?", [$lang]);
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
