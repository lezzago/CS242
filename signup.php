<?php
/**
 * This registers the users to an account. If they submit wrong information, it will tell
 * the user what they have done wrong. Also it will send you an email to confirm.
 */

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
				$errors[] = 'Please input an username.';
			if(empty($pass))
				$errors[] = 'Please input a password.';
			if($pass !== $pass_conf)
				$errors[] = 'The two passwords do not match.';
			if(check_user($user))
				$errors[] = 'The username has been taken.';
			if(empty($email))
				$errors[] = 'Please input an email.';
			elseif(!preg_match('/^[\S]+@[\S]+\.[\S]+$/', $email))
				$errors[] = 'That is not a valid email address.';
			if(!empty($email_check))
				$errors[] = 'There is already an account associated to this email.';
			if(empty($errors))
			{
				add_verify($user, $pass, $email);
				$errors[] = 'You should recieve an email soon. Sometimes it may take about 30 minutes.';
			}
		}
			
		echo $twig->render('signup.html', array('errors' => $errors,
							'username' => $user,
							'email' => $email));
	}
?>