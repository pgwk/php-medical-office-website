<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil.css">
    <title> Consultations </title>
</head>
<body>
    <h1> Parcourir </h1>

    <form method="post" action="affichageconsultation.php">
        <table class="tableFiltres">
            <tr>
                <th>Médecin</th>
                <th>Patient</th>
                <th>Date de consultation</th>
           </tr>
            <tr>
                <td><input type="text" name="nom" value='<?php if (isset($_POST['medecin'])) echo $_POST['medecin'] ?>'></td>
                <td><input type="text" name="prenom" value='<?php if (isset($_POST['patient'])) echo $_POST['patient'] ?>'></td>
                <td><input type="date" name="date" value='<?php if (isset($_POST['date'])) echo $_POST['date'] ?>'></td>
            </tr>   
        </table>
        <input type="reset" value="Vider" name="vider">
        <input type="submit" value="Rechercher" name="valider">
    </form>
    <br><br>

    <table class="tableResultats"> 
        <tr>
            <th>Médecin</th>
            <th>Patient</th>
            <th>Date de consultation</th>
            <th>Heure de consultation</th>
            <th>Durée de consultation</th>
        </tr>

        <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $medecin = $_REQUEST["medecin"];
                $patient = $_REQUEST["patient"];
                $date = $_REQUEST["date"];

                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=cabinetmed;charset=utf8', 'root', '');
                } catch (Exception $e) {
                    echo ("Erreur ".$e);
                }
    
                $stmt = $pdo->prepare(  'SELECT m.nom AS nomMed, m.prenom AS prenomMed, 
                                        u.nom AS nomUsager, u.prenom AS prenomUsager, 
                                        c.dateConsultation AS dateCons, 
                                        c.heureDebut AS heure, c.duree AS duree
                                        FROM medecin m, usager u, consultation c
                                        WHERE c.idMedecin = m.idMedecin AND c.idUsager = u.idUsager 
                                        AND CONCAT(m.nom," ",m.prenom) LIKE ? 
                                        AND CONCAT(u.nom," ",u.prenom) LIKE ? 
                                        AND c.dateConsultation = ?');
    
                if ($stmt == false) {
                    echo "PREPARE ERROR";
                } else {
                    $stmt->execute(['$medecin', '$patient', '$date']);
                    while($row = $stmt->fetch()) {
                        echo '<tr>';
                        echo '<td>'.$row['nomMed'].' '.$row['prenomMed'].'</td>';
                        echo '<td>'.$row['nomUsager'].' '.$row['prenomUsager'].'</td>';
                        echo '<td>'.$row['dateCons'].'</td>';
                        echo '<td>'.$row['heure'].'</td>';
                        echo '<td>'.$row['duree'].'</td>';
                        echo '</tr>';
                    }
                }

            }

        ?>
</body>

</html>       