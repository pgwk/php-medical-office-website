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

    if (!empty($_POST["Confirmer"])) {

        $idMed = $_POST['idMed'];
        $idUsager = $_POST['idUsager'];
        $date = $_POST['date'];
        $heure = $_POST['heureD'];
        $duree = $_POST['duree'];

        $stmt = $pdo->prepare("SELECT heureDebut, duree FROM Consultation c, Medecin m WHERE c.idMedecin = m.idMedecin AND m.idMedecin = ? AND dateConsultation = ?");
        $stmt->execute(["$idMed", "$date"]);

        $message = '';
        $classeMessage = '';

        if ($stmt){
            $consulationsChevauchantes = false;
            while (!$consulationsChevauchantes && $consultation = $stmt->fetch()){
                if (consultationsChevauchantes($heure, $duree, substr($consultation['heureDebut'], 0, 5), substr($consultation['duree'], 0, 5))) {
                    $consulationsChevauchantes = true;
                }
            }
        } else {
            $message = "Erreur lors d'un execute statement : " . $stmt->errorInfo();
            $classeMessage = 'erreur';
        }

        if (!$consulationsChevauchantes) {
            $stmt = $pdo->prepare("INSERT INTO consultation VALUES (?,?,?,?,?)");
            $stmt->execute(["$idMed", "$date", "$heure", "$duree", "$idUsager"]);
            if ($stmt) {
                $elementsDate = explode('-', $date);
                $dateFormatee = $elementsDate[2] . '/' . $elementsDate[1] . '/' . $elementsDate[0];
                $nomMedecin = $pdo->query("SELECT CONCAT(' ', nom, ' ', prenom) FROM Medecin WHERE idMedecin = " . $idMed)->fetchColumn();
                $message = 'La consultation du <strong>' . $dateFormatee . '</strong> à <strong>' . str_replace(':', 'H', $heure) . '</strong> pour le médecin <strong>'. $nomMedecin . '</strong> a été ajoutée !';
                $classeMessage = 'succes';
            } else {
                $message = 'Erreur lors de la tentative d\'ajout de la consultation';
                $classeMessage = 'erreur';
            }
        } else {
            $message = 'La consultation chevauche avec un autre créneau pour ce médecin';
            $classeMessage = 'erreur';
        }
        echo '<div class="popup ' . $classeMessage . '">' .
            $message .
            '</div>';
    }

    ?>

    <div class="titre_formulaire">
        <h1> Planification d'une consultation </h1>
    </div>

    <form class="formulaire" action="creationconsultation.php" method="post">

        <?php
        $today = gmdate('Y-m-d', time());

        // On crée la combobox des médecins
        $stmt = $pdo->prepare("SELECT idMedecin, civilite, nom, prenom FROM medecin");
        if ($stmt == false) {
            echo "PREPARE ERROR";
        } else {
            echo 'Médecin <select name="idMed" id="idMed">';
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $id = $row["idMedecin"];
                $titre = $row["civilite"] . '. ' . $row["nom"] . ' ' . $row["prenom"];
                echo '<option value=' . $id . '> ' . $titre . '</option>';
            }
            echo '</select><br><br>';
        }

        // On crée la combobox des usagers
        $stmt = $pdo->prepare("SELECT idUsager, numeroSecuriteSociale, civilite, nom, prenom FROM usager ORDER BY nom, prenom ASC");
        if ($stmt == false) {
            echo "PREPARE ERROR";
        } else {
            echo 'Patient <select name="idUsager" id="idUsager">';
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $id = $row["idUsager"];
                $titre = str_pad($row["civilite"].'. ', 5, ' ') . $row["nom"] . ' ' . $row["prenom"] . ' (' . $row["numeroSecuriteSociale"] . ')';
                echo '<option value=' . $id . '> ' . htmlspecialchars($titre) . '</option>';
            }
            echo '</select><br><br>';
        }
        ?>
        <div class="ligne_formulaire temps_consultation">
            <div class="colonne_formulaire moitie">
                Date de consultation <input type="date" name="date" value="" min="<?php echo $today ?>" required>
            </div>
            <div class="colonne_formulaire moitie">
                Horaire de consultation <input type="time" name="heureD" min="08:00" max="20:00" value="08:00" required>
            </div>
            <div class="colonne_formulaire petit">
                Durée de consultation <input type="time" name="duree" min="00:05" max="02:00" value="00:30" required>
            </div>
        </div>
        <div class="conteneur_boutons">
            <input type="reset" name="Vider" value="Vider">
            <input type="submit" name="Confirmer" value="Confirmer">
        </div>
    </form>
</body>
</html>