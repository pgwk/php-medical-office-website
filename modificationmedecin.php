<?php session_start();
    require('fonctions.php');
    verifierAuthentification();
    $pdo = creerConnexion();

    if (!empty($_POST['Confirmer'])) {
        $stmt = $pdo->prepare('UPDATE medecin SET nom = ?, prenom = ?, civilite = ? WHERE idMedecin = ?');
        verifierPrepare($stmt);
        verifierExecute($stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['civ'], $_GET['idMedecin']]));
    }

    if (isset($_GET['idMedecin'])) {
        $sql = 'SELECT * FROM medecin WHERE idMedecin = ?';
        $stmt = $pdo->prepare($sql);
        verifierPrepare($stmt);
        verifierExecute($stmt->execute([$_GET['idMedecin']]));
        $result = $stmt->fetchAll();
    
        $civilite = array_column($result, 'civilite')[0];
        $nom = array_column($result, 'nom')[0];
        $prenom = array_column($result, 'prenom')[0];
    }
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="style.css">
    <title> Modification d'un médecin </title>
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

    <div class="titre_formulaire">
        <h1> Modification d'un médecin </h1>
    </div>

    <form class="formulaire" action="modificationMedecin.php?idMedecin=<?php echo $_GET['idMedecin']; ?>" method="post">
        <div class="conteneur_civilite">
            Civilité
            <div class="choix_civilite">
                <input type="radio" id="civM" name="civ" value="M" <?php if ($civilite == 'M') { echo 'checked'; } ?>/>
                <label for="civM">M</label>
                <img src="Images/homme.png" alt="Homme" class="image_civilite">
            </div>
            <div class="choix_civilite">
                <input type="radio" id="civMme" name="civ" value="Mme" <?php if ($civilite == 'Mme') { echo 'checked'; } ?> />
                <label for="civMme">Mme</label>
                <img src="Images/femme.png" alt="Femme" class="image_civilite">
            </div>
        </div>
        <div class="ligne_formulaire">
            <div class="colonne_formulaire moitie">
                Nom <input type="text" name="nom" value="<?php echo $nom ?>" maxlength=50 required>
            </div>
            <div class="colonne_formulaire moitie">
                Prénom <input type="text" name="prenom" value="<?php echo $prenom ?>" maxlength=50 required>
            </div>
        </div>
        <div class="conteneur_boutons">
            <input type="reset" name="Vider" value="Vider">
            <input type="submit" name="Confirmer" value="Confirmer">
        </div>
    </form>

</body>
</html>