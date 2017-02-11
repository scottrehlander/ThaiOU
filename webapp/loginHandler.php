<?php

	// Try to authenticate the user
	require("../DbDataAdapter.php");
	
	$userDa = new UsersTableDataAdapter();
	$result = $userDa->AuthenticateUser($_POST['username'], $_POST['password']);
	
	if($result < 0)
	{
		//require("index.php&err=loginFailed");
		
		/* Redirect to a different page in the current directory that was requested */
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'index.php?err=loginError';
		header("Location: http://$host$uri/$extra");
	}
	else
	{
		require("home.php");
	}
?>