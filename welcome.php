<?php
/**
 * This is the welcome page where you can only access it if you are signed in.
 * The page will show your username and email address
 */

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