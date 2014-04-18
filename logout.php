<?php
/**
 * This logs the user out by destroying the session and
 * then redirects them to the login page.
 */

	session_start();
	$_SESSION = array();
	session_destroy();
	header('Location: login.php');

?>