<?php

include_once "../library/OAuthStore.php";
include_once "../library/OAuthRequester.php";

// URL
define("OAUTH_HOST", "http://notifcenter.zapto.org/oauth2m/server");
define("REQUEST_TOKEN_URL", OAUTH_HOST . "/request_token.php");
define("AUTHORIZE_URL", OAUTH_HOST . "/authorize.php");
define("ACCESS_TOKEN_URL", OAUTH_HOST . "/access_token.php");

$key = '5bf2249015df8f82bb7f39c11991c8f405042a1f9';
$secret = 'f610d7dbcbe0dd1a386843178beb0380'; 
$options = array(
  'consumer_key' => $key,
  'consumer_secret' => $secret
);
OAuthStore::instance("2Leg", $options);

$url = REQUEST_TOKEN_URL; // this is the URL of the request
$method = "POST"; // you can also use POST instead

try
{
  // Obtain a request object for the request we want to make
  $request = new OAuthRequester($url, $method);
  $result = $request->doRequest(0);
  parse_str($result['body'], $params);
  $params['oauth_consumer_key'] = $key;
  
  // now make the request. 
  $request_uri = 'http://notifcenter.zapto.org/oauth2m/server/hello.php';
  $request = new OAuthRequester($request_uri, 'GET', $params);
  $result = $request->doRequest();
  var_dump($result['body']);
}
catch(OAuthException2 $e)
{
  echo __LINE__." - OAuthException2:  " . $e->getMessage().PHP_EOL;
  var_dump($e);
}

?>