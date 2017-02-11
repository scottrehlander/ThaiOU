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
			<a href="index.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Register</h1>
		</div>
		
		<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false">
				<label>Username</label>
				<input name="registerUserUsername"></input>
				<label>PIN</label>
				<input name="registerUserPin"></input>
				<label>Email</label>
				<input name="registerUserEmail"></input>
				<label>First Name</label>
				<input name="registerUserFirstName"></input>
				<label>Last Name</label>
				<input name="registerUserLastName"></input>
				
				<input type="submit" value="Submit"  />
				
				<input type="hidden" name="action" value="registerUser"  />
			</form>
	</div>
</body>
</html>