<?php 
require_once '../server/config.inc.php';

if(isset($_GET['oauth_token'])){

	$token = $_GET['oauth_token'];
	$sql = "SELECT ost_usa_id_ref, ost_token_type, username_client FROM oauth_server_token, any_user_auth WHERE oauth_server_token.ost_usa_id_ref=any_user_auth.usa_id_ref AND ost_token='$token'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur');
		if (mysql_num_rows($req) > 0) {
			$data = mysql_fetch_assoc($req);
		}
		
		
		if($data['ost_token_type'] == 'access'){
			echo $data['username_client']; // On affiche l'username 
		}
}else{
		$var = array("error" => "Could not authenticate you.", "request" => "\/api\/verify_credentials.php");
		print(json_encode($var));

}



?>