<?php

// Dépendance
include_once "../library/OAuthStore.php";
include_once "../library/OAuthRequester.php";
require_once '../server/config.inc.php';


// Clé de connexion
/*if(isset($_GET['ident']) && isset($_GET['ockey']) && isset($_GET['ocs'])){
echo '1er';

	$id = $_GET['ident'];

	$req = mysql_query("SELECT * FROM oauth_server_registry WHERE osr_usa_id_ref='$id'");
	$data = mysql_fetch_array($req);
	
	$callb = $data['osr_callback_uri'];
	$csecret = $_GET['ocs'];
	$ckey = $_GET['ockey'];
	
		
	define("CONSUMER_KEY", $ckey);
	define("CONSUMER_SECRET", $csecret);
	

	
}else{
echo '2eme';
	$id = $_GET['usr_id'];

	$req = mysql_query("SELECT * FROM oauth_server_registry WHERE osr_usa_id_ref='$id'");
	$data = mysql_fetch_array($req);
	
	$ckey = $_GET['consumer_key'];
	$csecret = $data['osr_consumer_secret'];

	define("CONSUMER_KEY", $ckey);
	define("CONSUMER_SECRET", $csecret);
	
	//echo $_GET['consumer_key'];
}	
*/
	define("CONSUMER_KEY","6fb7c6ab4e6f6be3d4f8be5175c8fb6404fe2f93c");
	define("CONSUMER_SECRET", "6f0874b1806c3f22cef0c4d46366e46d");
	define("USER_ID","1");

	
// URL
define("OAUTH_HOST", "http://notifcenter.zapto.org/oauth2m/server");
define("REQUEST_TOKEN_URL", OAUTH_HOST . "/request_token.php");
define("AUTHORIZE_URL", OAUTH_HOST . "/authorize.php");
define("ACCESS_TOKEN_URL", OAUTH_HOST . "/access_token.php");

// Dossier temporaire
define('OAUTH_TMP_DIR', function_exists('sys_get_temp_dir') ? sys_get_temp_dir() : realpath($_ENV["TMP"]));

// Données de trace
$options = array(
  'server' => 'localhost',
  'username' => 'root',
  'password' => '', 
  'database' => 'oauth'
);

$store = OAuthStore::instance('MySQL', $options);

echo '<pre>';

// Identifiant de l'application
$user_id = USER_ID;


// Description des URLs OAuth
$server = array(
	'consumer_key' => CONSUMER_KEY, 
	'consumer_secret' => CONSUMER_SECRET,
	'server_uri' => OAUTH_HOST,
    'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
	'request_token_uri' => REQUEST_TOKEN_URL,
	'authorize_uri' => AUTHORIZE_URL,
	'access_token_uri' => ACCESS_TOKEN_URL
);



// Enregistrement du "consumer"
$bCall = false;
try {

  $consumer_key = $store->updateServer($server, $user_id);
   //$servers = $store->listServers('', $user_id);
  // $store->deleteServer($consumer_key, $user_id);
  

}
catch(OAuthException2 $e) {

  $consumer_key = CONSUMER_KEY;
  
}


try {
  // ---------------------------------------
  // STEP 1: Demande token / identifiant ---
  // ---------------------------------------
  if (empty($_GET["oauth_token"])) {


    // Obtain a request token from the server
    $token = OAuthRequester::requestRequestToken($consumer_key, $user_id);
	
    echo $consumer_key.'<br />';
	echo $user_id.'<br />';
    // Callback to our (consumer) site, will be called when the user finished the authorization at the server
	
	  $callback_uri = 'http://notifcenter.zapto.org/oauth2m/client/client-test.php?consumer_key='.rawurlencode($consumer_key).'&usr_id='.intval($user_id);

    // Now redirect to the autorization uri and get us authorized
    if (!empty($token['authorize_uri'])) {

      // Redirect to the server, add a callback to our server
      if (strpos($token['authorize_uri'], '?'))
        $uri = $token['authorize_uri'] . '&'; 
      else
        $uri = $token['authorize_uri'] . '?'; 
      
      $uri .= 'oauth_token='.rawurlencode($token['token']).'&oauth_callback='.rawurlencode($callback_uri);
    }
    else {
      // No authorization uri, assume we are authorized, exchange request token for access token
      $uri = $callback_uri . '&oauth_token='.rawurlencode($token['token']);
    }

    header('Location: '.$uri);
    exit();
  }
}
catch(OAuthException2 $e) { 

  echo __LINE__." - OAuthException2:  " . $e->getMessage().PHP_EOL;
	var_dump($e);
  exit;
}


// ---------------------------
// STEP 2: Demande d'accès ---
// ---------------------------
try {
  if (!empty($_GET["oauth_token"])) {
    // Request parameters are oauth_token, consumer_key and usr_id.
    $consumer_key = $_GET['consumer_key'];
    $oauth_token = $_GET['oauth_token'];
    $user_id = USER_ID;
  
    OAuthRequester::requestAccessToken($consumer_key, $oauth_token, $user_id, 'POST', $_GET);
    $bCall = true;
  }
}
catch (OAuthException2 $e) {
  echo __LINE__." - OAuthException2:  " . $e->getMessage().PHP_EOL;
  var_dump($e);
  exit;
  
  // Something wrong with the oauth_token.
  // Could be:
  // 1. Was already ok
  // 2. We were not authorized
  $bCall = true;
}


// --------------------------------
// STEP 3: Appel du web service ---
// --------------------------------
try {
  if($bCall) {
  
    // The request uri being called.
	
    $request_uri = 'http://notifcenter.zapto.org/oauth2m/server/hello.php';

    // Parameters, appended to the request depending on the request method.
    // Will become the POST body or the GET query string.
    $params = array(
      'method' => 'POST'
    );

    // Obtain a request object for the request we want to make
    // $params['oauth_consumer_key'] = CONSUMER_KEY;
    $req = new OAuthRequester($request_uri, 'GET', $params);

    // Sign the request, perform a curl request and return the results, throws OAuthException exception on an error
    $result = $req->doRequest($user_id);
	
    // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
    echo $result['body'];
	//header('Location: '.$_GET['callback']);
  }
}
catch (OAuthException2 $e) {
  echo __LINE__." - OAuthException2:  " . $e->getMessage().PHP_EOL;
  var_dump($e);
}

?>
