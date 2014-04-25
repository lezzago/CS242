<?php
	require 'sendEmail.php';
	
	$configs = include('config.php');


	/**
	* This connects use to the server and then to the required database
	*
	* @return       It returns the connection to the server.
	*/
	function connect()
	{
		$con = mysqli_connect($GLOBALS['configs']['host'], $GLOBALS['configs']['user'], $GLOBALS['configs']['pass']);
		if (mysqli_connect_errno()) {
			die('Could not connect: ' . mysqli_connect_errno());
		}
		mysqli_select_db($con, $GLOBALS['configs']['database']);
		return $con;
	}
	
	/**
	* This tells us if the given username is a user in the database
	*
	* @param $user 	string This is the name of the username we are checking for
	* @return int      It returns 1 if the user exists and 0 if they don't.
	*/
	function check_user($user)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$get_user = "SELECT user_id FROM user_accounts WHERE user_accounts.user_name = '$user'";
		$result = mysqli_query($con, $get_user);
		$ret_val = (mysqli_num_rows($result) > 0);
		mysqli_close($con);
		return $ret_val;
	}
	
	/**
	* This tells us if the given email is already associated to an user account
	*
	* @param $email 	string This is the name of the email we are checking for
	* @return String      It returns the email if it exists or then it is NULL.
	*/
	function check_email($email)
	{
		$con = connect();
		$email = mysqli_real_escape_string($con, $email);
		$get_email = "SELECT * FROM user_accounts WHERE user_accounts.email = '$email'";
		if ($result = mysqli_query($con, $get_email)) {
			if (mysqli_num_rows($result) > 0 && $data = mysqli_fetch_assoc($result))
			{
				$ret_email = $data['email'];
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		return $ret_email;
	}
	
	/**
	* This gives us the email for the given user
	*
	* @param $user 	string This is user for whom we want the email
	* @return String      It returns the email of the given user.
	*/
	function get_email($user)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$get_email = "SELECT * FROM user_accounts WHERE user_accounts.user_name = '$user'";
		if ($result = mysqli_query($con, $get_email)) {
			if (mysqli_num_rows($result) > 0)
			{
				if ($data = mysqli_fetch_assoc($result))
					$email = $data['email'];
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		return $email;
	}
	
	/**
	* This adds a user to the database with the given information
	*
	* @param $user 	string This is the name of the user we are adding
	*/
	function add_user($user)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$get_user = "SELECT * FROM email_verify WHERE email_verify.user_name = '$user'";
		if ($result = mysqli_query($con, $get_user)) {
			if (mysqli_num_rows($result) > 0 && $data = mysqli_fetch_assoc($result))
			{
				$account = $data;
			}
			mysqli_free_result($result);
		}
		$email = $account['email'];
		$pass = $account['password'];
		$insert_user = "INSERT INTO user_accounts (user_name, password, email) VALUES ('$user', '$pass', '$email')";
		mysqli_query($con, $insert_user);
		mysqli_close($con);
	}

	/**
	* This adds an email to the database to which it will send an email at the given time
	*
	* @param $user  	string This is the user who wants to send the email
	* @param $email 	string This is the email address that we are sending an email to
	* @param $subject 	string This is the email subject of the user we are adding
	* @param $message 	string This is the email message of the user we are adding
	* @param $time  	string This is the time the email will be sent
	*/
	function add_email($user, $email, $subject, $message, $time)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$subject = mysqli_real_escape_string($con, $subject);
		$email = mysqli_real_escape_string($con, $email);
		$message = mysqli_real_escape_string($con, $message);
		$time = mysqli_real_escape_string($con, $time);
		$insert_message = "INSERT INTO future_emails (user_name, email, subject, message, time) VALUES ('$user', '$email', '$subject', '$message', '$time')";
		mysqli_query($con, $insert_message);
		mysqli_close($con);
	}
	
	/**
	* This deletes an email to the database to which it will send an email at the given time
	*
	* @param $email_id 	string This is the id of the email we are deleting
	*/
	function remove_email($email_id)
	{
		$con = connect();
		$email_id = mysqli_real_escape_string($con, $email_id);
		$remove_message = "DELETE FROM future_emails  WHERE future_emails.email_id = '$email_id'";
		mysqli_query($con, $remove_message);
		mysqli_close($con);
	}
	
	/**
	* This adds a verification to the database, so the user can confirm the email
	*
	* @param $user 	string This is the name of the user we are adding
	* @param $pass 	string This is the password of the user we are adding
	* @param $email 	string This is the email of the user we are adding
	*/
	function add_verify($user, $pass, $email)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$email = mysqli_real_escape_string($con, $email);
		$pass = mysqli_real_escape_string($con, $pass);
		$pass = password_hash($pass, PASSWORD_BCRYPT, $GLOBALS['configs']['options']);
		$hash = password_hash($user, PASSWORD_BCRYPT, $GLOBALS['configs']['options']);
		$insert_user = "INSERT INTO email_verify (user_name, hash, password, email) VALUES ('$user', '$hash', '$pass', '$email')";
		$result = mysqli_query($con, $insert_user);
		mysqli_close($con);
		send_conf($user, $email, $hash);
	}
	
	/**
	* This changes the user's password
	*
	* @param $user 	string This is the name of the user we are changing
	* @param $pass 	string This is the password of the user we are adding
	*/
	function change_pass($user, $pass)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$pass = mysqli_real_escape_string($con, $pass);
		$pass = password_hash($pass, PASSWORD_BCRYPT, $GLOBALS['configs']['options']);
		$update_pass = "UPDATE user_accounts SET user_accounts.password = '$pass' WHERE user_accounts.user_name ='$user'";
		$result = mysqli_query($con, $update_pass);
		mysqli_close($con);
	}
	
	/**
	* This changes the user's username
	*
	* @param $old_user 	string This is the name of the user we are changing
	* @param $new_user 	string This is the new username we want
	*/
	function change_user($old_user, $new_user)
	{
		$con = connect();
		$old_user= mysqli_real_escape_string($con, $old_user);
		$new_user= mysqli_real_escape_string($con, $new_user);
		$update_user = "UPDATE user_accounts SET user_accounts.user_name = '$new_user' WHERE user_accounts.user_name ='$old_user'";
		$result = mysqli_query($con, $update_user);
		mysqli_close($con);
	}
	
	/**
	* This deletes the verification entry we had because the user has been verified
	*
	* @param $user 	string This is the name of the user we are removing
	* @param $hash 	string This is the hash of the user we are removing
	*/
	function delete_verify($user, $hash)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$hash = mysqli_real_escape_string($con, $hash);
		$delete_user = "DELETE FROM email_verify WHERE email_verify.user_name = '$user' AND email_verify.hash = '$hash'";
		$result = mysqli_query($con, $delete_user);
		mysqli_close($con);
	}
	
	/**
	* This tells us if the given username and password match one account in the database
	*
	* @param $user 	string This is the name of the username we are checking for
	* @param $pass 	string This is the password we are checking for
	* @return int      It returns 1 if the account exists and 0 if they don't.
	*/
	function valid_cred($user, $pass)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$pass = mysqli_real_escape_string($con, $pass);
		$login = FALSE;
		$get_user = "SELECT * FROM user_accounts WHERE user_accounts.user_name = '$user'";
		if ($result = mysqli_query($con, $get_user)) {
			if (mysqli_num_rows($result) > 0 && $data = mysqli_fetch_assoc($result))
			{
				$hash = $data['password'];
				if (password_verify($pass, $hash)) {
				    $login = TRUE;
				}
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		return $login;
	}
	
	/**
	* This tells us if the given username and password match one account in the database
	*
	* @param $user 	string This is the name of the username we are checking for
	* @param $email 	string This is the email of the username we are checking for
	* @param $hash 	string This is the hash of the username we are checking for
	* @return Array      It returns the entry that has the given values.
	*/
	function check_conf($user, $email, $hash)
	{
		$con = connect();
		$user = mysqli_real_escape_string($con, $user);
		$email = mysqli_real_escape_string($con, $email);
		$hash = mysqli_real_escape_string($con, $hash);
		$get_info = "SELECT * FROM email_verify WHERE email_verify.user_name = '$user' AND email_verify.email = '$email' AND email_verify.hash = '$hash'";
		if ($result = mysqli_query($con, $get_info)) {
			if (mysqli_num_rows($result) > 0 && $data = mysqli_fetch_assoc($result))
			{
				$account = $data;
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		return $account;
	}
?>