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