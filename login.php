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
				$errors[] = 'Please input an username.';
			if(empty($pass))
				$errors[] = 'Please input a password.';
			if(empty($errors) && !valid_cred($user, $pass))
				$errors[] = 'The username and password don\'t match.';
			if(empty($errors))
			{
				$_SESSION['username'] = $user;
				header('Location: welcome.php');
			}
		}
			
		echo $twig->render('login.html', array('errors' => $errors,
						'username' => $user));
	}
		

	

?>