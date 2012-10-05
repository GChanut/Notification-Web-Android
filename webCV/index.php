<?php
/**
 * @file
 * User has successfully authenticated with NC. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('oauth/NCoauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a NCOauth object with consumer/user tokens. */
$connection = new NCOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('verify_credentials');

/* Include HTML to display on the page */
print_r($content);
print($access_token['oauth_token']);
?>
<a href="./clearsessions.php">Se deconnecter</a>