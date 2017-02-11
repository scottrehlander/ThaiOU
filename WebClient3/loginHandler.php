<?php

	session_start();
	
	require("api/DbDataAdapter.php");
	
	if(isset($_POST['username']))
	{
		// attempt a login
		$usersDa = new UsersTableDataAdapter();
		$userId = $usersDa->AuthenticateUser($_POST['username'], $_POST['password']);
			
		if($userId < 0)
		{
			setcookie("username", $_POST['username'], time()+3600*2400);
			setcookie("password", "", time()+3600*2400);
			
			// redirect to index.php with fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=1\">";
			die();
		}
		
		$_SESSION['userId'] = $userId;
		
		setcookie("username", $_POST['username'], time()+3600*2400);
		setcookie("password", $_POST['password'], time()+3600*2400);
				
		if(isset($_POST['groupId']) && $_POST['groupId'] != "")
		{
			if($userId == -1)
			{
				// redirect to index.php with fail
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=1&groupId=" . $_POST['groupId'] . "\">";
				die();
			}
			else
			{
				// Success, join the group
				$groupsDa = new GroupsTableAdapter();
				$groupsDa->AddGroupAffiliation($userId, $_POST['groupId']);
				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php?joinedGroup=" . $_POST['groupId'] . "\">";
				die();
			}
		}
		
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php\">";
		die();
	}
	
?>