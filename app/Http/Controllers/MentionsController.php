<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class MentionsController extends Controller
{
    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue mentions.blade.php du dossier footer
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('footer.mentions');
    }
}
