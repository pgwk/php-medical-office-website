<?php session_start();
    

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil.css">
    <title> Statistiques </title>
</head>
<body>
    <style>
        table, th, td{
            border : solid 1px black;
            border-collapse: collapse;
            padding: 15px;
        }
    </style>
    <h1> Parcourir </h1>
    <?php
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=cabinetmed;charset=utf8', 'root', '');
        } catch (Exception $e) {
            echo ("Erreur ".$e);
        }
        $hommesMoins25 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'M\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') < 20')->fetch();
        $femmesMoins25 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'Mme\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') < 20')->fetch();
        $hommesEntre25et50 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'M\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') BETWEEN 25 AND 50')->fetch();
        $femmesEntre25et50 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'Mme\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') BETWEEN 25 AND 50')->fetch();
        $hommesPlus50 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'M\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') > 50')->fetch();
        $femmesPlus50 = $pdo->query('SELECT COUNT(*) as Nb FROM usager WHERE civilite = \'Mme\' AND DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), dateNaissance)), \'%Y\') > 50')->fetch();
    ?>
    <form method="post" action="affichageUsagers.php">
            <table class="tableFiltres">
                <tr>
                    <th>Tranche d'âge</th>
                    <th>Nombre d'hommes</th>
                    <th>Nombre de femmes</th>
                </tr>
                <tr>
                    <td>Moins de 25 ans</td>
                    <td><?php echo $hommesMoins25['Nb'] ?></td>
                    <td><?php echo $femmesMoins25['Nb'] ?></td>
                </tr>   
                <tr>
                    <td>Entre 25 et 50 ans</td>
                    <td><?php echo $hommesEntre25et50['Nb'] ?></td>
                    <td><?php echo $femmesEntre25et50['Nb'] ?></td>
                </tr>    
                <tr>
                    <td>Plus de 50 ans</td>
                    <td><?php echo $hommesPlus50['Nb'] ?></td>
                    <td><?php echo $femmesPlus50['Nb'] ?></td>
                </tr>     
            </table>
        
        </form>
        <br><br>
        
</body>

</html>