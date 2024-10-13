<?php namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Tri
{
    public static function symptomes($symptomes)
    {
        $symptomes_pas_accent=self::clone($symptomes)->map(function($symptome){
            $symptome->text=self::retirerAccents($symptome->traduction->text);
            return $symptome;
        });
        $symptomes_avec_accent=self::clone($symptomes);

        $symptomes_pas_accent=$symptomes_pas_accent->sortBy("text");
        $symptomes_final=$symptomes_pas_accent->map(function ($symptome) use ($symptomes_avec_accent) {
            return $symptomes_avec_accent->where('id',$symptome->id)->first();
        });

        return $symptomes_final;
    }

    private static function clone($symptomes){
        return collect($symptomes)->map(function($symptome){
            return clone $symptome;
        });
    }

    private static function retirerAccents($varMaChaine)
    {
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'œ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'oe');

        return str_replace($search, $replace, $varMaChaine); //On retourne le résultat
    }
}
