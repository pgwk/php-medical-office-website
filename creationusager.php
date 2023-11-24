<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Création d'usager </title>
    </head>
    <body>

            <h1> Création d'un usager </h1>

            <form action="ajoutusager.php" method="post">
                Civilité    <input type="radio" id="civM" name="civ" value="M" checked />
                            <label for="civM">M</label>
                            <input type="radio" id="civMme" name="civ" value="Mme" />
                            <label for="civMme">Mme</label> <br> <br>
                Nom <input type="text" name="nom" value="" maxlength=50><br><br>
                Prénom <input type="text" name="prenom" value="" maxlength=50><br><br>
                Adresse <input type="text" name="adr" value="" maxlength=100><br><br>
                Ville <input type="text" name="ville" value="" maxlength=50><br><br>
                Code postal <input type="text" name="cp" value="" maxlength=5><br><br>
                N° Sécurité sociale <input type="text" name="nss" value="" maxlength=15><br><br>
                Date de naissance <input type="date" name="date" value=""><br><br>
                Lieu de naissance <input type="text" name="lieu" value="" maxlength=50><br><br>
                Médecin reférent <select name="idMed" id="medRef">
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