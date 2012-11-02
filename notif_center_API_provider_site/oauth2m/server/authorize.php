<?php

require_once 'config.inc.php';
include_once "../library/OAuthStore.php";
include_once "../library/OAuthServer.php";
include_once "Mobile_Detect.php";
// The current user
//$user_id = 1;


// Fetch the oauth store and the oauth server.






	
	
$detect = new Mobile_Detect();
if ($detect->isMobile()) {
	if(isset($_GET['usa'])){
		$username = $_GET['usa'];
		$rs = mysql_query("SELECT usa_id_ref FROM any_user_auth WHERE username_client ='$username'");
		$data = mysql_fetch_assoc($rs);
		$user_id = $data['usa_id_ref'];
	}
 
}else{	
	
	assert_logged_in();
	$user_id = $_SESSION['id'];
	if(!isset($_SESSION['ck'])){
		$sql = "SELECT osr_application_title, osr_consumer_key, ost_osr_id_ref FROM oauth_server_registry, oauth_server_token WHERE oauth_server_registry.osr_id =oauth_server_token.ost_osr_id_ref AND ost_token='".$_GET['oauth_token']."' ";
		$req = mysql_query($sql) or die('Erreur de connexion au serveur serveur appli');
		if (mysql_num_rows($req) > 0) {
			$data = mysql_fetch_assoc($req);
			$_SESSION['ck'] = $data['osr_consumer_key'];
		}
	}
	
	$ck = $_SESSION['ck'];
	$sql = "SELECT * FROM oauth_user_consumer_right WHERE user_id ='$user_id' AND consumer_key='$ck'";
	
	$rs = mysql_query($sql);

		if (! $rs) {
			echo "Erreur requete";
		} else {
			if (mysql_num_rows($rs)==0) {
				mysql_query("INSERT INTO oauth_user_consumer_right (user_id,consumer_key, perm_1_see_info, perm_2_send_notif ) VALUES ('$user_id', '$ck',1,1)");
				
				
				$str_data = file_get_contents("../api/json/".$_SESSION['user'].".json");
			
				
				$data = json_decode($str_data,true);

				for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
					$i;
				}
	
				$data["application"][$i]["application_name"] = $_SESSION['title_appli'];
				$data["application"][$i]["application_user"] = $_SESSION['user'];
				$data["application"][$i]["notification"] = "null";
			 
				$fh = fopen("../api/json/".$_SESSION['user'].".json", 'w+') or die("Error opening output file");
				fwrite($fh, json_encode($data));
				fclose($fh);
			}
		}	
	
	//$req = mysql_query($sql) or die('Erreur de connexion au serveur serveur appli');
}

$store  = OAuthStore::instance();
$server = new OAuthServer();
try
{
  // Check if there is a valid request token in the current request
  // Returns an array with the consumer key, consumer secret, token, token secret and token type.
  $rs = $server->authorizeVerify();
  $server->authorizeFinish(true, $user_id);
}
catch (OAuthException $e) {
  // No token to be verified in the request, show a page where the user can enter the token to be verified
  // **your code here**
  var_dump($e);
  exit;
}

?>