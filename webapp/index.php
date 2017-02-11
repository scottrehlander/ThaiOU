<html>
<head>
	<style type="text/css" media="screen">
	@import "iui/iui.css";
	@import "iui/t/default/default-theme.css";
	@import "iui/ext-sandbox/masabi/t/default/iui_ext.css";
	@import "iui/ticketsales.css";
	</style>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<title>Lunch on Me</title>
	<link rel="stylesheet" href="iui/iui.css" type="text/css" />
	<link rel="stylesheet" href="iui/t/default/default-theme.css"  type="text/css"/>
	<script type="application/x-javascript" src="iui/iui.js"></script>
</head>

<body>
	<div class="toolbar">
	   <h1 id="pageTitle"></h1>
	   <a id="backButton" class="button" href="#"></a>
	   <a class="button" href="#search">Register New</a>
	</div>
	
	<form id="loginForm" title="Lunch on Me" class="panel" name="loginForm" action="loginHandler.php" method="post" selected="true">
	
	   
		<?php
			if(isset($_GET['err']))
			{
				echo("<h4><font color=\"red\"><center>Authentication failed, please try again.</center></font></h4>");
			}
		?>
	   <fieldset>
		  <div class="row">
			 <label>Login</label>
			 <input type="text" name="username" placeholder="Your login">
		  </div>
		  <div class="row">
			 <label>Password</label>
			 <input type="password" name="password" placeholder="Your password">
		  </div>
	   </fieldset>
	   
	   <a class="whiteButton" onClick="document.loginForm.submit()">Log me in!</a>

	</form>

	
</body>
</html>