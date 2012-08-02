<?php

require_once 'config.inc.php';
include_once "../library/OAuthStore.php";
include_once "../library/OAuthServer.php";

// The current user
if(isset($_GET['ident'])){
	
	$user_id = $_GET['ident'];
	
}
// Fetch the oauth store and the oauth server.
$store  = OAuthStore::instance();
$server = new OAuthServer();

assert_logged_in();

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