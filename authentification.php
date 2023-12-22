<?php session_start();
    if (isset($_POST['connexion']) && isset($_POST['nom']) && isset($_POST['motDePasse'])) {
        $nomUtilisateur = $_POST['nom'];
        $motDePasse = $_POST['motDePasse'];
    
        if ($nomUtilisateur == 'CABINET' && $motDePasse == 'CABINET') {
            $_SESSION['utilisateur'] = $nomUtilisateur;
            header('Location: affichagemedecins.php');
            exit();
        } else {
            echo 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="authentification.css">
    <title> Statistiques </title>
</head>
<body>
    <div id='login-container'>
            <div id='login-header'>
                <h2>Connexion au cabinet</h2>
            </div>
            <div id='login-body'>
                <form method='post' action='authentification.php'>
                    <input type='text' name='nom' placeholder="Nom d'utilisateur" required>
                    <input type='password' name='motDePasse' placeholder="Mot de passe" required>
                    <input type='submit' id='submitButton' name='connexion' value='Connexion'>
                </form>
            </div>
            <div id='login-footer'>
                <p>Footer de la page ©copyright</p>
            </div>
    </div>    
</body>

</html>