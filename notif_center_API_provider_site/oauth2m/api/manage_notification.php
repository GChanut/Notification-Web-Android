<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" /> 
</head>
<?php 
require_once '../server/config.inc.php';

if(isset($_GET['oauth_token']) && isset($_GET['content'])){

	$token = $_GET['oauth_token'];
	$sql = "SELECT ost_usa_id_ref, ost_token_type, username_client, osr_application_title FROM oauth_server_token, any_user_auth, oauth_server_registry WHERE oauth_server_token.ost_usa_id_ref=any_user_auth.usa_id_ref AND oauth_server_token.ost_osr_id_ref=oauth_server_registry.osr_id AND ost_token='$token'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur');
		if (mysql_num_rows($req) > 0) {
			$data = mysql_fetch_assoc($req);
			
		}
		
		if($data['ost_token_type'] == 'access'){
			$str_data = file_get_contents("json/".$data['username_client'].".json");
			
			$jsondata = json_decode($str_data,true);
			for ($i = 0, $len = count($jsondata["application"]); $i < $len; ++$i) {
				if($jsondata["application"][$i]["application_name"] == $data['osr_application_title']){
						
						
						//echo htmlentities($_GET['content']);
					$string = htmlentities($_GET['content']);
					$needle = array("&lt;","&gt;", "&quot;");
					$replace = array("<", ">", "\"");
					
					$string = str_replace($needle, $replace, $string);
					
					$jsondata["application"][$i]["notification"] = $string;
					
					$fh = fopen("json/".$data['username_client'].".json", 'w+') or die("Error opening output file");
					fwrite($fh, json_encode($jsondata));
					fclose($fh);
				
				}
			}
		}
	//header('Location: '.$_SERVER['HTTP_REFERER']);
}else{
		$var = array("error" => "Could not authenticate you.", "request" => "\/api\/manage_notificationh.php");
		print(json_encode($var));

}



?>