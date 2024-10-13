<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tri extends Model
{
    public static function symptomes($symptomes)
    {
        $symptomes_pas_accent=Tri::array_clone($symptomes);
        $symptomes_avec_accent=Tri::array_clone($symptomes);

        foreach ($symptomes_pas_accent as $symptome_pas_accent ){
            $symptome_pas_accent->nom=Tri::retirerAccents($symptome_pas_accent->nom);
        }

        usort($symptomes_pas_accent, array(Tri::class,"cmp"));

        foreach ($symptomes_pas_accent as $symptome_pas_accent){
            foreach ($symptomes_avec_accent as $symptome_avec_accent){
                if(0===strcmp(Tri::retirerAccents($symptome_avec_accent->nom),$symptome_pas_accent->nom)){
                    $symptome_pas_accent->nom=$symptome_avec_accent->nom;
                    break;
                }
            }
        }
        return $symptomes_pas_accent;
    }
    private static function array_clone($array) {
        return array_map(function($element) {
            return ((is_array($element))
                ? array_clone($element)
                : ((is_object($element))
                    ? clone $element
                    : $element
                )
            );
        }, $array);
    }

    private static function cmp($a, $b)
    {
        return strcmp($a->nom, $b->nom);
    }
    private static function retirerAccents($varMaChaine)
    {
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'œ');
        //Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'oe');

        return str_replace($search, $replace, $varMaChaine); //On retourne le résultat
    }
}
