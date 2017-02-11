<?php

	class BaseDataAdapter
	{
		protected $pdo;
		private $dbServer;
		private $dbName;
		private $dbUser;
		private $dbPass;
		
		function __construct()
		{
			//$this->dbServer = 'localhost:3306';
			//$this->dbName = 'lunchonme';
			//$this->dbUser = 'root';
			//$this->dbPass = '';
			
			// When site is live
			$this->dbServer = 'db2917.perfora.net';
			$this->dbName = 'db364639291';
			$this->dbUser = 'dbo364639291';
			$this->dbPass = 'lunchonme123';
			
			// Production
			//$this->dbServer = 'localhost';
			//$this->dbName = 'db313001608';
			//$this->dbUser = 'dbo313001608';
			//$this->dbPass = 'dbo313001608';
		}

		function ExecuteQuery($sql)
		{			
			if(empty($this->pdo))
			{
				$connectDb = "mysql:host=" . $this->dbServer . ";dbname=" . $this->dbName;
				$this->pdo = new pdo($connectDb, $this->dbUser, $this->dbPass);
			}
			
			return $this->pdo->query($sql);
		}
		
		// We want to pass in the query and an associative array of variables
		//  Bind the variables and then execute the query.
		function ExecutePreparedQuery($sql, $vars)
		{
			if(empty($this->pdo))
			{
				$connectDb = "mysql:host=" . $this->dbServer . ";dbname=" . $this->dbName;
				//$connectDb = "mysql:unix_socket=/tmp/mysql5.sock;dbname=" . $this->dbName;
				$this->pdo = new pdo($connectDb, $this->dbUser, $this->dbPass);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			
			try
			{
				$query = $this->pdo->prepare($sql);
			}
			catch(Exception $e)
			{
				echo("<b><font color=\"red\">Error executing sql query:</font><br><br>");
				print_r($e->getMessage());
				die();
			}
			if($query)
				$query->execute($vars);
			else
			{
				print_r($this->pdo->errorInfo);
				die();
			}
			
			try
			{
				return $query->fetchAll(PDO::FETCH_ASSOC);
			}
			catch(Exception $e)
			{	// An insert will throw, this is crappy and should probably be handled better
				return "";
			}
		}
		
		function ExecuteUpdateQuery($sql)
		{
			$connectDb = "mysql:host=" . $this->dbServer . ";dbname=" . $this->dbName;
			
			if(empty($pdo))
				$pdo = new pdo($connectDb, $this->dbUser, $this->dbPass);
			
			try
			{
				$pdo->beginTransaction();
				$pdo->query($sql);
				$pdo->commit();
			}
			catch(Exception $ex) { $pdo->rollbackTransaction(); }
		}
		
		function ExecutePreparedUpdateQuery($sql, $vars)
		{
			$connectDb = "mysql:host=" . $this->dbServer . ";dbname=" . $this->dbName;
			//$connectDb = "mysql:unix_socket=/tmp/mysql5.sock;dbname=" . $this->dbName;
			
			if(empty($pdo))
				$pdo = new pdo($connectDb, $this->dbUser, $this->dbPass);
			
			try
			{
				$pdo->beginTransaction();
				$query = $pdo->prepare($sql);

				$query->Execute($vars);
				$pdo->commit();
			}
			catch(Exception $ex) { print_r($ex); die(); $pdo->rollbackTransaction(); }
		}
	
		function GetLastInsertId()
		{
			$sql = "SELECT LAST_INSERT_ID()";
			$result = $this->ExecuteQuery($sql);
			
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
			$result = $result[0];
			return $result['LAST_INSERT_ID()'];
		}
	
	}

	class UsersTableDataAdapter extends BaseDataAdapter
	{
		// Returns a list of User DataRows 
		function GetUsers()
		{
			$sql = "SELECT * from users";
			$varsArray = array ();
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			return $result;
		}
		
		function AuthenticateUser($username, $password)
		{
			$userId = -1;
			
			$sql = "SELECT * FROM users WHERE users.Username = :username AND users.UserPassword = :password";
			$varsArray = array ( ':username'=>$username, ':password'=>$password );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			// If we get in this loop, we got one result that is the user - if not, we leave userId to default -1
			foreach($result as $row)
			{
				$userId = $row['UserId'];
			}
			
			return $userId;
		}
		
		// Returns -1 if the user already exists
		function RegisterUser($username, $pin, $email, $firstName, $lastName)
		{
			$userId = -1;
			
			if($this->UserExists($username) == true)
			{
				return -1;
			}
			
			$sql = "INSERT INTO users (UserName, UserPassword, UserEmail, UserFirstName, UserLastName, UserVerified) ";
			$sql .= "VALUES (:username, :pin, :email, :firstName, :lastName, :verified)";
	
			$varsArray = array ( ':username'=>$username, ':pin'=>$pin, ':email'=>$email, ':firstName'=>$firstName, ':lastName'=>$lastName, ':verified'=>0 );
			
			$this->ExecutePreparedQuery($sql, $varsArray);
			
			return $this->GetLastInsertId();
		}

		function DeleteUser($userId)
		{
			$sql = "DELETE FROM users WHERE UserId = :userId";
			$varsArray = array ( ':userId'=>$userId );
			
			$this->ExecutePreparedQuery($sql, $varsArray);
		}
		
		function SetUserToActivated($userId)
		{
			$sql = "UPDATE users set users.UserVerified = :isVerified WHERE users.UserId = :userId";
			$varsArray = array( ':isVerified'=>1, ':userId'=>$userId );
			
			$this->ExecutePreparedQuery($sql, $varsArray);
		}
		
		function GetUser($userId)
		{
			$sql = "SELECT * FROM users WHERE users.UserId = :userId";
			$varsArray = array( ':userId'=>$userId );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			foreach($result as $row)
			{
				return $row;
			}
			
			return null;
		}
		
		function UserExists($username)
		{
			$sql = "SELECT * FROM users WHERE users.Username = :username";
			$varsArray = array ( ':username'=>$username );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			foreach($result as $row)
			{
				return true;
			}
			
			return false;
		}
		
	}
	
	class BillsTableAdapter extends BaseDataAdapter
	{
		// Returns a new ass array of 'owed' and 'owes' given a user id
		function GetBillsForUser($userId)
		{
			$sql = "SELECT * FROM bills WHERE (bills.UserBillOwedTo = :userId OR bills.UserBillOwedBy = :userId) AND bills.BillArchived <> 1 ORDER BY bills.BillDate";
			
			$varsArray = array(':userId'=>$userId);
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			$returnVal = array();
			$returnVal = array('owed'=>array(), 'owes'=>array());
			
			foreach($result as $row)
			{
				if($row['UserBillOwedTo'] == $userId)
				{
					$returnVal['owed'][] = $row;
				}
				else
				{
					$returnVal['owes'][] = $row;
				}
			}
			
			return $returnVal;
		}
		
		// Returns the bill datarow, otherwise an empty string
		function GetBillById($billId)
		{
			$sql = "SELECT * FROM bills WHERE bills.BillId = :billId";
			$varsArray = array(':billId'=>$billId);
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			foreach($result as $row)
			{
				return $row;
			}
			
			return "";
		}
		
		// Returns 0 on success
		function RemoveBill($billId)
		{
			$sql = "UPDATE bills SET bills.BillArchived = 1 WHERE bills.BillId = :billId";
			$varsArray = array( ':billId'=>$billId );
			$this->ExecutePreparedQuery($sql, $varsArray);
			
			return 0;
		}
		
		// Returns the billId of the bill that is the created
		function CreateBill($billAmount, $billLocation, $userBillOwedTo, $userBillOwedBy, $billNotes)
		{
			$sql = "INSERT INTO bills (BillAmount, BillLocation, UserBillOwedTo, UserBillOwedBy, BillNotes) ";
			$sql .= "VALUES (:billAmount, :billLocation, :userBillOwedTo, :userBillOwedBy, :billNotes)";
			$varsArray = array(
				':billAmount'=>$billAmount,
				':billLocation'=>$billLocation, 
				':userBillOwedTo'=>$userBillOwedTo,
				':userBillOwedBy'=>$userBillOwedBy, 
				':billNotes'=>$billNotes
			);
			
			$this->ExecutePreparedQuery($sql, $varsArray);
			
			$billId = $this->GetLastInsertId();
			return $billId;
		}
	}
	
	class GroupsTableAdapter extends BaseDataAdapter
	{
	
		function GetGroup($groupId)
		{	
			$sql = "SELECT * FROM groups WHERE groups.GroupId = :groupId";
			$varsArray = array( ':groupId'=>$groupId );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			foreach($result as $row)
			{
				return $row;
			}
			
			return null;
		}
		
		// Returns an ass array of 'GroupId, 'response'
		function CreateGroup($creatorId, $groupName, $groupPin, $groupDescription)
		{
			// Add the group
			$sql = "INSERT INTO groups (GroupName, GroupPassword, GroupDescription, GroupCreator) VALUES (:groupName, :groupPassword, :groupDescription, :groupCreator)";
			$varsArray = array( ':groupName'=>$groupName, ':groupPassword'=>$groupPin, ':groupDescription'=>$groupDescription, ':groupCreator'=>$creatorId );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			$groupId = $this->GetLastInsertId();
			
			// Add this user to the group
			$this->AddGroupAffiliation($creatorId, $groupId);
			
			return array ( 'GroupId'=>$groupId, 'Response'=>"OK" );
		}
		
		function AddGroupAffiliation($userId, $groupId)
		{
			$sql = "INSERT INTO groupaffiliation (UserId, GroupId, RoleTypeId) VALUES (:userId, :groupId, :roleTypeId)";
			$varsArray = array( ':userId'=>$userId, ':groupId'=>$groupId, ':roleTypeId'=>1 );
			
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			$groupAffiliationId = $this->GetLastInsertId();
			return $groupAffiliationId;
		}
	
		// Returns a new ass array of 'users' and 'groups' given a user id	
		function GetAllGroupsAfilliated($userId)
		{
			// Find out which groups this user is affiliated with
			$sql = "SELECT * from groupaffiliation where UserId = :userId";
			$varsArray = array(":userId"=>$userId);
			$result = $this->ExecutePreparedQuery($sql, $varsArray);
			
			// This user has no groups
			if(count($result) == 0)
				return array('groups'=>array(), 'users'=>array());
			
			// Result will be all of the groups this user is in
			$groupsString = '(';
			
			$i = 0;
			foreach($result as $row)
			{
				$groupsString .= $row['GroupId'] . ', ';
				$i++;
				
				// If this is the last group, get rid of the trailing ', '
				if($i == count($result))
					$groupsString = substr($groupsString, 0, strlen($groupsString) - 2);
			}

			$groupsString .= ')';
			
			// Get all the groups
			$sql = "SELECT * FROM groups WHERE groups.GroupId in " . $groupsString;
			$varsArray = array();
			
			$groupsResult = $this->ExecutePreparedQuery($sql, $varsArray);
			
			$groupsArray = array();
			foreach($groupsResult as $row)
			{
				$groupsArray[] = $row;
			}
			
			// Grab all the users that are in any of the groups in $groups
			$sql = 'SELECT DISTINCT * FROM users INNER JOIN groupaffiliation ON ';
			$sql .= 'users.UserId = groupaffiliation.UserId AND ';
			$sql .= 'groupaffiliation.GroupId in ' . $groupsString . ' ORDER BY users.UserId';
			
			$varsArray = array(':groupsString'=>$groupsString);
			
			$usersResults = $this->ExecutePreparedQuery($sql, $varsArray);
			$distinctUsers = array();
			$addedUsers = array();
			
			foreach($usersResults as $row)
			{
				// Check all entries ot see if this user exists
				//if(in_array($row['UserId'], $addedUsers))
				//	continue;	
					
				$distinctUsers[] = $row;
				//$addedUsers[] = $row['UserId'];
			}
						
			return array('groupAffiliations'=>$result, 'users'=>$distinctUsers, 'groups'=>$groupsArray);
		}
	}
	
?>