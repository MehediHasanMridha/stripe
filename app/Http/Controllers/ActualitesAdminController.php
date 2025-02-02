<?php

namespace App\Http\Controllers;

use App\Helpers\Traduction;
use App\Models\Actualite;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class ActualitesAdminController extends Controller
{
    /**
     * @var mixed
     */
    private $iso;

    public function __construct()
    {
        $this->iso = Config::get("iso");
    }

    /**
     * @return Application|Factory|View
     */
    public function index() {
        $actualites=Actualite::all()->map(function ($actualite){
            $actualite=$this->getActualite($actualite->id);
            $actualite->date = date('d-m-Y', strtotime($actualite->date));
            return $actualite;
        });
        return view('admin.actualitesAdmin.index', compact('actualites'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function search(Request $request) {
        $search = $request->input('search');
        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('actualitesAdmin.index'));

        $traductions = Traduction::getTraductionByLang("actualites_traduction");
        $actualites=collect($traductions)->map(function($traduction) use ($search) {
            if (stristr($traduction->titre, $search)) {
                $actualite = $this->getActualite($traduction->idActualite);
                $actualite->date = date('d-m-Y', strtotime($actualite->date));
                return $actualite;
            }
        })->filter(function ($value) {
            return !empty($value);
        });

        return view('admin.actualitesAdmin.index', compact('actualites'));
    }


    /**
     * @param $id_actualite
     * @return Application|Factory|View
     */
    public function show($id_actualite) {
        $actualite = $this->getActualite($id_actualite);
        return view('admin.actualitesAdmin.show', compact('actualite'));
    }

    /**
     * @param $id_actualite
     * @return Application|Factory|View
     */
    public function edit($id_actualite) {

        $actualite = $this->getActualite($id_actualite);

        $all_categories = DB::select("SELECT * FROM categories");

        return view('admin.actualitesAdmin.edit', compact( 'actualite', 'all_categories'));
    }

    /**
     * @param $id_actualite
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update($id_actualite, Request $request) {
        $request->validate([
            'Titre' => 'required',
            'imageActualite' => 'sometimes|image|mimes:jpeg,png,jpg|max:5000'
        ]);
        $actualite=Actualite::findOrFail($id_actualite);

        $donnees = $request->input();

        $image_existante = DB::select("SELECT image FROM actualites WHERE id = ?", [$id_actualite])[0]->image;

        // On envoie l'image le ou elle doit être stockée
        if ($request->hasFile('imageActualite')) {
            $image = $request->file('imageActualite');
            $imageName = time(). '.' . $image->getClientOriginalExtension();
            $destinationImage = public_path('/storage/images/actualites');
            $image->move($destinationImage, $imageName);
            $imagePath = explode("public/", $destinationImage)[1].'/'.$imageName;
            $imagePath = str_replace("\\","/",$imagePath);

            if(file_exists($image_existante)) unlink($image_existante);
        }
        else {
            $imagePath = $image_existante;
        }

        $nouvelles_categories = array();
        foreach ($donnees as $key =>$categorie) {
            if(substr($key,0,9)==='categorie'){
                $nouvelles_categories[ltrim($categorie,'categorie')] = $categorie;
            }
        }
        $actualite->categories = json_encode($nouvelles_categories, JSON_UNESCAPED_UNICODE);
        $actualite->image=$imagePath;
        $actualite->date = now()->format('Y-m-d');
        $actualite->status = isset($donnees['statusActualite'])&&$donnees['statusActualite']==="on"?1:0;
        $actualite->save();


        $nouveau_titre = $donnees['Titre'];
        $nouveau_resume = $donnees['inputResume'];
        $nouveau_paragraphe = htmlentities($donnees['inputParagraphe'], ENT_HTML5, 'UTF-8');
        DB::update("UPDATE actualites_traduction SET titre = ?, paragraphe = ?, resume = ? WHERE idActualite = ? AND langue = ?", [$nouveau_titre, $nouveau_paragraphe, $nouveau_resume, $id_actualite, App::getLocale()]);

        return redirect(route('actualitesAdmin.show', [$id_actualite]));
    }

    /**
     * @return Application|Factory|View
     */
    public function create() {
        $all_categories = DB::select("SELECT * FROM categories");
        return view('admin.actualitesAdmin.create', compact( 'all_categories'));
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request) {
        $request->validate([
            'Titre' => 'required',
            'imageActualite' => 'sometimes|image|mimes:jpeg,png,jpg|max:5000'
        ]);

        $donnees = $request->input();

        $nouveau_titre = $donnees['Titre'];

        $nouveau_resume = $donnees['Resume'];

        $nouveau_paragraphe = htmlentities($donnees['Paragraphe'], ENT_HTML5, 'UTF-8');

        $imagePath = $this->setImagePath($request);

        $nouvelles_categories = array();
        foreach ($donnees as $key =>$categorie) {
            if(substr($key,0,9)==='categorie'){
                $nouvelles_categories[ltrim($categorie,'categorie')] = $categorie;
            }
        }
        $nouvelles_categories = json_encode($nouvelles_categories, JSON_UNESCAPED_UNICODE);
        $actualite=Actualite::create([
            'date'=>now()->format('Y-m-d'),
            'titre'=>$nouveau_titre,
            'paragraphe'=>$nouveau_paragraphe,
            'resume'=>$nouveau_resume,
            'image'=>$imagePath,
            'categories'=>$nouvelles_categories,
            'status'=>isset($donnees['statusActualite'])&&$donnees['statusActualite']==="on"?1:0,
        ]);
        $actualite->save();
        DB::insert("INSERT INTO actualites_traduction (langue, idActualite, titre, paragraphe, resume) VALUES ( ?, ?, ?, ?, ?)", [App::getLocale(), $actualite->id, $nouveau_titre, $nouveau_paragraphe, $nouveau_resume]);

        return redirect(route('actualitesAdmin.index'));
    }

    public function traduction($id_actualite) {
        $actualite = Actualite::findOrFail($id_actualite);
        $traduction = Traduction::getTraductionById('actualites_traduction', 'idActualite', $id_actualite);

        if (empty($traduction)) $traduction = Traduction::getTraductionById( 'actualites_traduction', 'idActualite', $id_actualite,"fr")[0];
        else $traduction = $traduction[0];

        $actualite->titre = $traduction->titre;
        $actualite->paragraphe = $traduction->paragraphe;
        $actualite->resume = $traduction->resume;

        $traductions_restantes = $this->iso;

        $traductions_existantes = DB::select("SELECT langue FROM actualites_traduction WHERE idActualite = ?", [$id_actualite]);
        $references = array();
        foreach ($traductions_existantes as $traductions_existante) {
            $reference = DB::select("SELECT titre, paragraphe, resume FROM actualites_traduction WHERE idActualite = ? AND langue = ?", [$id_actualite, $traductions_existante->langue])[0];
            $titre_actualite_traduction = $reference->titre;
            $paragraphe_actualite_traduction = $reference->paragraphe;
            $resume_actualite_traduction = $reference->resume;

            $references['titre'][$this->iso[$traductions_existante->langue]] = $titre_actualite_traduction;
            if ($paragraphe_actualite_traduction != null) $references['paragraphe'][$this->iso[$traductions_existante->langue]] = $paragraphe_actualite_traduction;
            if ($resume_actualite_traduction != null) $references['resume'][$this->iso[$traductions_existante->langue]] = $resume_actualite_traduction;

            if (array_search($traductions_existante->langue, $traductions_restantes)) unset($traductions_restantes[$traductions_existante->langue]);
        }

        return view('admin.actualitesAdmin.traduction', compact( 'actualite', 'references', 'traductions_restantes'));
    }

    public function updateTraduction($id_actualite, Request $request) {
        $donnees = $request->input();
        unset($donnees['_token']);
        unset($donnees['_method']);

        $donnees_actualite = array();

        foreach ($donnees as $key => $donnee) {
            if (stristr($key, 'Actualite')) $donnees_actualite['titre'][$key] = $donnee;
            else if (stristr($key, 'Paragraphe')) $donnees_actualite['paragraphe'][$key] = $donnee;
            else if (stristr($key, 'Resume')) $donnees_actualite['resume'][$key] = $donnee;
        }

        /**************
         * TITRE
         **************/

        foreach ($donnees_actualite['titre'] as $key => $titre_actualite) {
            if (stristr($key, 'editActualite__')) {
                $id_actualite_edit = explode('-', explode('__', $key)[1])[0];
                $langue_actualite_edit = array_search(explode('-', explode('__', $key)[1])[1], $this->iso);

                DB::update("UPDATE ingredient_traduction SET nom = ? WHERE idIngredient = ? AND langue = ?", [$titre_actualite, $id_actualite_edit, $langue_actualite_edit]);
            } else if (stristr($key, 'addActualite_')) {
                $langue_actualite = $donnees_actualite['titre']['langueActualite_'.explode('_', $key)[1]];

                $traduction_existante = DB::select("SELECT * FROM actualites_traduction WHERE idActualite = ? AND langue = ?", [$id_actualite, $langue_actualite]);

                if ($traduction_existante) DB::update("UPDATE actualites_traduction SET titre = ? WHERE idActualite = ? AND langue = ?", [$titre_actualite, $id_actualite, $langue_actualite]);
                else DB::insert("INSERT INTO actualites_traduction (langue, idActualite, titre) VALUES (?, ?, ?)", [$langue_actualite, $id_actualite, $titre_actualite]);
            }
        }

        /**************
         * PARAGRAPHE
         **************/

        if (!empty($donnees_actualite['paragraphe'])) {
            foreach ($donnees_actualite['paragraphe'] as $key => $paragraphe_actualite) {
                if (stristr($key, 'editParagraphe__')) {
                    $langue_actualite_edit = array_search(explode('__', $key)[1], $this->iso);

                    DB::update('UPDATE ingredient_traduction SET tropisme = ? WHERE idIngredient = ? AND langue = ?', [htmlentities($paragraphe_actualite, ENT_HTML5, 'UTF-8'), $id_actualite, $langue_actualite_edit]);
                } else if (stristr($key, 'addParagraphe')) {
                    $langue_actualite = $donnees_actualite['paragraphe']['langueParagraphe_'.explode('_', $key)[1]];

                    $traduction_existante = DB::select("SELECT * FROM actualites_traduction WHERE idActualite = ? AND langue = ?", [$id_actualite, $langue_actualite]);

                    if ($traduction_existante) DB::update("UPDATE actualites_traduction SET paragraphe = ? WHERE idActualite = ? AND langue = ?", [htmlentities($paragraphe_actualite, ENT_HTML5, 'UTF-8'), $id_actualite, $langue_actualite]);
                    else DB::insert("INSERT INTO actualites_traduction (langue, idActualite, paragraphe) VALUES (?, ?, ?)", [$langue_actualite, $id_actualite, htmlentities($paragraphe_actualite, ENT_HTML5, 'UTF-8')]);
                }
            }
        }

        /**************
         * RESUME
         **************/

        if (!empty($donnees_actualite['resume'])) {
            foreach ($donnees_actualite['resume'] as $key => $resume_actualite) {
                if (stristr($key, 'editResume__')) {
                    $langue_actualite_edit = array_search(explode('__', $key)[1], $this->iso);

                    DB::update('UPDATE ingredient_traduction SET tropisme = ? WHERE idIngredient = ? AND langue = ?', [$resume_actualite, $id_actualite, $langue_actualite_edit]);
                } else if (stristr($key, 'addResume')) {
                    $langue_actualite = $donnees_actualite['resume']['langueResume_'.explode('_', $key)[1]];

                    $traduction_existante = DB::select("SELECT * FROM actualites_traduction WHERE idActualite = ? AND langue = ?", [$id_actualite, $langue_actualite]);

                    if ($traduction_existante) DB::update("UPDATE actualites_traduction SET resume = ? WHERE idActualite = ? AND langue = ?", [$resume_actualite, $id_actualite, $langue_actualite]);
                    else DB::insert("INSERT INTO actualites_traduction (langue, idActualite, resume) VALUES (?, ?, ?)", [$langue_actualite, $id_actualite, $resume_actualite]);
                }
            }
        }

        return redirect(route("actualitesAdmin.show", [$id_actualite]));
    }

    /**
     * @param $id_actualite
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy($id_actualite) {
        DB::delete("DELETE FROM actualites_traduction WHERE idActualite = ?", [$id_actualite]);
        DB::delete("DELETE FROM actualites WHERE id = ?", [$id_actualite]);

        $count = 'COUNT(*)';
        $nb_actualites = DB::select("SELECT COUNT(*) FROM actualites")[0]->$count;
        $nb_actualites_traduction = DB::select("SELECT COUNT(*) FROM actualites_traduction")[0]->$count;

        DB::update("ALTER TABLE actualites AUTO_INCREMENT = " . $nb_actualites);
        DB::update("ALTER TABLE actualites_traduction AUTO_INCREMENT = " . $nb_actualites_traduction);

        return redirect(route('actualitesAdmin.index'));
    }

    /**
     * @param Request $request
     * @return array|string|string[]|null
     */
    private function setImagePath(Request $request) {
        if ($request->hasFile('imageActualite')) {
            $image = $request->file('imageActualite');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationImage = public_path('/storage/images/actualites');
            $image->move($destinationImage, $imageName);
            $imagePath = explode("public/", $destinationImage)[1] . '/' . $imageName;

            return str_replace("\\", "/", $imagePath);
        }
        else return null;
    }

    /**
     * @param $id_actualite
     * @return mixed
     */
    public function getActualite($id_actualite)
    {
        $actualite = Actualite::findOrFail($id_actualite);
        $traduction = Traduction::getTraductionByIdFirst('actualites_traduction', 'idActualite', $id_actualite);
        $actualite->titre = $traduction->titre;
        $actualite->resume = $traduction->resume;
        $actualite->paragraphe = $traduction->paragraphe;
        $actualite->categories = json_decode($actualite->categories);
        foreach ($actualite->categories as $key => $id_categorie) {
            $actualite->categories->$key = DB::select("SELECT nom FROM categories WHERE id = ?", [$id_categorie])[0]->nom;
        }
        return $actualite;
    }
}
