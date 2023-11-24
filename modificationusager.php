<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Modification d'usager </title>
    </head>
    <?php
            if (isset($_GET['idUsager'])){

                $bdd = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
                $sql = 'SELECT * FROM usager WHERE idUsager = '.$_GET['idUsager'];
                $stmt = $bdd->prepare($sql);
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
                $bdd = new PDO("mysql:host=localhost;dbname=cabinetMed", 'root', '');
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

            <form action="modificationusager.php" method="post">
                <?php

                    if ($civilite == 'M') {
                        echo 'Civilité    <input type="radio" id="civM" name="civ" value="M" checked />';
                        echo '<label for="civM">M</label>';
                        echo '<input type="radio" id="civMme" name="civ" value="Mme" />';
                        echo '<label for="civMme">Mme</label> <br> <br>';
                    } else {
                        echo 'Civilité    <input type="radio" id="civM" name="civ" value="M" />';
                        echo '<label for="civM">M</label>';
                        echo '<input type="radio" id="civMme" name="civ" value="Mme" checked />';
                        echo '<label for="civMme">Mme</label> <br> <br>';
                    }
                ?>
                
                Nom : <input type="text" name="nom" value="<?php echo $nom ?>" maxlength=50><br><br>
                Prénom : <input type="text" name="prenom" value="<?php echo $prenom ?>" maxlength=50><br><br>
                Adresse : <input type="text" name="adr" value="<?php echo $adresse ?>" maxlength=100><br><br>
                Ville : <input type="text" name="ville" value="<?php echo $ville ?>" maxlength=50><br><br>
                Code postal : <input type="text" name="cp" value="<?php echo $codePostal ?>" maxlength=5><br><br>
                N° Sécurité sociale : <input type="text" name="nss" value="<?php echo $numeroSecuriteSociale ?>" maxlength=15><br><br>
                Date de naissance : <input type="date" name="date" value="<?php echo $dateNaissance ?>"><br><br>
                Lieu de naissance : <input type="text" name="lieu" value="<?php echo $lieuNaissance ?>" maxlength=50><br><br>
                Médecin reférent <select name="idMed" id="medRef">
                    <?php
                    
                        try {
                            $pdo = new PDO("mysql:host=localhost;dbname=cabinetmed", 'root', '');
                        } catch (Exception $e) {
                            echo ("Erreur : ".$e);
                        }

                        $stmt = $pdo->prepare("SELECT idMedecin, medecin.civilite, medecin.nom, medecin.prenom FROM medecin, usager WHERE idMedecin=medecinReferent AND idUsager=".$_GET['idUsager']);
                        if ($stmt == false) {
                            echo "PREPARE ERROR"; 
                        } else {
                            $stmt->execute();
                            if ($row = $stmt->fetch()) {
                                $id = $row["idMedecin"];
                                $titre = $row["civilite"].'. '.$row["nom"].' '.$row["prenom"];
                                echo '<option value='.$id.'> '.$titre.'</option>';
                            } else {
                                echo '<option value="">--Veuillez choisir un médecin reférent</option>';
                            }
                        }

                        $stmt = $pdo->prepare("SELECT idMedecin, civilite, nom, prenom FROM medecin WHERE idMedecin NOT IN SELECT idMedecin FROM medecin, usager WHERE idMedecin=medecinReferent AND idUsager=".$_GET['idUsager']);
                        if ($stmt == false) {
                            echo "PREPARE ERROR";
                        } else {
                            $stmt -> execute();
                            echo 'in';
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