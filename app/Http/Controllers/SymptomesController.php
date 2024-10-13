<?php

namespace App\Http\Controllers;

use App\Helpers\Tri;
use App\Models\Symptome;
use App\Models\SymptomeTraduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SymptomesController extends Controller
{
    private $iso;

    public function __construct()
    {
        $this->iso = Config::get("iso");
    }

    // Fonction qui permet d'afficher tous les symptomes dans une liste index
    public function index(){
        $symptomes = collect([]);
        foreach (Symptome::whereNull('id_parent')->cursor() as $symptome) {
            $synonymes = Symptome::getSynonymesOf($symptome->id);
            $symptome->synonymes=$synonymes;
            $symptomes->push($symptome);
        }
        //$symptomes=Tri::symptomes($symptomes);

        return view("admin.symptomes.index", compact("symptomes"));
    }

    // Fonction permettant d'effectuer une recherche dans les listes
    public function search(Request $request) {
        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = $request->input('search');
        $search = $this->retirerAccents( $search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('symptomes.index'));

        $symptomes = Symptome::getAllSymptomes();
        $search=self::retirerAccents($search);
        $symptomes=$symptomes->map(function($symptome) use ($search) {
            $synonymes = Symptome::getSynonymesOf($symptome->id);
            $symptome->synonymes=$synonymes;
            if(stristr(self::retirerAccents($symptome->traduction->text), $search))return $symptome;
            $present=$synonymes->contains(function ($value, $key) use ($search) {
                return stristr(self::retirerAccents($value->traduction->text), $search);
            });
            if($present)return $symptome;

        })->reject(function ($name) {
            return empty($name);
        });

        // Retourne la vue liste.blade.php du dossier symptome avec comme arguments les symptomes
        return view('admin.symptomes.index', compact('symptomes'));
    }

    // Fonction qui ramène l'utilisateur vers la page de création d'un symptôme quand on clique sur le +
    public function create(){
        return view('admin.symptomes.create');
    }

    // Fonction qui permet d'enregistrer les modification ou la création d'un symptome
    public function store( Request $request){
        $lang=App::getLocale();

        // On demande à l'utilisateur d'entrer le nom du nouveau symptome
        $data = $request->input();
        $request->validate([
            'nouveauSymptome' => 'required',
        ]);

        $newsymptome = Symptome::create([]);
        $newsymptome ->save();
        $newtrad = SymptomeTraduction::create([
            'lang' => $lang,
            'text' => $data["nouveauSymptome"],
            'id_signe' => $newsymptome->id
        ]);
        $newtrad->save();

        unset($data["nouveauSymptome"]);
        unset($data["_token"]);

        foreach ($data as $synonyme) {
            if($synonyme != null){
                $newsynonyme = Symptome::create(['id_parent'=>$newsymptome->id]);
                $newsynonyme  ->save();
                $newtrad = SymptomeTraduction::create([
                    'lang' => $lang,
                    'text' => $synonyme,
                    'id_signe' => $newsynonyme ->id
                ]);
                $newtrad->save();
            }
        }
        return redirect(route('symptomes.index'));
    }


    // Ensemble des fonctions utilisées dans show permettant d'afficher les éléments
    public function show(int $id_symptome){
        $lang=App::getLocale();

        // On récupère toutes les information dans la BDD grace a l'ID du symptome choisis
        $symptome = Symptome::find($id_symptome);
        $synonymes = Symptome::getSynonymesOf($id_symptome);


        $syndrome_detail = DB::select("SELECT idSyndrome, score FROM syndrome_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $syndromes = [];
        foreach ($syndrome_detail as $key => $value) {
            $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$value->idSyndrome])[0];
            $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $syndrome->id);

            if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0];
            else $traduction_syndrome = $traduction_syndrome[0];

            $syndrome->nom = $traduction_syndrome->nom;
            $syndrome->score = $value->score;

            array_push($syndromes, $syndrome);
        }

        $formule_detail = DB::select("SELECT idFormule, score FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $formules = [];
        foreach ($formule_detail as $key => $value) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$value->idFormule])[0];
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $formule->id);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;
            $formule->score = $value->score;

            array_push($formules,$formule);
        }

        $formules = collect($formules)->sortBy('nom_langue')->toArray();
        $synonymes = Tri::symptomes($synonymes);
        $syndromes = collect($syndromes)->sortBy('nom')->toArray();

        return view('admin.symptomes.show', compact('symptome',"synonymes","formules","syndromes") );
    }

    // Fonction de recherche des syndromes liés au symptome
    public function searchSyndromes(int $id_symptome, Request $request) {
        $lang=App::getLocale();

        $search = $request->input('Syndromesearch');

        $symptome = DB::select("SELECT * FROM symptome WHERE id = ?", [$id_symptome])[0];
        $traduction = $this->getTraductionById($lang, 'symptome_traduction', 'idSymptome', $id_symptome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'symptome_traduction', 'idSymptome', $id_symptome)[0];
        else $traduction = $traduction[0];

        $symptome->nom = $traduction->nom;

        $synonymes = DB::select('SELECT * FROM symptome_synonyme WHERE idSymptome = ?', [$id_symptome]);
        foreach ($synonymes as $synonyme) {
            $traduction_synonyme = $this->getTraductionById($lang, 'synonyme_traduction', 'idSynonyme', $synonyme->id);

            if (empty($traduction_synonyme)) $traduction_synonyme = $this->getTraductionById('fr', 'synonyme_traduction', 'idSynonyme', $synonyme->id)[0];
            else $traduction_synonyme = $traduction_synonyme[0];

            $synonyme->nom = $traduction_synonyme->nom;
        }

        $syndrome_detail = DB::select("SELECT idSyndrome, score FROM syndrome_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $syndromes = [];
        foreach ($syndrome_detail as $key => $value) {
            $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$value->idSyndrome])[0];
            $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $value->idSyndrome);

            if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $value->id)[0];
            else $traduction_syndrome = $traduction_syndrome[0];

            $syndrome->nom = $traduction_syndrome->nom;

           if (stristr($syndrome->nom, $search)) {
               $syndrome->score = $value->score;
               array_push($syndromes, $syndrome);
           }
        }

        $formule_detail = DB::select("SELECT idFormule, score FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $formules = [];
        foreach ($formule_detail as $key => $value) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$value->idFormule])[0];
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $formule->id);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $formule->id)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;
            $formule->score = $value->score;

            array_push($formules,$formule);
        }


        return view('admin.symptomes.show', compact('symptome', "synonymes", "formules", "syndromes") );
    }

    // Fonction de recherche des formules liés au symptome
    public function searchFormules(int $id_symptome, Request $request) {
        $lang=App::getLocale();

        $search = $request->input('Formulesearch');

        $symptome = DB::select("SELECT * FROM symptome WHERE id = ?", [$id_symptome])[0];
        $traduction = $this->getTraductionById($lang, 'symptome_traduction', 'idSymptome', $id_symptome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'symptome_traduction', 'idSymptome', $id_symptome)[0];
        else $traduction = $traduction[0];

        $symptome->nom = $traduction->nom;

        $synonymes = DB::select('SELECT * FROM symptome_synonyme WHERE idSymptome = ?', [$id_symptome]);
        foreach ($synonymes as $synonyme) {
            $traduction_synonyme = $this->getTraductionById($lang, 'synonyme_traduction', 'idSynonyme', $synonyme->id);

            if (empty($traduction_synonyme)) $traduction_synonyme = $this->getTraductionById('fr', 'synonyme_traduction', 'idSynonyme', $synonyme->id)[0];
            else $traduction_synonyme = $traduction_synonyme[0];

            $synonyme->nom = $traduction_synonyme->nom;
        }

        $syndrome_detail = DB::select("SELECT idSyndrome, score FROM syndrome_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $syndromes = [];
        foreach ($syndrome_detail as $key => $value) {
            $syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$value->idSyndrome])[0];
            $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $syndrome->id);

            if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0];
            else $traduction_syndrome = $traduction_syndrome[0];

            $syndrome->nom = $traduction_syndrome->nom;
            $syndrome->score = $value->score;

            array_push($syndromes, $syndrome);
        }

        $formule_detail = DB::select("SELECT idFormule, score FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);
        $formules = [];
        foreach ($formule_detail as $key => $value) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$value->idFormule])[0];
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $value->idFormule);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $value->id)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;

            if (stristr($formule->nom, $search) || stristr($formule->nom_chinois, $search) || stristr($formule->nom_langue, $search)) {
                $formule->score = $value->score;
                array_push($formules, $formule);
            }
        }


        return view('admin.symptomes.show', compact('symptome', "synonymes", "formules", "syndromes") );
    }

    // Fonction permettant d'éditer un symptome déja existant
    public function edit($id_symptome) {
        $symptome = Symptome::find($id_symptome);
        $synonymes = Symptome::getSynonymesOf($id_symptome);
        $synonymes = Tri::symptomes($synonymes);
        $concordance=Symptome::getConcordantOf($symptome->id);
        return view('admin.symptomes.edit', compact("symptome", "synonymes","concordance"));
    }

    // Fonction qui permet de mettre a jour un symptome une fois éditer
    public function update($id_symptome, Request $request) {
        $request->validate([
            "editSymptome" => "required|min:1"
        ]);

        $donnees = $request->input();
        $nom_symptome = $request->input("editSymptome");
        $concordance = $request->input("editConcordance");
        $concordant_activate_symptome = $request->input("concordance_symptome");
        unset($donnees['editSymptome']);
        unset($donnees['_token']);
        unset($donnees['_method']);

        $symptome = Symptome::find($id_symptome);


        $symptome->traduction->text=$nom_symptome;
        $symptome->traduction->save();

        $symptome->concordant_activate=isset($concordant_activate_symptome);
        $symptome->save();

        if(isset($concordance)){
            $conc=Symptome::getConcordantOf($symptome->id);
            if($conc){
                $conc->traduction->text=$concordance;
                $conc->traduction->save();
            }
            else{
                $newconcordance = Symptome::create(['id_parent'=>$symptome->id,'is_concordant'=>true]);
                $newconcordance->save();
                $newconcordancetrad = SymptomeTraduction::create([
                    'lang' => 'fr',
                    'text' => $concordance,
                    'id_signe' => $newconcordance->id
                ]);
                $newconcordancetrad->save();
            }
        }

        $donnees_synonymes = array();
        $donnees_concordants = array();
        $donnees_a_supprimer = collect([]);
        foreach ($donnees as $key => $donnee) {
            if(stristr($key, 'delete'))$donnees_a_supprimer->push($key);
            else if (stristr($key, 'Synonyme')) $donnees_synonymes[$key] = $donnee;
            else if (stristr($key, 'concordant')) {
                $key=explode('_', $key)[1];
                $donnees_concordants[$key] = $donnee;
            }
        }
        /**************
         *  SYNONYMES *
         **************/

        foreach ($donnees_synonymes as $key => $synonyme) {
            $id=explode('__',$key)[1];
            $concordant_activate= isset($donnees_concordants[$id]);

            if (stristr($key, 'editSynonyme__')) {
                $id_synonyme = explode('__', $key)[1];
                $syn=Symptome::find($id_synonyme);
                $syn->traduction->text=$synonyme;
                $syn->traduction->save();
                $syn->concordant_activate=$concordant_activate;
                $syn->save();
            } else if (stristr($key, 'addSynonyme__')) {
                if($synonyme != null){
                    $newsynonyme = Symptome::create(['id_parent'=>$id_symptome]);
                    $newsynonyme->save();
                    $newtradsyn = SymptomeTraduction::create([
                        'lang' => 'fr',
                        'text' => $synonyme,
                        'id_signe' => $newsynonyme->id
                    ]);
                    $newtradsyn->save();
                }
            }
        }
        foreach ($donnees_a_supprimer as $donnee){
            $id=explode("_",$donnee)[1];
            $syn=Symptome::find($id);
            $alltrad=SymptomeTraduction::getTranslationByIdAllLang($id);
            foreach ($alltrad as $trad){
                $trad->delete();
            }
            $syn->delete();
        }

        return redirect(route("symptomes.show", [$id_symptome]));
    }

    public function traduction($id_symptome) {

        $symptome = Symptome::find($id_symptome);
        $synonymes = Symptome::getAllChildren($id_symptome);

        $traductions_synonymes = $synonymes->map(function($synonyme){
            $traductions =  SymptomeTraduction::getTranslationByIdAllLang($synonyme->id);
            return [$synonyme->id => $traductions];
        });

        /*************
         * SYMPTOMES
         *************/

        $traductions_restantes = $this->iso;

        $traductions_symptomes =  SymptomeTraduction::getTranslationByIdAllLang($id_symptome);

        return view('admin.symptomes.traduction', compact('symptome', 'synonymes','traductions_restantes', 'traductions_symptomes', 'traductions_synonymes'));
    }

    public function updateTraduction($id_symptome, Request $request) {
        $donnees = $request->input();
        unset($donnees['_token']);
        unset($donnees['_method']);
        $donnees_symptomes = array();
        $donnees_synonymes = array();
        $donnees_a_supprimer = collect([]);
        foreach ($donnees as $key => $donnee) {
            if (stristr($key, 'delete'))$donnees_a_supprimer->push($key);
            if (stristr($key, 'Symptome')) $donnees_symptomes[$key] = $donnee;
            else if (stristr($key, 'Synonyme')) $donnees_synonymes[$key] = $donnee;
        }

        /**************
         * SYMPTOMES
         **************/
        foreach ($donnees_symptomes as $key => $symptome) {
            if (stristr($key, 'editSymptome__')) {
                $id_symp_edit = explode('-', explode('__', $key)[1])[0];
                $symp=SymptomeTraduction::find($id_symp_edit);
                $symp->text=$symptome;
                $symp->save();
            } else if (stristr($key, 'addSymptome_')) {
                $langue_symptome = $donnees_symptomes['langueSymptome_'.explode('_', $key)[1]];
                $newtradsyn = SymptomeTraduction::create([
                    'lang' => $langue_symptome,
                    'text' => $symptome,
                    'id_signe' => $id_symptome
                ]);
                $newtradsyn->save();

            }
        }

        /**************
         * SYNONYMES
         **************/

        foreach ($donnees_synonymes as $key => $synonyme) {
            if (stristr($key, 'editSynonyme__')) {
                $id_synonyme = explode('-', explode('__', $key)[1])[0];
                $symp=SymptomeTraduction::find($id_synonyme);
                $symp->text=$synonyme;
                $symp->save();
            } else if (stristr($key, 'addSynonyme')) {

                $langue_synonyme = $donnees_synonymes['langueSynonyme'.explode('addSynonyme', $key)[1]];
                $id_synonyme =explode('_',explode('addSynonyme', $key)[1])[0];
                $newtradsyn = SymptomeTraduction::create([
                    'lang' => $langue_synonyme,
                    'text' => $synonyme,
                    'id_signe' => $id_synonyme
                ]);
                $newtradsyn->save();
            }
        }
        foreach ($donnees_a_supprimer as $donnee) {
            $id=explode("_",$donnee)[1];
            $trad=SymptomeTraduction::find($id);
            if($trad && $trad->lang!="fr")$trad->delete();
        }

        return redirect(route("symptomes.show", [$id_symptome]));
    }

    // Fonction qui détruit le symptome et toutes les liaisons avec ce dernier
    public function destroy($id_symptome){
        $symptome_alltrad = SymptomeTraduction::getTranslationByIdAllLang($id_symptome);
        $symptome_alltrad->push(Symptome::find($id_symptome));
        $synonyme_alltrad = collect([]);
        Symptome::getAllChildren($id_symptome)->map(function($syn) use ($synonyme_alltrad) {
            $synonyme_alltrad->push($syn);
            SymptomeTraduction::getTranslationByIdAllLang($syn->id)->map(function($trad) use ($synonyme_alltrad) {
                $synonyme_alltrad->push($trad);
            });
        });
        $synonyme_alltrad->map(function($trad){
            $trad->delete();
        });
        $symptome_alltrad->map(function($trad){
            $trad->delete();
        });

        DB::delete('DELETE FROM syndrome_detail WHERE idSymptome = ?', [$id_symptome]);
        DB::delete('DELETE FROM formule_detail WHERE idSymptome = ?', [$id_symptome]);

        return redirect(route('symptomes.index'));
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

