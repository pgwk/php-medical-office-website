<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Modification d'usager </title>
    </head>
    <?php
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
        } catch (Exception $e) {
            echo ("Erreur : ".$e);
        }
            if (isset($_GET['idUsager'])){

                $sql = 'SELECT * FROM usager WHERE idUsager = '.$_GET['idUsager'];
                $stmt = $pdo->prepare($sql);
                if ($stmt == false){
                    echo 'ERREUR';
                }
                $stmt->execute();
                $result = $stmt->fetchAll();

                $civilite = array_column($result, 'civilite')[0];
                $nom = array_column($result, 'nom')[0];
                $prenom = array_column($result, 'prenom')[0];
                $adresse = array_column($result, 'adresse')[0];
                $ville = array_column($result, 'ville')[0];
                $codePostal = array_column($result, 'codePostal')[0];
                $numeroSecuriteSociale = array_column($result, 'numeroSecuriteSociale')[0];
                $dateNaissance = array_column($result, 'dateNaissance')[0];
                $lieuNaissance = array_column($result, 'lieuNaissance')[0];
            }

            if (isset($_POST['valider'])){
                $sql = 'UPDATE contact  SET nom = \''.$_POST['nom'].'\',
                                            prenom = \''.$_POST['prenom'].'\',
                                            civilite = \''.$_POST['civ'].'\',
                                            adresse = \''.$_POST['adr'].'\',
                                            codePostal = \''.$_POST['cp'].'\',
                                            ville = \''.$_POST['ville'].'\',
                                            numeroSecuriteSociale = \''.$_POST['nss'].'\',
                                            dateNaissance = \''.$_POST['date'].'\',
                                            lieuNaissance = \''.$_POST['lieu'].'\'
                                        WHERE id = \''.$_GET['idContact'].'\';';
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
                Adresse <input type="text" name="adresse" maxlength=100 value='<?php echo $adresse ?>'><br><br>
                Ville <input type="text" name="ville" maxlength=50 value='<?php echo $ville ?>'><br><br>
                Code postal <input type="text" name="codePostal" maxlength=5 value='<?php echo $codePostal ?>'><br><br>
                N° Sécurité sociale <input type="text" name="numeroSecuriteSociale" maxlength=15 value='<?php echo $numeroSecuriteSociale ?>'><br><br>
                Date de naissance <input type="date" name="dateNaissance" value='<?php echo $dateNaissance ?>'><br><br>
                Lieu de naissance <input type="text" name="lieuNaissance" value='<?php echo $lieuNaissance ?>'><br><br>
                Médecin reférent <select name="medecinReferent" id="medRef">
                    <option value="">--Veuillez choisir un médecin reférent</option>
                    <?php
                        try {
                            $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
                        } catch (Exception $e) {
                            echo ("Erreur : ".$e);
                        }
                        $stmt = $pdo->prepare("SELECT idMedecin, civilite, nom, prenom FROM medecin");
                        if ($stmt == false) {
                            echo "PREPARE ERROR"; 
                        } else {
                            $stmt->execute();
                            while ($row = $stmt->fetch()) {
                                $id = $row["idMedecin"];
                                $titre = $row["civilite"].'. '.$row["nom"].' '.$row["prenom"];
                                echo '<option value='.$id.'> '.$titre.'</option>';
                            }
                        }
                    ?>
                </select> 
                <input type="submit" name="Valider" value="Confirmer">
                <input type="reset" name="Vider" value ="Vider">
            </form>

    </body>
</html>