

<?php

require_once 'config.inc.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

	$username = htmlentities($_POST['username']);
	$username = mysql_escape_string($username);
	$password = htmlentities($_POST['password']);
	$password = mysql_escape_string($password);
	//Verification de l'existence de l'utilisateur dans la BDD
	$sql = "SELECT * FROM any_user_auth WHERE username_client='$username' AND mdp_client='$password'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur log');
		if (mysql_num_rows($req) > 0) {
			$data = mysql_fetch_assoc($req);
			//Verification Mot de Passe dans la BDD
			if ($password == $data['mdp_client']) {
				$_SESSION['authorized'] = true;
				$_SESSION['user'] = $username;
				$_SESSION['pass'] = $password;
				$_SESSION['id']= $data['usa_id_ref'];

				if (!empty($_GET['goto'])) {
					$var = array("username" => $_POST['username']);
					print(json_encode($var));
					header('Refresh: 1; ' . $_GET['goto']);
					die;
				}		
				$var = array("username" => $_POST['username']);
				print(json_encode($var));			
				echo "Logon succesfull.";
				die;
			}
		}else{echo "Erreur Login ou mot de passe";}
}

?>

<?php
if(!empty($_SESSION['toktok'])){
	
	//on récupère url ou le token se trouve
	$ot = $_SESSION['toktok'];
	//on prend une partie de la chaine à partir du 1er '='
	$toktok = strpbrk($ot, '=');
	//On enleve le caractère '='
	$first_token  = strtok($toktok, '=');
	
	//recupération du nom de l'application pour la liaison
	$sql = "SELECT osr_application_title, osr_consumer_key, ost_osr_id_ref FROM oauth_server_registry, oauth_server_token WHERE oauth_server_registry.osr_id =oauth_server_token.ost_osr_id_ref AND ost_token='$first_token'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur serveur appli');
	if (mysql_num_rows($req) > 0) {
		$data = mysql_fetch_assoc($req);
		$_SESSION['ck'] = $data['osr_consumer_key'];
		$_SESSION['id_appli'] = $data['ost_osr_id_ref'];
		$_SESSION['title_appli'] = $data['osr_application_title'];
	}
}

if(isset($_SESSION['title_appli'])){
	$nameAppli = "Autoriser" ." ". $_SESSION['title_appli']. " à utiliser votre compte Notif'Center?";
}else{
	$nameAppli = "Se connecter à Notif'Center";
}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/style.css" />
		<title> Connexion à l'API </title>
	</head>

<body>


  <div id="principale_logon">
    
			<h1><?php echo $nameAppli; ?></h1>
				<p>Cette application <strong>sera autorisée à</strong> :</p>
				<ul>
					<li>Lire vos notifications</li>
					<li>Mettre à jour votre profil.</li>
				</ul>
			<form method="post">
				<input type="hidden" name="goto" value="" />
      
				<label for="username">User name</label><br />
				<input type="text" name="username" id="username" class="in" />
      
				<br /><br />

				<label for="password">Password</label><br />
				<input type="password" name="password" id="password" class="in" />
      
				<br /><br />
      
				<input type="submit" value="Autoriser l'application" class="button"/>
				<form><input type="button" value="Je refuse" OnClick="window.location.href=\'http://notifcenter.zapto.org/webCV/\'" class="button"></form>
			</form>
	
  </div> 
 </body>
</html>