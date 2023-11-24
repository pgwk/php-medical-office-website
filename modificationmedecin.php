<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Modification d'un médecin </title>
    </head>
    <?php
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
        } catch (Exception $e) {
            echo ("Erreur : ".$e);
        }
            if (isset($_GET['idMedecin'])){

                $sql = 'SELECT * FROM medecin WHERE idMedecin = '.$_GET['idMedecin'];
                $stmt = $pdo->prepare($sql);
                if ($stmt == false){
                    echo 'ERREUR';
                }
                $stmt->execute();
                $result = $stmt->fetchAll();

                $civilite = array_column($result, 'civilite')[0];
                $nom = array_column($result, 'nom')[0];
                $prenom = array_column($result, 'prenom')[0];
            }

            if (isset($_POST['valider'])){
                $sql = 'UPDATE contact  SET nom = \''.$_POST['nom'].'\',
                                            prenom = \''.$_POST['prenom'].'\',
                                            civilite = \''.$_POST['civilite'].'\'
                                        WHERE id = \''.$_GET['idMedecin'].'\';';
                $bdd->query($sql);
            }
    ?>
    <body>

            <h1> Modification d'un usager </h1>

            <form action="ajoutusager.php" method="post">
                Civilité    <input type="radio" id="civM" name="civ" value="M" <?php if ($civilite == 'M'){ echo 'checked';} ?> />
                            <label for="civM">M</label>
                            <input type="radio" id="civMme" name="civ" value="Mme" <?php if ($civilite == 'Mme'){ echo 'checked';} ?> />
                            <label for="civMme">Mme</label><br><br>
                Nom <input type="text" name="nom" maxlength=50 value='<?php echo $nom ?>'><br><br>
                Prénom <input type="text" name="prenom" maxlength=50 value='<?php echo $prenom ?>'><br><br>
                <input type="submit" name="Valider" value="Confirmer">
                <input type="reset" name="Vider" value ="Vider">
            </form>

    </body>
</html>