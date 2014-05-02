<?php
	require 'session.php';
	
	$time = date('m-d-Y H:i');
	$con = connect();
	$get_message = "SELECT * FROM future_emails WHERE future_emails.time <= '$time'";
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
	if(!empty($messages))
	{
		foreach($messages as $message)
		{
			imap_mail($message['email'], $message['subject'], $message['message']);
			remove_email($message['email_id']);
		}
	}

?>