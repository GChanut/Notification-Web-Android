<?php 
	session_start();
	mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
	mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
	$id=$_SESSION['id'];
	$req = mysql_query("SELECT * FROM any_user_auth WHERE usa_id_ref = $id;");	
	$result = mysql_fetch_array($req);	
	if(!empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']) && !empty($_POST['nom']) && !empty($_POST['prenom'])){
		//$email = $_POST['email']; 
		//$nom = $_POST['nom'];
		//$prenom = $_POST['prenom'];
		//$mdp = $_POST['mdp'];
		//$mdp2 = $_POST['mdp2'];
		if($result['mdp_client']==$_POST['mdp']){
			mysql_query("UPDATE any_user_auth SET nom_client='$nom', prenom_client='$prenom', email_client='$email', mdp_client='$mdp2' WHERE usa_id_ref=$id;");}
	}
		$result['prenom_client'];
					
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<title> Logon </title>
</head>
<div id="principale">
<form method="post">
<a href="param.php?choix">TEST</a>

	<table>
				<tr><td>Nom d'utilisateur :</td><td><?php echo $result['username_client'];?></tr></td>
				<tr><td><label for="nom">Nom</label> :</td><td> <input type="text" name="nom" id='in' value="<?php echo $result['nom_client'];?>"/> <br /></tr></td>
				<tr><td><label for="prenom">Prenom</label> : </td><td><input type="text" name="prenom" id='in' value="<?php echo $result['prenom_client'];?>"/><br /></tr></td>
				<tr><td><label for="email">E-Mail</label> : </td><td><input type="text" name="email" id="in" value="<?php echo $result['email_client'];?>"/>
					<script>var f20 = new LiveValidation('in');
						f20.add( Validate.Email );
					</script><br /></tr></td>
				<tr><td><label for="mdp">Mot de Passe</label> 
				(Actuel) : </td><td><input type="text" name="mdp" id="in" value="<?php echo $mdp?>"/><br /></tr></td>
				<tr><td><label for="mdp2">Mot de Passe (Nouveau)</label> : </td><td><input type="text" name="mdp2" id="in" value="<?php echo $mdp;?>"/>
					<script>
						var f19 = new LiveValidation('f19');
						f19.add( Validate.Confirmation, { match: 'myPasswordField' } );
					</script><br /></tr></td>			
				<tr><td><input type='submit' value="Modifier" class="button"></td></tr>
				
</table>
</form>
</div>

</html>


		