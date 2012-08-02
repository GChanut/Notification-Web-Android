<?php

/*
 * Simple 'user management'
 */
define ('USERNAME', 'sysadmin');
define ('PASSWORD', 'sysadmin');

/*
 * Always announce XRDS OAuth discovery
 */
header('X-XRDS-Location: http://notifcenter.zapto.org/oauth2m/server/services.xrds.php');

/*
 * Initialize the database connection
 */
$info['host'] = 'localhost';
$info['user'] = 'root';
$info['pass'] = '';
$info['path'] = 'oauth';

($GLOBALS['db_conn'] = mysql_connect($info['host'], $info['user'], $info['pass'])) || die(mysql_error());
mysql_select_db(basename($info['path']), $GLOBALS['db_conn']) || die(mysql_error());
unset($info);

require_once '../library/OAuthServer.php';

/*
 * Initialize OAuth store
 */
require_once '../library/OAuthStore.php';
OAuthStore::instance('MySQL', array('conn' => $GLOBALS['db_conn']));

/*
 * Session
 */
session_start();

/*
 * Template handling
 */
//require_once 'smarty/libs/Smarty.class.php';
function session_smarty()
{
	if (!isset($GLOBALS['smarty']))
	{
		$GLOBALS['smarty'] = new Smarty;
		$GLOBALS['smarty']->template_dir = dirname(__FILE__) . '/templates/';
		$GLOBALS['smarty']->compile_dir = dirname(__FILE__) . '/../cache/templates_c';
	}
	
	return $GLOBALS['smarty'];
}

function assert_logged_in()
{
	if (empty($_SESSION['authorized']))
	{
		$uri = $_SERVER['REQUEST_URI'];
		header('Location: http://notifcenter.zapto.org/oauth2m/server/logon.php?goto=' . urlencode($uri));
		exit();
	}
}

function assert_request_vars()
{
	foreach(func_get_args() as $a)
	{
		if (!isset($_REQUEST[$a]))
		{
			header('HTTP/1.1 400 Bad Request');
			echo 'Bad request.';
			exit;
		}
	}
}

function assert_request_vars_all()
{
	foreach($_REQUEST as $row)
	{
		foreach(func_get_args() as $a)
		{
			if (!isset($row[$a]))
			{
				header('HTTP/1.1 400 Bad Request');
				echo 'Bad request.';
				exit;
			}
		}
	}
}

?>