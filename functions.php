<?php
	// sanitize the data
	function sanitize($input) 
	{
		if(is_array($input)):
			foreach($input as $key=>$value):
				$result[$key] = sanitize($value);
			endforeach;
		else:
			$result = htmlentities($input, ENT_QUOTES, 'UTF-8');
		endif;

		return $result;
	}


	function isEmailExists($db, $tableName, $email) 
	{
		// SQL Statement
		$sql = "SELECT * FROM ".$tableName." WHERE email='".$email."'";

		// Process the query
		$results = $db->query($sql);

		// Fetch Associative array
		$row = $results->fetch_assoc();

		// Check if there is a result and response to  1 if email is existing
		return (is_array($row) && count($row)>0);
	}

	function isEmailValid($email) 
	{
		return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
	}

?>