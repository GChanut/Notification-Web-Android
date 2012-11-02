<?php 
	session_start();
	mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
	mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
	$id=$_SESSION['id'];
	$req = mysql_query("SELECT * FROM any_user_auth WHERE usa_id_ref = $id;");	
	$result = mysql_fetch_array($req);
	
	/*if(!empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']) && !empty($_POST['nom']) && !empty($_POST['prenom'])){
		//$email = $_POST['email']; 
		//$nom = $_POST['nom'];
		//$prenom = $_POST['prenom'];
		//$mdp = $_POST['mdp'];
		//$mdp2 = $_POST['mdp2'];
		if($result['mdp_client']==$_POST['mdp']){
			mysql_query("UPDATE any_user_auth SET nom_client='$nom', prenom_client='$prenom', email_client='$email', mdp_client='$mdp2' WHERE usa_id_ref=$id;");}
	}
		$result['prenom_client'];
	*/

	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" /> 
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link href="css/style2.css" rel="stylesheet" type="text/css" />	
	<script type="text/javascript" src="scripts/jquery.js"></script>
	<script type="text/javascript" src="scripts/interface.js"></script>
	<title>Notif'Center - Paramètres du compte</title>
</head>
<body>
<div id="principale"><p><div align="left">&nbsp; &nbsp; <a id="titrecool">NotifCenter</a></div><div align="right"><a>

<!--<a href="param.php?choix">TEST</a>-->
<?php
			if(isset($result['prenom_client']) && isset($result['nom_client'])){
				echo $result['prenom_client'].' '.$result['nom_client'];
				echo " ".'<a href="disconnect.php">Se déconnecter</a>';
				echo ' <div id="formul"><hr /></div>';
			}
			echo '</a></div>';
//Modification Nom
if ( isset($_POST['modifnom']) )
{
	echo'modifnom';
	if ( !empty($_POST['nom']) ) 
	{
		$nom = htmlentities($_POST['nom']);
		$nom = mysql_escape_string($nom);
		mysql_query("UPDATE any_user_auth SET nom_client='$nom' WHERE usa_id_ref=$id;");
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Changement Nom effectué');
						</SCRIPT>";
						header('Refresh: 1;http://notifcenter.zapto.org/notifcenter/param.php');
	}
	else 
	{
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('le champs Nom est vide');
						</SCRIPT>";
	}
	?> 
	<?php
}
//Modification Prenom
else if(isset($_POST['modifprenom']) )
{
	if( !empty($_POST['prenom']) ) 
	{
		$prenom = htmlentities($_POST['prenom']);
		$prenom = mysql_escape_string($prenom);
		mysql_query("UPDATE any_user_auth SET prenom_client='$prenom' WHERE usa_id_ref=$id;");
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Changement Prenom effectué');
						</SCRIPT>";
						header('Refresh: 1; http://notifcenter.zapto.org/notifcenter/param.php');
	}
	else 
	{
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Le champs Prenom est vide');
						</SCRIPT>";
	}
	?> 
	<?php
}
//Modification Email
else if ( isset($_POST['modifemail']) )
{
	if ( !empty($_POST['email']) ) 
	{
		$email = htmlentities($_POST['email']);
		$email = mysql_escape_string($email);
		mysql_query("UPDATE any_user_auth SET email_client='$email' WHERE usa_id_ref=$id;");
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Changement E-mail effectué');
						</SCRIPT>";
						header('Refresh: 1; http://notifcenter.zapto.org/notifcenter/param.php');
	}
	else 
	{
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Le champs E-mail est vide');
						</SCRIPT>";
	}
	?> 
	<?php
}
//Modification Mot de passe
else if ( isset($_POST['modifmdp']) )
{
	if ( empty($_POST['mdp']) )
	{
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Pour modifier votre Mot de Passe, vous devez saisir votre mot de passe actuel');
						</SCRIPT>";
	}
	else if ( $_POST['mdp'] != $result['mdp_client'] ) 
	{
		
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Mot de passe incorrect');
						</SCRIPT>";
	}
	else if ( empty($_POST['mdp2']) )
	{
									echo "<SCRIPT type=\"text/javascript\"> 
							alert('Le champs Mot de Passe est vide');
						</SCRIPT>";
	}
	else 
	{
		$mdp2 = htmlentities($_POST['mdp2']);
		$mdp2 = mysql_escape_string($mdp2);
		mysql_query("UPDATE any_user_auth SET mdp_client='$mdp2' WHERE usa_id_ref=$id;");
							echo "<SCRIPT type=\"text/javascript\"> 
							alert('Changement de Mot de Passe effectué');
						</SCRIPT>";
						header('Refresh: 1; http://notifcenter.zapto.org/notifcenter/param.php');
	}
	
	
	
	?> 
	<?php
}		
	
//Formulaire des paramètres
?>
	<form method="post" action="param.php" enctype="multipart/form-data">
	<div align="center">
	<table style="margin-top: -10%">
				<tr><td>Nom d'utilisateur :</td><td><?php echo $result['username_client'];?></tr></td>
				<tr><td><label for="nom">Nom</label> :</td><td> <input type="text" name="nom" id='in' value="<?php echo $result['nom_client'];?>"/> </td><td><input type='submit' name="modifnom" value="Modifier" class="button"></td><br /></tr>
				<tr><td><label for="prenom">Prenom</label> : </td><td><input type="text" name="prenom" id='in' value="<?php echo $result['prenom_client'];?>"/></td><td><input type='submit' name="modifprenom" value="Modifier" class="button"></td><br /></tr>
				<tr><td><label for="email">E-Mail</label> : </td><td><input type="text" name="email" id="in" value="<?php echo $result['email_client'];?>"/>
					<!--<script>var f20 = new LiveValidation('in');
						f20.add( Validate.Email );
					</script><br />--></td><td><input type='submit' name="modifemail" value="Modifier" class="button"></td><br /></tr>
				<tr><td><label for="mdp">Mot de Passe</label> 
				(Actuel) : </td><td><input type="text" name="mdp" id="in" value=""/><br /></tr></td>
				<tr><td><label for="mdp2">Mot de Passe (Nouveau)</label> : </td><td><input type="text" name="mdp2" id="in" value=""/>
					<!--<script>
						var f19 = new LiveValidation('f19');
						f19.add( Validate.Confirmation, { match: 'myPasswordField' } );
					</script>--><br /></td><td><input type='submit' name="modifmdp" value="Modifier" class="button"></td><br /></tr>		
				<!--<tr><td><input type='submit' value="Modifier" class="button"></td></tr>-->
	</form>	

<?php 
$req = mysql_query("SELECT osr_application_title FROM oauth_server_registry, oauth_user_consumer_right WHERE oauth_server_registry.osr_consumer_key = oauth_user_consumer_right.consumer_key AND user_id = $id;");	
while($result = mysql_fetch_array($req)){
  echo "<tr><form method=\"post\">";
  echo "<td>".$result['osr_application_title']."</td><td><input type='hidden' name='nameapp' value='".$result['osr_application_title']."' > <input type='submit' name='suppapp' value=\"Déconnecter le compte\" class='button'></td>";
  echo "</form></tr>";	
}
?>	

</table>
</div>
<br />

<?php

 	//Supression des applications
	if(isset($_POST['suppapp'])){
		if ( !empty($_POST['nameapp']) ){
			//SUPPRESSION OAUTH RIGHT
			$nameapp=$_POST['nameapp'];
			$req = mysql_query("SELECT osr_consumer_key FROM  oauth_server_registry WHERE osr_application_title ='$nameapp' ");
			$result = mysql_fetch_array($req);
			$ck=$result['osr_consumer_key'];
			mysql_query("DELETE FROM oauth_user_consumer_right WHERE user_id = '$id' AND consumer_key='$ck' ");
			
			//SUPPRESSION JSON
			$str_data = file_get_contents("oauth2m/api/json/".$_SESSION['user'].".json");
			$data = json_decode($str_data,true);
			$unset_queue = array();
			 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
				if($data["application"][$i]["application_name"] == $nameapp){
					unset($data["application"][$i]);		
				}
			}	
			$data['application'] = array_values($data['application']);

			$fh = fopen("oauth2m/api/json/".$_SESSION['user'].".json", 'w+') or die("Error opening output file");
			fwrite($fh, json_encode($data));
			fclose($fh);
				header('Refresh: 0; param.php');
		}
	}
?>
</div>

		</div>		
								<!--bottom dock -->
				<div class="dock" id="dock2">
					<div class="dock-container2">
						<a class="dock-item2" href="index.php"><span>Accueil</span><img src="img/home.png"/></a>
						<a class="dock-item2" href="docs/NotifCenter.zip"><span>Download NC Mobile</span><img src="img/dl.png"/></a>
						<a class="dock-item2" href="docs.php"><span>Documentations & Aide</span><img src="img/info.png"/></a>
						<a class="dock-item2" href="param.php"><span>Paramètres</span><img src="img/settings.png"/></a>
						
					</div>
				</div>
					<!--dock menu JS options -->
	<script type="text/javascript">
	$(document).ready(
	function()
	{
	$('#dock2').Fisheye(
	{
	maxWidth: 60,
	items: 'a',
	itemsText: 'span',
	container: '.dock-container2',
	itemWidth: 80, //Agrandit la taille de base
	proximity: 80,
	alignment : 'left',
	valign: 'bottom',
	halign : 'center'
	}
	)
	}
	);

	</script>
<body>
</html>		