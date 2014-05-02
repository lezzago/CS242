<?php

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	$user = $_SESSION['username'];
	if(empty($user))
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
				$email = get_email($user);
				echo $twig->render('welcome.html', array('username' => $user,
									'email' => $email,
									'logged_in' => true));
			}
			else
				header('Location: signup.php');
		}
		else
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