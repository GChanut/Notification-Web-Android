<?php 
	mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
	mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
	
	
	if(!empty($_POST['email']) && !empty($_POST['pseudo']) && !empty($_POST['mdp']) && !empty($_POST['mdp2']) && !empty($_POST['nom']) && !empty($_POST['prenom'])){
		$email = $_POST['email']; 
		$pseudo = $_POST['pseudo'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$mdp = $_POST['mdp'];
		$mdp2 = $_POST['mdp2'];
		if($mdp == $mdp2){
			mysql_query("INSERT INTO any_user_auth VALUES('', '$pseudo', '$nom', '$prenom', '$email', '$mdp')");
			mysql_close();
				$file = fopen ('oauth2m/api/json/'.$pseudo.'.json', 'a+'); //chemin de création 
			
							$content ='
							{
									"contacts": [
										{
											"name": "'.$pseudo.'",
											"device_id" :""
										}
									],
									"application": [
									]
								}';
					
					fwrite($file, $content);
					fclose($file);
					header('Location: http://www.google.com');
	}else{echo "Le mot de passe de confirmation est erroné";}
}else{echo "Vous n'avez pas rempli tous les champs";}		
?>
<form method="post">
<table>
				<tr><td><label for="pseudo">Pseudo(seulement 0-9,a-z,A-Z)</label> :</td><td> <input type="text" name="pseudo" class="in" id="f"/>
				<script type="text/javascript">
		        var f16 = new LiveValidation('f');
				f16.add( Validate.Exclusion, { within: [<?php $req = mysql_query("SELECT * FROM any_user_auth");
						while($result = mysql_fetch_array($req)){echo "'";echo $result['username_client']; echo "'"; echo ",";}?>] } );
				</script>
				<br /></tr></td>
				<tr><td><label for="nom">Nom</label> :</td><td> <input type="text" name="nom" class='in' id="f1"/> 
				<script type="text/javascript">
		         var f1 = new LiveValidation('f1');
				f1.add( Validate.Presence );
				</script>
				<br /></tr></td>
				<tr><td><label for="prenom">Prenom</label> : </td><td><input type="text" name="prenom" class='in' id="f2"/> 
				<script type="text/javascript">
		         var f1 = new LiveValidation('f2');
				 f1.add( Validate.Presence );
				</script>
				<br /></tr></td>
				<tr><td><label for="email">E-Mail</label> : </td><td><input type="text" name="email" class="in" id="f3"/>
                <script>
				var f20 = new LiveValidation('f3');
				f20.add( Validate.Email );
				</script>
				<br /></tr></td>
				<tr><td><label for="mdp">Mot de Passe</label> : </td><td><input type="password" name="mdp" class="in" id="f4"/><br /></tr></td><script type="text/javascript">
		         var f1 = new LiveValidation('f4');
				 f1.add( Validate.Presence );
				</script>
				<tr><td><label for="mdp2">Mot de Passe (confirmation)</label> : </td><td><input type="password" name="mdp2" class="in" id="f5"/><script>var f19 = new LiveValidation('f5');
f19.add( Validate.Confirmation, { match: 'f4' } );</script><br /></tr></td>
				
				<tr><td><input type='submit' value="S'inscrire" class="button"></td></tr>
</table>
</form>





		