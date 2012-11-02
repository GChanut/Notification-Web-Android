<?php



require_once 'config.inc.php';

assert_logged_in(); 
$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$ost_id = $_POST['ident']; //id de l'application: dans notre cas, 1 sera webCV. Et 2 pour l'appli Mobile

		
		$store = OAuthStore::instance();
		$key   = $store->updateConsumer($_POST, $ost_id, true);

		$c = $store->getConsumer($key, $ost_id);
		
		echo 'Your consumer key is: <strong>' . $c['consumer_key'] . '</strong><br />';
		echo 'Your consumer secret is: <strong>' . $c['consumer_secret'] . '</strong><br />';
		
		echo 'Application enregistré';
		
	}
	catch (OAuthException2 $e)
	{
		echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
	}
}

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/style.css" />
		<title> Oauth_register </title>
	</head>
<body>
  
    <h1>Enregistrer votre Site</h1>

    <div id="principale_logon">

    

      

<?php	
if ( !isset($_POST['application_title']) || !isset($_POST['application_uri']) || !isset($_POST['callback_uri']) || !isset($_FILES['iconapp']['name']))
{
?>	<form method="post" action="" enctype="multipart/form-data">

    <br/>    
        
        <p>
            <label for="ident">ID de l'utilisateur: &nbsp;<?php echo $user_id; ?></label><br/>
            <input id="ident" class="text" name="ident" type="hidden" value="<?php echo $user_id; ?>" />
        </p>
		
		<p>
            <label for="application_title">Nom de l'application</label><br/>
            <input id="application_title" class="text" name="application_title" type="text" value="" />
        </p>
		
		<p>
            <label for="application_uri">URL of your application or site</label><br/>
            <input id="application_uri" class="text" name="application_uri" type="text" value="" />
        </p>
        
        <p>
            <label for="callback_uri">Callback URL</label><br/>
            <input id="callback_uri" class="text" name="callback_uri" type="text" value="" />
        </p>
		<p>
			<label for="callback_uri">Icone de votre application <br/> (JPG ou PNG | max. 1 Mo | Il est conseillé de mettre une image carré)</label><br/>
			<input type="hidden" name="MAX_ICONAPP_SIZE" value="3145728" />
			<input type="file" name="iconapp" />
		</p>
		
<!---		<p>
			<label for="callback_uri">Icone d'alerte sur le mobile <br/>(JPG ou PNG | max. 3 Mo | Il est conseillé de mettre une image carré)</label><br/>
			<input type="hidden" name="MAX_ICONOTIF_SIZE" value="3145728" / class="button">
			<input type="file" name="iconotif"  />
		</p> -->

      

      <input type="submit" value="Register server" class="button" />
    </form>
	
<?php	
}
else
{	
	
	//Liste dans une variable les différentes extensions valides
	$extensions_valides = array( 'jpg' , 'jpeg' , 'png' );
	//On prend juste les extensions des fichiers uploadés en caractères minuscules
	$extension_upload_iconapp = strtolower(  substr(  strrchr($_FILES['iconapp']['name'], '.')  ,1)  );
//	$extension_upload_iconotif = strtolower(  substr(  strrchr($_FILES['iconotif']['name'], '.')  ,1)  ); 
 
	if ( empty($_POST['application_title']) || empty($_POST['application_uri']) || empty($_POST['callback_uri']) || empty($_FILES['iconapp']['name']))
	{
		echo 'Veuillez remplir tous les champs <br>'; ?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}
	// Verification si le transfert de l'icone d'application est réussit et si sa taille est correcte
	else if ($_FILES['iconapp']['size'] > $_POST['MAX_ICONAPP_SIZE'])
	{
		echo "Taille de l'image d'application trop grande <br>"; ?>
	<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}
// Verification si le transfert de l'icone de notification est réussit et si sa taille est correcte
/*	else if ($_FILES['iconotif']['size'] > $_POST['MAX_ICONOTIF_SIZE'])
	{
		echo "Taille de l'image de notification trop grande <br>"; ?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}*/
	// On test si le transfert de fichier a réussit
	else if ($_FILES['iconapp']['error'] > 0)
	{
		echo "Erreur lors du transfert de fichier de l'icône d'application <br>"; ?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}
/*	else if ($_FILES['iconotif']['error'] > 0)
	{
		echo "Erreur lors du transfert de fichier de l'icône de notification <br>"; ?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}*/
	// On compare les extensions avec la liste d'extensions valides
	else if ( !in_array($extension_upload_iconapp,$extensions_valides) ) 
	{
		echo "Extension icone application incorrecte <br>"; ?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}
/*	else if ( !in_array($extension_upload_iconotif,$extensions_valides) ) 
	{
	echo "Extension icone notification incorrecte <br>";?>
		<a href = "test_upload.php"> Retour au formulaire </a> <?php
	}*/

	// Tout va bien !
	else
	{
		mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
		mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
		
		$application_title = htmlentities($_POST['application_title']);
		$application_title = mysql_escape_string($application_title);
		$application_uri = htmlentities($_POST['application_uri']);
		$application_uri = mysql_escape_string($application_uri);
		$callback_uri = htmlentities($_POST['callback_uri']);
		$callback_uri = mysql_escape_string($callback_uri);
		
		
		$req = mysql_query("SELECT * FROM  oauth_server_registry WHERE osr_usa_id_ref =".$_POST['ident']." AND osr_callback_uri = '$callback_uri' AND osr_application_uri = '$application_uri' AND osr_application_title = '$application_title';");
		$data = mysql_fetch_array($req);
		
		
		

		$nomapp = "app" .$data['osr_id'] ;
		//$nomnotif = "notif" .$data['osr_id'] ;
		
		// $nom est le chemin de l'image
		$nom = "../../img/icones/{$nomapp}.{$extension_upload_iconapp}";
		// On l'enregistre dans le dossier voulu
		$resultat = move_uploaded_file($_FILES['iconapp']['tmp_name'],$nom);
		if ($resultat) echo "Transfert d'icône réussit <br>";

		if ($extension_upload_iconapp != 'png')
		{
			$img = imagecreatefromjpeg($nom);
			$mauvaise_extension = $extension_upload_iconapp;
			$extension_upload_iconapp = 'png';
			$nom = "../../img/icones/{$nomapp}.{$extension_upload_iconapp}";
			imagepng($img, $nom, 5);
			imagedestroy($img);
			unlink("../../img/icones/{$nomapp}.$mauvaise_extension");
		}

		/*
		$source = imagecreatefrompng($nom); // La photo est la source
		$destination = imagecreatetruecolor(150, 150); // On crée la miniature vide

		// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);
		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);

		// On crée la miniature
		imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

		// On enregistre la miniature sous le nom mini_"nom_image"
		imagepng($destination, "../../img/icones/mini_{$nomapp}.{$extension_upload_iconapp}");
		
		// Déplacement du fichier miniature
		rename("../../img/icones/mini_{$nomapp}.{$extension_upload_iconapp}", "../../img/icones/miniatures/mini_{$nomapp}.{$extension_upload_iconapp}");





		$nom = "../../img/icones/{$nomnotif}.{$extension_upload_iconotif}";
		$resultat = move_uploaded_file($_FILES['iconotif']['tmp_name'],$nom);
		if ($resultat) echo "Transfert iconotif reussit <br>";


		if ($extension_upload_iconotif != 'png')
		{
			$img = imagecreatefromjpeg($nom);
			$mauvaise_extension = $extension_upload_iconotif;
			$extension_upload_iconotif = 'png';
			$nom = "../../img/icones/{$nomnotif}.{$extension_upload_iconotif}";
			imagepng($img, $nom);
			imagedestroy($img);
			unlink("../../img/icones/{$nomnotif}.$mauvaise_extension");
	
		}
		
		$source = imagecreatefrompng($nom); // La photo est la source
		$destination = imagecreatetruecolor(150, 150); // On crée la miniature vide

		// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);
		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);

		// On crée la miniature
		imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

		// On enregistre la miniature sous le nom mini_"nom_image"
		imagepng($destination, "../../img/icones/mini_{$nomnotif}.{$extension_upload_iconotif}");

		// Déplacement du fichier miniature
		rename("../../img/icones/mini_{$nomnotif}.{$extension_upload_iconotif}", "../../img/icones/miniatures/mini_{$nomnotif}.{$extension_upload_iconotif}");*/?>
		
		<a href = "oauth_register.php"> Retour au formulaire </a> <?php


	}
}
?>
	</div>
 </body>
</html>