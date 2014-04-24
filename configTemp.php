<?php
/**
 * This contains information we would not like to be out in the open. 
 *Fill out the information so it will be used in the other files correctly.
 */

	$options = [
	    'cost' => 'CHOOSE COST OF SALTING HERE',
	    'salt' => 'CHOOSE METHOD OF SALTING HERE',
	];
	return array(
	    'host' => 'PUT HOST NAME HERE TO CONNECT TO',
	    'user' => 'PUT DATABASE USERNAME HERE',
	    'pass' => 'PUT DATABASE PASSWORD HERE',
	    'database' => 'PUT DATABASE NAME HERE',
	    'options' => $options,
	    'email_pass' => 'PUT EMAIL PASS HERE',
	);

?>