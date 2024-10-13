<?php

// Variable servant à la connexion à la base de données
//$serveur = 'localhost';  // Chemin pour se connecter
//$bdd = 'sunsimiao';  // Nom de la base de données qui nous intéresse
//$utilisateur = 'sunsimiaofr';  // Nom de l'utilisateur servant à se connecter
//$mdp = 'xq9J8@2p';  // Mot de passe de l'utilisateur servant à se connecter

$serveur = 'localhost';  // Chemin pour se connecter
$bdd = 'jz';  // Nom de la base de données qui nous intéresse
$utilisateur = 'root';  // Nom de l'utilisateur servant à se connecter
$mdp = '';  // Mot de passe de l'utilisateur servant à se connecter

// Connexion à la base de données en lui donnant les bons paramètres défini plus haut
$pdo = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8", $utilisateur, $mdp);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Constante qui attribuera un score au symptome selon son emplacement dans la liste des symptomes donnée par l'utilisateur
$SCORE_SYMPTOME = [30, 25, 20, 10, 5];

// Requête qui renverra le nombre de symptômes présent dans la table symptome
$nombre_symptome = $pdo->query('SELECT COUNT(*) FROM symptome')->fetch()['COUNT(*)'];

// Variable de type array (clé => valeur)
$detail_score_syndrome = array();
// Variable de type array (clé => valeur)
$score_syndrome = array();
// Boucle allant de 0 au nombre de symptômes présent dans la table symptome récupérer
for($i = 0; $i <= $nombre_symptome; $i++) {
    // Attribut à score_syndrome, à la clé l'id du syndrome la valeur 0
    $score_syndrome[$i+1] = 0;
    // Attribut à detail_score_syndrome, à la clé l'id du syndrome la valeur texte vide
    $detail_score_syndrome[$i+1] = '';
}

// Variable de type array (clé => valeur)
$detail_score_formule = array();
// Variable de type array (clé => valeur)
$score_formule = array();
// Boucle allant de 0 au nombre de symptômes présent dans la table symptome récupérer
for($i = 0; $i <= $nombre_symptome; $i++) {
    // Attribut à score_formule, à la clé l'id de la formule la valeur 0
    $score_formule[$i+1] = 0;
    // Attribut à detail_score_syndrome, à la clé l'id du syndrome la valeur texte vide
    $detail_score_formule[$i+1] = '';
}

// Variable qui contiendra la liste des symptomes envoyés par l'utilisateur
$liste_symptomes = [];

// Boucle de 6 itérations
for ($i = 1; $i < 6; $i++) {
    // Récupère le texte du symptômes envoyé par le formulaire rempli l'utilisateur
    $symptome = $_GET['symptome' . $i];
    // Si la longueur du texte du symptome est supérieur à 0, on ajoute à la liste des symptômes, le symptome
    if (strlen($symptome) > 0) array_push($liste_symptomes, $symptome);
}

// Pour chaque symptôme présent dans liste_symptomes où on récupère l'index de celui-ci dans le tableau et sa valeur
foreach ($liste_symptomes as $index => $symptome) {
    // Lecture du nom du symptome de la table symptome avec le paramètre nom à définir
    $requete_id_symptome = $pdo->prepare("SELECT id FROM symptome WHERE nom=?;");
    // Exécution de la commande écrite au-dessus avec le paramètre défini
    $requete_id_symptome->execute([$symptome]);

    // On récupère le résultat de la requête
    $id_symptome = $requete_id_symptome->fetch()['id'];

    /* Provoque la fermeture de l'analyse des résultats de cette requête
    afin d'éviter d'éventuelles erreurs à la prochaine requête */
    $requete_id_symptome->closeCursor();


    /*******************************
     * REQUETE SYNDROME_DETAIL
     ******************************/


    // Lecture de l'idSyndrome et de son score de la table syndrome_detail avec le paramètre idSymptome à définir
    $requete_syndrome_detail = $pdo->prepare("SELECT idSyndrome, score FROM syndrome_detail WHERE idSymptome=?;");
    // Exécution de la commande écrite au-dessus avec le paramètre défini
    $requete_syndrome_detail->execute([$id_symptome]);

    // Tant que la requête envoie des données (contenu dans la variable donnees)
    while ($donnees = $requete_syndrome_detail->fetch()) {
        // Si le score du syndrome pour ce symptome récupéré est supérieur à 0
        if ($donnees['score'] > 0) {
            /* On ajoute à score_syndrome à la clé correspondant à l'id du syndrome récupéré :
            le score du syndrome récupéré et la valeur contenu dans la constante SCORE_SYMPTOME
            correspondant à l'index du symptome dans le tableau des symptomes */
            $score_syndrome[$donnees['idSyndrome']] += $SCORE_SYMPTOME[$index] + $donnees['score'];
            /* On ajoute à detail_score_syndrome à la clé correspondant à l'id du syndrome récupéré :
            le detail du calcul qui a permit d'obtenir le score */
            $detail_score_syndrome[$donnees['idSyndrome']] .= '<span style="color: orange">' . $SCORE_SYMPTOME[$index] . '</span> + <span style="color: dodgerblue">' . $donnees['score'] . '</span>;';
        }
        // Sinon on indique qu'il n'y eu aucun calcul entre ce syndrome et ce symptôme
        else $detail_score_syndrome[$donnees['idSyndrome']] .= ';';
    }

    /* Provoque la fermeture de l'analyse des résultats de cette requête
    afin d'éviter d'éventuelles erreurs à la prochaine requête */
    $requete_syndrome_detail->closeCursor();


    /*******************************
     * REQUETE FORMULE_DETAIL
     ******************************/


    // Lecture de l'idFormule et de son score de la table formule_detail avec le paramètre idSymptome à définir
    $requete_formule_detail = $pdo->prepare('SELECT idFormule, score FROM formule_detail WHERE idSymptome=?;');
    // Exécution de la commande écrite au-dessus avec le paramètre défini
    $requete_formule_detail->execute([$id_symptome]);

    // Tant que la requête envoie des données (contenu dans la variable donnees)
    while ($donnees = $requete_formule_detail->fetch()) {
        // Si le score de la formule pour ce symptome récupéré est supérieur à 0
        if ($donnees['score'] > 0) {
            /* On ajoute à score_formule à la clé correspondant à l'id de la formule récupéré :
            le score de la formule récupéré et la valeur contenu dans la constante SCORE_SYMPTOME
            correspondant à l'index du symptome dans le tableau des symptomes */
            $score_formule[$donnees['idFormule']] += $SCORE_SYMPTOME[$index] + $donnees['score'];
            /* On ajoute à detail_score_formule à la clé correspondant à l'id de la formule récupéré :
            le detail du calcul qui a permit d'obtenir le score */
            $detail_score_formule[$donnees['idFormule']] .= '<span style="color: orange">' . $SCORE_SYMPTOME[$index] . '</span> + <span style="color: dodgerblue">' . $donnees['score'] . '</span>;';
        }
        // Sinon on indique qu'il n'y eu aucun calcul entre cette formule et ce symptôme
        else $detail_score_formule[$donnees['idFormule']] .= ';';
    }

    /* Provoque la fermeture de l'analyse des résultats de cette requête
    afin d'éviter d'éventuelles erreurs à la prochaine requête */
    $requete_formule_detail->closeCursor();
}


/*******************************
 * REQUETE SYNDROME
 ******************************/


// Variable de type array (clé => valeur)
$liste_detail_syndrome = array();
$liste_syndrome_max_score = array();

// Boucle de 3 itérations
for ($i = 0; $i < 3; $i++) {
    // Récupère le score maximum contenu dans l'array score_syndrome
    $score_max = max($score_syndrome);
    // Récupère la clé de ce score
    $cle_score_max = array_search($score_max, $score_syndrome);

    /* Créer dans liste_detail_syndrome une ligne possédant comme clé l'index du score récupéré et comme valeur le detail
    qui a permit d'obtenir ce score */
    $liste_detail_syndrome[$cle_score_max] = $detail_score_syndrome[$cle_score_max];

    // Créer dans liste_syndrome_max_score une ligne possédant comme clé l'index du score récupéré et comme valeur le score
    $liste_syndrome_max_score[$cle_score_max] = $score_max;

    // Supprime la clé et le score récupéré de l'array score_syndrome
    unset($score_syndrome[array_search($score_max, $score_syndrome)]);
}

// Variable de type array (clé => valeur)
$liste_detail_score_syndrome = array();
$liste_syndrome = array();

// Pour chaque syndrome présent dans liste_syndrome_max_score où on récupère la clé de celui-ci dans le tableau et son score
foreach ($liste_syndrome_max_score as $cle => $score) {
    // Lecture du nom du syndrome de la table syndrome avec le paramètre id à définir
    $requete_nom_syndrome = $pdo->prepare("SELECT nom FROM syndrome WHERE id=?;");
    // Exécution de la commande écrite au-dessus avec le paramètre défini
    $requete_nom_syndrome->execute([$cle]);
    // On récupère le résultat de la requête
    $nom_syndrome = $requete_nom_syndrome->fetch()['nom'];

    // Créer dans liste_detail_score_syndrome une ligne possédant comme clé le nom du syndrome récupéré et comme valeur le detail du score
    $liste_detail_score_syndrome[$nom_syndrome] = $liste_detail_syndrome[$cle];

    // Créer dans liste_syndrome une ligne possédant comme clé le nom du syndrome récupéré et comme valeur le score
    $liste_syndrome[$nom_syndrome] = $score;

    /* Provoque la fermeture de l'analyse des résultats de cette requête
    afin d'éviter d'éventuelles erreurs à la prochaine requête */
    $requete_nom_syndrome->closeCursor();
}


/*******************************
 * REQUETE FORMULE
 ******************************/


// Variable de type array (clé => valeur)
$liste_detail_formule = array();
$liste_formule_max_score = array();

// Boucle de 3 itérations
for ($i = 0; $i < 3; $i++) {
    // Récupère le score maximum contenu dans score_formule
    $score_max = max($score_formule);
    // Récupère la clé de ce score
    $cle_score_max = array_search($score_max, $score_formule);

    /* Créer dans liste_detail_syndrome une ligne possédant comme clé l'index du score récupéré et comme valeur le detail
    qui a permit d'obtenir ce score */
    $liste_detail_formule[$cle_score_max] = $detail_score_formule[$cle_score_max];

    // Créer dans liste_formule_max_score une ligne possédant comme clé l'index du score récupéré et comme valeur le score
    $liste_formule_max_score[$cle_score_max] = $score_max;

    // Supprime la clé et le score récupéré de l'array score_syndrome
    unset($score_formule[array_search($score_max, $score_formule)]);
}

// Variable de type array (clé => valeur)
$liste_detail_score_formule = array();
$liste_formule = array();

foreach ($liste_formule_max_score as $cle => $score) {
    // Lecture du nom de la formule de la table formule avec le paramètre id à définir
    $requete_nom_formule = $pdo->prepare("SELECT nom FROM formule WHERE id=?;");
    // Exécution de la commande écrite au-dessus avec le paramètre défini
    $requete_nom_formule->execute([$cle]);
    // On récupère le résultat de la requête
    $nom_formule = $requete_nom_formule->fetch()['nom'];

    // Créer dans liste_detail_score_formule une ligne possédant comme clé le nom du syndrome récupéré et comme valeur le detail du score
    $liste_detail_score_formule[$nom_formule] = $liste_detail_formule[$cle];

    // Créer dans liste_formule une ligne possédant comme clé le nom de la formule récupéré et comme valeur le score
    $liste_formule[$nom_formule] = $score;

    /* Provoque la fermeture de l'analyse des résultats de cette requête
    afin d'éviter d'éventuelles erreurs à la prochaine requête */
    $requete_nom_formule->closeCursor();
}

$calcul_syndrome = [];
// Pour chaque élément contenu dans liste_detail_score_syndrome et instancié dans detail_syndrome
foreach ($liste_detail_score_syndrome as $detail_syndrome) {
    // On sépare les élément qui sont avant et après ';'
    $calculs = explode(';', $detail_syndrome);

    // Si le dernier élément de calculs et une chaine de caractère vide, on supprime cette élément
    if(strlen($calculs[count($calculs) -1]) < 1) unset($calculs[count($calculs)-1]);

    // On ajoute au tableau calcul_syndrome le tableau contenant le détail des calculs du score de ce syndrome
    array_push($calcul_syndrome, $calculs);
}

$calcul_formule = [];
// Pour chaque élément contenu dans liste_detail_score_formule et instancié dans detail_formule
foreach ($liste_detail_score_formule as $detail_formule) {
    // On sépare les élément qui sont avant et après ';'
    $calculs = explode(';', $detail_formule);

    // Si le dernier élément de calculs et une chaine de caractère vide, on supprime cette élément
    if(strlen($calculs[count($calculs) -1]) < 1) unset($calculs[count($calculs)-1]);

    // On ajoute au tableau calcul_formule le tableau contenant le détail des calculs du score de cette formule
    array_push($calcul_formule, $calculs);
}

// Variable indiquant à quelle ligne nous sommes
$nb_ligne = 0;
?>

<!DOCTYPE html>

<html lang="{{ $lang }}">
    <head>
        <meta charset="utf-8" />
        <!-- Ajoute un fichier style.css -->
        <link href="{{ asset('css/protojz.css') }}" rel="stylesheet" type="text/css" />
        <!-- Titre de l'onglet de la page -->
        <title>{{__("textes.proto_page_nom")}}</title>
    </head>
    <body>
        <header>
            <!-- Titre de la page -->
            <h1 class="title">{{__("textes.proto_resultat_titre")}}</h1>
            <!-- Affiche tous les symptômes écris par l'utilisateur dans ce sous-titre -->
            <h3 class="subtitle">{{__("textes.proto_resultat_sous_titre")}} <?php foreach ($liste_symptomes as $symptome) echo '| ' . $symptome . ' |' ?></h3>
        </header>

        <section>

            <!-------------
            - SYNDROME
            -------------->

            <!-- Créer un nouveau tableau qui affichera tous les syndromes choisi par le programme et leur score -->
            <table class="tableau">
                <thead class="tableau_header">
                    <!-- Affiche dans une nouvelle ligne -->
                    <tr>
                        <!-- Titre du tableau prenant comme largeur 3 + le nombre des symptôme indiqués colonnes -->
                        <th class="title_tableau" colspan="<?php echo 3 + count($liste_symptomes) ?>">{{__("textes.proto_resultat_premier_tableau_titre")}}</th>
                    </tr>
                    <!-- Affiche dans une nouvelle ligne -->
                    <tr>
                        <!-- Légende de la colonne 1 -->
                        <th>{{__("textes.proto_resultat_premier_tableau_nom")}}</th>
                        <!-- Légende du nom de tous les symptomes indiqués -->
                        <?php foreach($liste_symptomes as $symptome) echo '<th>' . $symptome . '</th>' ?>
                        <!-- Légende de la colonne 2 -->
                        <th>{{__("textes.proto_resultat_premier_tableau_score")}}</th>
                        <!-- Légende de la colonne 3 -->
                        <th>{{__("textes.proto_resultat_premier_tableau_pourcentage")}}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pour chaque syndrome et son score contenu dans l'array liste_syndrome -->
                    <?php foreach ($liste_syndrome as $syndrome => $score) { ?>
                        <!-- Affiche dans une nouvelle ligne -->
                        <tr>
                            <!-- Une colonne affichant le syndrome -->
                            <td>
                                    <?php
                                        // Si le score du syndrome est supérieur à 0, on affiche le syndrome
                                        if ($score > 0) echo $syndrome;
                                        // Sinon on affiche une phrase à la place du syndrome
                                        else echo "Plus aucun syndrome ne correspond au(x) symptômes déclaré(s)"
                                    ?>
                            </td>
                            <!-- Pour chaque symptôme et son index contenu dans le tableau liste-symptomes -->
                            <?php foreach($liste_symptomes as $idx => $symptome) { ?>
                                <td>
                                    <?php
                                        // Si un calcul existe entre le syndrome et le symptôme, on l'affiche
                                        if(isset($calcul_syndrome[$nb_ligne][$idx])) echo $calcul_syndrome[$nb_ligne][$idx];
                                        // Sinon on affiche rien
                                        else echo '';
                                    ?>
                                </td>
                            <?php } ?>
                            <!-- Une colonne affichant son score -->
                            <td><?php echo $score ?></td>
                            <!-- Et une colonne affichant le pourcentage -->
                            <td>
                                <?php
                                    // La sommes des scores contenu dans liste_syndrome est supérieur à 0
                                    if (array_sum($liste_syndrome) > 0) {
                                        // On affiche le pourcentage du score récupéré par rapport à la sommes ainsi que le calcul
                                        echo (int)(($score * 100) / array_sum($liste_syndrome)) . ' %';
                                    }
                                    // Sinon on affiche rien
                                    else echo '' ;
                                ?>
                            </td>
                        </tr>
                    <!-- On incrément nb_ligne de 1 car on passe à une nouvelle ligne du tableau -->
                    <?php $nb_ligne++; } ?>
                </tbody>
            </table>

            <!-------------
            - FORMULE
            -------------->

            <!-- Créer un nouveau tableau qui affichera toutes les formules choisi par le programme  et leur score -->
            <table class="tableau">
                <thead class="tableau_header">
                <!-- Affiche dans une nouvelle ligne -->
                <tr>
                    <!-- Titre du tableau prenant comme largeur 5 + le nombre des symptôme indiqués colonnes -->
                    <th class="title_tableau" colspan="<?php echo 5 + count($liste_symptomes) ?>">{{__("textes.proto_resultat_deuxieme_tableau_titre")}}</th>
                </tr>
                <!-- Affiche dans une nouvelle ligne -->
                <tr>
                    <!-- Légende de la colonne 1 -->
                    <th>{{__("textes.proto_resultat_deuxieme_tableau_nom")}}</th>
                    <!-- Légende de la colonne 2 -->
                    <th>{{__("textes.proto_resultat_deuxieme_tableau_nom_fr")}}</th>
                    <!-- Légende de la colonne 3 -->
                    <th>{{__("textes.proto_resultat_deuxieme_tableau_nom_zh")}}</th>
                    <!-- Légende du nom de tous les symptomes indiqués -->
                    <?php foreach($liste_symptomes as $symptome) echo '<th>' . $symptome . '</th>' ?>
                    <!-- Légende de la colonne après le dernier symptôme -->
                    <th>{{__("textes.proto_resultat_deuxieme_tableau_score")}}</th>
                    <!-- Légende de la colonne d'après -->
                    <th>{{__("textes.proto_resultat_deuxieme_tableau_pourcentage")}}</th>
                </tr>
                </thead>
                <tbody>
                    <!-- Pour chaque formule et son score contenu dans l'array liste_formule -->
                    <?php $nb_ligne = 0; foreach ($liste_formule as $formule => $score) { ?>
                        <!-- Affiche dans une nouvelle ligne -->
                        <tr>
                            <!-- Une colonne affichant la formule -->
                            <td>
                                <?php
                                    // Si le score de la formule est supérieur à 0, on affiche la formule
                                    if ($score > 0) echo $formule;
                                    // Sinon on affiche une phrase à la place du syndrome
                                    else echo "Plus aucune formule ne correspond au(x) symptômes déclaré(s)"
                                ?>
                            </td>
                            <!-- Une colonne affichant la formule en français -->
                            <!--<td>
                                <?php
                                // Si le score de la formule est supérieur à 0, on affiche la formule
                                //if ($score > 0) {
                                //    // Lecture du nom français de la formule de la table formule avec le paramètre nom à //définir
                                //    $requete_nom_francais_formule = $pdo->prepare('SELECT nom_francais FROM formule WHERE //nom = ?');
                                //    // Exécution de la commande écrite au-dessus avec le paramètre défini
                                //    $requete_nom_francais_formule->execute([$formule]);
//
                                //    // On récupère le résultat de la requête
                                //    $nom_francais_formule = $requete_nom_francais_formule->fetch()['nom_francais'];
//
                                //    // On affiche le nom français de la formule
                                //    echo $nom_francais_formule;
                                //}
                                //// Sinon on affiche rien
                                //else echo ""
                                ?>
                            </td>-->
                            <!-- Une colonne affichant la formule en chinois -->
                            <td>
                                <?php
                                // Si le score de la formule est supérieur à 0, on affiche la formule
                                if ($score > 0) {
                                    // Lecture du nom chinois de la formule de la table formule avec le paramètre nom à définir
                                    $requete_nom_chinois_formule = $pdo->prepare('SELECT nom_chinois FROM formule WHERE nom = ?');
                                    // Exécution de la commande écrite au-dessus avec le paramètre défini
                                    $requete_nom_chinois_formule->execute([$formule]);

                                    // On récupère le résultat de la requête
                                    $nom_chinois_formule = $requete_nom_chinois_formule->fetch()['nom_chinois'];

                                    // On affiche le nom français de la formule
                                    echo $nom_chinois_formule;
                                }
                                // Sinon on affiche rien
                                else echo ""
                                ?>
                            </td>

                            <?php foreach($liste_symptomes as $idx => $symptome) { ?>
                                <td>
                                    <?php
                                    // Si un calcul existe entre cette formule et ce symptôme, on l'affiche
                                    if(isset($calcul_formule[$nb_ligne][$idx])) echo $calcul_formule[$nb_ligne][$idx];
                                    // Sinon on affiche rien
                                    else echo '';
                                    ?>
                                </td>
                            <?php } ?>

                            <!-- Une colonne affichant son score -->
                            <td><?php echo $score ?></td>
                            <!-- Et une colonne affichant le pourcentage -->
                            <td>
                                <?php
                                    // La sommes des scores contenu dans liste_formule est supérieur à 0
                                    if (array_sum($liste_formule) > 0) {
                                        // On affiche le pourcentage du score récupéré par rapport à la sommes
                                        echo (int)(($score * 100) / array_sum($liste_formule)) . ' %';
                                    }
                                    // Sinon on affiche rien
                                    else echo '';
                                ?>
                            </td>
                        </tr>
                    <!-- On incrément nb_ligne de 1 car on passe à une nouvelle ligne du tableau -->
                    <?php $nb_ligne++; } ?>
                </tbody>
            </table>
        </section>

        <footer>
            <!-- Bouton qui envoie l'utilisateur vers la page précédente  -->
            <button type="button" onclick="history.back()">{{__("textes.proto_resultat_btn_back")}}</button>
        </footer>
    </body>
</html>
