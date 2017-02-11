<?php
	// Send email
	require("Emailer.php");
			
	$groupsDa = new GroupsTableAdapter();
		
	if(!isset($_GET['command']))
		throw new Exception("command not specified in groups processor");
		
	// Groups command processor
	switch($_GET['command'])
	{
		case "getAllGroupsAffiliated":
			if(!isset($_GET['userId']))
				throw new Exception("userId not specified for getAllGroupsAffiliated command.");
			
			checkUserLoggedIn($_GET['userId']);
						
			$result = $groupsDa->GetAllGroupsAfilliated($_GET['userId']);
			echo(json_encode($result));
		
			break;
		case "createGroup":
			if(!isset($_POST['creatorId']))
				throw new Exception("creatorId not specified for createGroup");
			if(!isset($_POST['groupName']))
				throw new Exception("groupName not specified for createGroup");
			if(!isset($_POST['groupPin']))
				throw new Exception("groupPin not specified for createGroup");
			if(!isset($_POST['groupDescription']))
				throw new Exception("groupDescription not specified for createGroup");
				
			$result = $groupsDa->CreateGroup($_POST['creatorId'], $_POST['groupName'], $_POST['groupPin'], $_POST['groupDescription']);
			echo(json_encode($result));
			
			break;
		case "inviteToGroup":
			// Send an email to the user asking them to accept the invite
			if(!isset($_POST['email']))
				throw new Exception("email not specified for inviteToGroup");
			if(!isset($_POST['groupId']))
				throw new Exception("groupId not specified for inviteToGroup");
			if(!isset($_POST['message']))
				throw new Exception("message not specified for inviteToGroup");
			if(!isset($_POST['from']))
				throw new Exception("from not specified for inviteToGroup");
			
			// Get the user name of the user who requested the invitation
			$userDa = new UsersTableDataAdapter();
			$user = $userDa->GetUser($_POST['from']);
			
			$group = $groupsDa->GetGroup($_POST['groupId']);
			
			// Send email
			$response = Emailer::SendGroupInviteEmail($user['UserFirstName'] . ' ' . $user['UserLastName'], $_POST['email'], $_POST['groupId'], $group['GroupName'], $_POST['message']);
			
			echo($response);
			
			break;
		default:
			throw new Exception("command not recognized in groups processor");
	}
	
?>