<?php session_start();
    require('fonctions.php');
    verifierAuthentification();
    $pdo = creerConnexion();
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil2.css">
    <title> Accueil </title>
</head>
<body>

    <h1> Bienvenue ! </h1>
     
    <div class=divBtns>
        <a href="affichageconsultation.php"> <button class="bouton">Consultations</button> </a> <br>
        <a href="creationconsultation.php"> <button class="bouton">Créer une consultation</button> </a> <br>
        <a href="affichagemedecins.php"> <button class="bouton">Médecins</button> </a> <br>
        <a href="creationmedecin.php"> <button class="bouton">Créer un médecin</button> </a> <br>
        <a href="affichageUsagers.php"> <button class="bouton">Patients</button> </a> <br>
        <a href="creationusager.php"> <button class="bouton">Créer un patient</button> </a> <br>
    </div>

</body>

</html>