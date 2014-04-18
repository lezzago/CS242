<?php
/**
 * This send an email to the user from what they have filled in for the fields.
 */

	require_once './vendor/autoload.php';
	require 'session.php';
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	if(!check_user($_SESSION['username']))
		header('Location: login.php');
	else
	{
		$user = $_SESSION['username'];
		$subject = $_POST['subject'];
		$email_message = $_POST['message'];
		if(isset($subject, $email_message))
		{
			$email = get_email($user);
			send_mail($email, $subject, $email_message);
			$message = 'Email has been sent. Soon in the future, choosing a time to send the email will be implemented.';
		}
			
		echo $twig->render('futureEmail.html', array('message' => $message,
							'username' => $user));
	}
		

	

?>