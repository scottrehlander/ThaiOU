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
			<ul id="billsIOweList" data-role="listview" data-inset="true" data-filter="true">
				<li id="billsIOweListDivider" data-role="list-divider">Bills</li>
				
				<?php
					// Grab the bills I Owe 
					require("../DbDataAdapter.php");
	
					$billsDa = new BillsTableAdapter();
					$bills = $billsDa->GetBillsForUser($_SESSION['userId']);
					
					$usersDa = new UsersTableDataAdapter();
					$usersRetreived = array();
										
					foreach($bills['owes'] as $bill)
					{
						if(!array_key_exists($bill['UserBillOwedTo'], $usersRetreived))
						{
							$userInfo = $usersDa->GetUser($bill['UserBillOwedTo']);
							$usersRetreived[$bill['UserBillOwedTo']] = $userInfo;
							
							echo('<li><a href="userBillsIOwe.php?userIOweId=' . $bill['UserBillOwedTo'] .'">' . $usersRetreived[$bill['UserBillOwedTo']]["UserFirstName"] . ' ');
							echo($usersRetreived[$bill['UserBillOwedTo']]["UserLastName"] . '</a></li>'); 
						}
					}
				?>
				
			</ul>
		</div>
	</div>
</body>
</html>