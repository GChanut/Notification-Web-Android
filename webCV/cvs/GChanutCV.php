<?php
if (isset($_POST['access'])){
	$fp = fopen("GChanut_compteur.txt","r+");
	$nbvisites = fgets($fp,10);
	if ($nbvisites=="") $nbvisites = 0;
	fseek($fp,0);
	fputs($fp,$nbvisites);
	fclose($fp);	
	//if($_POST['access'] == "access"){
		$_POST['access'] = $nbvisites;
		echo $_POST['access'];
	die;
	//}else {echo "echec"; die;}
}

?>

<?php
$fp = fopen("GChanut_compteur.txt","r+");
$nbvisites = fgets($fp,10);
if ($nbvisites=="") $nbvisites = 0;
$nbvisites++;
fseek($fp,0);
fputs($fp,$nbvisites);
fclose($fp);
echo "$nbvisites"; 
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
	<head>
		<link rel="stylesheet" media="screen" type="text/css" href="css/css.css"/>
	</head>

	<body>
		<br />Nom: CHANUT
		<br />Prenom: Ga�tan
		<br />adresse: 129 Rue Dedieu
		<br />Description: Bac STI Electronique
	
		<form method="post">
			<input type="hidden" name="access" id="access" />
			 <div class="hide">
				   <input type='submit' name='validerHide' value='Ok' />
			 </div>	  	   
		</form>	
	</body>
	
</html>