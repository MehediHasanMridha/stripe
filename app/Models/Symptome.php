<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;


class Symptome extends Model
{
    public $timestamps = false;
    protected $table = 'signes';
    protected $primaryKey = 'id';
    public $score;

    protected $fillable = ['id_parent','is_concordant','concordant_activate'];


    public function traduction()
    {
        return $this->hasOne(SymptomeTraduction::class, 'id_signe')->where("lang",App::getLocale())->withDefault();;
    }
    public function synonymes()
    {
        return $this->hasMany(Symptome::class, 'id_parent');
    }
    public function concordant()
    {
        if(!$this->concordant_activate) return $this->hasOne(Symptome::class,'id_parent')->where("id_parent",$this->id_parent)->whereNotNull('is_concordant')->whereNull('is_concordant');
        if($this->id_parent) return $this->hasOne(Symptome::class,'id_parent',"id_parent")->where("id_parent",$this->id_parent)->whereNotNull('is_concordant');
        else return $this->hasOne(Symptome::class,'id_parent',"id")->where("id_parent",$this->id)->whereNotNull('is_concordant');
    }

    public static function getAllSymptomes(){
        return self::whereNull('id_parent')->whereNull('is_concordant')->get();
    }



    public function getParent(){
        $parent=self::find($this->id_parent);
        return $parent?:$this;
    }
    public static function getParentById($id){
        $symp=Symptome::find($id);
        $parent=self::find($symp->id_parent);
        return $parent?:$symp;
    }

    public static function getAllSynonyme(){
        return self::whereNotNull('id_parent')->whereNull('is_concordant')->get();
    }

    public static function getSynonymesOf($id_main){
        return self::where('id_parent',$id_main)->whereNull('is_concordant')->get();
    }
    public static function getAllChildren($id_main){
        return self::where('id_parent',$id_main)->get();
    }

    public static function getConcordantOf($id_main){
        $concordant=self::where('id_parent',$id_main)->whereNotNull('is_concordant')->first();
        return $concordant?:NULL;
    }

}

