<?php

        use Illuminate\Support\Facades\DB;

        $requete_textes = DB::select("SELECT * FROM traductions WHERE langue = 'zo'");

        unset($requete_textes[0]->id);
        unset($requete_textes[0]->langue);

        $textes = [];
        foreach ($requete_textes[0] as $pages) {
            $pages = json_decode($pages);
            foreach ($pages as $key => $texte) {
                $textes[$key] = $texte;
            }
        }

        return $textes;