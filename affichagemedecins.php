<?php session_start();
    if (isset($_POST['envoyer']) && !empty($_POST['pseudonyme']) && !empty($_POST['mdp'])){
        $pseudonyme = $_POST['pseudonyme'];

        if (!isset($_SESSION['pseudo'])){
            $_SESSION['pseudo'] = $pseudonyme;
        }
        if (!isset($_SESSION['nbVisitesUne']) && !isset($_SESSION['nbVisitesDeux'])){
            $nbVisitesUne = 0;
            $nbVisitesDeux = 0;
            
            $_SESSION['nbVisitesUne'] = $nbVisitesUne;
            $_SESSION['nbVisitesDeux'] = $nbVisitesDeux;
        }
    }

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="header.css">
    <title> Médecins </title>
</head>
<body>
    <header id="menu_navigation">
        <div id="logo_site">
            <img src="delete.png" width="50">
        </div>
        <nav id="navigation">
			<label for="hamburger_defiler" id="hamburger">
				<span></span>
				<span></span>
				<span></span>
			</label>
			<input class="defiler" type="checkbox" id="hamburger_defiler" role="button" aria-pressed="true">
            <ul class="headings">
                <li><a class="lien_header" href="Accueil.html">Accueil</a></li>
                <li class="deroulant"><a class="lien_header">Ajouter</a>
                    <ul class="liste_deroulante">
                        <li><a class="lien_header" href="creationusager.php">Un usager</a></li>
                        <li><a class="lien_header" href="creationmedecin.php">Un médecin</a></li>
                        <li><a class="lien_header" href="creationconsultation.php">Une consultation</a></li>
                    </ul>
                </li>
                <li class="deroulant"><a class="lien_header">Consulter</a>
                    <ul class="liste_deroulante">
                        <li><a class="lien_header" href="Competence1.html">Les usagers</a></li>
                        <li><a class="lien_header" href="Competence2.html">Les médecins</a></li>
                        <li><a class="lien_header" href="Competence3.html">Les consultations</a></li>
                    </ul>
                </li>
                <li><a class="lien_header" href="Contact.html">Statistiques</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="main_affichage">
        <h1> Liste des médecins </h1>
        <div class="conteneur_table_recherche">
            <form method="post" action="affichageMedecins.php" class="formulaire_table">
                <input type="text" name="criteres" placeholder="Entrez des mots-clés séparés par un espace" value="<?php if (isset($_POST['criteres'])) echo $_POST['criteres'] ?>">
                <input type="reset" value="Vider">
                <input type="submit" value="Rechercher">
            </form>
            </div>
                <?php
                    try {
                        $pdo = new PDO('mysql:host=localhost;dbname=cabinetmed;charset=utf8', 'root', '');
                    } catch (Exception $e) {
                        echo ("Erreur " . $e);
                    }

                    // Début de la requête, on sélectionne tous les usages et leur potentiel médecin référent
                    $reqMedecins = ' SELECT * FROM Medecin';
                    
                    $tropDeCriteres = false;
                    // Si des mots-clés/critères on été saisis
                    if (!empty($_POST["criteres"])) {
                        // On sépare les critères saisis avec les espaces
                        $listeCriteres = preg_split('/\s+/', $_POST['criteres']);

                        $nombreCriteres = count($listeCriteres);
                        // Si le dernier critère est simplement un espace, on retire un au nombre de critères
                        if ($listeCriteres[count($listeCriteres) - 1] == '') {
                            $nombreCriteres--;
                        }

                        // S'il y a trop de critères, on annule la recherche
                        if ($nombreCriteres > 5){
                            $tropDeCriteres = true;
                        }

                        // On vérifie, pour chacune des colonnes, si elle correspond à un des critère
                        $listeColonnes = array('civilite', 'nom', 'prenom');
                        if ($nombreCriteres > 0 && !$tropDeCriteres) {
                            $reqMedecins = $reqMedecins . ' WHERE ';
                            for ($i = 0; $i < count($listeColonnes); $i++) {
                                for ($j = 0; $j < $nombreCriteres; $j++) {
                                    $reqMedecins = $reqMedecins . $listeColonnes[$i] . ' LIKE :critere' . $j . ' OR ';
                                }
                            }
                            // Pour enlever le dernier 'OR'
                            $reqMedecins = substr($reqMedecins, 0, -4);

                            // On remplace les ':critereX' avec un prepared statement
                            $stmt = $pdo->prepare($reqMedecins);
                            for ($i = 0; $i < $nombreCriteres; $i++) {
                                $stmt->bindParam(':critere' . $i, $listeCriteres[$i]);
                            }
                        }
                    } else { // Sinon on prépare simplement la requête
                        $stmt = $pdo->prepare($reqMedecins);
                    }

                    // Si la recherche est annulée, on affiche un message d'erreur
                    if ($tropDeCriteres){
                        echo '<div class="nombre_lignes" style="color: red;"> Veuillez saisir au plus <strong>5</strong> mots-clés</div>';
                    } else { // Sinon on procède à la recherche
                        // On execute la requête 
                        if (!$stmt->execute()) { print_r($stmt->errorInfo()); }

                        // On affiche toutes les lignes renvoyées ou un message si rien n'a été trouvé
                        if ($stmt->rowCount() > 0){
                            echo '<div class="nombre_lignes"><strong>'.$stmt->rowCount().'</strong> médecins trouvés</div>';
                            echo '<table id="table_affichage">
                                    <thead>
                                        <tr>
                                            <th onclick="sortTable(0)">Civilite </th>
                                            <th onclick="sortTable(1)">Nom </th>
                                            <th onclick="sortTable(2)">Prenom </th>
                                        </tr>
                                    </thead>';
                            while ($dataMedecin = $stmt->fetch()){
                                echo '<tr><td>'.$dataMedecin['civilite'].'</td>'. 
                                        '<td>'.$dataMedecin['nom'].'</td>'.
                                        '<td>'.$dataMedecin['prenom'].'</td>'.                    
                                        '<td>'.'<a href = \'modificationMedecin.php?idMedecin='.$dataMedecin[0].'\'><img src="Images/modifier.png" alt=""width=30px></img></a>'.'</td>'.
                                        '<td>'.'<a href = \'suppression.php?id='.$dataMedecin[0].'&type=medecin\'><img src="Images/supprimer.png" alt=""width=30px></img></a>'.'</td>'.'</tr>';
                            }
                        } else {
                            echo '<div class="nombre_lignes" style="color: red;"><strong>Aucun</strong> médecin trouvé</div>';
                        }
                    }
                    ?>
        </div>
    </main>
    <!-- Script pour trier une table en cliquant sur une colonne -->
    <script src="tri-tableau.js"></script>
</body>

</html>