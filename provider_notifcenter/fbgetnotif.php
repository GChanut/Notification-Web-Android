<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<?php


  $app_id = '231253576996714';
  $app_secret = '24865b6ce63bbf76d6895e32656dadfb';
  $my_url = 'http://notifcenter.zapto.org/notifcenter/fbgetnotif.php';

  

  if(!isset($_REQUEST["code"])) {
		// Get permission from the user to publish to their page. 
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
		  . $app_id . "&redirect_uri=" . urlencode($my_url)
		  . "&scope=manage_notifications,offline_access,publish_stream";
		echo('<script>top.location.href="' . $dialog_url . '";</script>');
  } else {
  
		$code = $_REQUEST["code"];
		// Get access token for the user
		$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
		  . $app_id . "&redirect_uri=" . urlencode($my_url)
		  . "&client_secret=" . $app_secret
		  . "&code=" . $code;
		$access_token = file_get_contents($token_url);
	echo $access_token;
		//récuperation données utilisateur facebook
		$user = "https://graph.facebook.com/me?" . $access_token;
		$response2 = file_get_contents($user);
		$resp_2 = json_decode($response2,true);
		$language = $resp_2['locale'];
		
		//récupération notification utilisateur facebook
		$notifications = "https://graph.facebook.com/me/notifications?" . $access_token."&locale=".$language;
		$response = file_get_contents($notifications);
		$resp_a = json_decode($response,true);
		
		

		echo '<pre>';
		print_r($resp_a);
		echo '</pre>';
	
		echo '<table border="1">';
		foreach($resp_a['data'] as $p){		
			echo '<tr><td><div ><a href="'.$p['link'].'" style="text-decoration: none; color: blue;">'.$p['title'].'</a></div></td></tr>';
		}
		echo '</table>';


  }
  
?>