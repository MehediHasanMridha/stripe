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

# Récupère le nom de tous les symptômes
$requete_symptome = $pdo->query('SELECT id, nom FROM symptome;')

?>

<!DOCTYPE html>

<html lang="{{ $lang }}">
    <head>
        <meta charset="utf-8" />
        <!-- Ajoute Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <!-- Ajoute un fichier style.css -->
        <link href="{{ asset('css/protojz.css') }}" rel="stylesheet" type="text/css" />
        <!-- Titre de l'onglet de la page -->
        <title>{{__("textes.proto_page_nom")}}</title>
    </head>
    <body>
        <header>
            <!-- Titre de la page -->
            <h1 class="title">{{__("textes.proto_index_titre")}}</h1>
        </header>
        <section>
            <!-- Datalist attaché à tous les input possédant comme nom de list symptomeListOptions -->
            <datalist id="symptomeListOptions">
                <!-- Tant que la requête envoie des données (contenu dans la variable donnees) -->
                <?php
                    while ($donnees = $requete_symptome->fetch()) {
                        // Lecture du nombre de synonyme lié au paramètre idSymptome à définir
                        $requete_nombre_synonyme_symptome = $pdo->prepare("SELECT COUNT(*) FROM symptome_synonyme WHERE idSymptome=?");
                        // Exécution de la commande écrite au-dessus avec le paramètre défini
                        $requete_nombre_synonyme_symptome->execute([$donnees['id']]);

                        // On récupère le résultat de la requête
                        $nombre_synonyme_symptome = $requete_nombre_synonyme_symptome->fetch()['COUNT(*)'];

                        // Si le nombre de synonymes lié au symptome est supérieur à 0
                        if ($nombre_synonyme_symptome > 0) {
                            // Lecture du nom du synonyme lié au paramètre idSymptome à définir
                            $requete_synonyme_symptome = $pdo->prepare('SELECT nom FROM symptome_synonyme WHERE idSymptome=?;');
                            // Exécution de la commande écrite au-dessus avec le paramètre défini
                            $requete_synonyme_symptome->execute([$donnees['id']]);

                            // Tant que la requête envoie des données (contenu dans la variable donnes_synonyme)
                            while ($donnes_synonyme = $requete_synonyme_symptome->fetch()) { ?>
                                <!-- Ajoute une option à la datalist avec comme valeur le nom du symptome et comme affichage le synonyme -->
                                <option value="<?php echo $donnees['nom']; ?>"><?php echo $donnes_synonyme['nom']; ?></option>
                <?php       }
                        }
                        // Sinon
                        else {?>
                            <!-- Ajoute une option à la datalist avec comme valeur le nom du symptome -->
                            <option value="<?php echo $donnees['nom']; ?>">
                <?php   }
                    }
                ?>
            </datalist>

            <!-- Titre du formulaire -->
            <h2 class="title_form">{{__("textes.proto_index_question")}}</h2>

            <!-- Formulaire qui une fois validé, renverra vers le script resultat.php avec comme méthode d'envoie des données GET -->
            <form class="formulaire" name="symptome_formulaire" method="GET" action="{{  route('protojz.resultat', [$lang]) }}">

                <!-------------
                - SYMPTOME 1
                -------------->

                <!-- Boite qui contient un label, et son input avec sa datalist -->
                <div class="boite_symptome">
                    <!-- Label attaché au input symptome1 précisant que celui-ci est requis pour que le formulaire puisse être validé  -->
                    <label for="symptome1" class="form-label">{{__("textes.proto_index_premier_symptome")}} <span class="required">(*)</span></label>
                    <!-- Input de type texte possédant une liste de symptome de nom symptomeListOptions -->
                    <input class="form-control w-50" type="text" name="symptome1" list="symptomeListOptions" id="symptome1" placeholder="{{__("textes.proto_index_placeholder_symptome")}}" autocomplete="off" required>
                </div>

                <!-------------
                - SYMPTOME 2
                -------------->

                <!-- Boite qui contient un label, et son input avec sa datalist -->
                <div class="boite_symptome">
                    <!-- Label attaché au input symptome2 -->
                    <label for="symptome2" class="form-label">{{__("textes.proto_index_deuxieme_symptome")}}</label>
                    <!-- Input de type texte possédant une liste de symptome de nom symptomeListOptions -->
                    <input class="form-control w-50" type="text" name="symptome2" list="symptomeListOptions" id="symptome2" placeholder={{__("textes.proto_index_placeholder_symptome")}}" autocomplete="off" >
                </div>

                <!-------------
                - SYMPTOME 3
                -------------->

                <!-- Boite qui contient un label, et son input avec sa datalist -->
                <div class="boite_symptome">
                    <!-- Label attaché au input symptome3 -->
                    <label for="symptome3" class="form-label">{{__("textes.proto_index_troisieme_symptome")}}</label>
                    <!-- Input de type texte possédant une liste de symptome de nom symptomeListOptions -->
                    <input class="form-control w-50" type="text" name="symptome3" list="symptomeListOptions" id="symptome3" placeholder="{{__("textes.proto_index_placeholder_symptome")}}" autocomplete="off" >
                </div>

                <!-------------
                - SYMPTOME 4
                -------------->

                <!-- Boite qui contient un label, et son input avec sa datalist -->
                <div class="boite_symptome">
                    <!-- Label attaché au input symptome4 -->
                    <label for="symptome4" class="form-label">{{__("textes.proto_index_quatrieme_symptome")}}</label>
                    <!-- Input de type texte possédant une liste de symptome de nom symptomeListOptions -->
                    <input class="form-control w-50" type="text" name="symptome4" list="symptomeListOptions" id="symptome4" placeholder="{{__("textes.proto_index_placeholder_symptome")}}" autocomplete="off" >
                </div>

                <!-------------
                - SYMPTOME 5
                -------------->

                <!-- Boite qui contient un label, et son input avec sa datalist -->
                <div class="boite_symptome">
                    <!-- Label attaché au input symptome5 -->
                    <label for="symptome5" class="form-label">{{__("textes.proto_index_cinquieme_symptome")}}</label>
                    <!-- Input de type texte possédant une liste de symptome de nom symptomeListOptions -->
                    <input class="form-control w-50" type="text" name="symptome5" list="symptomeListOptions" id="symptome5" placeholder="{{__("textes.proto_index_placeholder_symptome")}}" autocomplete="off" >
                </div>


                <!-- Bouton qui notifiera le formulaire que l'utilisateur valide le formulaire  -->
                <input class="soumettre" type="submit" value="{{__("textes.proto_index_valider")}}" />
            </form>
        </section>
    </body>
</html>
