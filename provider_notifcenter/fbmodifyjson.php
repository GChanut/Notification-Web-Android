<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" media="screen" type="text/css" href="css/style.css"/>
<link href="css/style2.css" rel="stylesheet" type="text/css" />
</head>
<?php 
// Read the file contents into a string variable,
// and parse the string into a data structure
session_start();
mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
mysql_select_db("oauth") or die("erreur de connexion au serveur 2");


if(!empty($_GET['usa']) && !empty($_GET['id']) && !empty($_GET['token'])){
	$str_data = file_get_contents("oauth2m/api/json/".$_SESSION['user'].".json");
	
	$data = json_decode($str_data,true);

	 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
		$i;
	}
	$data["application"][$i]["application_name"] = "Facebook";
	$data["application"][$i]["application_user"] = $_GET['usa'];
	$data["application"][$i]["application_id"] = $_GET['id'];
	$data["application"][$i]["application_token"] = $_GET['token'];
	$data["application"][$i]["notification"] = "";
	 
	$fh = fopen("oauth2m/api/json/".$_SESSION['user'].".json", 'w+') or die("Error opening output file");
	fwrite($fh, json_encode($data));
	fclose($fh);
	
	$user_id = $_SESSION['id'];
	echo $user_id;
	$ck = '231253576996714';
	echo $ck;
	$sql = "SELECT * FROM oauth_user_consumer_right WHERE user_id ='$user_id' AND consumer_key='$ck'";
	$rs = mysql_query($sql);
	
	if (! $rs) {
		echo "Erreur requete";
	} else {
		if (mysql_num_rows($rs)==0) {
			mysql_query("INSERT INTO oauth_user_consumer_right (user_id,consumer_key, perm_1_see_info, perm_2_send_notif ) VALUES ('$user_id', '$ck',1,1)");
		}
	}
	
	header('Location: index.php');
}else{echo 'erreur de parsing';}
?>