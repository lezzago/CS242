<?php

	/*
	 * Renders the verification page. Here is verifies the user's account and now allows them to user the service.
	 */

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());

	session_start();
	if(!check_user($_SESSION['username']))
		$logged_in = false;
	else
	{
		$logged_in = true;
		$user = $_SESSION['username'];
	}
	
	if(isset($_GET['user']) && isset($_GET['email']) && isset($_GET['hash']))
	{
		$account = check_conf($_GET['user'], $_GET['email'], $_GET['hash']);
		if(!empty($account))
		{
			add_user($_GET['user']);
			delete_verify($_GET['user'], $_GET['hash']);
			$message = 'You have successfully verified your account '.$_GET['user'].'! Congratulations!';
		}
		else
			$message = 'You have accidentally screwed up the link. Please click on the correct link in the email.';
	}
	else
		$message = 'You have accidentally stumbled upon the verification page. 
					Please login or signup for an account, if you are not signed in.';
	
	echo $twig->render('verify.html', array('message' => $message,
											'username' => $user,
											'logged_in' => $logged_in));
?>