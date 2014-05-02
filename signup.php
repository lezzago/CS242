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
		$pass_conf = $_POST['pass_confirmation'];
		$email = $_POST['email'];
		$errors = array();
		if(isset($user, $pass, $pass_conf, $email))
		{
			$email_check = check_email($email);
			if(empty($user))
				$invalid_user = 'Please input an username.';
			if(empty($pass))
				$invalid_pass = 'Please input a password.';
			if($pass !== $pass_conf)
				$invalid_confirm = 'The two passwords do not match.';
			if(check_user($user))
				$invalid_user = 'The username has been taken.';
			if(empty($email))
				$invalid_email = 'Please input an email.';
			elseif(!preg_match('/^[\S]+@[\S]+\.[\S]+$/', $email))
				$invalid_email = 'That is not a valid email address.';
			if(!empty($email_check))
				$invalid_email = 'There is already an account associated to this email.';
			if(!$invalid_email && !$invalid_user && !$invalid_pass && !$invalid_confirm)
			{
				add_verify($user, $pass, $email);
				$success = 'You should recieve an email soon. Sometimes it may take about 30 minutes.';
			}
		}
			
		echo $twig->render('signup.html', array('invalid_email' => $invalid_email,
												'invalid_pass' => $invalid_pass,
												'invalid_user' => $invalid_user,
												'invalid_confirm' => $invalid_confirm,
												'success' => $success,
												'username' => $user,
												'email' => $email,
												'logged_in' => false));
	}
		

	

?>