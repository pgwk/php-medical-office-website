<?php session_start();
    

?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="header.css">
    <title> Liste des usagers </title>
</head>
<body>
    <header class="site-header">
      <div class="wrapper site-header__wrapper">
        <a href="#" class="brand">Cabinet</a>
        <nav class="nav">
          <button class="nav__toggle" aria-expanded="false" type="button">
            menu
          </button>
          <ul class="nav__wrapper">
            <li class="nav__item"><a href="#">Liste</a></li>
            <li class="nav__item"><a href="#">About</a></li>
            <li class="nav__item"><a href="#">Services</a></li>
            <li class="nav__item"><a href="#">Hire us</a></li>
            <li class="nav__item"><a href="#">Contact</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <main>
    <h1> Liste des usagers </h1>
    <div class="conteneurCentre">
    <form method="post" action="affichageUsagers.php">
  
            <td><input type="text" name="criteres" value='<?php if (isset($_POST['criteres'])) echo $_POST['criteres'] ?>'></td>
            
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

            $listeCriteres = preg_split('/\s+/', $_POST['criteres']);
            echo count($listeCriteres);
            $dernierEspace = 0;
            if (ctype_space($listeCriteres[count($listeCriteres)])){
              $dernierEspace = 1;
            }
            echo ":".$listeCriteres[count($listeCriteres) - 1].":";
            echo count($listeCriteres) - $dernierEspace;
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
                        '<td>'.'<a href = \'modificationusager.php?idUsager='.$dataUsager[0].'\'><img src=".\modify.png" alt=""width=30px></img></a>'.'</td>'.
                        '<td>'.'<a href = \'suppression.php?id='.$dataUsager[0].'&type=usager\'><img src=".\delete.png" alt=""width=30px></img></a>'.'</td>'.'</tr>';
            }
        ?>
        </table> 
          </div>
          </main>
</body>

</html>