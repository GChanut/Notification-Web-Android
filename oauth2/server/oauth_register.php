<?php

require_once 'config.inc.php';

//assert_logged_in(); // Ceci est un log administrateur

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$ost_id = $_POST['ident']; //id de l'application: dans notre cas, 1 sera webCV. Et 2 pour l'appli Mobile

		
		$store = OAuthStore::instance();
		$key   = $store->updateConsumer($_POST, $ost_id, true);

		$c = $store->getConsumer($key, $ost_id);
		
		echo 'Your consumer key is: <strong>' . $c['consumer_key'] . '</strong><br />';
		echo 'Your consumer secret is: <strong>' . $c['consumer_secret'] . '</strong><br />';
		
		echo 'Application enregistré';
		
	}
	catch (OAuthException2 $e)
	{
		echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
	}
}

?>

<html>
  <body>
    <h1>Register server</h1>

    <p>Register a server which is gonna act as an identity client.</p>

    <form method="post">
      <!--<fieldset>
        <legend>About You</legend>
        
        <p>
            <label for="requester_name">Your name</label><br/>
            <input class="text" id="requester_name"  name="requester_name" type="text" value="" />
        </p>
        
        <p>
            <label for="requester_email">Your email address</label><br/>
            <input class="text" id="requester_email"  name="requester_email" type="text" value="" />
        </p>
      </fieldset> -->
      
      <fieldset>
        <legend>Location Of Your Application Or Site</legend>
        
        <p>
            <label for="ident">ID de l'application</label><br/>
            <input id="ident" class="text" name="ident" type="text" value="" />
        </p>
		
		<p>
            <label for="application_title">Nom de l'application</label><br/>
            <input id="application_title" class="text" name="application_title" type="text" value="" />
        </p>
		
		<p>
            <label for="application_uri">URL of your application or site</label><br/>
            <input id="application_uri" class="text" name="application_uri" type="text" value="" />
        </p>
        
        <p>
            <label for="callback_uri">Callback URL</label><br/>
            <input id="callback_uri" class="text" name="callback_uri" type="text" value="" />
        </p>
      </fieldset>
      
      <br />
      <input type="submit" value="Register server" />
    </form>
  </body>
</html>