<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Suppression de contact </title>
    </head>
    <body>

    <h2> Suppression d'un contact </h2>

            <?php

                require('fonctions.php');

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if ($_GET['type']=='usager') {
                        echo '<p> Êtes vous sûr(e) de vouloir supprimer cet usager? </p>';
                    } if ($_GET['type']=='medecin') {
                        echo '<p> Êtes vous sûr(e) de vouloir supprimer ce médecin? </p>';
                    } else {
                        echo '<p> Êtes vous sûr(e) de vouloir supprimer cette consultation? </p>';
                    }
                    echo '<form action="suppression.php" method="post">';
                    echo '<input type="hidden" name="type" value="'.$_GET['type'].'">';
                    echo '<input type="hidden" name="id" value="'.$_GET['id'].'">';
                    echo '<input type="button" onclick="history.back();" value="Non">';
                    echo '<input type="submit" name ="valider" value="Oui">';
                    echo '</form>';
                }
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    try {
                        $pdo = creerConnexion();
                    } catch (Exception $e) {
                        echo ("Erreur : ".$e);
                    }
                    
                    $arguments = array();
                    if ($_REQUEST['type'] == 'usager') {
                        $stmt = $pdo->prepare("DELETE FROM consultation WHERE idUsager = ".$_REQUEST['id']);
                        $stmt->execute();
                        $stmt = $pdo->prepare("DELETE FROM usager WHERE idUsager=".$_REQUEST['id']);
                    } else if ($_REQUEST['type'] == 'medecin') {
                        $stmt = $pdo->prepare("UPDATE usager SET medecinReferent = NULL WHERE medecinReferent=".$_REQUEST['id']);
                        $stmt->execute();
                        $stmt = $pdo->prepare("DELETE FROM consultation WHERE idMedecin = ".$_REQUEST['id']);
                        $stmt->execute();
                        $stmt = $pdo->prepare("DELETE FROM medecin WHERE idMedecin=".$_REQUEST['id']);
                    } else {
                        $stmt = $pdo->prepare("DELETE FROM consultation WHERE idMedecin = ? AND dateConsultation = ? AND heureDebut = ?");
                        $arguments = explode('$', $_POST["id"]);
                    }
                    if (!$stmt) { 
                        echo "Erreur lors d'un prepare statement : " . $stmt->errorInfo(); //exit(1); 
                    }
                    
                    if ($stmt->execute($arguments)) {
                        echo 'Suppression effectuée!';
                        header("Location : index.php");
                    } else {
                        echo "Erreur lors d'un execute statement : " . $stmt->errorInfo(); //exit(1);
                    }
                    
                }

            ?>

    </body>
</html>