<?php

require_once 'config.inc.php';

assert_logged_in(); 
$user_id = $_SESSION['id'];
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
    <h1>Enregistrer votre Site</h1>

    

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
        
        
        <p>
            <label for="ident">ID de l'utilisateur: &nbsp;<?php echo $user_id; ?></label><br/>
            <input id="ident" class="text" name="ident" type="hidden" value="<?php echo $user_id; ?>" />
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
		<p>
			<label for="callback_uri">Icone de votre application</label><br/> <!-- BIEN SPECIFIER LA TAILLE -->
			<input type="file" name="iconapp" />
		</p>
		
		<p>
			<label for="callback_uri">Icone d'alerte sur le mobile</label><br/><!-- BIEN SPECIFIER LA TAILLE -->
			<input type="file" name="iconotif" />
		</p>
		<!-- BIEN FAIRE LE TEST QUE TOUS LES CHAMPS DOIVENT ETRE REMPLI -->
      </fieldset>
      
      <br />
      <input type="submit" value="Register server" />
    </form>
  </body>
</html>