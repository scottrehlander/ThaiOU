<?php
	session_start();
?>	

<html>
	
	
<?php	

	// Check that the user is logged in
	if(!isset($_SESSION["userId"]) || $_SESSION['userId'] < 0)
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=2\">";
		die();
	}	
?>

<?php $dataTheme="c"; ?>

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
 
	
	<!-- Overview Navigation Page -->
	<div data-role="page" id="overview" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header">
			<h1>Overview</h1>
			<a href="index.php" data-icon="alert" class="ui-btn-right">Log out</a>
		</div>
		
		<div data-role="content">
			<ul id="overviewList" data-role="listview" data-inset="true">
				<li id="overListDivier" data-role="list-divider">Main Navigation</li> 
				<li id="overviewWhoOwes"><a href="owedToMe.php" data-ajax="false">Bills Owed To Me</a></li>
				<li id="overviewWhoOwed"><a href="billsIOwe.php">Bills That I Owe</a></li>
				<li id="overviewGroup"><a href="groups.php">Groups</a></li>
			</ul>
			
			<?php
				if(isset($_GET['joinedGroup']))
				{
					require('../DbDataAdapter.php');
					$groupsDa = new GroupsTableAdapter();
					$group = $groupsDa->GetGroup($_GET['joinedGroup']);
					echo("<center>You have successfully joined the group \"" . stripcslashes($group['GroupName']) . "\"</center>");
				}
			?>
		</div>
	</div>
</body>
</html>
