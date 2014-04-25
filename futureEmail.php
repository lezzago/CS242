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
		$time = date('m-d-Y H:i');
		$date = str_replace('/', '-',$_POST['date']);
		if($_POST['ampm'] == 'pm')
			$datetime = $date.' '.($_POST['hour']+12).':'.$_POST['minute'];
		else
			$datetime = $date.' '.$_POST['hour'].':'.$_POST['minute'];
		$hour = $_POST['hour'];
		$message = 'Please enter the proper stuff';
		if(!empty($date) && !empty($_POST['hour']) && !empty($_POST['minute']) && !empty($_POST['ampm']) && $time > $datetime)
			$message = 'Sorry you cannot send an email to the past.';
		elseif(isset($subject) && !empty($subject) && isset($email_message) && !empty($email_message))
		{
			$email = get_email($user);
			add_email($user, $email, $subject, $email_message, $datetime);
			$message = 'Email has been sent. Soon in the future, choosing a time to send the email will be implemented.';
		}
			
		echo $twig->render('futureEmail.html', array('message' => $message,
							'username' => $user));
	}
		

	

?>