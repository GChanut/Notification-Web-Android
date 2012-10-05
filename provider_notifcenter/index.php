<?php 
session_start();
mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
mysql_select_db("oauth") or die("erreur de connexion au serveur 2");

if (isset($_POST['username']) && isset($_POST['password'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];
	//Verification de l'existence de l'utilisateur dans la BDD
	$sql = "SELECT * FROM any_user_auth WHERE username_client='$username'";
	$req = mysql_query($sql) or die('Erreur de connexion au serveur log');
		if (mysql_num_rows($req) > 0) {

			$data = mysql_fetch_assoc($req);
			//Verification Mot de Passe dans la BDD
			if ($password == $data['mdp_client']) {
				$_SESSION['authorized'] = true;
				$_SESSION['user'] = $username;
				$_SESSION['pass'] = $password;
				$_SESSION['id']= $data['usa_id_ref'];
			}else{echo 'Mauvais login ou mot de passe';}
		}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" media="screen" type="text/css" href="css/style.css"/>
<link href="css/style2.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='scripts/line.js'></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/interface.js"></script>
<script type="text/javascript" src="scripts/valid.js"></script>
<script>
function getAppli(toThis){
	document.location.href="index.php?app="+toThis;
  }
</script>
<title>NotifCenter - Manage Your Notifications</title>
</head>

<body>
 <?php	if(!empty($_SESSION['id'])){
		$id=$_SESSION['id'];
		$req = mysql_query("SELECT * FROM any_user_auth WHERE usa_id_ref = $id");
		$result = mysql_fetch_array($req);
	}
 ?>
 <div id="principale"><p><div align="left">&nbsp; &nbsp; <a id="titrecool">NotifCenter</a></div><div align="right"><a>

 <?php
 if(isset($result['prenom_client']) && isset($result['nom_client'])){
 				echo $result['prenom_client'].' '.$result['nom_client'];
				echo " ".'<a href="disconnect.php">Se déconnecter</a>';
				echo '	<div id="formul"><hr /></div>';
 }
 
 if(!empty($_SESSION['id'])){
			if(isset($_GET['app'])){
			
				if($_GET['app'] == 'Facebook'){
				
							//Recuperation des données utilisateurs notifcenter
							$str_data = file_get_contents("oauth2m/api/json/".$_SESSION['user'].".json");
							$data = json_decode($str_data,true);
							 for ($i = 0, $len = count($data["application"]); $i < $len; ++$i) {
								if($data["application"][$i]["application_name"] == "Facebook"){								
									$token = $data["application"][$i]["application_token"];
									$id = $data["application"][$i]["application_id"];
									$temp = $i;
									$notif = $data["application"][$i]["notification"];
								}
							}
							
							//Récuperation de la langue de l'utilisateur facebook
							$user = "https://graph.facebook.com/".$id."?" . $token;
							$response2 = file_get_contents($user);
							$resp_2 = json_decode($response2,true);
							$language = $resp_2['locale'];
							
							//récupération notification utilisateur facebook
							$notifications = "https://graph.facebook.com/".$id."/notifications?" . $token."&locale=".$language;
							$response = file_get_contents($notifications);
							$resp_a = json_decode($response,true);

							$fbnotif = "";
							foreach($resp_a['data'] as $p){	
								$fbnotif =	$fbnotif."<tr><td onmouseover='javascript:this.style.background=\"#aaa\"' onmouseout='javascript:this.style.background=\"\" ' ><a href=\"".$p['link']."\" target='_blank' style='text-decoration: none; color: #cccccc; display:inline-block'><div  style='margin: 5px 5px 5px 5px'><img src='https://graph.facebook.com/".$p['from']['id']."/picture' align='absmiddle'/><h4>".$p['title']."</h4></div></a></td></tr>";
								
							}		
					
							if($notif != $fbnotif){
								$newnotif = $data["application"][$temp]["notification"] = $fbnotif;
								$fh = fopen("oauth2m/api/json/".$_SESSION['user'].".json", 'w+') or die("Error opening output file");
								fwrite($fh, json_encode($data));
								fclose($fh);
							}else{
								$newnotif = $data["application"][$temp]["notification"];
							}
							
							echo '<table align="center" border="1">'.$newnotif. '</table>';
			
						
				}else if($_GET['app'] == 'Twitter'){
					
				/*}else{*/
				
				}
			
			}else{

				echo '<div style="text-align: center;">';
				require 'fbdesign.php';
				require 'twtdesign.php';
				echo "</div>";
				}
			
	}else {?>
 <br />
	<form method="post">
		<input type="text" name="username" class="in">
		<input type="password" name="password" class="in">
		<input type="submit" value="Valider" class="button">
	</form>
	<div id="formul">
		<hr />
	</div>
	<div align="center">
	<br /><?php include("register.php")?>
	</div>

<?php
	
	}
	?> </a></div></div>
<!--bottom dock -->
<div class="dock" id="dock2">
  <div class="dock-container2">
    <a class="dock-item2" href="#"><span>Download NC Mobile</span><img src="img/dl.png"/></a> 
	<a class="dock-item2" href="docs.php"><span>Documentations</span><img src="img/info.png"/></a>
		
    <?php if(!empty($_SESSION['id']))
	 { 
		echo '<a class="dock-item2" href="param.php"><span>Paramètres du compte</span><img src="img/Settings.png"/></a>'; 
		$req2 = mysql_query("SELECT * FROM oauth_user_consumer_right WHERE user_id = '$id'");
		while($result2 = mysql_fetch_array($req2))
		{
			$perm1=$result2['perm_1_see_info'];
			$perm2=$result2['perm_2_send_notif'];
			$key=$result2['consumer_key'];
			$req3 = mysql_query("SELECT * FROM oauth_server_registry WHERE osr_consumer_key = '$key'");
			while($result3 = mysql_fetch_array($req3))
			{
				$i=$result3['osr_id'];
				$appName=$result3['osr_application_title'];
				$appDescr=$result3['osr_application_descr'];
				$appDev=$result3['osr_requester_name'];
				$appUrl=$result3['osr_application_uri'];?>
				<a class="dock-item2" href="javascript:;" onClick="getAppli('<?php echo $appName; ?>');" ><span><?php echo $appName;?></span><img src="img/<?php echo "app$i";?>.png"/></a><?php 
			}	
		}
	}
	?>
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

</body>
</html>








