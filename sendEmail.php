<?php
	
	/**
     * This sends a confirmation email to the given email
     *
     * @param $user 	string This is the user we are sending to
     * @param $email 	string This is the email we want to send to
     * @param $hash 	string This is the hashed string that we use to create the verification link
     */
	function send_conf($user, $email, $hash)
	{
		$subject = 'Confirmation for a FutureSend account';
		
		$message = 'Thanks for signing up!
					You have requested for a FutureSend account with the following USERNAME: '.$user.'.
					You can login with the following credentials after you have activated your account by pressing the url below.
					
					Please click this link to activate your account:
					http://web.engr.illinois.edu/~aagrawl3/verify.php?user='.$user.'&email='.$email.'&hash='.$hash.'
					
					If you are not sure you have created an account here, you can go here: http://web.engr.illinois.edu/~aagrawl3/welcome.php';
		
		imap_mail($email, $subject, $message);
	}

	/**
     * This sends an email to the given email
     *
     * @param $email 	string This is the email we want to send to
     * @param $subject 	string This is the subject of the email
     * @param $message 	string This is the body of the email
     */
	function send_mail($email, $subject, $message)
	{
		imap_mail($email, $subject, $message);
	}

?>