<?php session_start();
    if (isset($_POST['envoyer']) && !empty($_POST['pseudonyme']) && !empty($_POST['mdp'])){
        $pseudonyme = $_POST['pseudonyme'];

        if (!isset($_SESSION['pseudo'])){
            $_SESSION['pseudo'] = $pseudonyme;
        }
        if (!isset($_SESSION['nbVisitesUne']) && !isset($_SESSION['nbVisitesDeux'])){
            $nbVisitesUne = 0;
            $nbVisitesDeux = 0;
            
            $_SESSION['nbVisitesUne'] = $nbVisitesUne;
            $_SESSION['nbVisitesDeux'] = $nbVisitesDeux;
        }
    }

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="header.css">
    <title> Médecins </title>
</head>
<body>
    <h1> Parcourir </h1>

    <form method="post" action="affichagemedecins.php">
        <table class="tableFiltres">
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
           </tr>
            <tr>
                <td><input type="text" name="nom" value='<?php if (isset($_POST['nom'])) echo $_POST['nom'] ?>'></td>
                <td><input type="text" name="prenom" value='<?php if (isset($_POST['prenom'])) echo $_POST['prenom'] ?>'></td>
            </tr>   
        </table>
        <input type="reset" value="Vider" name="vider">
        <input type="submit" value="Rechercher" name="valider">
    </form>
    <br><br>

    <table class="tableResultats"> 
        <tr>
            <th>Civilite</th>
            <th>Nom</th>
            <th>Prenom</th>
        </tr>

        <?php

            try {
                $bdd = new PDO('mysql:host=localhost;dbname=cabinetmed;charset=utf8', 'root', '');
            } catch (Exception $e) {
                echo ("Erreur ".$e);
            }

            $sql = 'SELECT * FROM medecin';

            if (isset($_POST["valider"])){
                $listeCriteres = array('civilite', 'nom', 'prenom');
                
                $firstCriteria = true;
                for ($i = 0; $i <= 2; $i++){
                    if (!empty($_POST[$listeCriteres[$i]])){
                        $sql = $firstCriteria ? $sql.' WHERE ' : $sql.' AND ';
                        $sql = $sql.$listeCriteres[$i].' = \''.$_POST[$listeCriteres[$i]].'\'';
                        $firstCriteria = false;
                    } 
                }
            } 

            $res = $bdd->query($sql);
            while ($data = $res->fetch()){
                echo '<tr><td>'.$data['civilite'].'</td>'.
                        '<td>'.$data['nom'].'</td>'.
                        '<td>'.$data['prenom'].'</td>'.                            
                        '<td>'.'<a href = \'modificationmedecin.php?idMedecin='.$data[0].'\'> Modifier </a>'.'</td>'.
                        '<td>'.'<a href = \'suppression.php?id='.$data[0].'&type=medecin\'> Supprimer </a>'.'</td>'.'</tr>';
            }
        ?>
</body>

</html>