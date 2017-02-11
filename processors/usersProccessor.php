<?php

	// Send email
	require("Emailer.php");
			
	$userDa = new UsersTableDataAdapter();
		
	if(!isset($_GET['command']))
		throw new Exception("command not specified in users processor");
		
	// Users command processor
	switch($_GET['command'])
	{
		case "testEmail":
		
			$userRow = $userDa->GetUser(1);
			SendConfirmationCode($userRow);
			
			break;
		case "inviteUsersToLunch":
			if(!isset($_POST['invitorId']))
				throw new Exception("invitorId not specified for inviteUsersToLunch");
			if(!isset($_POST['location']))
				throw new Exception("location not specified for inviteUsersToLunch");
			if(!isset($_POST['date']))
				throw new Exception("date not specified for inviteUsersToLunch");
			if(!isset($_POST['time']))
				throw new Exception("time not specified for inviteUsersToLunch");
			if(!isset($_POST['usersToInvite']))
				throw new Exception("usersToInvite not specified for inviteUsersToLunch");
			
			// Grab the info for users involved
			$invitorRow = $userDa->GetUser($_POST['invitorId']);
			
			$users = array();
			$userIds = explode(";", $_POST['usersToInvite']);
			foreach($userIds as $userId)
			{
				$users[] = $userDa->GetUser($userId);
			}
			
			$result = Emailer::SendLunchInvitation($invitorRow, $users, $_POST['location'], $_POST['date'], $_POST['time']);
			
			echo($result);
			break;
		case "resendConfirmationCode":
			if(!isset($_GET['userId']))
				throw new Exception("userId not specified in users processor - confirmRegistration");
			if(!isset($_POST['usersToInvite']))
				throw new Exception("username not specified for registerUser");
				
			$userRow = $userDa->GetUser($_GET['userId']);
			SendConfirmationCode($userRow);
			
			break;
		case "confirmRegistration":
			if(!isset($_GET['userId']))
				throw new Exception("userId not specified in users processor - confirmRegistration");
			if(!isset($_GET['confirmationCode']))
				throw new Exception("confirmationCode not specified in users processor - confirmRegistration");
				
			$userRow = $userDa->GetUser($_GET['userId']);
			
			$confCode = Emailer::CreateConfirmationCode($userRow['UserCreatedDate']);
			
			if($confCode == $_GET['confirmationCode'])
			{
				$userDa->SetUserToActivated($userRow['UserId']);
			}
			else
			{
				die("Invalid confirmation code - " . $confCode . " " . $_GET['confirmationCode']);
			}
			
			echo("You have successfully activated your Lunch on Me account.  You may now login and start using the app!");
			echo("<br /><br />Click <a href=\"http://rehlander.com/tou/WebClient3\">here</a> to get started!");
			die();
		case "getAllUsers":
			$result = $userDa->GetUsers();
			echo(json_encode($result));
			
			break;
		case "authenticateUser":
			if(!isset($_GET['username']))
				throw new Exception("username not specified in users processor - autenticateUser");
			if(!isset($_GET['password']))
				throw new Exception("password not specified in users processor - autenticateUser");
				
			$userId = $userDa->AuthenticateUser($_GET['username'], $_GET['password']);
			
			setLogin($userId);
			
			// No need for JSON for just an int.
			echo $userId;
			
			break;
		case "registerUser":
			if(!isset($_POST['username']))
				throw new Exception("username not specified for registerUser");
			if(!isset($_POST['pin']))
				throw new Exception("pin not specified for registerUser");
			if(!isset($_POST['email']))
				throw new Exception("email not specified for registerUser");
			if(!isset($_POST['firstName']))
				throw new Exception("firstName not specified for registerUser");
			if(!isset($_POST['lastName']))
				throw new Exception("lastName not specified for registerUser");
			
			$userId = $userDa->RegisterUser($_POST['username'], $_POST['pin'], $_POST['email'], $_POST['firstName'], $_POST['lastName']);
	
			if($userId == -1)
				die("-1");
			
			// Grab the user so we can calculate the confirmation code
			$userRow = $userDa->GetUser($userId);
			
			// Send email
			$wasMailSent = SendConfirmationCode($userRow);
			
			if($wasMailSent == "Mail sent")
			{
				die($userRow['UserId']);
			}
			
			// Registered the user, but the mail could not be sent
			die("-2");
			
			break;
		default:
			throw new Exception("command not recognized in users processor");
	}
	
	function SendConfirmationCode($userRow)
	{	
		$confCode = Emailer::CreateConfirmationCode($userRow['UserCreatedDate']);
		
		// Send email
		return Emailer::SendConfirmationEmail($userRow['UserId'], $userRow['UserEmail'], $confCode);
	}
	
	
?>