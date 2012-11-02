<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<link rel="stylesheet" media="screen" type="text/css" href="css/style.css"/>
</head>
<body>
  	  <?php 
mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
	mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
$email = $_POST['email']; 
$pseudo = $_POST['pseudo'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mdp = $_POST['mdp'];
mysql_query("INSERT INTO oauth_server_client VALUES('', '$pseudo', '$nom', '$prenom', '$email', '$mdp')");
mysql_close();
$file = fopen ('../../api/json/'.$pseudo.'.json', 'a+'); //chemin de création 
	
					$content ='
					{
							"contacts": [
								{
									"name": "'.$pseudo.'"
								}
							],
							"application": [
								{
									"application_name": "WebCV",
									"state": "true"
								},
								{
									"application_name": "Facebook",
									"state": "false"
								},
								{
									"application_name": "Twitter",
									"state": "false"
								},
								{
									"application_name": "Gmail",
									"state": "false"
								}
							]
						}';
			
			fwrite($file, $content);
			fclose($file);
			
?>

      
	  </div>
	  </div>
Copyright 2012-2013 - NotifCenter Team.
</body>
</html>
