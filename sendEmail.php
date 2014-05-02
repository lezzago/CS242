<?php

	function send_conf($user, $email, $hash)
	{
		$subject = 'Confirmation for a FutureSend account';
		
		$message = 
		'Thanks for signing up!
		You have requested for a FutureSend account with the following USERNAME: '.$user.'.
		You can login with the following credentials after you have activated your account by pressing the url below.
		
		Please click this link to activate your account:
		http://web.engr.illinois.edu/~aagrawl3/verify.php?user='.$user.'&email='.$email.'&hash='.$hash.'
		
		If you are not sure you have created an account here, you can go here: http://web.engr.illinois.edu/~aagrawl3/welcome.php';
		
		imap_mail($email, $subject, $message);
	}
	
	function send_mail($email, $subject, $message)
	{
		imap_mail($email, $subject, $message);
	}

?>