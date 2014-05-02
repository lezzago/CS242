<?php
	require './session.php';
	require_once './vendor/autoload.php';
	
	/*
	 * Renders the page for editing a user's account information. Form validation is done here as well.
	 */
	
	$loader = new Twig_Loader_Filesystem('./templates');
	$twig = new Twig_Environment($loader, array());
	
	session_start();
	if(!check_user($_SESSION['username']))
		header('Location: login.php');
	else
	{
		$user = $_SESSION['username'];
		$email = get_email($user);
		
		$new_user = $_POST['newuser'];
		$old_pass = $_POST['oldpass'];
		$new_pass = $_POST['newpass'];
		$new_pass2 = $_POST['newpassconfirm'];
	
		$change_user = false;
		$change_pass = false;
	
		$valid = true;
		
		if(isset($new_user) || isset($old_user) || isset($new_pass) || isset($new_pass2))
		{
			if (!empty($new_user) && !check_user($new_user)) 
				$change_user = true;
			
			elseif (check_user($new_user))
					$invalid_user = 'This username has already been taken.';
			
			if (empty($old_pass) || !valid_cred($user, $old_pass))
			{
				$invalid_pass = 'The password you entered was incorrect.';
				$valid = false;
			}
				
			if (!empty($new_pass) && strcmp($new_pass, $new_pass2) == 0)
				$change_pass = true;
			
			else
				$invalid_confirm = 'Your passwords didn\'t match.';
		
			
			if ($valid && ($change_user || $change_pass))
			{
				if($change_user)
				{
					$messages = '';
					change_user($user, $new_user);
					$messages[] = 'Successfully changed the user to '.$new_user.'.';
				}
				
				if($change_pass)
				{
					change_pass($user, $new_pass);
					$messages[] = 'Successfully changed the password.';
				}
			}
		}
		
		echo $twig->render('edit.html', array(
			'username' => $user,
			'email' => $email,
			'messages' => $messages,
			'logged_in' => true,
			'invalid_user' => $invalid_user,
			'invalid_pass' => $invalid_pass,
			'invalid_confirm' => $invalid_confirm));
		
	}
?>