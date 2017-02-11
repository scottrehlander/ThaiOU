<html>
<head>
</head>
<body>

	<?php
		require("DbDataAdapter.php");
		
		try
		{
			// Switch on the Command Get Param
			switch($_GET['processor'])
			{
				case "users":
					include ('./processors/usersProccessor.php' );
					break;
				case "groups":
					include ('./processors/groupsProcessor.php' );
					break;
				case "bills":
					include ('./processors/billsProcessor.php' );
					break;
				default:
					throw new Exception("processor not specified");
			}
		}
		catch(Exception $exception)
		{
			echo($exception);
		}
		
		
	?>

</body>

</html>