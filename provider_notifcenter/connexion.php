<?php 
session_start();
mysql_connect("localhost", "root", "") or die("erreur de connexion au serveur 1");
	mysql_select_db("oauth") or die("erreur de connexion au serveur 2");
	if(!empty($_SESSION['id']))
	{
	$id=$_SESSION['id'];
	
$req = mysql_query("SELECT * FROM any_user_auth WHERE usa_id_ref = $id");
$result = mysql_fetch_array($req);
$req2 = mysql_query("SELECT * FROM oauth_user_consumer_right WHERE user_id = $id");
}

?>