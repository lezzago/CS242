<?php
	/*
	This deletes the emails you choose to delete from the ones you have made. 
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
		$con = connect();
		$count = $_POST['count'];
		for($i = 0; $i < $count; $i++)
		{
			if(!empty($_POST["$i"]))
				remove_email($_POST["$i"]);
		}
		$get_message = "SELECT * FROM future_emails WHERE future_emails.user_name <= '$user'";
		if ($result = mysqli_query($con, $get_message)) 
		{
			if(mysqli_num_rows($result) > 0)
			{
				while($data = mysqli_fetch_assoc($result))
					$messages[] = $data;
			}
			mysqli_free_result($result);
			
		}
		mysqli_close($con);
		$size = count($messages);
		
		echo $twig->render('deleteEmails.html', array('messages' => $messages,
								'message_size' => $size));
	}
?>