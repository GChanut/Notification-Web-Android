<?php

include_once "../library/OAuthStore.php";
include_once "../library/OAuthRequester.php";

// URL
define("OAUTH_HOST", "http://localhost/oauth/server");
define("REQUEST_TOKEN_URL", OAUTH_HOST . "/request_token.php");
define("AUTHORIZE_URL", OAUTH_HOST . "/authorize.php");
define("ACCESS_TOKEN_URL", OAUTH_HOST . "/access_token.php");

$key = 'c93b1a2263fc75aadb8e3ae1fef485c504ccad4e5'; // this is your consumer key
$secret = 'c60ef4d3f12e33562e5b1ce7824436f1'; // this is your secret key

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
  $request_uri = 'http://localhost/oauth/server/hello.php';
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