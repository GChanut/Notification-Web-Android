<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" media="screen" type="text/css" href="css/style.css"/>
<link href="css/style2.css" rel="stylesheet" type="text/css" />
</head>
<?php

$str_data = file_get_contents("oauth2m/api/json/".$_SESSION['user'].".json"); //mettre la SESSION['user'] avec le lien relatif api

$data = json_decode($str_data,true);
$bool = false;
//On test si l'appli twitter est bien repertoriť sur notre api json de notifcenter
 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
	if($data["application"][$i]["application_name"] == "Twitter"){
		$bool = true;
		$var = $data["application"][$i]["application_user"];
	}
	
}


if($bool == false){
?>
		<html>
		  <head>
			<link href="network.css" media="screen" rel="stylesheet" type="text/css" />
			</head>
		<body>
		<ul>
		<li class="social-connector facebook connected sharing-enabled" data-service-name="facebook">
				<div class="left-section">
				  <img class="network-icon" src="img/twt.jpg" alt="Facebook" />
				  <div class="info">
					<span class="social-network-name">Twitter</span>	
					<span class="social-network-username">Partage: <font color=red>desactiver</font></span>					
				  </div>		  
						<span class="social-network-button"><button onclick="window.location.href='twtauthorize.php'">Connecter les comptes</button></span>
				</div>

		</li>
		</ul>
		</body>
		</html>
<?php
}else{
?>

		<html>
		  <head>
			<link href="network.css" media="screen" rel="stylesheet" type="text/css" />
			</head>
		<body>
		<ul>
		<li class="social facebook connected sharing-enabled" data-service-name="facebook">
				<div class="left-section">
				  <img class="network-icon" src="img/twt.jpg" alt="Facebook" />
				  <div class="info">
					<img class="network-icon" src="https://api.twitter.com/1/users/profile_image?screen_name=<?php echo $var; ?>&size=normal"/>
					<span class="social-network-username">@<?php echo $var; ?></span>
					<span class="social-network-stat">Partage: <font color=green>activer</font></span>
				  </div>		  
					
				</div>
		</li>
		</ul>
		</body>
		</html>

<?php
}
?>
