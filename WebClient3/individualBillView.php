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
			<a href="<?php echo($_GET['returnTo'] . '.php?' . $_GET['withParamKey'] . '=' . $_GET['withParamVal']); ?>" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<ul id="billViewList" data-role="listview" data-inset="true">
				<li id="billViewListDivider" data-role="list-divider">Bill</li>
				
				<?php
				
					// Grab the bill from the id and show it
					require('../DbDataAdapter.php');
				
					$billsDa = new BillsTableAdapter();
					$billInfo = $billsDa->GetBillById($_GET['billId']);
					
					echo('<li>Bill Date: ' . date('m-d-Y', strtotime($billInfo['BillDate'])) .'</li>');
					echo('<li>Location: ' . stripcslashes($billInfo['BillLocation']) .'</li>');
					echo('<li>Amount: ' . stripcslashes($billInfo['BillAmount']) .'</li>');			
				?>
				
			</ul>
			
			<?php
				// Show the Delete Bill button if the user is owed this bill
				if(isset($billInfo) && $billInfo['UserBillOwedTo'] == $_SESSION['userId'])
				{
					echo('<form action="actionHandler.php" method="post" data-ajax="false">');
						echo('<input id="billDelete" type="submit" value="Delete Bill" data-theme="e" />');
						
						echo('<input name="billId" type="hidden" value="' . $billInfo['BillId'] . '" data-theme="e" />');
						echo('<input name="action" type="hidden" value="deleteBill" data-theme="e" />');
					echo('</form>'); 
				}				
			?>
		</div>
	</div>
</body>
</html>