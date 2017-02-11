<?php
	
	$billsDa = new BillsTableAdapter();
		
	if(!isset($_GET['command']))
		throw new Exception("command not specified in groups processor");
		
	// Bills command processor
	switch($_GET['command'])
	{
		case "getBillsForUser":
			// Returns an associative array of what the user owes and is owed
			
			checkUserLoggedIn($_GET['userId']);
			
			if(!isset($_GET['userId']))
				throw new Exception("userId not specified for getBillsForUser command.");
				
			$result = $billsDa->GetBillsForUser($_GET['userId']);
			echo(json_encode($result));
		
			break;
		case "getBillById":
			// Returns billId of the bill created
			
			if(!isset($_GET['billId']))
				throw new Exception("billId not specified for getBillById command.");
			
			$result = $billsDa->GetBillById($_GET['billId']);
			echo(json_encode($result));
		
			break;
		case "createBill":
			if(!isset($_POST['billAmount']))
				throw new Exception("billAmount not specified for createBill command.");
			if(!isset($_POST['billLocation']))
				throw new Exception("billLocation not specified for createBill command.");
			if(!isset($_POST['userBillOwedTo']))
				throw new Exception("userBillOwedTo not specified for createBill command.");
			if(!isset($_POST['userBillOwedBy']))
				throw new Exception("userBillOwedBy not specified for createBill command.");
			if(!isset($_POST['billNotes']))
				throw new Exception("billNotes not specified for createBill command.");
				
			checkUserLoggedIn($_POST['userBillOwedTo']);
							
			$result = $billsDa->CreateBill($_POST['billAmount'], $_POST['billLocation'], $_POST['userBillOwedTo'], $_POST['userBillOwedBy'], $_POST['billNotes']);
			echo($result);
			
			break;
		case "removeBill":
			if(!isset($_GET['billId']))
				throw new Exception("billId not specified for removeBill command.");
			
			checkUserLoggedInWithoutId();
			
			$result = $billsDa->RemoveBill($_GET['billId']);

			echo($_GET['billId']);
			break;
		default:
			throw new Exception("command not recognized in users processor");
	}
	
?>