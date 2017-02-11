<html>
<head>
</head>
<body>

	<?php
		require("DbDataAdapter.php");
		
		///// Users
		$userDa = new UsersTableDataAdapter();
		
		// Echo Users
		$result = $userDa->GetUsers();
		print_r(json_encode($result));
		
		echo("<br />");
		echo("<br />");
		
		
		///// Groups
		$groupsDa = new GroupsTableAdapter();
		
		// Echo all users in any affiliated group for user 1
		$result = $groupsDa->GetAllGroupsAfilliated(1);
		
		print_r(json_encode($result['users']));
		echo("<br />");
		echo("<br />");
		
		
		///// Bills
		$billsDa = new BillsTableAdapter();
		
		// Create a new bill that is owed to user 1 from user 4
		$billId = $billsDa->CreateBill("12.50", "Kansas City bbq", 1, 4, "Test Bill");
		echo("Bill created with id " . $billId . "<br/>");
		$billCreatedRow = $billsDa->GetBillById($billId);
		print_r(json_encode($billCreatedRow));
		echo("<br />");
		echo("<br />");
		
		// Echo any bills that pertain to user 1
		$result = $billsDa->GetBillsForUser(1);
		print_r(json_encode($result['owed']));
		echo("<br />");
		echo("<br />");
		print_r(json_encode($result['owes']));
		die();
	?>

</body>

</html>