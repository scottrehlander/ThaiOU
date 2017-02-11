<?php 
	session_start();
	$dataTheme="c"; 
?>

<html>

<head>
	<meta charset="utf-8" http-equiv="X-UA-Compatible" content="IE=9" />
	<!--[if IE 7]><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><![endif]-->
	<!--[if IE]>
	
	    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	    <style type="text/css">
	        .clear {
	            zoom: 1;
	            display: none;
	        }
	    </style>
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
	
	<script type="text/javascript">
	
		$(document).ready(function() {		   
			
		});
		
	</script>
</head>

<body>
	<div data-role="page" id="page" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header">
			<a href="overview.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<ul id="groupsOverviewPageList" data-role="listview" data-inset="true" data-filter="true">
				<li id="groupsOverviewPageDivider" data-role="list-divider">Groups</li>
				
				<?php
					require('../DbDataAdapter.php');
				
					$groupsDa = new GroupsTableAdapter();
					$groupAffiliations = $groupsDa->GetAllGroupsAfilliated($_SESSION['userId']);
					
					foreach($groupAffiliations["groups"] as $group)
					{
						echo('<li><a href="groupMembers.php?groupId=' . $group['GroupId'] . '">' . stripcslashes($group["GroupName"]) . '</a></li>');
					}					
				?>
				
			</ul>
			
			<?php
				// Action response handling
				if(isset($_GET['groupCreated']))
				{
					if($_GET['groupCreated'] < 0)
					{
						// Group creation failed
						if($_GET['groupCreated'] == -1)
						{
							echo("Failed to create group.  Please try again.");
						}
					}
					else
					{
						echo("Group created.");	
					}
				}
				
				if(isset($_GET['userInvited']))
				{
					if($_GET['userInvited'] < 0)
					{
						if($_GET['userInvited'] == -1)
						{
							echo("Failed to send email to user.  Please check the address and try again.");
						}
					}
					else 
					{
						echo("Group invitation sent.");
					}
				}
			?>
			
			<ul id="groupsOverviewPageList" data-role="listview" data-inset="true" >
				<li id="groupsOverviewPageDivider" data-role="list-divider">Actions</li>
				<li><a href="createGroup.php" >Create Group</a></li>
				<li><a href="inviteUserToGroup.php" >Invite User to Group</a></li>
			</ul>
		</div>
	</div>
</body>
</html>