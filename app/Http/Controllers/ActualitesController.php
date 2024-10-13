<?php

namespace App\Http\Controllers;

use App\Helpers\Traduction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ActualitesController extends Controller
{
    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère la liste des actualités de la base de données
     * Retourne la vue index.blade.php du dossier actualites
     *
     * @return Application|Factory|View
     */
    public function index() {
        $lang=App::getLocale();
        // Récupère la traduction de chaque actualité dans la langue demandé
        $traductions = $this->getTraductionByLang($lang);
        // Récupère toutes les actualités stocké dans la table actualites
        $all_actualites = DB::select("SELECT * FROM actualites ORDER BY `date` ASC");

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

        return view('actualites.index', compact( 'actualites'));
    }


    /**
     * Défini la langue affiché pour l'utilisateur avec celle donnée
     * Récupère les informations de l'actualité où son id est égale à celui demandé
     * Retourne la vue show.blade.php du dossier actualites
     *
     * @param $id_actualite
     * @return Application|Factory|View
     */
    public function show($id_actualite) {
        $lang=App::getLocale();

        // Récupère toutes les informations de l'actualité qui possède un id égale à celui donnée en paramètre
        $actualite = DB::select("SELECT * FROM actualites WHERE id = ?", [$id_actualite])[0];
        // Récupère sa traduction dans la langue du site demandé
        $traduction = $this->getTraductionById($lang, 'actualites_traduction', 'idActualite', $id_actualite);

        // Si sa traduction n'existe pas, on récupère la traduction de l'ingrédient en français (langue par défaut)
        if (empty($traduction)) $traduction = $this->getTraductionById('fr', 'actualites_traduction', 'idActualite', $id_actualite)[0];
        // Sinon on garde cette traduction
        else $traduction = $traduction[0];

        // Change le titre, le paragraphe, et le résumé l'actualité par ceux récupéré de la traduction
        $actualite->titre = $traduction->titre;
        $actualite->paragraphe = $traduction->paragraphe;
        $actualite->resume = $traduction->resume;

        // Récupère le nom des catégories stocké en base de données lié à cette actualité
        $actualite->categories = json_decode($actualite->categories);
        foreach ($actualite->categories as $key => $id_categorie) {
            $actualite->categories->$key = DB::select("SELECT nom FROM categories WHERE id = ?", [$id_categorie])[0]->nom;
        }

        return view('actualites.show', compact( 'actualite'));
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
}
