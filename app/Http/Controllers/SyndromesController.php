<?php

namespace App\Http\Controllers;

use App\Helpers\Tri;
use App\Models\Symptome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SyndromesController extends Controller
{

    public function liste() {
        $lang=App::getLocale();

        // Sélectionne toutes les données de toutes les lignes de la table syndrome
        $syndromes = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_syndromes = DB::select("SELECT * FROM syndrome ORDER BY nom");
        foreach ($all_syndromes as $syndrome) {
            $default_traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0]->nom;
            $syndrome->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($syndrome->id == $traduction->idSyndrome) $syndrome->nom = $traduction->nom;
            }

            array_push($syndromes, $syndrome);
        }
        $syndromes = collect($syndromes)->sortBy('nom')->toArray();
        // Retourne la vue liste.blade.php du dossier syndromes avec comme arguments la variable syndrome
        return view('syndromes.liste', compact('syndromes'));
    }


    public function search(Request $request) {
        $lang=App::getLocale();

        // Construit une variable search avec '%(la valeur inscrite dans le input search)%'
        $search = $request->input('search');
        $search = $this->retirerAccents( $search);

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('syndromes.liste'));

        // Sélectionne toutes les données des lignes où il y a la valeur inscrite dans le input search de la table syndrome
        $syndromes = [];
        $traductions = $this->getTraductionByLang($lang);

        $all_syndromes = DB::select("SELECT * FROM syndrome");
        foreach ($all_syndromes as $syndrome) {
            $default_traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $syndrome->id)[0]->nom;
            $syndrome->nom = $default_traduction;

            foreach ($traductions as $traduction) {
                if ($syndrome->id == $traduction->idSyndrome) $syndrome->nom = $traduction->nom;
            }

            if (stristr($this->retirerAccents($syndrome->nom), $search)) array_push($syndromes, $syndrome);
        }

        // Retourne la vue liste.blade.php du dossier syndromes avec comme arguments la varibale syndrome
        return view('syndromes.liste', compact('syndromes'));
    }


    public function show($id_syndrome) {
        $lang=App::getLocale();

        $syndrome = DB::select('SELECT * FROM syndrome WHERE id = ?', [$id_syndrome])[0];
        $traduction = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $id_syndrome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $id_syndrome)[0];
        else $traduction = $traduction[0];

        $syndrome->nom = $traduction->nom;


        /*********************
         * SYMPTOMES
         ********************/

        // Sélectionne toutes les données des lignes où idSyndrome est égale à la variable id_syndrome de la table syndrome_detail
        $detail_syndromes = DB::select('SELECT * FROM syndrome_detail WHERE idSyndrome = ? AND score > 0', [$id_syndrome]);

        // Pour chaque ligne récupérée de la requete contenu dans detail_syndromes
        $liste_score_symptomes = array();
        foreach($detail_syndromes as $detail_syndrome) {
            // On ajoute à l'array $liste_score_symptomes à la clé l'idSymptome, le score
            $liste_score_symptomes[$detail_syndrome->idSymptome] = $detail_syndrome->score;
        }

        // Récupère les 5 symptomes qui ont le meilleur scores pour ce syndrome
        $id_symptomes = [];
        for($i = 0; $i < 5; $i++) {

            if (empty($liste_score_symptomes)) break;

            $max_symptome = max($liste_score_symptomes);
            $id_max_symptome = array_search($max_symptome, $liste_score_symptomes);

            array_push($id_symptomes, $id_max_symptome);

            unset($liste_score_symptomes[array_search($max_symptome, $liste_score_symptomes)]);
        }


        /*********************
         * FORMULES
         ********************/

        $liste_score_formule = array();
        foreach ($id_symptomes as $id_symptome) {
            $detail_formules = DB::select("SELECT * FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);

            foreach ($detail_formules as $detail_formule) $liste_score_formule[$detail_formule->idFormule] = 0;

            foreach ($detail_formules as $detail_formule) {
                $liste_score_formule[$detail_formule->idFormule] += $detail_formule->score;
            }
        }

        $id_formules = [];
        for($i = 0; $i < 3; $i++) {

            if (empty($liste_score_formule)) break;

            $max_formule = max($liste_score_formule);
            $id_max_formule = array_search($max_formule, $liste_score_formule);

            array_push($id_formules, $id_max_formule);

            unset($liste_score_formule[$id_max_formule]);
        }

        $formules = [];
        foreach ($id_formules as $id_formule) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule])[0];
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;

            array_push($formules, $formule);
        }


        // Pour chaque valeur contenu dans le tableau id_symptomes

        $symptomes=collect($detail_syndromes)->map(function($detail){
            Log::info(print_r($detail,true));
            return Symptome::find($detail->idSymptome);
        });

        $symptomes = Tri::symptomes($symptomes);
        $formules = collect($formules)->sortBy('nom_langue')->toArray();

        // Retourne la vue show.blade.php du dossier syndromes
        return view('syndromes.show', compact('syndrome', 'symptomes', 'formules'));
    }

    public function symptomeSearch($id_syndrome, Request $request) {
        $lang=App::getLocale();

        $search = $request->input('Symptomesearch');

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('syndromes.show', [$id_syndrome]));

        $syndrome = DB::select('SELECT * FROM syndrome WHERE id = ?', [$id_syndrome])[0];
        $traduction = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $id_syndrome);

        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', $id_syndrome)[0];
        else $traduction = $traduction[0];

        $syndrome->nom = $traduction->nom;


        /*********************
         * SYMPTOMES
         ********************/

        // Sélectionne toutes les données des lignes où idSyndrome est égale à la variable id_syndrome de la table syndrome_detail
        $detail_syndromes = DB::select('SELECT * FROM syndrome_detail WHERE idSyndrome = ? AND score > 0', [$id_syndrome]);

        // Pour chaque ligne récupérée de la requete contenu dans detail_syndromes
        $liste_score_symptomes = array();
        foreach($detail_syndromes as $detail_syndrome) {
            // On ajoute à l'array $liste_score_symptomes à la clé l'idSymptome, le score
            $liste_score_symptomes[$detail_syndrome->idSymptome] = $detail_syndrome->score;
        }

        // Récupère les 5 symptomes qui ont le meilleur scores pour ce syndrome
        $id_symptomes = [];
        for($i = 0; $i < 5; $i++) {
            $max_symptome = max($liste_score_symptomes);
            $id_max_symptome = array_search($max_symptome, $liste_score_symptomes);

            array_push($id_symptomes, $id_max_symptome);

            unset($liste_score_symptomes[array_search($max_symptome, $liste_score_symptomes)]);
        }


        /*********************
         * FORMULES
         ********************/

        $liste_score_formule = array();
        foreach ($id_symptomes as $id_symptome) {
            $detail_formules = DB::select("SELECT * FROM formule_detail WHERE idSymptome = ? AND score > 0", [$id_symptome]);

            foreach ($detail_formules as $detail_formule) $liste_score_formule[$detail_formule->idFormule] = 0;

            foreach ($detail_formules as $detail_formule) {
                $liste_score_formule[$detail_formule->idFormule] += $detail_formule->score;
            }
        }

        $id_formules = [];
        for($i = 0; $i < 3; $i++) {
            $max_formule = max($liste_score_formule);
            $id_max_formule = array_search($max_formule, $liste_score_formule);

            array_push($id_formules, $id_max_formule);

            unset($liste_score_formule[$id_max_formule]);
        }

        $formules = [];
        foreach ($id_formules as $id_formule) {
            $formule = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule])[0];
            $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', $id_formule);

            if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', $id_formule)[0];
            else $traduction_formule = $traduction_formule[0];

            $formule->nom_langue = $traduction_formule->nom_langue;

            array_push($formules, $formule);
        }


        // Pour chaque valeur contenu dans le tableau id_symptomes
        $symptomes = [];
        foreach ($detail_syndromes as $detail_syndrome) {
            $traduction_symptome = $this->getTraductionById($lang, 'symptome_traduction', 'idSymptome', $detail_syndrome->idSymptome);

            if (empty($traduction_symptome)) $traduction_symptome = $this->getTraductionById('fr', 'symptome_traduction', 'idSymptome', $detail_syndrome->idSymptome)[0];
            else $traduction_symptome = $traduction_symptome[0];

            if (stristr($traduction_symptome->nom, $search)) {
                $symptome = DB::select("SELECT * FROM symptome WHERE id = ?", [$detail_syndrome->idSymptome])[0];
                $symptome->nom = $traduction_symptome->nom;

                array_push($symptomes, $symptome);
            }

        }

        // Retourne la vue show.blade.php du dossier syndromes
        return view('syndromes.show', compact('syndrome', 'symptomes', 'formules'));
    }

    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM syndrome_traduction WHERE langue = ?", [$lang]);
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
