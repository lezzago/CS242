<?php

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	if(check_user($_SESSION['username']))
		header('Location: welcome.php');
	else
	{
		$user = $_POST['username'];
		$pass = $_POST['pass'];
		$errors = array();
		if(isset($user, $pass))
		{
			if(empty($user))
				$invalid_user = 'Please input an username.';
			if(empty($pass))
				$invalid_pass = 'Please input a password.';
			if(!$invalid_pass && !$invalid_user && !valid_cred($user, $pass))
			{
				$invalid_user = 'The username and password don\'t match.';
				$invalid_pass = 'The username and password don\'t match.';
			}
			if(!$invalid_pass && !$invalid_user)
			{
				$_SESSION['username'] = $user;
				header('Location: welcome.php');
			}
		}
			
		echo $twig->render('login.html', array('errors' => $errors,
											'username' => $user,
											'invalid_pass' => $invalid_pass,
											'invalid_user' => $invalid_user));
	}
		

	

?>