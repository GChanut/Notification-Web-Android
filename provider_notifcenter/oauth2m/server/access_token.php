<?php

require_once 'config.inc.php';

if(isset($_GET['oauth_token'])){
		$ot = $_GET['oauth_token'];
		$sql= mysql_query("SELECT ost_osr_id_ref, ost_usa_id_ref FROM oauth_server_token WHERE ost_token='$ot'");
		$result = mysql_fetch_array($sql);
		$usa_id = $result['ost_usa_id_ref'];
		$osr_id = $result['ost_osr_id_ref'];
		
			//Requete SQL pour savoir si un access token est déjà créé
			$rs = mysql_query("SELECT * FROM oauth_server_token WHERE ost_usa_id_ref ='$usa_id' AND ost_osr_id_ref='$osr_id' AND ost_token_type='access'");
			$result2 = mysql_fetch_array($rs);
			if (! $rs) {
				echo "Erreur requete";
			} else {
				if (mysql_num_rows($rs)==0) {
						$server = new OAuthServer();
						$server->accessToken();
				}else{
						$_SESSION['usa_id'] = $usa_id;
						$server = new OAuthServer();
						$server->accessTokenIsExist($result2['ost_token']);
				}
			}

}
			exit();

?>


