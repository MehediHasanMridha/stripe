<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

use App\Helpers\Traduction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;



class AccueilController extends Controller
{
    /**
     * Récupère la langue du site et redirige vers la fonction lang
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function index() {

        $lang=App::getLocale();
        
        // Récupère la traduction de chaque actualité dans la langue demandé
        $traductions = $this->getTraductionByLang($lang);
        // Récupère les 3 dernières actualités stocké dans la table actualites
        $all_actualites = DB::select("SELECT * FROM actualites ORDER BY `date` ASC  LIMIT 3");
        
        // Pour chaque actualité, change les informations par celles par défaut puis si elles existent par la langue du site demandé
        $actualites = [];
        foreach ($all_actualites as $actualite) {
            $default_traduction = $this->getTraductionById('fr', 'actualites_traduction', 'idActualite', $actualite->id)[0];
            $actualite->titre = $default_traduction->titre;
            $actualite->paragraphe = $default_traduction->paragraphe;
            $actualite->resume = $default_traduction->resume;
            $actualite->categories = json_decode($actualite->categories);

            foreach ($traductions as $traduction) {
                if ($actualite->id == $traduction->idActualite) {
                    $actualite->titre = $traduction->titre;
                    $actualite->paragraphe = $traduction->paragraphe;
                    $actualite->resume = $traduction->resume;
                }
            }

            // Récupère le nom des catégories stocké en base de données lié à cette actualité
            foreach ($actualite->categories as $key => $id_categorie) {
                $actualite->categories->$key = DB::select("SELECT nom FROM categories WHERE id = ?", [$id_categorie])[0]->nom;
            }

            // Transforme le format de la date récupéré par un autre
            $actualite->date = date('d-m-Y', strtotime($actualite->date));
            $actualite->date = explode('-', $actualite->date)[0] . ' ' . $this->getMonth(explode('-', $actualite->date)[1]) . ' ' . explode('-', $actualite->date)[2];

            // Ajoute l'actualité dans l'array actualites
            array_push($actualites, $actualite);
        }

        return view('welcome', compact( 'actualites'));
    }

    /**
     * Retourne une array contenant toutes les traductions existantes se trouvant dans la table ingredient_traduction
     * et où la langue est égale à celle donnée en paramètre
     *
     * @param string $lang
     * @return array
     */
    private function getTraductionByLang(string $lang): array {
        return DB::select("SELECT * FROM actualites_traduction WHERE langue = ?", [$lang]);
    }

     /**
     * Retourne une array contenant la traduction contenu dans la table donnée en paramètre et où la langue est
     * égale à celle donnée en paramètre et où la colonne donnée en paramètre est égale à l'élément donnée en paramètre
     *
     * @param string $lang
     * @param string $table
     * @param string $colonne
     * @param int $id
     * @return array
     */
    private function getTraductionById(string $lang, string $table, string $colonne, int $id): array {
        return DB::select("SELECT * FROM ".$table." WHERE langue = ? AND ".$colonne." = ?", [$lang, $id]);
    }

    /**
     * Retourne le mois en français correspondant au nombre donnée en paramètre
     *
     * @param $number
     * @return string
     */
    private function getMonth($number): string {
        if ($number == 1) return 'janvier';
        elseif ($number == 2) return 'février';
        elseif ($number == 3) return 'mars';
        elseif ($number == 4) return 'avril';
        elseif ($number == 5) return 'mai';
        elseif ($number == 6) return 'juin';
        elseif ($number == 7) return 'juillet';
        elseif ($number == 8) return 'août';
        elseif ($number == 9) return 'septembre';
        elseif ($number == 10) return 'octobre';
        elseif ($number == 11) return 'novembre';
        else return 'décembre';
    }

    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Retourne la vue admin-welcome.blade.php du dossier admin
     *
     * @return Application|Factory|View
     */
    public function admin() {
        return view('admin.admin-welcome');
    }
}
