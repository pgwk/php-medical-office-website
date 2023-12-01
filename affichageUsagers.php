<?php session_start();
    

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil.css">
    <title> Liste des usagers </title>
</head>
<body>
    <h1> Parcourir </h1>
    <form method="post" action="affichageUsagers.php">
            <table class="tableFiltres">
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Civilite</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Numéro sécurité sociale</th>
                    <th>Date de naissance</th>
                    <th>Lieu de naissance</th>
                </tr>
                <tr>
                    <td><input type="text" name="nom" value='<?php if (isset($_POST['nom'])) echo $_POST['nom'] ?>'></td>
                    <td><input type="text" name="prenom" value='<?php if (isset($_POST['prenom'])) echo $_POST['prenom'] ?>'></td>
                    <td><input type="text" name="civilite" value='<?php if (isset($_POST['civilite'])) echo $_POST['civilite'] ?>'></td>
                    <td><input type="text" name="adresse" value='<?php if (isset($_POST['adresse'])) echo $_POST['adresse'] ?>'></td>
                    <td><input type="text" name="ville" value='<?php if (isset($_POST['ville'])) echo $_POST['ville'] ?>'></td>
                    <td><input type="text" name="codePostal" value='<?php if (isset($_POST['codePostal'])) echo $_POST['codePostal'] ?>'></td>
                    <td><input type="text" name="numeroSecuriteSociale" value='<?php if (isset($_POST['numeroSecuriteSociale'])) echo $_POST['numeroSecuriteSociale'] ?>'></td>
                    <td><input type="text" name="dateNaissance" value='<?php if (isset($_POST['dateNaissance'])) echo $_POST['dateNaissance'] ?>'></td>
                    <td><input type="text" name="lieuNaissance" value='<?php if (isset($_POST['lieuNaissance'])) echo $_POST['lieuNaissance'] ?>'></td>
            </tr>   
            </table>
            
            <input type="reset" value="Vider" name="vider">
            <input type="submit" value="Rechercher" name="valider">
        </form>
        <br><br>
        <table class="tableResultats"> 
                    <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Civilite</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Numéro sécurité sociale</th>
                    <th>Date de naissance</th>
                    <th>Lieu de naissance</th>
                    <th>Médecin référent</th>
                    </tr>
        <?php
            
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=cabinetmed;charset=utf8', 'root', '');
            } catch (Exception $e) {
                echo ("Erreur ".$e);
            }

            $reqUsagers = ' SELECT u.*, m.nom as nomMedecin, m.prenom as prenomMedecin
                            FROM usager u
                            LEFT JOIN medecin M ON u.medecinReferent = m.idMedecin';

            $listeCriteres = array('nom', 'prenom', 'civilite', 'adresse', 'ville', 'codePostal', 'numeroSecuriteSociale', 'dateNaissance', 'lieuNaissance');
            $firstCriteria = true;
            for ($i = 0; $i <= 8; $i++){
                if (!empty($_POST[$listeCriteres[$i]])){
                    $reqUsagers = $firstCriteria ? $reqUsagers.' WHERE u.' : $reqUsagers.' AND u.';
                    $reqUsagers = $reqUsagers.$listeCriteres[$i].' = \''.$_POST[$listeCriteres[$i]].'\'';
                    $firstCriteria = false;
                } 
            }

            $resUsagers = $pdo->query($reqUsagers);
            while ($dataUsager = $resUsagers->fetch()){
                echo '<tr><td>'.$dataUsager['nom'].'</td>'.
                        '<td>'.$dataUsager['prenom'].'</td>'.
                        '<td>'.$dataUsager['civilite'].'</td>'.                            
                        '<td>'.$dataUsager['adresse'].'</td>'.
                        '<td>'.$dataUsager['ville'].'</td>'.
                        '<td>'.$dataUsager['codePostal'].'</td>'.
                        '<td>'.$dataUsager['numeroSecuriteSociale'].'</td>'.
                        '<td>'.$dataUsager['dateNaissance'].'</td>'.
                        '<td>'.$dataUsager['lieuNaissance'].'</td>'.
                        '<td>'.$dataUsager['nomMedecin'].' '.$dataUsager['prenomMedecin'].'</td>'.
                        '<td>'.'<a href = \'modificationusager.php?idUsager='.$dataUsager[0].'\'> Modifier </a>'.'</td>'.
                        '<td>'.'<a href = \'suppression.php?id='.$dataUsager[0].'&type=usager\'> Supprimer </a>'.'</td>'.'</tr>';
            }
        ?>
        </table> 
</body>

</html>