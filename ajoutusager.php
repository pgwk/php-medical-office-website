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
            $adr=$_REQUEST['adr'];
            $ville=$_REQUEST['ville'];
            $cp=$_REQUEST['cp'];
            $nss=$_REQUEST['nss'];
            $date=$_REQUEST['date'];
            $lieu=$_REQUEST['lieu'];
            $idMed=$_REQUEST['idMed'];

            if ($idMed == "") {
                $stmt = $pdo->prepare( "INSERT INTO usager (civilite, nom, prenom, adresse, ville, codePostal, numeroSecuriteSociale, dateNaissance, lieuNaissance, medecinReferent)
                 VALUES (?,?,?,?,?,?,?,?,?,NULL)");
            $stmt->execute(["$civ","$nom","$prenom","$adr","$ville","$cp","$nss","$date","$lieu"]);

            } else {
                $stmt = $pdo->prepare( "INSERT INTO usager (civilite, nom, prenom, adresse, ville, codePostal, numeroSecuriteSociale, dateNaissance, lieuNaissance, medecinReferent)
                 VALUES (?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute(["$civ","$nom","$prenom","$adr","$ville","$cp","$nss","$date","$lieu","$idMed"]);

            }

            if ($stmt) {
                echo "Ajout d'usager effectuée!";
            } else {
                echo 'PREPARE ERROR';
            }
            
        }

    ?>