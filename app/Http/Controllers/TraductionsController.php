<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class TraductionsController extends Controller
{
    private $iso_639_1 = [
        "aa" => "Afaraf",
        "ab" => "Аҧсуа",
        "ae" => "Avesta",
        "af" => "Afrikaans",
        "ak" => "Akan",
        "am" => "አማርኛ",
        "an" => "Aragonés",
        "ar" => "العربية",
        "as" => "অসমীয়া",
        "av" => "авар мацӀ",
        "ay" => "Aymar aru",
        "az" => "Azərbaycan dili",
        "ba" => "башҡорт теле",
        "be" => "Беларуская",
        "bg" => "български език",
        "bh" => "भोजपुरी",
        "bi" => "Bislama",
        "bm" => "Bamanankan",
        "bn" => "বাংলা",
        "bo" => "བོད་ཡིག",
        "br" => "Brezhoneg",
        "bs" => "Bosanski jezik",
        "ca" => "Català",
        "ce" => "нохчийн мотт",
        "ch" => "Chamoru",
        "co" => "Corsu",
        "cr" => "ᓀᐦᐃᔭᐍᐏᐣ",
        "cs" => "Česky",
        "cu" => "Словѣньскъ",
        "cv" => "чӑваш чӗлхи",
        "cy" => "Cymraeg",
        "da" => "Dansk",
        "de" => "Deutsch",
        "dv" => "ދިވެހި",
        "dz" => "རྫོང་ཁ",
        "ee" => "Ɛʋɛgbɛ",
        "el" => "Ελληνικά",
        "en" => "English",
        "eo" => "Esperanto",
        "es" => "Español",
        "et" => "Eesti keel",
        "eu" => "Euskara",
        "fa" => "فارسی",
        "ff" => "Fulfulde",
        "fi" => "Suomen kieli",
        "fj" => "Vosa Vakaviti",
        "fo" => "Føroyskt",
        "fr" => "Français",
        "fy" => "Frysk",
        "ga" => "Gaeilge",
        "gd" => "Gàidhlig",
        "gl" => "Galego",
        "gn" => "Avañe'ẽ",
        "gu" => "ગુજરાતી",
        "gv" => "Ghaelg",
        "ha" => "هَوُسَ",
        "he" => "עברית",
        "hi" => "हिन्दी",
        "ho" => "Hiri Motu",
        "hr" => "Hrvatski",
        "ht" => "Kreyòl ayisyen",
        "hu" => "magyar",
        "hy" => "Հայերեն",
        "hz" => "Otjiherero",
        "ia" => "Interlingua",
        "id" => "Bahasa Indonesia",
        "ie" => "Interlingue",
        "ig" => "Igbo",
        "ii" => "ꆇꉙ",
        "ik" => "Iñupiaq",
        "io" => "Ido",
        "is" => "Íslenska",
        "it" => "Italiano",
        "iu" => "ᐃᓄᒃᑎᑐᑦ",
        "ja" => "日本語",
        "jv" => "Basa Jawa",
        "ka" => "ქართული",
        "kg" => "KiKongo",
        "ki" => "Gĩkũyũ",
        "kj" => "Kuanyama",
        "kk" => "Қазақ тілі",
        "kl" => "Kalaallisut",
        "km" => "ភាសាខ្មែរ",
        "kn" => "ಕನ್ನಡ",
        "ko" => "한국어",
        "kr" => "Kanuri",
        "ks" => "कश्मीरी",
        "ku" => "Kurdî",
        "kv" => "коми кыв",
        "kw" => "Kernewek",
        "ky" => "кыргыз тили",
        "la" => "Latine",
        "lb" => "Lëtzebuergesch",
        "lg" => "Luganda",
        "li" => "Limburgs",
        "ln" => "Lingála",
        "lo" => "ພາສາລາວ",
        "lt" => "Lietuvių kalba",
        "lu" => "tshiluba",
        "lv" => "Latviešu valoda",
        "mg" => "Fiteny malagasy",
        "mh" => "Kajin M̧ajeļ",
        "mi" => "Te reo Māori",
        "mk" => "македонски јазик",
        "ml" => "മലയാളം",
        "mn" => "Монгол",
        "mo" => "лимба молдовеняскэ",
        "mr" => "मराठी",
        "ms" => "Bahasa Melayu",
        "mt" => "Malti",
        "my" => "ဗမာစာ",
        "na" => "Ekakairũ Naoero",
        "nb" => "Norsk bokmål",
        "nd" => "isiNdebele",
        "ne" => "नेपाली",
        "ng" => "Owambo",
        "nl" => "Nederlands",
        "nn" => "Norsk nynorsk",
        "no" => "Norsk",
        "nr" => "Ndébélé",
        "nv" => "Diné bizaad",
        "ny" => "ChiCheŵa",
        "oc" => "Occitan",
        "oj" => "ᐊᓂᔑᓈᐯᒧᐎᓐ",
        "om" => "Afaan Oromoo",
        "or" => "ଓଡ଼ିଆ",
        "os" => "Ирон ӕвзаг",
        "pa" => "ਪੰਜਾਬੀ",
        "pi" => "पािऴ",
        "pl" => "Polski",
        "ps" => "پښتو",
        "pt" => "Português",
        "qu" => "Runa Simi",
        "rc" => "Kréol Rénioné",
        "rm" => "Rumantsch grischun",
        "rn" => "kiRundi",
        "ro" => "Română",
        "ru" => "русский язык",
        "rw" => "Kinyarwanda",
        "sa" => "संस्कृतम्",
        "sc" => "sardu",
        "sd" => "सिन्धी",
        "se" => "Davvisámegiella",
        "sg" => "Yângâ tî sängö",
        "sh" => "srpskohrvatski jezik",
        "si" => "සිංහල",
        "sk" => "Slovenčina",
        "sl" => "Slovenščina",
        "sm" => "Gagana fa'a Samoa",
        "sn" => "chiShona",
        "so" => "Soomaaliga",
        "sq" => "Shqip",
        "sr" => "српски језик",
        "ss" => "SiSwati",
        "st" => "seSotho",
        "su" => "Basa Sunda",
        "sv" => "Svenska",
        "sw" => "Kiswahili",
        "ta" => "தமிழ்",
        "te" => "తెలుగు",
        "tg" => "тоҷикӣ",
        "th" => "ไทย",
        "ti" => "ትግርኛ",
        "tk" => "Türkmen",
        "tl" => "Tagalog",
        "tn" => "seTswana",
        "to" => "faka Tonga",
        "tr" => "Türkçe",
        "ts" => "xiTsonga",
        "tt" => "татарча",
        "tw" => "Twi",
        "ty" => "Reo Mā`ohi",
        "ug" => "Uyƣurqə",
        "uk" => "українська мова",
        "ur" => "اردو",
        "uz" => "O'zbek ",
        "ve" => "tshiVenḓa",
        "vi" => "Tiếng Việt",
        "vo" => "Volapük",
        "wa" => "Walon",
        "wo" => "Wollof",
        "xh" => "isiXhosa",
        "yi" => "ייִדיש",
        "yo" => "Yorùbá",
        "za" => "Saɯ cueŋƅ",
        "zh" => "中文",
        "zu" => "isiZulu",
        "zo" => "Zoulou",
    ];

    /**
     * @return Application|Factory|View
     */
    public function index() {
        App::setLocale(Session::get('locale'));

        $langues = DB::select("SELECT id,langue FROM traductions ORDER BY id");

        foreach ($langues as $langue) {
            $textes = $this->getTraduction($langue->id)[0];

            $nb_lignes = 0;
            $nb_lignes_traduites = 0;
            foreach ($textes as $page => $texte) {
                foreach ($textes->$page as $key => $texte_page) {
                    if ($texte_page != '') $nb_lignes_traduites++;

                    $nb_lignes++;
                }
            }

            if ($nb_lignes > 0) $langue->taux = round(($nb_lignes_traduites / $nb_lignes)*100, 2);
            else $langue->taux = 0;
        }

        return view('admin.traductions.index', compact('langues'));
    }

    /**
     * @param $id_langue
     * @return Application|Factory|View
     */
    public function show($id_langue) {
        App::setLocale(Session::get('locale'));

        $ressources = $this->getTraduction($id_langue);

        $traduction = $ressources[0];
        $infos = $ressources[1];

        $nb_lignes = 0;
        $nb_lignes_traduites = 0;

        foreach($traduction as $page => $textes) {
            foreach ($traduction->$page as $key => $texte) {
                if ($texte != '') $nb_lignes_traduites++;
                $nb_lignes++;
            }
        }

        if ($nb_lignes > 0) $taux = round(($nb_lignes_traduites / $nb_lignes) * 100, 2);
        else $taux = 0;

        $id_zoulou = DB::select("SELECT id FROM traductions WHERE langue = 'zo'")[0]->id;
        $model_zoulou = $this->getTraduction($id_zoulou)[0];

        $zoulou = array();
        foreach ($model_zoulou as $pages => $textes) {
            foreach ($model_zoulou->$pages as $key => $texte) {
                $zoulou[$key] = $texte;
            }
        }

        return view('admin.traductions.show', compact('infos', 'traduction', 'zoulou', 'taux'));
    }

    public function search($id_langue, Request $request) {
        App::setLocale(Session::get('locale'));

        $search = $request->input('search');

        // Si la recherche est vide, on retourne sur la page de la liste complète
        if (strlen($search) == 0) return redirect(route('traductions.show', [$id_langue]));

        $ressources = $this->getTraduction($id_langue);

        $traduction_all = $ressources[0];
        $infos = $ressources[1];

        $id_zoulou = DB::select("SELECT id FROM traductions WHERE langue = 'zo'")[0]->id;
        $model_zoulou = $this->getTraduction($id_zoulou)[0];

        $zoulou = array();
        foreach ($model_zoulou as $pages => $textes) {
            foreach ($model_zoulou->$pages as $key => $texte) {
                $zoulou[$key] = $texte;
            }
        }

        $nb_lignes = 0;
        $nb_lignes_traduites = 0;

        $traduction = array();
        foreach($traduction_all as $pages => $textes) {
            foreach($traduction_all->$pages as $key => $texte) {
                if ($texte != '') $nb_lignes_traduites++;

                if (stristr($texte, $search) || stristr($key, $search) || strpos($zoulou[$key], $search)) $traduction[$pages][$key] = $texte;

                $nb_lignes++;
            }
        }

        if ($nb_lignes > 0) $taux = round(($nb_lignes_traduites / $nb_lignes)*100, 2);
        else $taux = 0;

        return view('admin.traductions.show', compact('infos', 'traduction', 'zoulou', 'taux'));
    }

    /**
     * @param $lang
     * @param $id_langue
     * @param int $id_reference
     * @return Application|Factory|View
     */
    public function edit($id_langue, int $id_reference) {
        App::setLocale(Session::get('locale'));

        $ressources = $this->getTraduction($id_langue);

        $traduction = $ressources[0];
        $infos = $ressources[1];

        $id_exemple = DB::select("SELECT id FROM traductions WHERE id = ?", [$id_reference])[0]->id;

        $exemple_ressources = $this->getTraduction($id_exemple);
        $exemple = array();
        foreach ($exemple_ressources[0] as $pages => $textes) {
            foreach ($exemple_ressources[0]->$pages as $key => $texte) {
                $exemple[$key] = $texte;
            }
        }

        $infos['exId'] = $exemple_ressources[1]['id'];
        $infos['exLangue'] = $this->iso_639_1[$exemple_ressources[1]['langue']];

        $traductions_existantes = DB::select("SELECT langue, id FROM traductions");

        $id_zoulou = DB::select("SELECT id FROM traductions WHERE langue = 'zo'")[0]->id;
        $model_zoulou = $this->getTraduction($id_zoulou)[0];

        $zoulou = array();
        foreach ($model_zoulou as $pages => $textes) {
            foreach ($model_zoulou->$pages as $key => $texte) {
                $zoulou[$key] = $texte;
            }
        }

        $langues_existantes = [];
        foreach ($traductions_existantes as $traductions) {
            array_push($langues_existantes, $traductions->langue);
            $traductions->langue = $this->iso_639_1[$traductions->langue];
        }

        $iso_639_1 = $this->iso_639_1;
        foreach($iso_639_1 as $iso => $nom) {
            if (in_array($iso, $langues_existantes) && $iso != $infos['langue']) unset($iso_639_1[$iso]);
        }

        return view('admin.traductions.edit', compact('infos', 'traduction', 'iso_639_1', 'exemple', 'zoulou', 'traductions_existantes'));
    }

    public function editSearch($id_langue, $id_reference, Request $request) {
        App::setLocale(Session::get('locale'));

        $search = $request->input('search');

        $id_zoulou = DB::select("SELECT id FROM traductions WHERE langue = 'zo'")[0]->id;
        $model_zoulou = $this->getTraduction($id_zoulou)[0];

        $zoulou = array();
        foreach ($model_zoulou as $pages => $textes) {
            foreach ($model_zoulou->$pages as $key => $texte) {
                $zoulou[$key] = $texte;
            }
        }

        $ressources = $this->getTraduction($id_langue);

        $traduction_all = $ressources[0];
        $infos = $ressources[1];

        $traduction = array();
        foreach($traduction_all as $pages => $textes) {
            foreach($traduction_all->$pages as $key => $texte) {
                if (stristr($texte, $search) || stristr($key, $search) || strpos($zoulou[$key], $search)) {
                    $traduction[$pages][$key] = $texte;
                }
            }
        }

        $id_exemple = DB::select("SELECT id FROM traductions WHERE id = ?", [$id_reference])[0]->id;

        $exemple_ressources = $this->getTraduction($id_exemple);
        $exemple = array();
        foreach ($exemple_ressources[0] as $pages => $textes) {
            foreach ($exemple_ressources[0]->$pages as $key => $texte) {
                $exemple[$key] = $texte;
            }
        }

        $infos['exId'] = $exemple_ressources[1]['id'];
        $infos['exLangue'] = $this->iso_639_1[$exemple_ressources[1]['langue']];

        $traductions_existantes = DB::select("SELECT langue, id FROM traductions");

        $langues_existantes = [];
        foreach ($traductions_existantes as $traductions) {
            array_push($langues_existantes, $traductions->langue);
            $traductions->langue = $this->iso_639_1[$traductions->langue];
        }

        $iso_639_1 = $this->iso_639_1;
        foreach($iso_639_1 as $iso => $nom) {
            if (in_array($iso, $langues_existantes) && $iso != $infos['langue']) unset($iso_639_1[$iso]);
        }

        return view('admin.traductions.edit', compact('infos', 'traduction', 'iso_639_1', 'exemple', 'zoulou', 'traductions_existantes'));
    }

    /**
     * @param $id_langue
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update($id_langue, Request $request) {
        App::setLocale(Session::get('locale'));

        $donnees = $request->input();
        unset($donnees['_token']);
        unset($donnees['_method']);

        $nouvelle_langue = $donnees['selectLangue'];
        unset($donnees['selectLangue']);

        $ancienne_langue = DB::select("SELECT langue FROM traductions WHERE id = ?", [$id_langue])[0]->langue;

        if ($ancienne_langue != $nouvelle_langue) {
            DB::update("UPDATE traductions SET langue = ? WHERE id = ?", [$nouvelle_langue, $id_langue]);

            $chemin = substr(__DIR__, 0, -20) . 'resources\\lang\\' . $ancienne_langue;
            rename($chemin, substr($chemin, 0, -2) . $nouvelle_langue);

            $chemin = substr(__DIR__, 0, -20) . 'resources\\lang\\' . $nouvelle_langue;

            file_put_contents($chemin . '/textes.php', $this->getCodeFile($nouvelle_langue));

            $traductions = DB::select("SELECT id, footer FROM traductions WHERE NOT id = ? ORDER BY id", [$id_langue]);

            foreach ($traductions as $traduction) {
                $traduction->footer = json_decode($traduction->footer);

                $nouvelle_cle_langue = 'footer_btn_lang_' . $nouvelle_langue;
                $traduction->footer->$nouvelle_cle_langue = $this->iso_639_1[$nouvelle_langue];
                $donnees_footer[$nouvelle_cle_langue] = $this->iso_639_1[$nouvelle_langue];

                DB::update("UPDATE traductions SET footer = ? WHERE id = ?", [json_encode($traduction->footer), $traduction->id]);
            }
        }

        foreach ($donnees as $key => $donnee) {
            if (stristr($key, '__') && !stristr($key, 'input')) {
                $nouvelle_cle = explode('__', $key)[0] . '_' . $donnee;
                $donnees[$nouvelle_cle] = $donnees['input' . $key];

                unset($donnees['input' . $key]);
                unset($donnees[$key]);
            }
        }

        $donnees_json = $this->separateData($donnees);
        foreach ($donnees_json as $page => $textes) {
            if ($textes == "[]") $textes = json_encode(['canebuguerapas' => 'vraiment pas']);
            $donnees_json[$page] = json_decode($textes);
        }

        $donnees_json_all = DB::select("SELECT * FROM traductions WHERE id = ?", [$id_langue])[0];
        unset($donnees_json_all->id);
        unset($donnees_json_all->langue);
        foreach ($donnees_json_all as $page => $textes) $donnees_json_all->$page = json_decode($textes);

        foreach ($donnees_json as $page => $textes) {
            if (sizeof((array)$textes) < sizeof((array)$donnees_json_all->$page)) {
                foreach ($donnees_json_all->$page as $key => $texte) {
                    if (!property_exists($textes, $key)) $textes->$key = $donnees_json_all->$page->$key;
                }
            }
            unset($textes->canebuguerapas);
        }

        foreach ($donnees_json as $page => $textes) $donnees_json[$page] = json_encode($textes);

        $traductions = DB::select("SELECT * FROM traductions WHERE NOT id = ? ORDER BY id", [$id_langue]);

        $compteur_zoulou = $this->getMaxZoulou();

        foreach ($traductions as $traduction) {
            $id = $traduction->id;
            $langue = $traduction->langue;
            unset($traduction->langue);
            unset($traduction->id);

            foreach ($traduction as $page => $json) {
                $traduction->$page = json_decode($json);
                $traduction->$page->canebuguerapas = 'Vraiment pas';

                foreach ($traduction->$page as $key => $texte) {
                    if ($page == "accueil") $donnees_page = $donnees_json['accueil'];
                    elseif ($page == "admin") $donnees_page = $donnees_json['admin'];
                    elseif ($page == "conseillers") $donnees_page = $donnees_json['conseillers'];
                    elseif ($page == "expertise") $donnees_page = $donnees_json['expertise'];
                    elseif ($page == "formules") $donnees_page = $donnees_json['formules'];
                    elseif ($page == "ingredients") $donnees_page = $donnees_json['ingredients'];
                    elseif ($page == "ingredientsAdmin") $donnees_page = $donnees_json['ingredientsAdmin'];
                    elseif ($page == "proto") $donnees_page = $donnees_json['proto'];
                    elseif ($page == "symptomes") $donnees_page = $donnees_json['symptomes'];
                    elseif ($page == "syndromes") $donnees_page = $donnees_json['syndromes'];
                    elseif ($page == "formulesAdmin") $donnees_page = $donnees_json['formulesAdmin'];
                    elseif ($page == "actualites") $donnees_page = $donnees_json['actualites'];
                    elseif ($page == "traductions") $donnees_page = $donnees_json['traductions'];
                    elseif ($page == "rechercheAdmin") $donnees_page = $donnees_json['rechercheAdmin'];
                    elseif ($page == "header") $donnees_page = $donnees_json['header'];
                    elseif ($page == "headerAdmin") $donnees_page = $donnees_json['headerAdmin'];
                    elseif ($page == "conseillersAdmin") $donnees_page = $donnees_json['conseillersAdmin'];
                    elseif ($page == "actualitesAdmin") $donnees_page = $donnees_json['actualitesAdmin'];
                    elseif ($page == "arianne") $donnees_page = $donnees_json['arianne'];
                    elseif ($page == "compte") $donnees_page = $donnees_json['compte'];
                    elseif ($page == "utilisateursAdmin") $donnees_page = $donnees_json['utilisateursAdmin'];
                    elseif ($page == "stripe") $donnees_page = $donnees_json['stripe'];
                    else $donnees_page = $donnees_json['footer'];

                    $donnees_page = json_decode($donnees_page);

                    foreach ($traduction->$page as $key_page => $texte_page) {
                        if (!property_exists($donnees_page ,$key_page)) unset($traduction->$page->$key_page);
                    }

                    foreach ($donnees_page as $key_donnees => $texte_donnees) {
                        if (!property_exists($traduction->$page ,$key_donnees)) {
                            if ($langue != "zo") $traduction->$page->$key_donnees = "";
                            else {
                                $compteur_zoulou++;
                                $traduction->$page->$key_donnees = sprintf('#%06s', $compteur_zoulou);
                            }
                        }
                    }
                }

                unset($traduction->$page->canebuguerapas);
            }
            DB::update("UPDATE traductions SET header = ?, footer = ?, accueil = ?, conseillers = ?, expertise = ?, formules = ?, ingredients = ?, ingredientsAdmin = ?, proto = ?, symptomes = ?, syndromes = ?, formulesAdmin = ?, actualites = ?, traductions = ?, admin = ?, headerAdmin = ?, rechercheAdmin = ?, conseillersAdmin = ?, actualitesAdmin = ?, arianne = ?, compte = ?, utilisateursAdmin = ?, stripe = ? WHERE id = ?", [json_encode($traduction->header), json_encode($traduction->footer), json_encode($traduction->accueil), json_encode($traduction->conseillers), json_encode($traduction->expertise), json_encode($traduction->formules), json_encode($traduction->ingredients), json_encode($traduction->ingredientsAdmin), json_encode($traduction->proto), json_encode($traduction->symptomes), json_encode($traduction->syndromes), json_encode($traduction->formulesAdmin), json_encode($traduction->actualites), json_encode($traduction->traductions), json_encode($traduction->admin), json_encode($traduction->headerAdmin), json_encode($traduction->rechercheAdmin), json_encode($traduction->conseillersAdmin), json_encode($traduction->actualitesAdmin), json_encode($traduction->arianne), json_encode($traduction->compte), json_encode($traduction->utilisateursAdmin), json_encode($traduction->stripe), $id]);
        }
        DB::update("UPDATE traductions SET header = ?, footer = ?, accueil = ?, conseillers = ?, expertise = ?, formules = ?, ingredients = ?, ingredientsAdmin = ?, proto = ?, symptomes = ?, syndromes = ?, formulesAdmin = ?, actualites = ?, traductions = ?, admin = ?, headerAdmin = ?, rechercheAdmin = ?, conseillersAdmin = ?, actualitesAdmin = ?, arianne = ?,compte = ?, utilisateursAdmin = ?, stripe = ? WHERE id = ?", [$donnees_json['header'], $donnees_json['footer'], $donnees_json['accueil'], $donnees_json['conseillers'], $donnees_json['expertise'], $donnees_json['formules'], $donnees_json['ingredients'], $donnees_json['ingredientsAdmin'], $donnees_json['proto'], $donnees_json['symptomes'], $donnees_json['syndromes'], $donnees_json['formulesAdmin'], $donnees_json['actualites'], $donnees_json['traductions'] , $donnees_json['admin'], $donnees_json['headerAdmin'], $donnees_json['rechercheAdmin'], $donnees_json['conseillersAdmin'], $donnees_json['actualitesAdmin'], $donnees_json['arianne'],$donnees_json['compte'], $donnees_json['utilisateursAdmin'], $donnees_json['stripe'], $id_langue]);

        return redirect(route('traductions.show', [$id_langue]));
    }

    /**
     * @param $id_langue
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy($id_langue) {
        App::setLocale(Session::get('locale'));

        $count = 'COUNT(id)';
        $nb_traductions = DB::select('SELECT COUNT(id) FROM traductions')[0]->$count;

        $langue = DB::select("SELECT langue FROM traductions WHERE id = ?", [$id_langue])[0]->langue;

        DB::delete("DELETE FROM traductions WHERE id = ?", [$id_langue]);
        DB::update('ALTER TABLE traductions AUTO_INCREMENT = ' . $nb_traductions);

        $chemin = substr(__DIR__, 0, -20) . 'resources\\lang\\' . $langue;

        File::deleteDirectory($chemin);

        $traductions = DB::select("SELECT id, footer FROM traductions");
        $key_langue = 'footer_btn_lang_' . $langue;

        foreach($traductions as $traduction) {
            $nouveau_footer = json_decode($traduction->footer);

            foreach($nouveau_footer as $key => $texte) unset($nouveau_footer->$key_langue);

            $nouveau_footer = json_encode($nouveau_footer);
            DB::update("UPDATE traductions SET footer = ? WHERE id = ?", [$nouveau_footer, $traduction->id]);
        }

        return redirect(route('traductions.index'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create() {
        App::setLocale(Session::get('locale'));

        $model = $this->getTraduction(1)[0];

        $requete_traductions_existantes = DB::select("SELECT langue FROM traductions");

        $traductions_existantes = [];
        foreach ($requete_traductions_existantes as $traductions) {
            array_push($traductions_existantes, $traductions->langue);
        }

        $iso_639_1 = $this->iso_639_1;

        foreach($iso_639_1 as $iso => $nom) {
            if (in_array($iso, $traductions_existantes)) unset($iso_639_1[$iso]);
        }


        return view('admin.traductions.create', compact('model', 'iso_639_1'));
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request) {
        App::setLocale(Session::get('locale'));

        $request->validate([
            'selectLangue' => 'required|min:2|max:2'
        ]);

        $donnees = $request->input();
        unset($donnees['_token']);

        $langue = $donnees['selectLangue'];
        unset($donnees['selectLangue']);

        $donnees_json = $this->separateData($donnees);

        $cle = 'footer_btn_lang_' . $langue;

        $donnees_json_footer = json_decode($donnees_json['footer']);
        $donnees_json_footer->$cle = $this->iso_639_1[$langue];
        $donnees_json['footer'] = json_encode($donnees_json_footer);

        $traductions = DB::select("SELECT * FROM traductions");

        foreach($traductions as $traduction) {
            $footer = $traduction->footer;
            $footer = json_decode($footer);

            if ($traduction->langue != "zo") $footer->$cle = $this->iso_639_1[$langue];
            else $footer->$cle = sprintf('#%06s', $this->getMaxZoulou()+1);

            $nouveau_footer = json_encode($footer);

            DB::update("UPDATE traductions SET footer = ? WHERE id = ?", [$nouveau_footer, $traduction->id]);
        }

        DB::insert("INSERT INTO traductions (langue, header, footer, accueil, conseillers, expertise, formules, ingredients, ingredientsAdmin, proto, symptomes, syndromes, formulesAdmin, actualites, traductions, admin, headerAdmin, rechercheAdmin, conseillersAdmin, actualitesAdmin, arianne, compte, utilisateursAdmin, stripe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)", [$langue, $donnees_json['header'], $donnees_json['footer'], $donnees_json['accueil'], $donnees_json['conseillers'], $donnees_json['expertise'], $donnees_json['formules'], $donnees_json['ingredients'], $donnees_json['ingredientsAdmin'], $donnees_json['proto'], $donnees_json['symptomes'], $donnees_json['syndromes'], $donnees_json['formulesAdmin'], $donnees_json['actualites'], $donnees_json['traductions'], $donnees_json['admin'], $donnees_json['headerAdmin'], $donnees_json['rechercheAdmin'], $donnees_json['conseillersAdmin'], $donnees_json['actualitesAdmin'], $donnees_json['arianne'], $donnees_json['compte'],$donnees_json['utilisateursAdmin'],$donnees_json['stripe']]);


        $chemin = substr(__DIR__, 0, -20) . 'resources\\lang\\' . $langue;
        File::makeDirectory($chemin);
        file_put_contents($chemin . '/textes.php', $this->getCodeFile($langue));

        return redirect(route('traductions.index'));
    }

    /**
     * @param $id_langue
     * @return array
     */
    private function getTraduction($id_langue): array {
        $traduction = DB::select("SELECT * FROM traductions WHERE id = ?", [$id_langue]);
        $traduction = $traduction[0];

        $infos['id'] = $traduction->id;
        unset($traduction->id);

        $infos['langue'] = $traduction->langue;
        unset($traduction->langue);

        foreach ($traduction as $pages => $textes) {
            $traduction->$pages = json_decode($textes);
        }

        return [$traduction, $infos];
    }

    /**
     * @return int
     */
    private function getMaxZoulou(): int {
        $id_zoulou = DB::select("SELECT id FROM traductions WHERE langue = 'zo'")[0]->id;
        $zoulou = $this->getTraduction($id_zoulou)[0];

        $max = 0;
        foreach ($zoulou as $page => $textes) {
            foreach ($zoulou->$page as $value) {
                if ($max < (int)(substr($value, 1))) $max = (int)(substr($value, 1));
            }
        }

        return $max;
    }

    /**
     * @param $donnees
     * @return array
     */
    private function separateData($donnees): array {
        $donnees_accueil = array();
        $donnees_admin = array();
        $donnees_conseillers = array();
        $donnees_expertise = array();
        $donnees_formules = array();
        $donnees_ingredients = array();
        $donnees_ingredientsAdmin = array();
        $donnees_proto = array();
        $donnees_symptomes = array();
        $donnees_syndromes = array();
        $donnees_formulesAdmin = array();
        $donnees_traductions = array();
        $donnees_actualites = array();
        $donnees_rechercheAdmin = array();
        $donnees_header = array();
        $donnees_headerAdmin = array();
        $donnees_footer = array();
        $donnees_conseillersAdmin = array();
        $donnees_actualitesAdmin = array();
        $donnees_arianne = array();
        $donnees_compte = array();
        $donnees_utilisateursAdmin=array();
        $donnees_stripe=array();

        foreach($donnees as $key => $donnee) {
            if (explode('_', $key)[0] == "accueil") $donnees_accueil[$key] = $donnee;
            elseif (explode('_', $key)[0] == "admin") $donnees_admin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "conseillers") $donnees_conseillers[$key] = $donnee;
            elseif (explode('_', $key)[0] == "expertise") $donnees_expertise[$key] = $donnee;
            elseif (explode('_', $key)[0] == "formules") $donnees_formules[$key] = $donnee;
            elseif (explode('_', $key)[0] == "ingredients") $donnees_ingredients[$key] = $donnee;
            elseif (explode('_', $key)[0] == "ingredientsAdmin") $donnees_ingredientsAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "proto") $donnees_proto[$key] = $donnee;
            elseif (explode('_', $key)[0] == "symptomes") $donnees_symptomes[$key] = $donnee;
            elseif (explode('_', $key)[0] == "actualites") $donnees_actualites[$key] = $donnee;
            elseif (explode('_', $key)[0] == "actualitesAdmin") $donnees_actualitesAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "syndromes") $donnees_syndromes[$key] = $donnee;
            elseif (explode('_', $key)[0] == "formulesAdmin") $donnees_formulesAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "traductions") $donnees_traductions[$key] = $donnee;
            elseif (explode('_', $key)[0] == "rechercheAdmin") $donnees_rechercheAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "header") $donnees_header[$key] = $donnee;
            elseif (explode('_', $key)[0] == "headerAdmin") $donnees_headerAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "conseillersAdmin") $donnees_conseillersAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "footer") $donnees_footer[$key] = $donnee;
            elseif (explode('_', $key)[0] == "arianne") $donnees_arianne[$key] = $donnee;
            elseif (explode('_', $key)[0] == "compte") $donnees_compte[$key] = $donnee;
            elseif (explode('_', $key)[0] == "utilisateursAdmin") $donnees_utilisateursAdmin[$key] = $donnee;
            elseif (explode('_', $key)[0] == "stripe") $donnees_stripe[$key] = $donnee;
        }

        return [
            'accueil' => json_encode($donnees_accueil),
            'admin' => json_encode($donnees_admin),
            'conseillers' => json_encode($donnees_conseillers),
            'expertise' => json_encode($donnees_expertise),
            'formules' => json_encode($donnees_formules),
            'ingredients' => json_encode($donnees_ingredients),
            'ingredientsAdmin' => json_encode($donnees_ingredientsAdmin),
            'proto' => json_encode($donnees_proto),
            'symptomes' => json_encode($donnees_symptomes),
            'syndromes' => json_encode($donnees_syndromes),
            'formulesAdmin' => json_encode($donnees_formulesAdmin),
            'traductions' => json_encode($donnees_traductions),
            'actualites' => json_encode($donnees_actualites),
            'rechercheAdmin' => json_encode($donnees_rechercheAdmin),
            'header' => json_encode($donnees_header),
            'headerAdmin' => json_encode($donnees_headerAdmin),
            'conseillersAdmin' => json_encode($donnees_conseillersAdmin),
            'actualitesAdmin' => json_encode($donnees_actualitesAdmin),
            'footer' => json_encode($donnees_footer),
            'arianne' => json_encode($donnees_arianne),
            'compte' => json_encode($donnees_compte),
            'utilisateursAdmin' => json_encode($donnees_utilisateursAdmin),
            'stripe' => json_encode($donnees_stripe)
        ];
    }

    private function getCodeFile($langue): string {
        return (
            "<?php

            use Illuminate\Support\Facades\DB;

            \$requete_textes = DB::select(\"SELECT * FROM traductions WHERE langue = '" . $langue . "'\");

            unset(\$requete_textes[0]->id);
            unset(\$requete_textes[0]->langue);

            \$textes = [];
            foreach (\$requete_textes[0] as \$pages) {
                \$pages = json_decode(\$pages);
                foreach (\$pages as \$key => \$texte) {
                    \$textes[\$key] = \$texte;
                }
            }

            return \$textes;"
        );
    }
}
