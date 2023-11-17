<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title> Création de médecin </title>
    </head>
    <body>

            <h1> Création d'un médecin </h1>

            <form action="ajoutmed.php" method="post">
                Civilité    <input type="radio" id="civM" name="civ" value="M" checked />
                            <label for="civM">M</label>
                            <input type="radio" id="civMme" name="civ" value="Mme" />
                            <label for="civMme">Mme</label> <br> <br>
                Nom : <input type="text" name="nom" value="" maxlength=50><br><br>
                Prénom : <input type="text" name="prenom" value="" maxlength=50><br><br>
                <input type="submit" name="Valider" value="Confirmer">
                <input type="reset" name="Vider" value ="Vider">
            </form>

    </body>
</html>