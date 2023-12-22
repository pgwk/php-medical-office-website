<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Planification de consultation </title>
    </head>
    <body>

            <h1> Planification d'une consultation </h1>

            <form action="creationconsultation.php" method="post">
                <?php
                    $today = gmdate('Y-m-d', time());
                    echo 'Date de consultation <input type="date" name="date" value="" min ='.$today.'><br><br>';
                    try {
                        $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
                    } catch (Exception $e) {
                        echo ("Erreur : ".$e);
                    }
                    $stmt = $pdo->prepare("SELECT idMedecin, civilite, nom, prenom FROM medecin");
                    if ($stmt == false) {
                        echo "PREPARE ERROR"; 
                    } else {
                        echo 'Médecin <select name="idMed" id="idMed">';
                        $stmt->execute();
                        while ($row = $stmt->fetch()) {
                            $id = $row["idMedecin"];
                            $titre = $row["civilite"].'. '.$row["nom"].' '.$row["prenom"];
                            echo '<option value='.$id.'> '.$titre.'</option>';
                        }
                        echo '</select><br><br>';
                    }
                    $stmt = $pdo->prepare("SELECT idUsager, civilite, nom, prenom FROM usager");
                    if ($stmt == false) {
                        echo "PREPARE ERROR"; 
                    } else {
                        echo 'Patient <select name="idUsager" id="idUsager">';
                        $stmt->execute();
                        while ($row = $stmt->fetch()) {
                            $id = $row["idUsager"];
                            $titre = $row["civilite"].'. '.$row["nom"].' '.$row["prenom"];
                            echo '<option value='.$id.'> '.$titre.'</option>';
                        }
                        echo '</select><br><br>';
                    }
                ?>
                Horaire de consultation <input id="heureD" type="time" name="heureD" /><br><br>
                Durée de consultation <input id="duree" name = "duree" class="html-duration-picker" data-duration="00:30:00" data-hide-seconds><br><br>
                <input type="submit" name="Valider" value="Confirmer">
                <input type="reset" name="Vider" value ="Vider">
            </form>
            <br><br>

    <script src="html-duration-picker.min.js"></script>
    </body>
</html>

<?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            try {
                $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
            } catch (Exception $e) {
                echo ("Erreur : ".$e);
            }

            $idMed=$_REQUEST['idMed'];
            $idUsager=$_REQUEST['idUsager'];
            $date=$_REQUEST['date'];
            $heure=$_REQUEST['heureD'];
            $duree=$_REQUEST['duree'];

            $stmt = $pdo->prepare( "INSERT INTO consultation VALUES (?,?,?,?,?)");
                $stmt->execute(["$idMed","$date","$heure","$duree","$idUsager"]);

            if ($stmt) {
                echo '<script type="text/javascript">window.alert("Planification de consultation effecutée");</script>';
            } else {
                echo 'PREPARE ERROR';
            }

        }

    ?>