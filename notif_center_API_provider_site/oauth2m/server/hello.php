<?php
require_once 'config.inc.php';
$exception = null;

$authorized = false;
$server = new OAuthServer();
try
{
	if ($server->verifyIfSigned())
		$authorized = true;
}
catch (OAuthException2 $e)
{ $exception = $e; }

if (!$authorized)
{
	header('HTTP/1.1 401 Unauthorized');
	header('Content-Type: text/plain');
	
	echo "OAuth Verification Failed";
  if(!empty($exception))
    echo ": ".$exception->getMessage();
  
	die;
}

// A partir d'ici nous sommes connecté via OAuth
header('Content-type: text/plain');

echo 'Hello, world!';




?>