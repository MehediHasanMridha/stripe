<?php
namespace App\Helpers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Traduction
{

    /**
     * Retourne une array contenant toutes les traductions existantes se trouvant dans la table ingredient_traduction
     * et où la langue est égale à celle donnée en paramètre
     *
     * @param string|null $lang
     * @return array
     */
    public static function getTraductionByLang(string $table, string $lang=null)
    {
        return (DB::table($table)
                ->where("langue",$lang?:App::getLocale())
                ->get()->toArray());
    }


    /**
     * Retourne une array contenant la traduction contenu dans la table donnée en paramètre et où la langue est
     * égale à celle donnée en paramètre et où la colonne donnée en paramètre est égale à l'élément donnée en paramètre
     *
     * @param string|null $lang
     * @param string $table
     * @param string $colonne
     * @param int $id
     * @return array
     */
    public static function getTraductionById(string $table, string $colonne, int $id, string $lang=null){
        return (DB::table($table)
            ->where("langue",$lang?:App::getLocale())
            ->where($colonne,$id)
            ->get()->toArray());
    }

    public static function getTraductionByIdFirst(string $table, string $colonne, int $id, string $lang=null){
        $traduction = DB::table($table)
            ->where("langue",$lang?:App::getLocale())
            ->where($colonne,$id)
            ->first();
        return $traduction?:Traduction::getTraductionByIdFirst($table, $colonne, $id, "fr");
    }
}
