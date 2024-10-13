<?php /** @noinspection ALL */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SymptomeTraduction extends Model
{
    public $timestamps = false;

    protected $table = 'signes_traduction';
    protected $primaryKey = 'id';
    protected $fillable = ['lang','text','id_signe'];

    public static function getTranslationById($id){
        $first=self::where('id_signe',$id)->where("lang",App::getLocale())->first();
        if(!empty($first)) return $first;
        $second=self::where('id_signe',$id)->where("lang","fr")->first();
        if(!empty($second)) return $second;
        $last=self::where('id_signe',$id)->first();
        return $last;
    }
    public static function getTranslationByIdAllLang($id){
        return self::where('id_signe',$id)->get();
    }
    public static function getTranslationAll(){
        return self::where("lang",App::getLocale())->get();
    }

}
