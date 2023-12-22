<?php
	if (isset($_POST['valider']) && isset($_POST['nom'])){
		$utilisateur = 'utilisateur';
		$utilisateur_nom = $_POST['nom'];
		if (!setcookie($utilisateur, $utilisateur_nom, time() + 150)){
			echo 'Cookie utilisateur échoué';
		}
		
		$visites = 'nbVisites';
		if (isset($_COOKIE[$visites])){
			echo 'Premier';
			$nb_visites = $_COOKIE[$visites] + 1;
		} else {
			echo 'Deuxieme';
			$nb_visites = 1;
		}

		if (!setcookie($visites, $nb_visites, time() + 15)){
			echo 'Cookie visites échoué';
		}
		
	}
?>
<!doctype html>
<html>

<head>
	<title>Connexion</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="loginV2.css">

</head>

<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="login-wrap">
				<div class="icon d-flex align-items-center justify-content-center">
					<span class="fa fa-user-o"></span>
				</div>
				<h3 class="text-center mb-4">Connexion</h3>
				<form action="#" class="login-form">
					<input type="text" class="form-control form-group rounded-left" placeholder="Username" required>
					<input type="password" class="form-control form-group rounded-left" placeholder="Password" required>
					<button type="submit" name='valider' class="form-control form-group btn btn-primary rounded submit px-3">Login</button>
				</form>
			</div>
		</div>
	</div>
</body>

</html>