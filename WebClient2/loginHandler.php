<?php

	// Try to authenticate the user
	require("../DbDataAdapter.php");
	
	$userDa = new UsersTableDataAdapter();
	$result = $userDa->AuthenticateUser($_POST['username'], $_POST['password']);
	
	if($result > 0)
	{
		echo($result['UserId']);
	}
	else
	{
		echo("fail"); 
	}
?>