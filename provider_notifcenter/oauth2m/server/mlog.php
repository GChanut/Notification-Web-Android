<?php

$info['host'] = 'localhost';
$info['user'] = 'root';
$info['pass'] = '';
$info['path'] = 'oauth';

($GLOBALS['db_conn'] = mysql_connect($info['host'], $info['user'], $info['pass'])) || die(mysql_error());
mysql_select_db(basename($info['path']), $GLOBALS['db_conn']) || die(mysql_error());
unset($info);


if (isset($_POST['username']) && isset($_POST['password'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];
	//Verification de l'existence de l'utilisateur dans la BDD
	$sql = "SELECT * FROM any_user_auth WHERE username_client='$username' AND mdp_client='$password'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur log');
		if (mysql_num_rows($req) > 0) {
			$data = mysql_fetch_assoc($req);
			//Verification Mot de Passe dans la BDD
			if ($password == $data['mdp_client']) {
				
				$var = array("username" => $_POST['username']);
					
					print(json_encode($var));
					
					
				
			}
		}else{print 'null';die;}
}
?>


<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/style.css" />
		
	</head>

	<body>
		<form method="post">
		  <input type="hidden" name="goto" value="" />
		  
		  <label for="username">User name</label><br />
		  <input type="text" name="username" id="username" />
		  
		  <br /><br />

		  <label for="password">Password</label><br />
		  <input type="password" name="password" id="password" />
		  
		  <br /><br />
		  
		  <input type="submit" value="Autoriser l'application" />
		  <form><input type="button" value="Je refuse" OnClick="window.location.href=\'http://notifcenter.zapto.org/webCV/\'"></form>
		</form>
	</body>
</html>