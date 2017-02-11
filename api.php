<?php
	session_start();
	
	require("DbDataAdapter.php");
	
	try
	{
		// Switch on the Command Get Param
		switch($_GET['processor'])
		{
			case "users":
				include ('./processors/usersProccessor.php' );
				break;
			case "groups":
				include ('./processors/groupsProcessor.php' );
				break;
			case "bills":
				include ('./processors/billsProcessor.php' );
				break;
			default:
				throw new Exception("processor not specified");
		}
	}
	catch(Exception $exception)
	{
		echo($exception);
	}
	
	function checkUserLoggedIn($userId)
	{
		//if(!isset($_SESSION['userid']) || $_SESSION['userid'] != $userId)
		//	die("Not logged on. Session: " . $_SESSION['userid'] . " requested id " . $userId);	
	}
	
	function checkUserLoggedInWithoutId()
	{
		//if(!isset($_SESSION['userid']))
		//	die("Not logged on. Session is not set: " . $_SESSION['userid']);
		
		//if($_SESSION['userid'] <= 0)
		//	die("Not logged on. Session is less than 0: " . $_SESSION['userid']);
	}
	
	function setLogin($userid)
	{
		$_SESSION['userid'] = $userid;
	}
	
?>