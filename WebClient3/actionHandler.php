<?php
	session_start();
	
	require('../DbDataAdapter.php');
	
	$action = $_POST['action'];
	
	if($action == 'addBill')
	{
		$billsDa = new BillsTableAdapter();
		
		$retVal = $billsDa->CreateBill($_POST['billAmount'], $_POST['billLocation'], $_POST['userBillOwedTo'], $_POST['userBillOwedBy'], $_POST['billNotes']);
		
		if($retVal > 0)
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billCreate=1\">";
			die();
		}
		else
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billCreate=-1\">";
			die();
		}
	}
	else if ($action == 'deleteBill')
	{
		$billsDa = new BillsTableAdapter();
		
		$retVal = $billsDa->RemoveBill($_POST['billId']);
		if($retVal == 0)
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?&billRemoved=1\">";
			die();
		}
		else
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billRemoved=-1\">";
			die();
		}
	}
	else if($action == "createGroup")
	{
		$groupsDa = new GroupsTableAdapter();
		
		//function CreateGroup($creatorId, $groupName, $groupPin, $groupDescription)
		$retVal = $groupsDa->CreateGroup($_POST['groupCreator'], $_POST['groupCreateName'], $_POST['groupCreatePin'], $_POST['groupCreateDescription']);
		
		if(!isset($retVal) || !is_array($retVal) || !$retVal['Response'] == "OK")
		{
			// Fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?groupCreated=-1\">";
			die();
		}
		else
		{
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?groupCreated=1\">";
			die();
		}
	}
	else if ($action == "inviteUserToGroup")
	{
		require('../Emailer.php');
		
		$groupsDa = new GroupsTableAdapter();
		
		// Get the user name of the user who requested the invitation
		$userDa = new UsersTableDataAdapter();
		$user = $userDa->GetUser($_POST['userInviting']);
		
		$group = $groupsDa->GetGroup($_POST['inviteUserToGroupGroup']);
		
		//$message = $_POST['inviteUserToGroupMessage'];
		$message = "";
		
		// Send email
		$response = Emailer::SendGroupInviteEmail($user['UserFirstName'] . ' ' . $user['UserLastName'], $_POST['inviteUserToGroupEmail'], $_POST['inviteUserToGroupGroup'], $group['GroupName'], $message);
		
		// Check the response
		if($response == "Mail sent")
		{
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?userInvited=1\">";
			die();
		}
		else
		{
			// Fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?userInvited=-1\">";
			die();
		}
	}
	else if ($action == 'registerUser')
	{
		require('../Emailer.php');
		
		$userDa = new UsersTableDataAdapter();
		$userId = $userDa->RegisterUser($_POST['registerUserUsername'], $_POST['registerUserPin'], $_POST['registerUserEmail'], $_POST['registerUserFirstName'], $_POST['registerUserLastName']);
	
		
		if($userId == -1)
		{
			//die("user: " . $userId);
		
			// Fail user not created
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=-1\">";
			die();
		}
		
		// Grab the user so we can calculate the confirmation code
		$userRow = $userDa->GetUser($userId);
		
		// Send email
		$confCode = Emailer::CreateConfirmationCode($userRow['UserCreatedDate']);
		$wasMailSent = Emailer::SendConfirmationEmail($userRow['UserId'], $userRow['UserEmail'], $confCode);
		
		if($wasMailSent == "Mail sent")
		{
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=1\">";
			die();
		}
		else
		{
			$userDa->DeleteUser($userRow['UserId']);
			
			// Fail email not sent
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=-2\">";
			die();
		}
	}
	
	
	
	
	
	
?>