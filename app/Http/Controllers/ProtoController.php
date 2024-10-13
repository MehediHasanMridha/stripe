<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ProtoController extends Controller
{
    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue index.blade.php du dossier protojz
     *
     * @param $lang
     * @return Application|Factory|View
     */
    public function index($lang) {
        App::setLocale($lang);  // Change la langue du site avec celle donnée en paramètre

        return view('protojz.index', compact('lang'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue resultat.blade.php du dossier protojz
     *
     * @param $lang
     * @return Application|Factory|View
     */
    public function resultat($lang) {
        App::setLocale($lang);  // Change la langue du site avec celle donnée en paramètre

        return view('protojz.resultat', compact('lang'));
    }
}
