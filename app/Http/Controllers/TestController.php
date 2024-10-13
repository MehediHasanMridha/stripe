<?php

namespace App\Http\Controllers;

use App\Models\Symptome;
use App\Models\SymptomeTraduction;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index(){
        //self::migration();
        return "ok";
    }
    public function migration(){
        $all_symptomess = DB::select("SELECT * FROM symptome_traduction");
        $all_synonymes = DB::select("SELECT symptome_synonyme.idSymptome, synonyme_traduction.nom
                                     FROM `synonyme_traduction`
                                     INNER JOIN `symptome_synonyme` ON symptome_synonyme.id=synonyme_traduction.idSynonyme
                                     WHERE synonyme_traduction.langue='fr';");
        foreach ($all_symptomess as $symtpome){
            if($symtpome->langue == "fr") {
                $newsymptome = Symptome::create([]);
                $newsymptome ->save();
                $newtrad = SymptomeTraduction::create([
                    'lang' => 'fr',
                    'text' => $symtpome->nom,
                    'id_signe' => $newsymptome->id
                ]);
                $newtrad->save();
                DB::update("UPDATE syndrome_detail
                            SET idSymptome = ?
                            WHERE idSymptomeOld = ?;",[$newsymptome->id,$symtpome->idSymptome]);
                DB::update("UPDATE formule_detail
                            SET idSymptome = ?
                            WHERE idSymptomeOld = ?;",[$newsymptome->id,$symtpome->idSymptome]);
                foreach ($all_synonymes as $synonyme){

                    if($synonyme->idSymptome==$symtpome->idSymptome){
                        $newsynonyme = Symptome::create(['id_parent'=>$newsymptome->id]);
                        $newsynonyme->save();
                        $newtradsyn = SymptomeTraduction::create([
                            'lang' => 'fr',
                            'text' => $synonyme->nom,
                            'id_signe' => $newsynonyme->id
                        ]);
                        $newtradsyn->save();
                    }
                }
            }
        }
    }
}
