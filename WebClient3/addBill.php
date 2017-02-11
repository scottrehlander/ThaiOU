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
			<a href="owedToMe.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Who Owes Me?</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false">
				<label>Username</label>
				<select name="userBillOwedBy">
					
					<?php 
						require('../DbDataAdapter.php');
					
						// Populate the select
						$groupsDa = new GroupsTableAdapter();
						$groupAffiliations = $groupsDa->GetAllGroupsAfilliated($_SESSION['userId']);
						
						$usersAdded = array();
						foreach($groupAffiliations['users'] as $groupAffiliation)
						{
							if(!in_array($groupAffiliation['UserId'], $usersAdded))
							{
								echo("<option value=" .$groupAffiliation['UserId'] . ">" . $groupAffiliation['UserFirstName'] . " " . $groupAffiliation['UserLastName'] . "</option>");			
								
								$usersAdded[] = $groupAffiliation['UserId'];
							}
						}
					?>
				
				</select>
				<label>Location</label>
				<input name="billLocation"></input>
				<label>Amount</label>
				<input name="billAmount"></input>
				<label>Notes</label>
				<TextArea name="billNotes"></TextArea>
				
				<input type="hidden" name="userBillOwedTo" value="<?php echo($_SESSION['userId']) ?>">
				<input type="hidden" name="action" value="addBill">
				
				<input type="submit" value="Submit" />
			</form>
		</div>
	</div>
</body>
</html>