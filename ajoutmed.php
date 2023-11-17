<?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            try {
                $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
            } catch (Exception $e) {
                echo ("Erreur : ".$e);
            }

            $civ=$_REQUEST['civ'];
            $nom=$_REQUEST['nom'];
            $prenom=$_REQUEST['prenom'];

            $stmt = $pdo->prepare( "INSERT INTO medecin (civilite, nom, prenom)
                 VALUES (?,?,?)");
                $stmt->execute(["$civ","$nom","$prenom"]);

            if ($stmt) {
                echo "Ajout de médecin effectué!";
            } else {
                echo 'PREPARE ERROR';
            }

        }

    ?>