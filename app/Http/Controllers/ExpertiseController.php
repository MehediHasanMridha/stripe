<?php

namespace App\Http\Controllers;

use App\Models\Symptome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;


class ExpertiseController extends Controller
{
    // Fonction qui retourne la vue index.blade.php du dossier expertise
    // (Vue qui permet choisir entre l'expertise par symptômes ou par syndromes)
    public function index() {
        return view('expertise.index');
    }

    // Fonction qui retourne la vue symptomes.blade.php du dossier expertise
    // (Vue qui permet de sélectioner les symptomes pour l'expertise)
    public function symptome() {
        $symptomes = Symptome::all();
        $symptomes=$symptomes->map(function($symptome){
            $symptome->traduction;
            $symptome->concordant;
            return $symptome;
        });
        /* Récupère les pondérations en BDD */
        $ponderation_detail = DB::select("SELECT * FROM expertise");
        $ponderations = [];
        foreach ($ponderation_detail as $key => $value) {

            if($value->id == 1){
                $ponderation1 = $value->valeur;
                array_push($ponderations,$ponderation1);
            }
            if($value->id == 2){
                $ponderation2 = $value->valeur;
                array_push($ponderations,$ponderation2);
            }
            if($value->id == 3){
                $ponderation3 = $value->valeur;
                array_push($ponderations,$ponderation3);
            }
            if($value->id == 4){
                $ponderation4 = $value->valeur;
                array_push($ponderations,$ponderation4);
            }
            if($value->id == 5){
                $ponderation5 = $value->valeur;
                array_push($ponderations,$ponderation5);
            }
        }

        /* Retourne la vue symptomes.blade.php du dossier expertise avec les symptomes (qui contiennent aussi les synonymes) et les pondérations.*/
        return view('expertise.symptomes', compact('symptomes', 'ponderations'));
    }

    // Fonction qui retourne la vue show.blade.php du dossier expertise
    // (Vue qui affiche les résultats d'une expertise)
    public function show(Request $request) {
        $lang=App::getLocale();
        $singlemode=$request->input('single');

        $liste_symptomes = [];
        if($singlemode){
            $liste_symptomes = $request->validate([
                'symptome1' => 'required'
            ]);
        }
        else{
            $liste_symptomes = $request->validate([
                'symptome1' => 'required',
                'symptome2' => 'required',
                'symptome3' => 'required',
                'symptome4' => 'sometimes',
                'symptome5' => 'sometimes'
            ]);
        }

        $ponderation1 = $request->input('ponderation1');
        $ponderation2 = $request->input('ponderation2');
        $ponderation3 = $request->input('ponderation3');
        $ponderation4 = $request->input('ponderation4');
        $ponderation5 = $request->input('ponderation5');

        for($index=1;$index<sizeof($liste_symptomes)+1;$index++){
            $ponderation="ponderation".$index;
            DB::update('UPDATE expertise SET valeur = ? WHERE id = ?', [$$ponderation,$index]);
        }
        $SCORE_SYMPTOME = [$ponderation1, $ponderation2, $ponderation3, $ponderation4, $ponderation5];

        $symptomes = collect([]);
        foreach ($liste_symptomes as $symptome){
            $symptomes->push(Symptome::getParentById($symptome));
        }
        $liste_formules = [];
        $liste_syndromes = [];
        foreach($symptomes as $idx => $symptome) {
            /*********************
             * FORMULE
             **********************/

            $formules_positives = DB::select('SELECT * FROM formule_detail WHERE idSymptome = ? AND score > 0', [$symptome->id]);

            if (sizeof($formules_positives) > 0) {
                foreach ($formules_positives as $formule) {
                    $formule->score += $SCORE_SYMPTOME[$idx];
                    array_push($liste_formules, $formule);
                }
            }

            /*********************
             * SYNDROME
             **********************/

            $syndromes_positifs = DB::select('SELECT * FROM syndrome_detail WHERE idSymptome = ? AND score > 0', [$symptome->id]);

            if (sizeof($syndromes_positifs) > 0) {
                foreach ($syndromes_positifs as $syndrome) {
                    $syndrome->score += $SCORE_SYMPTOME[$idx];
                    array_push($liste_syndromes, $syndrome);
                }
            }
        }

        /*********************
         * FORMULE
         **********************/

        $formules = [];
        $proba_formules = [];

        if (sizeof($liste_formules) > 0) {
            $score_formules = array();

            foreach($liste_formules as $formule) $score_formules[$formule->idFormule] = 0;

            foreach($liste_formules as $formule) $score_formules[$formule->idFormule] += $formule->score;

            for($i = 0; $i < 3; $i++) {
                if (sizeof($score_formules) == 0) {
                    break;
                }

                $score_max_formule = max($score_formules);
                $id_formule_max = array_search($score_max_formule, $score_formules);

                array_push($proba_formules, $score_max_formule);

                $traduction_formule = $this->getTraductionById($lang, 'formule_traduction', 'idFormule', (int)[$id_formule_max]);

                if (empty($traduction_formule)) $traduction_formule = $this->getTraductionById('fr', 'formule_traduction', 'idFormule', (int)$id_formule_max)[0];
                else $traduction_formule = $traduction_formule[0];

                $nouvelle_formule = DB::select("SELECT * FROM formule WHERE id = ?", [$id_formule_max])[0];
                $nouvelle_formule->nom_langue = $traduction_formule->nom_langue;

                array_push($formules, $nouvelle_formule);

                unset($score_formules[array_search($score_max_formule, $score_formules)]);
            }

            $sommes_proba = array_sum($proba_formules);
            for($i = 0; $i < sizeof($proba_formules); $i++) {
                $proba_formules[$i] = (int)(($proba_formules[$i] * 100) / $sommes_proba);
            }
        }

        /*********************
         * SYNDROME
         **********************/

        $syndromes = [];
        $proba_syndromes = [];
        if(sizeof($liste_syndromes) > 0) {
            $score_syndromes = array();

            foreach($liste_syndromes as $syndrome) $score_syndromes[$syndrome->idSyndrome] = 0;

            foreach($liste_syndromes as $syndrome) {
                $score_syndromes[$syndrome->idSyndrome] += $syndrome->score;
            }

            for($i = 0; $i < 3; $i++) {
                if (sizeof($score_syndromes) == 0) break;

                $score_max_syndrome = max($score_syndromes);
                $id_syndrome_max = array_search($score_max_syndrome, $score_syndromes);

                array_push($proba_syndromes, $score_max_syndrome);

                $traduction_syndrome = $this->getTraductionById($lang, 'syndrome_traduction', 'idSyndrome', $id_syndrome_max);

                if (empty($traduction_syndrome)) $traduction_syndrome = $this->getTraductionById('fr', 'syndrome_traduction', 'idSyndrome', (int)$id_syndrome_max)[0];
                else $traduction_syndrome = $traduction_syndrome[0];

                $nouveau_syndrome = DB::select("SELECT * FROM syndrome WHERE id = ?", [$id_syndrome_max])[0];
                $nouveau_syndrome->nom = $traduction_syndrome->nom;

                array_push($syndromes, $nouveau_syndrome);

                unset($score_syndromes[$id_syndrome_max]);
            }

            $sommes_proba = array_sum($proba_syndromes);
            for($i = 0; $i < sizeof($proba_syndromes); $i++) {
                $proba_syndromes[$i] = (int)(($proba_syndromes[$i] * 100) / $sommes_proba);
            }
        }

        $liste_symptomes=collect($liste_symptomes)->map(function($symptome){
           return Symptome::find($symptome)->traduction->text;
        });

        return view('expertise.show', compact('symptomes', 'liste_symptomes', 'formules', 'syndromes', 'proba_syndromes', 'proba_formules'));
    }

    private function getTraductionByLang(string $lang, string $table): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ?", [$lang]);
    }

    private function getTraductionById(string $lang, string $table, string $colonne, int $id): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ? AND ".$colonne." = ?", [$lang, $id]);
    }
}
