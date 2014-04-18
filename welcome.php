<?php
/**
 * This is the welcome page where you can only access it if you are signed in.
 * The page will show your username and email address
 */

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	$user = $_SESSION['username'];
	if(empty($user))
		header('Location: login.php');
	else
	{
		$email = get_email($user);
		echo $twig->render('welcome.html', array('username' => $user,
							'email' => $email));
	}
?>