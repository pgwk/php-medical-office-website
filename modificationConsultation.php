<?php session_start();
    require('fonctions.php');
    verifierAuthentification();
    $pdo = creerConnexion();
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="style.css">
    <title> Planification de consultation </title>
</head>

<body id="body_fond">
    <header id="menu_navigation">
        <div id="logo_site">
            <a href="accueil.html"><img src="Images/logo.png" width="250"></a>
        </div>
        <nav id="navigation">
            <label for="hamburger_defiler" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <input class="defiler" type="checkbox" id="hamburger_defiler" role="button" aria-pressed="true">
            <ul class="headings">
                <li><a class="lien_header" href="affichageUsagers.php">Usagers</a></li>
                <li><a class="lien_header" href="affichageMedecins.php">Médecins</a></li>
                <li><a class="lien_header" href="affichageConsultations.php">Consultations</a></li>
                <li><a class="lien_header" href="statistiques.php">Statistiques</a></li>
            </ul>
        </nav>
    </header>

    <?php

    function consultationsChevauchantes($heureDebutC1, $dureeC1, $heureDebutC2, $dureeC2) {
        // On crée les dates de début et de fin des deux consultations
        $debutC1 = DateTime::createFromFormat('H:i', $heureDebutC1);
        $finC1 = clone $debutC1;
        list($hours, $minutes) = explode(':', $dureeC1);
        $finC1->add(new DateInterval("PT{$hours}H{$minutes}M"));

        $debutC2 = DateTime::createFromFormat('H:i', $heureDebutC2);
        $finC2 = clone $debutC2;
        list($hours, $minutes) = explode(':', $dureeC2);
        $finC2->add(new DateInterval("PT{$hours}H{$minutes}M"));

        // On vérifie si les consultations se chevauchent
        if (($debutC1 >= $debutC2 AND $debutC1 < $finC2) ||
            ($finC1 > $debutC2 AND $finC1 <= $finC2) || 
            ($debutC2 >= $debutC1 AND $debutC2 < $finC1)) {
                return true;
        }
        return false;
    }
    
    if (isset($_GET["id"])){

        $cleConsultation = explode('$', $_GET["id"]);
        if (isset($_POST["Confirmer"])) {

            $idUsager = $_POST['idUsager'];
            $date = $_POST['date'];
            $heure = substr($_POST['heureD'], 0, 5);
            $duree = substr($_POST['duree'], 0, 5);
    
            $stmt = $pdo->prepare(" SELECT heureDebut, duree 
                                    FROM Consultation c, Medecin m 
                                    WHERE c.idMedecin = m.idMedecin 
                                    AND c.idMedecin = ?
                                    AND c.dateConsultation = ?
                                    AND c.heureDebut <> ?");
            verifierPrepare($stmt);
            verifierExecute($stmt->execute($cleConsultation));

            $consulationsChevauchantes = false;
            while (!$consulationsChevauchantes && $consultation = $stmt->fetch()){
                if (consultationsChevauchantes($heure, $duree, substr($consultation['heureDebut'], 0, 5), substr($consultation['duree'], 0, 5))) {
                    $consulationsChevauchantes = true;
                }
            }
            

            if (!$consulationsChevauchantes) {
                $stmt = $pdo->prepare(" UPDATE Consultation
                                        SET idUsager = ?, heureDebut = ?, duree = ?
                                        WHERE idMedecin = ?
                                        AND dateConsultation = ?
                                        AND heureDebut = ?");
                verifierPrepare($stmt);
                verifierExecute($stmt->execute([$idUsager, $heure, $duree, $cleConsultation[0], $cleConsultation[1], $cleConsultation[2]]));

                $elementsDate = explode('-', $date);
                $dateFormatee = $elementsDate[2] . '/' . $elementsDate[1] . '/' . $elementsDate[0];
                $usager = $pdo->query("SELECT CONCAT(civilite, '. ', nom, ' ', prenom) FROM Usager WHERE idUsager = " . $idUsager)->fetchColumn();
                $message = 'La consultation a été modifiée ! Elle a lieu le <strong>' . $dateFormatee . '</strong> à <strong>' . str_replace(':', 'H', $heure) . '</strong> pour le patient <strong>' . $usager . '</strong>';
                $classeMessage = 'succes';
                $cleConsultation[2] = $heure;
            } else {
                $message = 'La consultation chevauche avec un autre créneau pour ce médecin';
                $classeMessage = 'erreur';
            }
            echo '<div class="popup ' . $classeMessage . '">' .
                $message .
                '</div>';
        }

        // Récupération du médecin et de l'usager concernés par la consultation
        // ainsi que de la date, l'heure et la durée de la consultation
        $medecin = '';
        $usagerActuel = '';
        $idUsagerActuel = -1;
        $stmt = $pdo->prepare(" SELECT m.civilite AS civM, m.nom AS nomM, m.prenom AS prenomM,
                                       u.civilite AS civU, u.nom AS nomU, u.prenom AS prenomU, numeroSecuriteSociale, u.idUsager AS idUsager,
                                       dateConsultation, heureDebut, duree
                                FROM medecin m, consultation c, usager u
                                WHERE m.idMedecin = c.idMedecin 
                                AND u.idUsager = c.idUsager
                                AND c.idMedecin = ?
                                AND c.dateConsultation = ?
                                AND c.heureDebut = ?");
        verifierPrepare($stmt);
        verifierExecute($stmt->execute($cleConsultation));
            
        $resultat = $stmt->fetch();
        $medecin = $resultat["civM"] . '. ' . $resultat["nomM"] . ' ' . $resultat["prenomM"];
        $usagerActuel = $resultat["civU"] . '. ' . $resultat["nomU"] . ' ' . $resultat["prenomU"] . ' (' . $resultat["numeroSecuriteSociale"] . ')';
        $idUsagerActuel = $resultat["idUsager"];
        $date = $resultat["dateConsultation"];
        $heure = $resultat["heureDebut"];
        $duree = $resultat["duree"];
        $id = $cleConsultation[0].'$'.$cleConsultation[1].'$'.$cleConsultation[2];
    }

    ?>

    <div class="titre_formulaire">
        <h1> Modification d'une consultation </h1>
    </div>

    <form class="formulaire" action="modificationConsultation.php?id=<?php echo $id ?>" method="post">
        Médecin <input type="type" value="<?php echo $medecin ?>" readonly><br><br>
        <?php
        $today = gmdate('Y-m-d', time());

        // Création de la liste des usagers
        $stmt = $pdo->prepare(" SELECT idUsager, numeroSecuriteSociale, civilite, nom, prenom 
                                    FROM usager
                                    WHERE idUsager <> ?
                                    ORDER BY nom, prenom ASC");
        verifierPrepare($stmt);
        verifierExecute($stmt->execute([$idUsagerActuel]));

        echo 'Patient <select name="idUsager" id="idUsager">';
        echo '<option value=' . $idUsagerActuel . '> ' . $usagerActuel . '</option>';
        $stmt->execute();
        while ($resultat = $stmt->fetch()) {
            $id = $resultat["idUsager"];
            $titre = $resultat["civilite"] . '. ' . $resultat["nom"] . ' ' . $resultat["prenom"] . ' (' . $resultat["numeroSecuriteSociale"] . ')';
            echo '<option value=' . $id . '> ' . htmlspecialchars($titre) . '</option>';
        }
        echo '</select><br><br>';


        ?>
        <div class="ligne_formulaire temps_consultation">
            <div class="colonne_formulaire moitie">
                Date de consultation <input type="date" name="date" value="<?php echo $date ?>" min="<?php echo $today ?>" readonly>
            </div>
            <div class="colonne_formulaire moitie">
                Horaire de consultation <input type="time" name="heureD" min="08:00" max="20:00" value="<?php echo $heure ?>" required>
            </div>
            <div class="colonne_formulaire petit">
                Durée de consultation <input type="time" name="duree" min="00:05" max="02:00" value="<?php echo $duree ?>" required>
            </div>
        </div>
        <div class="conteneur_boutons">
            <input type="reset" name="Vider" value="Réiniatiliser">
            <input type="submit" name="Confirmer" value="Confirmer">
        </div>
    </form>
</body>
</html>