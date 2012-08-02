<?php

require_once 'config.inc.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
	if ($_POST['username'] == USERNAME && $_POST['password'] == PASSWORD) {
		$_SESSION['authorized'] = true;
		if (!empty($_GET['goto'])) {
			header('Location: ' . $_GET['goto']);
			die;
		}

		echo "Logon succesfull.";
		die;
	}
}

?>

<html>
  <body>
    <h1>Login</h1>

    <form method="post">
      <input type="hidden" name="goto" value="" />
      
      <label for="username">User name</label><br />
      <input type="text" name="username" id="username" />
      
      <br /><br />

      <label for="password">Password</label><br />
      <input type="text" name="password" id="password" />
      
      <br /><br />
      
      <input type="submit" value="Login" />
    </form>
  </body>
</html>