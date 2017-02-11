<html>

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
	
		var returnForGroupAffiliations;
		var returnForBillsForUser;
		
		var userId = -1;
		var requestId = 1;
		
		// If any page is loaded first and the objects we need are null, switch to the login page
		$(document).ready(function() {		   
		   
		   	if(returnForGroupAffiliations == null || returnForBillsForUser == null)
		   		switchPage('#login');
		
			// Detect page changes
			$(window).hashchange( function(){
			
				// Alerts every time the hash changes!
				//alert( location.hash );
			
				switch(location.hash)
				{
					case "#owedToMePage":
						setupBillsOwedToMe();
						break;
					case "#billsIOwePage":
						setupBillsThatIOwe();		
						break;
					case "#groupsOverviewPage":
						viewGroups();
						break;
					case "#addBillPage":
						setupAddBillPage();
						break;
					case "#inviteUserToGroupPage":
						setupInviteUserToGroupPage();
						break;
				}
			
			})    
		});
		
		
		function addNewBill()
		{
			requestId++;
			
			var billLocation = $('#addBillLocation').val();
			var billNotes = $('#addBillNotes').val();
			var billAmount = $('#addBillAmount').val();
			var billOwedBy = $('#addBillUserSelect').val();
			
			alert(billLocation + " " + billNotes + " " + billAmount + " " + billOwedBy + " ");
			//return;
			
			// Make a call to add a new bill
			$.post("/../tou/api.php?processor=bills&command=createBill&requestId=" + requestId, 
				{ billAmount: billAmount, billLocation: billLocation, billNotes: billNotes, userBillOwedTo: userId, userBillOwedBy: billOwedBy },
				 function(data) {
				   	
				   	var billId = parseInt(data);
				   	if(billId == "NaN")
					{
						alert("There was an error creating the bill.");					   	
					}
					
					grabRelevantData('#owedToMePage');
					
					alert("Bill created.");
				 }
				);
			
			
			//postData.AppendFormat("&billAmount={0}", HttpUtility.UrlEncode(amount));
            //postData.AppendFormat("&billLocation={0}", HttpUtility.UrlEncode(location));
            //postData.AppendFormat("&billNotes={0}", HttpUtility.UrlEncode(notes));
            //postData.AppendFormat("&userBillOwedTo={0}", HttpUtility.UrlEncode(SessionData.UserId.ToString()));
            //postData.AppendFormat("&userBillOwedBy={0}", HttpUtility.UrlEncode(userId));
		}
		
		function createGroup()
		{
			var groupName = $('#groupCreateName').val();
			var groupPin = $('#groupCreatePin').val();
			var groupDescription = $('#groupCreateDescription').val();
			
			// Make a call to add a new group
			$.post("/../tou/api.php?processor=groups&command=createGroup&requestId=" + requestId, 
				{ 
					creatorId: userId, 
					groupName: groupName, 
					groupPin: groupPin, 
					groupDescription: groupDescription 
				},
				 function(data) {
				   				   	
					grabRelevantData('#groupsOverviewPage');
					
					alert("Group created.");
					
				 }
				);
		}
		
		function deleteBill(billId)
		{		
			requestId++;
			
			// Make a call to add a delete bill
			$.post("/../tou/api.php?processor=bills&command=removeBill&billId=" + billId + "&requestId=" + requestId, 
				{ },
				 function(data) {
				   	
				   	var billId = parseInt(data);
				   	if(billId == "NaN")
					{
						alert("There was an error deleting the bill.");					   	
					}
					
					grabRelevantData('#owedToMePage');
					
					alert("Bill deleted.");
				 }
				);
		}
		
		function findUserById(id)
		{
			user = "";
			
			// Search through the userlist and return the user object with this id
			$.each(returnForGroupAffiliations.users, function(index, data) {
				if(data.UserId == id)
				{
					user = data;
					
					// Breaks the loop
					return false;
				}
			});
			
			return user;
		}		
		
		function getUniqueUsers()
		{
			var usersAdded = new Array();
			var usersArray = new Array();
			
			$.each(returnForGroupAffiliations.users, function(index, data) {
				if(usersAdded.indexOf(data.UserId) > -1)
				{
					// continue
					return true;
				}
				
				usersArray.push(data);
				usersAdded.push(data.UserId);
			});
			
			return usersArray;
		}
				
		
		function grabRelevantData(pageToSwitchTo)
		{
			logMessage("starting grabRelevantData()");
			
			// Show the user that we are loading
			//switchPage('#loading');
			
			requestId++;
			
			var first = true;
			
			// Groups Affiliated:
			// 	http://rehlander.com/tou/api.php?processor=groups&command=getAllGroupsAffiliated&userId={0}&requestId={1}
			$.get(
				//"http://rehlander.com/tou/api.php?processor=groups&command=getAllGroupsAffiliated&userId=" + userId + "&requestId=" + requestId,
				"/../tou/api.php?processor=groups&command=getAllGroupsAffiliated&userId=" + userId + "&requestId=" + requestId,
				"{",
				function(data) 
				{ 
					returnForGroupAffiliations = JSON.parse(data);
					
					if(first)
					{
						logMessage("first is true - GroupsAffiliated");
						first = false;
					}
					else
					{
						logMessage("first is false in grabRelevantData - GroupsAffiliated");
						switchPage(pageToSwitchTo);
					}
						
				},
				"html"
			);

			// BillsForUser
			//	http://rehlander.com/tou/api.php?processor=bills&command=getBillsForUser&userId={0}&requestId={1}
			$.get(
				//"http://rehlander.com/tou/api.php?processor=bills&command=getBillsForUser&userId=" + userId + "&requestId=" + requestId,
				"/../tou/api.php?processor=bills&command=getBillsForUser&userId=" + userId + "&requestId=" + requestId,
				"{",
				function(data) 
				{ 
					returnForBillsForUser = JSON.parse(data);
					
					if(first)
					{
						logMessage("first is true - billsForUser");
						first = false;
					}
					else
					{
						logMessage("first is false in grabRelevantData - billsForUser");
						switchPage(pageToSwitchTo);
					}
						
				},
				"html"
			);
		}
		
		function inviteUserToGroup()
		{
			var email = $('#inviteUserToGroupEmail').val();
			var groupId = $('#inviteUserToGroupGroup').val();
			var message = $('#inviteUserToGroupMessage').val();
			
			// Make a call to add a new group
			$.post("/../tou/api.php?processor=groups&command=inviteToGroup&requestId=" + requestId, 
				{ 
					from: userId, 
					email: email, 
					groupId: groupId, 
					message: message 
				},
				 function(data) {
				   	
				   	alert(data);
				   				   	
					grabRelevantData('#groupsOverviewPage');
					
					alert("Group invite sent.");
					
				 }
				);
		}
		
		function login()
		{
			$.get(
				//"http://rehlander.com/tou/api.php?processor=users&command=authenticateUser&username=" + $("#loginUsername").val() + "&password=" + $("#loginPassword").val() + "&requestId=" + requestId,
				"/../tou/api.php?processor=users&command=authenticateUser&username=" + $("#loginUsername").val() + "&password=" + $("#loginPassword").val() + "&requestId=" + requestId,
				"{}",
				function(data) 
				{ 
					if(data == "-1")
					{
						alert("Login not accepted, please try again.");
					}
					else
					{
						userId = data;
						grabRelevantData('#overview');
					}
				},
				"html"
			);
		}
		
		function logMessage(messageString)
		{
			// Chrome, others?
			console.log(messageString);
		}
		
		function registerNewUser()
		{
			var username = $('#registerUserUsername').val();
			var pin = $('#registerUserPin').val();
			var email = $('#registerUserEmail').val();
			var firstName = $('#registerUserFirstName').val();
			var lastName = $('#registerUserLastName').val();
			
			// Make a call to add a new group
			$.post("/../tou/api.php?processor=users&command=registerUser&requestId=" + requestId, 
				{ 
					username: username, 
					pin: pin, 
					email: email, 
					firstName: firstName,
					lastName: lastName 
				},
				 function(data) {
				   	
				   	if(data > 0)
				   	{
				   		alert("Thanks for registering for Lunch on Me.  Please check your inbox (including spam folder) to confirm your email address.");
				   		history.go(-1);
				   		return;
				   	}
				   	else if (data == -1)
				   	{
				   		alert("Username is taken, please try again with a new name.");//  Error code " + data);
				   		return;
				   	}
				   	
				   	alert("Failed to regsiter new user, please check your email address and try again.  Error code " + data);
				   	
				 }
				);
		}
		
		function switchPage(pageName)
		{
			$.mobile.changePage($(pageName));
		}
		
		function setupAddBillPage()
		{
			$('#addBillUserSelect').empty();	
			
		  	// Load the select box with users from all groups
			// Search through the userlist and return the user object with this id
			var usersArray = getUniqueUsers();
			
			$.each(usersArray, function(index, data) {
				$('#addBillUserSelect').append("<option value=\"" + data.UserId + "\">" + data.UserFirstName + " " + data.UserLastName + " (" + data.UserName + ")</option>");
			});
			
			$('#addBillUserSelect').selectmenu('refresh');
		}
		
		function setupBillsOwedToMe()
		{
			// Add this bill that is owed to the billOwedToMeList
			$('#owedToMeBillsList').empty();
			$('#owedToMeBillsList').append("<li id=\"owedToMeBillsListDivider\" data-role=\"list-divider\">Who Owes Me?</li>");
			
			// Filter out the bills that are owed to me
			var usersAdded = new Array();
			
			$.each(returnForBillsForUser.owed, function(index, data) {
				
				// Insert a row for each user that owes us...
				user = findUserById(data.UserBillOwedBy);
				
				// continues the loop
				if(usersAdded.indexOf(user.UserName) > -1)
					return true;
				
				$('#owedToMeBillsList').append("<li><a onclick=\"viewBillsOwedToMeFromUser('" + user.UserFirstName + "', " + user.UserId + "); \">" + user.UserFirstName + " " + user.UserLastName + "</a></li>");
				
				usersAdded.push(user.UserName);
			});
			
			$('#owedToMeBillsList').listview('refresh');	
		}
		
		function setupBillsThatIOwe()
		{
			// Add this bill that is owed to the billOwedToMeList
			$('#billsIOweList').empty();
			$('#billsIOweList').append("<li id=\"billsIOweListDivider\" data-role=\"list-divider\">Who Do I Owe?</li>");
			
			// Filter out the bills that are owed to me
			var usersAdded = new Array();
			
			$.each(returnForBillsForUser.owes, function(index, data) {				
				// Insert a row for each user that owes us...
				user = findUserById(data.UserBillOwedTo);
				
				// continues the loop
				if(usersAdded.indexOf(user.UserName) > -1)
					return true;
				
				$('#billsIOweList').append("<li><a onclick=\"viewBillsIOweToUser('" + user.UserFirstName + "', " + user.UserId + "); \">" + user.UserFirstName + " " + user.UserLastName + "</a></li>");
				
				usersAdded.push(user.UserName);
			});
			
			$('#billsIOweList').listview('refresh');	
		}
		
		function setupInviteUserToGroupPage()
		{
			$('#inviteUserToGroupGroup').empty();
			
			var isFirst = true;
			
			// Load the select box with all of the group names
			$.each(returnForGroupAffiliations.groups, function(index, data) {
				$('#inviteUserToGroupGroup').append("<option value=\"" + data.GroupId + "\">" + data.GroupName + "</option>");
			});
			
			$('#inviteUserToGroupGroup').selectmenu('refresh');
		}
	
			
		
		
		function viewBillsOwedToMeFromUser(username, id)
		{
			// Add each bill that is owed by this user
			$('#specificUserOwedToMePageList').empty();
			$('#specificUserOwedToMePageList').append("<li id=\"specificUserOwedToMePageListDivider\" data-role=\"list-divider\">" + username + " Owes Me</li>");
			
			$.each(returnForBillsForUser.owed, function(index, data) {				
				
				// Insert a row for each bill that owes us...
				if(data.UserBillOwedBy == id)
					$('#specificUserOwedToMePageList').append("<li><a onclick=\" viewBill('" + data.UserBillOwedBy + "', '" + data.BillId + "'); \">" + data.BillLocation + " (" + data.BillAmount + ")</a></li>");
			});
						
			switchPage('#specificUserOwedToMePage');
						
			$('#specificUserOwedToMePageList').listview('refresh');	
		}
		
		function viewBill(userid, id)
		{
			// Grab the user that owes this bill
			var user = findUserById(userid);
			
			// Add each bill that is owed by this user
			$('#billViewList').empty();
			$('#billViewList').append("<li id=\"billViewListDivider\" data-role=\"list-divider\">Bill Owed By " + user.UserFirstName + "</li>");
			
			$.each(returnForBillsForUser.owed, function(index, data) {				
				
				// Insert a row for each bill that owes us...
				if(data.BillId == id)
				{
					$('#billViewList').append("<li data-icon=\"false\"><a>" + data.BillLocation + "</a></li>");
					$('#billViewList').append("<li data-icon=\"false\"><a>" + data.BillAmount + "</a></li>");
					$('#billViewList').append("<li data-icon=\"false\"><a>" + data.BillDate + "</a></li>");
					
					// Unregister current handlers and register a new one to delete the bill
					$('#billDelete').unbind();
					$('#billDelete').click(function() { deleteBill(data.BillId); });
					
					
					// Break the loop
					return false;	
				}
			});
			
			switchPage('#billPage');
			
			$('#billViewList').listview('refresh');	
		}
		
		
		function viewBillsIOweToUser(username, id)
		{
			// Add each bill that is owed by this user
			$('#specificUserIOwePageList').empty();
			$('#specificUserIOwePageList').append("<li id=\"specificUserIOwePageListDivider\" data-role=\"list-divider\">I Owe " + username + "</li>");
			
			$.each(returnForBillsForUser.owes, function(index, data) {				
				
				// Insert a row for each bill that owes us...
				if(data.UserBillOwedTo == id)
				{	
					$('#specificUserIOwePageList').append("<li><a>" + data.BillLocation + " (" + data.BillAmount + ")</a></li>");
				}
			});
			
			switchPage('#specificUserIOwePage');
			
			$('#specificUserIOwePageList').listview('refresh');						
		}
		
		function viewGroups()
		{
			// Add each group
			$('#groupsOverviewPageList').empty();
			$('#groupsOverviewPageList').append("<li id=\"groupsOverviewPageListDivider\" data-role=\"list-divider\">Group Overview</li>");
			
			$.each(returnForGroupAffiliations.groups, function(index, data) {				
				
				// Insert a row for each group
				$('#groupsOverviewPageList').append("<li><a onclick=\" viewSpecificGroup('" + data.GroupName + "', " + data.GroupId +"); \">" + data.GroupName + "</a></li>");
			});
			
			$('#groupsOverviewPageList').listview('refresh');		
		}
		
		function viewSpecificGroup(groupName, groupId)
		{
			// Add each user int he group
			$('#groupsUsersPageList').empty();
			$('#groupsUsersPageList').append("<li id=\"groupsUsersPageListDivider\" data-role=\"list-divider\">" + groupName + "</li>");
			
			$.each(returnForGroupAffiliations.users, function(index, data) {
				
				// Insert a row for each bill that owes us...
				if(data.GroupId == groupId)
				{
					$('#groupsUsersPageList').append("<li><a>" + data.UserFirstName + " " + data.UserLastName + "</a></li>");
				}
			});
			
			switchPage('#groupsUsersPage');
			
			$('#groupsUsersPageList').listview('refresh');	
		}
		
		
		
		
		
		
		
		
	</script>
</head>

<body>

	<!-- Login Page -->
	<div data-role="page" id="login" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header">
			<h1>Login to Lunch on Me</h1>
		</div>
		
		<div data-role="content">
				<label>Username</label>
				<input width="50" id="loginUsername" value="srehlander" />
				<br />
				<label>Password</label>
				<input width="50" id="loginPassword" value="1234" />
				
				<input type="submit" value="Login" href="#" onclick="login()" />
				<input type="submit" value="Register" onclick="switchPage('#registerNewUserPage')" />
		</div>
	</div>
	
	<!-- Loading Page -->
	<div data-role="page" data-rel="dialog" id="loading" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header">
			<h1>Overview</h1>
		</div>
		
		<div data-role="content"> 
			<img src="ajax-loader.gif" />
		</div>
	</div>
	
	<!-- Overview Navigation Page -->
	<div data-role="page" id="overview" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header">
			<h1>Overview</h1>
			<a onclick="location.reload(true);" data-icon="alert" class="ui-btn-right">Log out</a>
		</div>
		
		<div data-role="content">
			<ul id="overviewList" data-role="listview" data-inset="true">
				<li id="overListDivier" data-role="list-divider">Main Navigation</li>
				<li id="overviewWhoOwes"><a href="#owedToMePage">Bills Owed To Me</a></li>
				<li id="overviewWhoOwed"><a href="#billsIOwePage">Bills That I Owe</a></li>
				<li id="overviewGroup"><a href="#groupsOverviewPage">Groups</a></li>
			</ul>
		</div>
	</div>
	
	
	<!-- People Who Owe Me Bills Page -->
	<div data-role="page" id="owedToMePage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Bills Owed To Me</h1>
			<a onclick="grabRelevantData('#owedToMePage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" data-add-back-btn="true" >
			<ul id="owedToMeBillsList" data-role="listview" data-inset="true" data-filter="true">
				<li id="owedToMeBillsListDivider" data-role="list-divider">Who Owes Me?</li>
			</ul>
			<ul id="owedToMeActions" data-role="listview" data-inset="true">
				<li id="owedToMeAddBillDivider" data-role="list-divider">Actions</li>
				<li id="owedToMeAddBill"><a onclick="switchPage('#addBillPage');">Add a Bill</a></li>
			</ul>
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	
	<!-- Actual List of Bills Owed To Me from a Specific User -->
	<div data-role="page" id="specificUserOwedToMePage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Bills I Owe</h1>
			<a onclick="grabRelevantData('#specificUserOwedToMePage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="specificUserOwedToMePageList" data-role="listview" data-inset="true" data-filter="true">
				<li id="specificUserOwedToMePageListDivider" data-role="list-divider">Bills</li>
			</ul>
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	
	<!-- People Who I Owe Bills To Page -->
	<div data-role="page" id="billsIOwePage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Bills Owed To Me</h1>
			<a onclick="grabRelevantData('#billsIOwePage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="billsIOweList" data-role="listview" data-inset="true" data-filter="true">
				<li id="billsIOweListDivider" data-role="list-divider">Bills</li>
			</ul>
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	
	<!-- Actual List of Bills I Owe To a Specific User -->
	<div data-role="page" id="specificUserIOwePage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Bills That I Owe</h1>
			<a onclick="grabRelevantData('#specificUserIOwePage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="specificUserIOwePageList" data-role="listview" data-inset="true" data-filter="true">
				<li id="specificUserIOwePageListDivider" data-role="list-divider">Bills</li>
			</ul>
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
		
	<!-- Groups Overview Page -->
	<div data-role="page" id="groupsOverviewPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Groups</h1>
			<a onclick="grabRelevantData('#groupsOverviewPage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="groupsOverviewPageList" data-role="listview" data-inset="true" data-filter="true">
				<li id="groupsOverviewPageDivider" data-role="list-divider">Groups</li>
			</ul>
			
			<ul id="groupsOverviewPageList" data-role="listview" data-inset="true" >
				<li id="groupsOverviewPageDivider" data-role="list-divider">Actions</li>
				<li id="groupCreate"><a onclick="switchPage('#groupCreatePage');">Create Group</a></li>
				<li id="groupInviteUser"><a onclick="switchPage('#inviteUserToGroupPage');" >Invite User to Group</a></li>
			</ul>
			
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- Group User List Page -->
	<div data-role="page" id="groupsUsersPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Users in Group</h1>
			<a onclick="grabRelevantData('#groupsUsersPage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="groupsUsersPageList" data-role="listview" data-inset="true" data-filter="true">
				<li id="groupsUsersPageListDivider" data-role="list-divider">Groups</li>
			</ul>
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- Add a Bill Page -->
	<div data-role="page" id="addBillPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Add Bill</h1>
			<a onclick="grabRelevantData('#addBillPage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<label>Username</label>
			<select id="addBillUserSelect"></select>
			<label>Location</label>
			<input id="addBillLocation"></input>
			<label>Amount</label>
			<input id="addBillAmount"></input>
			<label>Notes</label>
			<TextArea id="addBillNotes"></TextArea>
			<input type="submit" value="Submit" onclick="addNewBill()" />
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- View a Bill Page -->
	<div data-role="page" id="billPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Bill Owed</h1>
			<a onclick="grabRelevantData('#billPage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<ul id="billViewList" data-role="listview" data-inset="true">
				<li id="billViewListDivider" data-role="list-divider">Bill</li>
			</ul>
			
			<input id="billDelete" onclick="" type="submit" value="Delete Bill" data-theme="e" />
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- Create Group Page -->
	<div data-role="page" id="groupCreatePage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Create Group</h1>
			<a onclick="grabRelevantData('#groupCreatePage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<label>Group Name</label>
			<input id="groupCreateName"></input>
			<label>PIN (optional)</label>
			<input id="groupCreatePin"></input>
			<label>Confirm PIN</label>
			<input id="groupCreatePin2"></input>
			<label>Description</label>
			<TextArea id="groupCreateDescription"></TextArea>
			<input type="submit" value="Submit" onclick="createGroup()" />
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- Invite User To Group Page -->
	<div data-role="page" id="inviteUserToGroupPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Invite to Group</h1>
			<a onclick="grabRelevantData('#inviteUserToGroupPage');" data-icon="refresh" class="ui-btn-right">Refresh</a>
		</div><!-- /header -->
		
		<div data-role="content" >
			<label>Friend's Email</label>
			<input id="inviteUserToGroupEmail"></input>
			<label>Group</label>
			<select id="inviteUserToGroupGroup"></select>
			<label>Message</label>
			<TextArea id="inviteUserToGroupMessage"></TextArea>
			<input type="submit" value="Submit" onclick="inviteUserToGroup()" />
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
	<!-- Register New User -->
	<div data-role="page" id="registerNewUserPage" data-add-back-btn="true" data-theme="<?php echo($dataTheme); ?>">
	
		<div data-role="header">
			<h1>Register</h1>
		</div><!-- /header -->
		
		<div data-role="content" >
			<label>Username</label>
			<input id="registerUserUsername"></input>
			<label>PIN</label>
			<input id="registerUserPin"></input>
			<label>Email</label>
			<input id="registerUserEmail"></input>
			<label>First Name</label>
			<input id="registerUserFirstName"></input>
			<label>Last Name</label>
			<input id="registerUserLastName"></input>
			
			<input type="submit" value="Submit" onclick="registerNewUser()" />
		</div>
		
		<div data-role="footer">
			<h4>Copyright 2011 - Rehlander Technologies</h4>
		</div><!-- /footer -->
	</div>
	
</body>
</html>