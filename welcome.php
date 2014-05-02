<?php

	/*
	 * Renders the welcome page when the user is signed in and serves as home page.
	 */

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	$user = $_SESSION['username'];
	if(empty($user))
	{
			header('Location: signup.php');
	}
	else
	{
		$email = get_email($user);
		echo $twig->render('welcome.html', array('username' => $user,
							'email' => $email,
							'logged_in' => true));
	}
?>