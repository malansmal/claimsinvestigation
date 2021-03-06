<?php
	session_start();


	$cookie = $_COOKIE['loggedincookie'];

		

	setcookie("loggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("loggedincookie", $cookie, time() + 3600*5);

	

	$loggedinuser = explode("-", $cookie);



	$loggedinuserid = $loggedinuser[0];

	$username = $loggedinuser[1];

	$password = $loggedinuser[2];

	

	require_once('connection.php');

	

	include('claims.php');

	include('functions.php');

	include('panelbeaters.php');

	include('claimsclerks.php');

	include('users.php');

	include('assessors.php');

	include('claimsinvestigators.php');

	include('administrators.php');

	include('insurers.php');

	include('brokers.php');	

	include('vehiclemake.php');
	
	include('partssuppliers.php');

	include('towingoperators.php');

	include('vehicletype.php');

	include('adverts.php');


?>



<!DOCTYPE html

PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"

"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<html>

<head>

<title>ACI Administration Section</title>

<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">
	input[name="receivedday"], input[name="receivedmonth"] {padding:0;}
	input[name="receivedyear"] {padding:0;}

	label {font-weight:normal !important;}

	.table {margin-top:30px;}
	.pad-10 {padding:10px;}

	.pad-table tr td:first-child {padding:10px;}

	.blue-bg {background:#d3d3ff;}

	.textinput-lg {width:80%;}

	@media print {
		table, input, select {font-size:12px;}
		input {width:170px;}
		@page {
			size: A4;
			margin: 0;
		}

		.no-show-in-print, a.send-email {display:none;}
		select[name="assessor"] {width:220px;}
		#anchor1 {display:none;}
	}

</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body>

<?php
	$containerClass = "container";
	
	$fluidPages = ["editclaim", "newclaim", "panelbeaters", "assessors", "addnewassessor", "confirmdeleteassessor", "assessoredited", "partssuppliers", "addnewpartsupplier", "partssupplieredited", "confirmdeletepartssupplier", "send-profile-link-to-panelbeater", "send-profile-link-to-partsuppliers", "deletepanelbeater", "panelbeateredited", "send-profile-link-to-towingoperator", "searchpanelbeaters", "towingoperators", "towingoperatoredited"];

	$pageaction = isset($_GET['action']) ? $_GET['action'] : "";

	if (in_array($pageaction, $fluidPages) || isset($fluidPageLayout)) {
		$containerClass = "container-fluid";
	}

?>

		<div class="<?php echo $containerClass; ?>" style="padding:30px;margin-top:40px;border:1px solid #e1e1e1;">

			<?php 

				$qry = "select count(id) as userexists from users where `username` = '$username' and `password` = '$password'";

				$qryresults = mysql_query($qry, $db);		

				$row = mysql_fetch_array($qryresults);

				

				if ($row["userexists"] == 1)

				{

					//echo "logged in still";

										

					$qry = "select * from users where `username` = '$username' and `password` = '$password'";

					$qryresults = mysql_query($qry, $db);		

					$row = mysql_fetch_array($qryresults);

					

					$admin = $row["admin"];

					

					if ($admin == 1)

					{

						echo "<h4 class='no-show-in-print'>User $username is currently logged in:</h4><h5 class='no-show-in-print'><a href=\"loggedin.php?lia=yes\">Go back to the main menu</a> | <a href=\"loggedinaction.php?action=claims&amp;from=1\">Go back to Claims</a></h5>";

					}
					else
					{
						echo "<h4 class='no-show-in-print'>User $username is currently logged in:</h4>";
					}


					if ( isset($_SESSION['success_message']) ) {
						echo '<div class="bg-info" style="padding:10px;margin:10px 0;border-radius:3px;border:2px solid #b8e5f9;">' . $_SESSION["success_message"] . '</div>';
						unset($_SESSION['success_message']);
					}

					

					$action = $_REQUEST["action"];

					

/***************************************************************************			

			START OF CLAIMS SECTION

***************************************************************************/				

					

					if ($action == "claims")

					{

						$from = isset($_REQUEST["from"]) ? $_REQUEST["from"] : '';

						Claims($from, $admin);							

					}	

					

/***************************************************************************			

			END OF CLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF SEARCHCLAIMS SECTION

***************************************************************************/				

					

					if ($action == "searchclaims")

					{
					    $fieldId = $_REQUEST["fieldId"];

						$claimno = $_REQUEST["claimno"];

						$clientno = $_REQUEST["clientno"];

						$clientname = $_REQUEST["clientname"];

						$vehicleregistrationno = $_REQUEST["vehicleregistrationno"];

						$panelbeatername = $_REQUEST["panelbeatername"];

						$from = $_REQUEST["from"];

						SearchClaimsWithId($fieldId, $claimno, $clientno, $clientname, $vehicleregistrationno, $from, $admin, $panelbeatername);

					}	

					

/***************************************************************************			

			END OF SEARCHCLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF NEWCLAIM SECTION

***************************************************************************/



					if ($action == "newclaim")

					{
						// finding panel beaters

						$qrypanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

						$respanelbeaters = mysql_query($qrypanelbeaters, $db);

						$panelbeatersArray = [];
						
						while($row = mysql_fetch_array($respanelbeaters)) {
							$panelbeatersArray[] = '"' . $row['name'] . '"';
						}

						$panelbeatersArray = implode(',', $panelbeatersArray);


						if ($admin == 0)

						{					

							echo "
							
								<script type='text/javascript' src='MSelectDBox.js'></script>


								<script type='text/javascript'>
									
									$(document).ready(function() {
										$('#panelbeatername').mSelectDBox({
											\"list\": [".$panelbeatersArray."], // Array of list items,
											\"autoComplete\": true,
											\"embeddedInput\": true
										});
									});
								
								</script>
								<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">



									<strong>Search for a claim:</strong><br>

									Client Number5: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> 
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\"> 

									Panelbeater: <input type=\"text\" name=\"panelbeatername\" id=\"panelbeatername\" /> 

									<input type=\"submit\" value=\"Search\">

									

									

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}


						$pbid = $_REQUEST["pbid"];

						

						//echo $pbid;

						

						NewClaim(0);



					}



/***************************************************************************			

			END OF NEWCLAIM SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWCLAIM SECTION

***************************************************************************/



					if ($action == "addnewclaim")

					{

						$clientname = addslashes($_REQUEST["clientname"]);

						$clientno = addslashes($_REQUEST["clientno"]);

						$claimno = addslashes($_REQUEST["claimno"]);

						$clientcontactno1 = addslashes($_REQUEST["clientcontactno1"]);

						$clientcontactno2 = addslashes($_REQUEST["clientcontactno2"]);

						

						$panelbeaterid = $_REQUEST["panelbeater"];

						

						$vehiclemakemodel = addslashes($_REQUEST["vehiclemakemodel"]);

						$vehicleyear = addslashes($_REQUEST["vehicleyear"]);

						$vehicleregistrationno = addslashes($_REQUEST["vehicleregistrationno"]);

						$vehicletype = $_REQUEST["vehicletype"];

						$vehiclevin = $_REQUEST["vehiclevin"];

						

						$administratorid = $_REQUEST["administrator"];

						

						$quoteno = addslashes($_REQUEST["quoteno"]);

						$insurerid = addslashes($_REQUEST["insurerid"]);
						$brokerid = addslashes($_REQUEST["brokerid"]);

						$claimsclerkid = addslashes($_REQUEST["claimsclerk"]);

						$authamount = $_REQUEST["authamount"] * 1;

						$excess = $_REQUEST["excess"] * 1;

						$betterment = $_REQUEST["betterment"] * 1;

						$quoteamount = $_REQUEST["quoteamount"] * 1;

						

						$assessorid = $_REQUEST["assessor"];

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number6: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						
						$clientemail = addslashes($_REQUEST["clientemail"]);

						$pbname = addslashes($_REQUEST["pbname"]);

						$pbowner = addslashes($_REQUEST["pbowner"]);

						$pbcostingclerk = addslashes($_REQUEST["pbcostingclerk"]);

						$pbcontactperson = addslashes($_REQUEST["pbcontactperson"]);

						$workshopmanager = addslashes($_REQUEST["workshopmanager"]);

						$pbcontactnumber = addslashes($_REQUEST["pbcontactnumber"]);

						$pbcontactnumber2 = addslashes($_REQUEST["pbcontactnumber2"]);

						$pbfaxno = addslashes($_REQUEST["pbfaxno"]);

						$pbemail = addslashes($_REQUEST["pbemail"]);

						$pbadr1 = addslashes($_REQUEST["pbadr1"]);

						$pbadr2 = addslashes($_REQUEST["pbadr2"]);

						$pbadr3 = addslashes($_REQUEST["pbadr3"]);

						$pbadr4 = addslashes($_REQUEST["pbadr4"]);

						$notes =  addslashes($_REQUEST["notes"]);

																		

						AddNewClaim($clientname, $clientno, $claimno, $clientcontactno1, $clientcontactno2, $clientemail, $panelbeaterid, $vehiclemakemodel, 

						 $vehicleregistrationno, $vehicleyear, $vehicletype, $administratorid, $quoteno, $insurerid, $brokerid, $claimsclerkid, 

						 $authamount, $excess, $betterment, $quoteamount, $assessorid, $pbname, $pbowner, $pbcostingclerk, $pbcontactperson, $workshopmanager,

						 $pbcontactnumber, $pbcontactnumber2, $pbfaxno, $pbemail, $pbadr1, $pbadr2, $pbadr3, $pbadr4, $notes, $vehiclevin);

					}



/***************************************************************************			

			END OF ADDNEWCLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITCLAIM SECTION

***************************************************************************/



					if ($action == "editclaim")

					{					
                      
						$claimid = $_REQUEST["claimid"];


						// finding panel beaters

						$qrypanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

						$respanelbeaters = mysql_query($qrypanelbeaters, $db);

						$panelbeatersArray = [];
						
						while($row = mysql_fetch_array($respanelbeaters)) {
							$panelbeatersArray[] = '"' . $row['name'] . '"';
						}

						$panelbeatersArray = implode(',', $panelbeatersArray);

						if ($admin == 0)

						{						

							echo "


							<script type='text/javascript' src='MSelectDBox.js'></script>


								<script type='text/javascript'>
									
									$(document).ready(function() {
										$('#panelbeatername').mSelectDBox({
											\"list\": [".$panelbeatersArray."], // Array of list items,
											\"autoComplete\": true,
											\"embeddedInput\": true
										});
									});
								
								</script>
							
							
							<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">
                                
									<strong>Search for a claim:</strong><br>
									
                                    File ID: <input type=\"text\" name=\"fieldId\"> 
									
									Client Number7: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> 
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									Panelbeater: <input type=\"text\" name=\"panelbeatername\" id=\"panelbeatername\">
									
									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form><br><br>";

						}

						

						$stepto = $_REQUEST["stepto"];

						//print_r($stepto);exit;

						EditClaim($claimid, $stepto);		

											

					}



/***************************************************************************			

			END OF EDITCLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF FOOTEREDITED SECTION

***************************************************************************/



					if ($action == "footeredited")

					{

						$claimid = $_REQUEST["claimid"];

						$investigator = addslashes($_REQUEST["investigator"]);

						$dateday = $_REQUEST["dateday"];

						$datemonth = $_REQUEST["datemonth"];

						$dateyear = $_REQUEST["dateyear"];

						$reportsentday = $_REQUEST["reportsentday"];

						$reportsentmonth = $_REQUEST["reportsentmonth"];

						$reportsentyear = $_REQUEST["reportsentyear"];

						$invoicesentday = $_REQUEST["invoicesentday"];

						$invoicesentmonth = $_REQUEST["invoicesentmonth"];

						$invoicesentyear = $_REQUEST["invoicesentyear"];



						FooterEdited($claimid, $investigator, $dateday, $datemonth, $dateyear, $reportsentday, $reportsentmonth, $reportsentyear, $invoicesentday, 

									 $invoicesentmonth, $invoicesentyear);

									 

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number8: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> 
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">
									
									<input type=\"submit\" value=\"Search\">


									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}			 

						

						EditClaim($claimid);

					}



/***************************************************************************			

			END OF FOOTEREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWITEM SECTION

***************************************************************************/



					if ($action == "newitem")

					{					

						$claimid = $_REQUEST["claimid"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number9: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> 
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

												

						NewItem($claimid);						

					}



/***************************************************************************			

			END OF NEWITEM SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWITEM SECTION

***************************************************************************/



					if ($action == "addnewitem")

					{					

						$claimid = $_REQUEST["claimid"];

						

						//echo "asdf " . $claimid . " asdf";

						

						/**

* 

* 						$qty = $_REQUEST["qty"] * 1;

* 						$description = addslashes($_REQUEST["description"]);

* 						$quoted = $_REQUEST["quoted"] * 1;

* 						$cost = $_REQUEST["cost"] * 1;

* 						$onetwofive = $_REQUEST["onetwofive"] * 1;

* 						$adjustment = $_REQUEST["adjustment"] * 1;*/

						

						$count = $_REQUEST["hoeveelheid"];

																		

						$qty = array();

						$description = array();

						$quoted = array();

						$cost = array();

						$onetwofive = array();

						$adjustment = array();

						

						for ($i = 1; $i <= $count; $i++)

						{

							$qty[$i] = $_REQUEST["qty" . $i] * 1;

							$description[$i] = $_REQUEST["description" . $i];

							$quoted[$i] = $_REQUEST["quoted" . $i] * 1;

							$cost[$i] = $_REQUEST["cost" . $i] * 1;

							$onetwofive[$i] = $_REQUEST["onetwofive" . $i] * 1;

							$adjustment[$i] = $_REQUEST["adjustment" . $i] * 1;

							

						}

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number10: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> 
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						//print_r($quoted);

						

						AddNewItem($claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment, $loggedinuserid, $count);		

					}



/***************************************************************************

			END OF ADDNEWITEM SECTION                                       

***************************************************************************/





/***************************************************************************			

			START OF EDITITEM SECTION

***************************************************************************/



					if ($action == "edititem")

					{

						$itemid = $_REQUEST["itemid"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number11: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						EditItem($itemid);

					}



/***************************************************************************			

			END OF EDITITEM SECTION

***************************************************************************/





/***************************************************************************			

			START OF ITEMEDITED SECTION

***************************************************************************/



					if ($action == "itemedited")

					{					

						$itemid = $_REQUEST["itemid"];

						$claimid = $_REQUEST["claimid"];

						

						$qty = $_REQUEST["qty"] * 1;

						$description = addslashes($_REQUEST["description"]);

						$quoted = $_REQUEST["quoted"] * 1;

						$cost = $_REQUEST["cost"] * 1;

						$onetwofive = $_REQUEST["onetwofive"] * 1;

						$adjustment = $_REQUEST["adjustment"] * 1;

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number12: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						ItemEdited($itemid, $claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment, $loggedinuserid);

					}



/***************************************************************************			

			END OF ITEMEDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEITEM SECTION

***************************************************************************/



					if ($action == "confirmdeleteitem")

					{					

						$itemid = $_REQUEST["itemid"];

						$key = get_rand_id(32);		

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number13: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

										

						ConfirmDeleteItem($itemid, $key);

					}//*/



/***************************************************************************			

			END OF CONFIRMDELETEITEM SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEITEM SECTION

***************************************************************************/



					if ($action == "deleteitem")

					{					

						$itemid = $_REQUEST["itemid"];

						$claimid = $_REQUEST["claimid"];

						$key = $_REQUEST["key"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number14: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

												

						DeleteItem($itemid, $claimid, $key);						

					}



/***************************************************************************			

			END OF DELETEITEM SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWREPORT SECTION

***************************************************************************/



					if ($action == "newreport")

					{					

						$claimid = $_REQUEST["claimid"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number15: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}						

						NewReport($claimid);

					}



/***************************************************************************			

			END OF NEWREPORT SECTION

***************************************************************************/





/***************************************************************************			

			START OF ADDNEWREPORT SECTION

***************************************************************************/



					if ($action == "addnewreport")

					{					

						$claimid = $_REQUEST["claimid"];

						$reportday = $_REQUEST["reportday"];

						$reportmonth = $_REQUEST["reportmonth"];

						$reportyear = $_REQUEST["reportyear"];

						$description = $_REQUEST["description"];

						$reporthours = $_REQUEST["hours"];

						$reportminutes = $_REQUEST["minutes"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number16: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						AddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours, $reportminutes, $description, $loggedinuserid);

						

					}



/***************************************************************************			

			END OF ADDNEWREPORT SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITREPORT SECTION

***************************************************************************/



					if ($action == "editreport")

					{					

						$reportid = $_REQUEST["reportid"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number17: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">
									
									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

												

						EditReport($reportid);						

					}



/***************************************************************************			

			END OF EDITREPORT SECTION

***************************************************************************/





/***************************************************************************			

			START OF REPORTEDITED SECTION

***************************************************************************/



					if ($action == "reportedited")

					{					

						$reportid = $_REQUEST["reportid"];	

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number18: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}					

						ReportEdited($reportid, $loggedinuserid);

					}



/***************************************************************************			

			END OF REPORTEDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEREPORT SECTION

***************************************************************************/



					if ($action == "confirmdeletereport")

					{					

						$reportid = $_REQUEST["reportid"];

						$key = get_rand_id(32);

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number19: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						ConfirmDeleteReport($reportid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEREPORT SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEREPORT SECTION

***************************************************************************/



					if ($action == "deletereport")

					{					

						$reportid = $_REQUEST["reportid"];

						$claimid = $_REQUEST["claimid"];

						$key = $_REQUEST["key"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number1: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

												

						DeleteReport($reportid, $claimid, $key);						

					}



/***************************************************************************			

			END OF DELETEREPORT SECTION

***************************************************************************/





/***************************************************************************			

			START OF CLAIMEDITED SECTION

***************************************************************************/



					if ($action == "claimedited")

					{

						$claimid = $_REQUEST["claimid"];

						$clientname = addslashes($_REQUEST["clientname"]);

						$clientno = addslashes($_REQUEST["clientno"]);

						$claimno = addslashes($_REQUEST["claimno"]);

						$clientcontactno1 = addslashes($_REQUEST["clientcontactno1"]);

						$clientcontactno2 = addslashes($_REQUEST["clientcontactno2"]);

						

						$panelbeaterid = $_REQUEST["panelbeater"];

						

						$vehiclemakemodel = addslashes($_REQUEST["vehiclemakemodel"]);

						$vehicleyear = addslashes($_REQUEST["vehicleyear"]);

						$vehicleregistrationno = addslashes($_REQUEST["vehicleregistrationno"]);

						$vehicletype = $_REQUEST["vehicletype"];

						

						$administratorid = $_REQUEST["administrator"];

						

						$quoteno = addslashes($_REQUEST["quoteno"]);

						$insurerid = addslashes($_REQUEST["insurerid"]);
						$brokerid = addslashes($_REQUEST["brokerid"]);

						$claimsclerkid = addslashes($_REQUEST["claimsclerk"]);

						$authamount = $_REQUEST["authamount"] * 1;

						$excess = $_REQUEST["excess"] * 1;

						$betterment = $_REQUEST["betterment"] * 1;

						$quoteamount = $_REQUEST["quoteamount"] * 1;

						

						$assessorid = $_REQUEST["assessor"];

					

						$pbname = addslashes($_REQUEST["pbname"]);

						$pbowner = addslashes($_REQUEST["pbowner"]);

						$pbcostingclerk = addslashes($_REQUEST["pbcostingclerk"]);

						$pbcontactperson = addslashes($_REQUEST["pbcontactperson"]);

						$workshopmanager = addslashes($_REQUEST["workshopmanager"]);

						$pbcontactnumber = addslashes($_REQUEST["pbcontactnumber"]);

						$pbfaxno = addslashes($_REQUEST["pbfaxno"]);

						$pbemail = addslashes($_REQUEST["pbemail"]);

						$pbadr1 = addslashes($_REQUEST["pbadr1"]);

						$pbadr2 = addslashes($_REQUEST["pbadr2"]);

						$pbadr3 = addslashes($_REQUEST["pbadr3"]);

						$pbadr4 = addslashes($_REQUEST["pbadr4"]);

						$notes = addslashes($_REQUEST["notes"]);

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number2: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						ClaimEdited($claimid, $clientname, $clientno, $claimno, $clientcontactno1, $clientcontactno2, $panelbeaterid, 

									$vehiclemakemodel, $vehicleregistrationno, $vehicleyear, $vehicletype, $administratorid, $quoteno, 

									$insurerid, $brokerid, $claimsclerkid, $authamount, $excess, $betterment, $quoteamount, $assessorid, 

									$pbname, $pbowner, $pbcostingclerk, $pbcontactperson, $workshopmanager, $pbcontactnumber, $pbfaxno, $pbemail, $pbadr1, $pbadr2, 

									$pbadr3, $pbadr4, $notes);

												

						EditClaim($claimid);

						

					}



/***************************************************************************			

			END OF CLAIMEDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETECLAIM SECTION

***************************************************************************/



					if ($action == "confirmdeleteclaim")

					{

						$claimid = $_REQUEST["claimid"];	

						$key = get_rand_id(32);			

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number3: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}								

						ConfirmDeleteClaim($claimid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETECLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETECLAIM SECTION

***************************************************************************/



					if ($action == "deleteclaim")

					{					

						$claimid = $_REQUEST["claimid"];

						$key = $_REQUEST["key"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number4: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\">
									
									Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

									<input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

												

						DeleteClaim($claimid, $key);						

					}



/***************************************************************************			

			END OF DELETECLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF PANELBEATERS SECTION

***************************************************************************/				

					

					if ($action == "panelbeaters")

					{

						$from = $_REQUEST["from"];

						

						Panelbeaters($from);

					}	

					

/***************************************************************************			

			END OF PANELBEATERS SECTION

***************************************************************************/





/***************************************************************************			

			START OF SEARCHPANELBEATERS SECTION

***************************************************************************/				

					

					if ($action == "searchpanelbeaters")

					{

						$pbname = $_REQUEST["pbname"];

						$pbowner = $_REQUEST["pbowner"];

						

						$from = $_REQUEST["from"];	
						
						
						Panelbeaters($from, $pbname, $pbowner);
						//SearchPanelbeaters($pbname, $pbowner, $from);

					}	

					

/***************************************************************************			

			END OF SEARCHPANELBEATERS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWPANELBEATER SECTION

***************************************************************************/



					if ($action == "newpanelbeater")

					{

						NewPanelbeater();						

					}



/***************************************************************************			

			END OF NEWPANELBEATER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWPANELBEATER SECTION

***************************************************************************/



					if ($action == "addnewpanelbeater")

					{

						

						$name = addslashes($_REQUEST["pbname"]);

						$owner = addslashes($_REQUEST["pbowner"]);

						$costingclerk = addslashes($_REQUEST["pbcostingclerk"]);

						$contactperson = addslashes($_REQUEST["pbcontactperson"]);

						$workshopmanager = addslashes($_REQUEST["workshopmanager"]);

						$adr1 = addslashes($_REQUEST["pbadr1"]);

						$adr2 = addslashes($_REQUEST["pbadr2"]);

						$adr3 = addslashes($_REQUEST["pbadr3"]);

						$adr4 = addslashes($_REQUEST["pbadr4"]);

						$notes = addslashes($_REQUEST["notes"]);

						$contactno = addslashes($_REQUEST["pbcontactno"]);

						$faxno = addslashes($_REQUEST["pbfaxno"]);

						$email = addslashes($_REQUEST["pbemail"]);
						$latitude = addslashes($_REQUEST["latitude"]);;
						$longitude = addslashes($_REQUEST["longitude"]);;

						

						AddNewPanelbeater($name, $owner, $costingclerk, $contactperson, $adr1, $adr2, $adr3, $adr4, $contactno, $faxno, $email, $latitude, $longitude);

					}



/***************************************************************************			

			END OF ADDNEWPANELBEATER SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITPANELBEATER SECTION

***************************************************************************/



					if ($action == "editpanelbeater")

					{

						$pbid = $_REQUEST["panelbeaterid"];

						EditPanelbeater($pbid);

					}



/***************************************************************************			

			END OF EDITPANELBEATER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF PANELBEATEREDITED SECTION

***************************************************************************/



					if ($action == "panelbeateredited")

					{

						$pbid = $_REQUEST["panelbeaterid"];

						$name = addslashes($_REQUEST["pbname"]);

						$owner = addslashes($_REQUEST["pbowner"]);

						$costingclerk = addslashes($_REQUEST["pbcostingclerk"]);

						$contactperson = addslashes($_REQUEST["pbcontactperson"]);

						$adr1 = addslashes($_REQUEST["pbadr1"]);

						$adr2 = addslashes($_REQUEST["pbadr2"]);

						$adr3 = addslashes($_REQUEST["pbadr3"]);

						$adr4 = addslashes($_REQUEST["pbadr4"]);
						$notes = addslashes($_REQUEST["notes"]);

						$contactno = addslashes($_REQUEST["pbcontactno"]);

						$faxno = addslashes($_REQUEST["pbfaxno"]);

						$email = addslashes($_REQUEST["pbemail"]);

						$latitude = addslashes($_REQUEST["latitude"]);
						$longitude = addslashes($_REQUEST["longitude"]);

						

						PanelbeaterEdited($pbid, $name, $owner, $costingclerk, $contactperson, $adr1, $adr2, $adr3, $adr4, $contactno, $faxno, $email,$latitude,$longitude);

					}



/***************************************************************************			

			END OF PANELBEATEREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEPANELBEATER SECTION

***************************************************************************/



					if ($action == "confirmdeletepanelbeater")

					{

						$pbid = $_REQUEST["panelbeaterid"];

						$key = get_rand_id(32);

						ConfirmDeletePanelbeater($pbid, $key);

					}

					if ($action == 'send-profile-link-to-panelbeater') {
					
						$pbid = $_GET['panelbeaterid'];
						SendProfileLinkToPanelbeater($pbid);
					}

					if ($action == 'send-profile-link-to-partsuppliers') {
					
						$psid = $_GET['id'];
						SendProfileLinkToPartSupplier($psid);
					}

					if ($action == 'send-profile-link-to-towingoperator') {
					
						$twid = $_GET['towingoperatorid'];
						SendProfileLinkToTowingOperator($twid);
					}



/***************************************************************************			

			END OF CONFIRMDELETEPANELBEATER SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEPANELBEATER SECTION

***************************************************************************/



					if ($action == "deletepanelbeater")

					{

					

						$pbid = $_REQUEST["panelbeaterid"];

						$key = $_REQUEST["key"];

												

						DeletePanelbeater($pbid, $key);

						

					}



/***************************************************************************			

			END OF DELETEPANELBEATER SECTION

***************************************************************************/





/***************************************************************************			

			START OF CLAIMSCLERKS SECTION

***************************************************************************/				

					

					if ($action == "claimsclerks")

					{

						$from = $_REQUEST["from"];

						

						ClaimsClerks($from);

					}	

					

/***************************************************************************			

			END OF CLAIMSCLERKS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWCLAIMSCLERK SECTION

***************************************************************************/



					if ($action == "newclaimsclerk")

					{

						NewClaimsClerk();						

					}



/***************************************************************************			

			END OF NEWCLAIMSCLERK SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWCLAIMSCLERK SECTION

***************************************************************************/



					if ($action == "addnewclaimsclerk")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$email = addslashes($_REQUEST["email"]);



						AddNewClaimsClerk($name, $telno, $faxno, $email);

					}



/***************************************************************************			

			END OF ADDNEWCLAIMSCLERK SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITCLAIMSCLERK SECTION

***************************************************************************/



					if ($action == "editclaimsclerk")

					{

						$ccid = $_REQUEST["claimsclerkid"];

						

						EditClaimsClerk($ccid);

					}



/***************************************************************************			

			END OF EDITCLAIMSCLERK SECTION

***************************************************************************/	





/***************************************************************************			

			START OF CLAIMSCLERKEDITED SECTION

***************************************************************************/



					if ($action == "claimsclerkedited")

					{

						$ccid = $_REQUEST["claimsclerkid"];

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$email = addslashes($_REQUEST["email"]);

						

						ClaimsClerkEdited($ccid, $name, $telno, $faxno, $email);

					}



/***************************************************************************			

			END OF CLAIMSCLERKEDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETECLAIMSCLERK SECTION

***************************************************************************/



					if ($action == "confirmdeleteclaimsclerk")

					{

						$ccid = $_REQUEST["claimsclerkid"];

						$key = get_rand_id(32);

						ConfirmDeleteClaimsClerk($ccid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETECLAIMSCLERK SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETECLAIMSCLERK SECTION

***************************************************************************/



					if ($action == "deleteclaimsclerk")

					{

					

						$ccid = $_REQUEST["claimsclerkid"];

						$key = $_REQUEST["key"];

												

						DeleteClaimsClerk($ccid, $key);						

					}



/***************************************************************************			

			END OF DELETECLAIMSCLERK SECTION

***************************************************************************/





/***************************************************************************			

			START OF USERS SECTION

***************************************************************************/				

					

					if ($action == "users")

					{

						if ($admin == 1)

						{							

							$from = $_REQUEST["from"];

							Users($from);

						}

					}	

					

/***************************************************************************			

			END OF USERS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWUSER SECTION

***************************************************************************/				

					

					if ($action == "newuser")

					{

						if ($admin == 1)

						{						

							NewUser();

						}

					}	

					

/***************************************************************************			

			END OF NEWUSER SECTION

***************************************************************************/





/***************************************************************************			

			START OF ADDNEWUSER SECTION

***************************************************************************/				

					

					if ($action == "addnewuser")

					{

						if ($admin == 1)

						{

							$username = addslashes($_REQUEST["username"]);

							$password = $_REQUEST["password"];

							$retypepassword = $_REQUEST["retypepassword"];

							

							AddNewUser($username, $password, $retypepassword);							

						}

					}	

					

/***************************************************************************			

			END OF ADDNEWUSER SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITUSER SECTION

***************************************************************************/				

					

					if ($action == "edituser")

					{

						if ($admin == 1)

						{

							$userid = $_REQUEST["userid"];

							

							EditUser($userid);

						}

					}	

					

/***************************************************************************			

			END OF EDITUSER SECTION

***************************************************************************/





/***************************************************************************			

			START OF USEREDITED SECTION

***************************************************************************/				

					

					if ($action == "useredited")

					{

						if ($admin == 1)

						{

							$userid = $_REQUEST["userid"];

							$username = addslashes($_REQUEST["username"]);

							UserEdited($userid, $username);

						}

					}	

					

/***************************************************************************			

			END OF USEREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITUSERPASSWORD SECTION

***************************************************************************/				

					

					if ($action == "edituserpassword")

					{

						if ($admin == 1)

						{

							$userid = $_REQUEST["userid"];

							EditUserPassword($userid);

						}

					}	

					

/***************************************************************************			

			END OF EDITUSERPASSWORD SECTION

***************************************************************************/





/***************************************************************************			

			START OF SAVEUSERPASSWORD SECTION

***************************************************************************/				

					

					if ($action == "saveuserpassword")

					{

						if ($admin == 1)

						{

							$userid = $_REQUEST["userid"];

							$password = $_REQUEST["password"];

							$retypepassword = $_REQUEST["retypepassword"];

							SaveUserPassword($userid, $password, $retypepassword);			

						}

					}	

					

/***************************************************************************			

			END OF SAVEUSERPASSWORD SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEUSER SECTION

***************************************************************************/				

					

					if ($action == "confirmdeleteuser")

					{

						if ($admin == 1)

						{

							$userid = $_REQUEST["userid"];

							$key = get_rand_id(32);

							ConfirmDeleteUser($userid, $key);

						}

					}	

					

/***************************************************************************			

			END OF CONFIRMDELETEUSER SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEUSER SECTION

***************************************************************************/



					if ($action == "deleteuser")

					{

						if ($admin == 1)

						{						

							$userid = $_REQUEST["userid"];

							$key = $_REQUEST["key"];

													

							DeleteUser($userid, $key);

						}

					}



/***************************************************************************			

			END OF DELETEUSER SECTION

***************************************************************************/





/***************************************************************************			

			START OF REPORTS SECTION

***************************************************************************/



					if ($action == "reports")

					{

						$claimid = $_REQUEST["claimid"];

						

						echo "	<p>Select which reports you want to view:<br><br>

									<a href=\"reports.php?action=assessmentinstruction&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Assessment Instruction</a>

									|| <a href=\"reports.php?action=assessmentreport&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Assessment Report</a>

									|| <a href=\"reports.php?action=pbinvoice&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Assessor Invoice</a>

									|| <a href=\"reports.php?action=authorization&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Authorization for Repairs</a>

									|| <a href=\"reports.php?action=pbdocrequest&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Panelbeater Document Request</a>

									|| <a href=\"reports.php?action=pbpartsrequest&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Panelbeater Parts Request</a>
									
									|| <a href=\"reports.php?action=pbfax&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Panelbeater Fax 111</a> 								

									|| <a href=\"reports.php?action=auditreport&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Audit Report</a> 

									|| <a href=\"reports.php?action=invoice&amp;claimid=$claimid\"  target=\"_blank\" class=\"newWindow\">Invoice</a></p>";

					}



/***************************************************************************			

			END OF REPORTS SECTION

***************************************************************************/







/***************************************************************************			

			START OF ASSESSORS SECTION

***************************************************************************/				

					

					if ($action == "assessors")

					{

						//$from = $_REQUEST["from"];

						

						Assessors($_REQUEST);						

					}	

					

/***************************************************************************			

			END OF ASSESSORS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWASSESSOR SECTION

***************************************************************************/



					if ($action == "newassessor")

					{

						NewAssessor();						

					}



/***************************************************************************			

			END OF NEWASSESSOR SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWASSESSOR SECTION

***************************************************************************/



					if ($action == "addnewassessor")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$company = addslashes($_REQUEST["company"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];

						$comments = addslashes($_REQUEST["comments"]);	

						$password = addslashes($_REQUEST["password"]);



						AddNewAssessor($name, $company, $telno, $faxno, $cellno, $email, $comments, $password);

					}



/***************************************************************************			

			END OF ADDNEWCLAIMSCLERK SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITASSESSOR SECTION

***************************************************************************/



					if ($action == "editassessor")

					{

						$assid = $_REQUEST["assessorid"];

						EditAssessor($assid);							

					}



/***************************************************************************			

			END OF EDITASSESSOR SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ASSESSOREDITED SECTION

***************************************************************************/



					if ($action == "assessoredited")

					{

						$assid = $_REQUEST["assessorid"];

						$name = addslashes($_REQUEST["name"]);

						$company = addslashes($_REQUEST["company"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];

						$comments = addslashes($_REQUEST["comments"]);

						$password = addslashes($_REQUEST["password"]);

						

						AssessorEdited($assid, $name, $company, $telno, $faxno, $cellno, $email, $comments, $password);

						

					}



/***************************************************************************

			END OF ASSESSOREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEASSESSOR SECTION

***************************************************************************/



					if ($action == "confirmdeleteassessor")

					{

						$assid = $_REQUEST["assessorid"];

						$key = get_rand_id(32);

						ConfirmDeleteAssessor($assid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEASSESSOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEASSESSOR SECTION

***************************************************************************/



					if ($action == "deleteassessor")

					{					

						$assid = $_REQUEST["assessorid"];

						$key = $_REQUEST["key"];

												

						DeleteAssessor($assid, $key);						

					}



/***************************************************************************			

			END OF DELETEASSESSOR SECTION

***************************************************************************/

					if ($action == "partssuppliers")

					{

						//$from = $_REQUEST["from"];

						

						PartSuppliers($_REQUEST);						

					}

					if ($action == "newpartssupplier")

					{

						NewPartsSupplier();						

					}


					if ($action == "addnewpartsupplier")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$contactname = addslashes($_REQUEST["contactname"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];
						
						$comments = addslashes($_REQUEST["comments"]);	

						$password = addslashes($_REQUEST["password"]);



						AddNewPartSupplier($name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password);

					}

					if ($action == "editpartssupplier")

					{

						$assid = $_REQUEST["partssupplierid"];

						EditPartSupplier($assid);							

					}

					if ($action == "partssupplieredited")

					{

						$partsupplierid = $_REQUEST["partsupplierid"];

						$name = addslashes($_REQUEST["name"]);

						$contactname = addslashes($_REQUEST["contactname"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];

						$comments = addslashes($_REQUEST["comments"]);

						$password = addslashes($_REQUEST["password"]);

					
						PartSupplierEdited($partsupplierid, $name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password);
					

					}

					if ($action == "confirmdeletepartssupplier")

					{

						$partssupplierid = $_REQUEST["partssupplierid"];

						$key = get_rand_id(32);

						ConfirmDeletePartSupplier($partssupplierid, $key);

					}


					if ($action == "deletepartsupplier")

					{					

						$partsupplierid = $_REQUEST["partsupplierid"];

						$key = $_REQUEST["key"];

												

						DeletePartSupplier($partsupplierid, $key);						

					}




/***************************************************************************			

			START OF SAVETHEITEMS SECTION

***************************************************************************/



					if ($action == "savetheitems")

					{					

						$claimid = $_REQUEST["claimid"];

						

						$qryitems = "select * from items where claimid = $claimid";

						$qryitemsresults = mysql_query($qryitems, $db);

						

						while ($itemrow = mysql_fetch_array($qryitemsresults))

						{

							$itemid = $itemrow["id"];

							$cost = $_REQUEST["cost_" . $itemid] * 1;

							$onetwofive = $_REQUEST["onetwofive_" . $itemid] * 1;

							$adjustment = $_REQUEST["adjustment_" . $itemid] * 1;

							

							//echo "<br>Cost: $cost<br>1.25: $onetwofive<br>Adjustment: $adjustment";

							

							$qryupdate = "update items set `cost` = $cost, `onetwofive` = $onetwofive, `adjustment` = $adjustment where `id` = $itemid";

							$qryupdateresults = mysql_query($qryupdate, $db);							

						}

						

						echo "<p><strong>All the values have been saved successfully</strong></p>";

						

						EditClaim($claimid);

						

					}



/***************************************************************************			

			END OF SAVETHEITEMS SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEASSESSOR SECTION

***************************************************************************/



					if ($action == "test")

					{

											

						Claims(1, 1);

						

					}



/***************************************************************************			

			END OF DELETEASSESSOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF CLAIMSINVESTIGATORS SECTION

***************************************************************************/				

					

					if ($action == "claimsinvestigators")

					{

						$from = $_REQUEST["from"];

						

						ClaimsInvestigators($from);

					}	

					

/***************************************************************************			

			END OF CLAIMSINVESTIGATORS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWCLAIMSINVESTIGATOR SECTION

***************************************************************************/



					if ($action == "newclaimsinvestigator")

					{

						NewClaimsInvestigator();						

					}



/***************************************************************************			

			END OF NEWCLAIMSINVESTIGATOR SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWCLAIMSINVESTIGATOR SECTION

***************************************************************************/



					if ($action == "addnewclaimsinvestigator")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$email = addslashes($_REQUEST["email"]);

						$cellno = addslashes($_REQUEST["cellno"]);



						AddNewClaimsInvestigator($name, $telno, $faxno, $email, $cellno);

					}



/***************************************************************************			

			END OF ADDNEWCLAIMSINVESTIGATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITCLAIMSINVESTIGATOR SECTION

***************************************************************************/



					if ($action == "editclaimsinvestigator")

					{

						$ciid = $_REQUEST["claimsinvestigatorid"];

						

						EditClaimsInvestigator($ciid);

					}



/***************************************************************************			

			END OF EDITCLAIMSINVESTIGATORS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF CLAIMSINVESTIGATOREDITED SECTION

***************************************************************************/



					if ($action == "claimsinvestigatoredited")

					{

						$ciid = $_REQUEST["claimsinvestigatorid"];

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$email = addslashes($_REQUEST["email"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						

						ClaimsInvestigatorEdited($ciid, $name, $telno, $faxno, $email, $cellno);

					}



/***************************************************************************			

			END OF CLAIMSINVESTIGATOREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETECLAIMSINVESTIGATOR SECTION

***************************************************************************/



					if ($action == "confirmdeleteclaimsinvestigator")

					{

						$ciid = $_REQUEST["claimsinvestigatorid"];

						$key = get_rand_id(32);

						ConfirmDeleteClaimsInvestigator($ciid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETECLAIMSINVESTIGATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETECLAIMSINVESTIGATOR SECTION

***************************************************************************/



					if ($action == "deleteclaimsinvestigator")

					{

					

						$ciid = $_REQUEST["claimsinvestigatorid"];

						$key = $_REQUEST["key"];

												

						DeleteClaimsInvestigator($ciid, $key);						

					}



/***************************************************************************			

			END OF DELETECLAIMSINVESTIGATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF ADMINISTRATORS SECTION

***************************************************************************/				

					

					if ($action == "administrators")

					{

						$from = $_REQUEST["from"];

						

						Administrators($from);

					}	

					

/***************************************************************************			

			END OF ADMINISTRATORS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWADMINISTRATOR SECTION

***************************************************************************/



					if ($action == "newadministrator")

					{

						NewAdministrator();						

					}



/***************************************************************************			

			END OF NEWADMINISTRATOR SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWADMINISTRATOR SECTION

***************************************************************************/



					if ($action == "addnewadministrator")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$adr1 = addslashes($_REQUEST["adr1"]);

						$adr2 = addslashes($_REQUEST["adr2"]);

						$adr3 = addslashes($_REQUEST["adr3"]);

						$adr4 = addslashes($_REQUEST["adr4"]);

						$vatno = addslashes($_REQUEST["vatno"]);



						AddNewAdministrator($name, $telno, $faxno, $adr1, $adr2, $adr3, $adr4, $vatno);

					}



/***************************************************************************			

			END OF ADDNEWADMINISTRATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITADMINISTRATOR SECTION

***************************************************************************/



					if ($action == "editadministrator")

					{

						$adminid = $_REQUEST["administratorid"];

						

						EditAdministrator($adminid);

					}



/***************************************************************************			

			END OF EDITADMINISTRATOR SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADMINISTRATOREDITED SECTION

***************************************************************************/



					if ($action == "administratoredited")

					{

						$adminid = $_REQUEST["administratorid"];

						$name = addslashes($_REQUEST["name"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$adr1 = addslashes($_REQUEST["adr1"]);

						$adr2 = addslashes($_REQUEST["adr2"]);

						$adr3 = addslashes($_REQUEST["adr3"]);

						$adr4 = addslashes($_REQUEST["adr4"]);

						$vatno = addslashes($_REQUEST["vatno"]);

						

						AdministratorEdited($adminid, $name, $telno, $faxno, $adr1, $adr2, $adr3, $adr4, $vatno);

					}



/***************************************************************************			

			END OF ADMINISTRATOREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEADMINISTRATOR SECTION

***************************************************************************/



					if ($action == "confirmdeleteadministrator")

					{

						$adminid = $_REQUEST["administratorid"];

						$key = get_rand_id(32);

						ConfirmDeleteAdministrator($adminid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEADMINISTRATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEADMINISTRATOR SECTION

***************************************************************************/



					if ($action == "deleteadministrator")

					{

					

						$adminid = $_REQUEST["administratorid"];

						$key = $_REQUEST["key"];

												

						DeleteAdministrator($adminid, $key);

					}



/***************************************************************************			

			END OF DELETEADMINISTRATOR SECTION

***************************************************************************/





/***************************************************************************			

			START OF AREAS SECTION

***************************************************************************/				

					

					if ($action == "areas")

					{

						$from = $_REQUEST["from"];

						

						Areas($from);

					}	

					

/***************************************************************************			

			END OF AREAS SECTION

***************************************************************************/
					
					else if ($action == "vehiclemake") {
						
						$from = $_REQUEST['from'];

						VehicleMake($from);

					}
					
					else if ($action == "newvehiclemake") {

						NewVehicleMake();

					}

					else if ($action == "addnewvehiclemake") {

						$vehiclemake = addslashes($_REQUEST["vehiclemake"]);

						$remarks = addslashes($_REQUEST["remarks"]);

						AddNewVehicleMake($vehiclemake, $remarks);

					}
					else if ($action == "editvehiclemake") {

						$vehiclemakeid = $_REQUEST["vehiclemakeid"];

						

						EditVehicleMake($vehiclemakeid);

					}
					else if ($action == "vehiclemakeedited") {

						$vehiclemakeid = $_REQUEST["vehiclemakeid"];

						$vehiclemake = addslashes($_REQUEST["vehiclemake"]);

						$remarks = addslashes($_REQUEST["remarks"]);

						VehicleMakeEdited($vehiclemakeid, $vehiclemake, $remarks);

					}
					else if ($action == "confirmdeletevehiclemake") {

						$vehiclemakeid = $_REQUEST["vehiclemakeid"];

						$key = get_rand_id(32);

						ConfirmDeleteVehicleMake($vehiclemakeid, $key);

					}
					else if ($action == "deletevehiclemake") {

						$vehiclemakeid = $_REQUEST["vehiclemakeid"];

						$key = $_REQUEST["key"];

						DeleteVehicleMake($vehiclemakeid, $key);

					}

					// Vehicle Type
					
					else if ($action == "vehicletype") {
						
						$from = $_REQUEST['from'];

						VehicleType($from);

					}
					
					else if ($action == "newvehicletype") {

						NewVehicleType();

					}

					else if ($action == "addnewvehicletype") {

						$vehicletype = addslashes($_REQUEST["vehicletype"]);

						$remarks = addslashes($_REQUEST["remarks"]);

						AddNewVehicleType($vehicletype, $remarks);

					}
					else if ($action == "editvehicletype") {

						$vehicletypeid = $_REQUEST["vehicletypeid"];

						EditVehicleType($vehicletypeid);

					}
					else if ($action == "vehicletypeedited") {

						$vehicletypeid = $_REQUEST["vehicletypeid"];

						$vehicletype = addslashes($_REQUEST["vehicletype"]);

						$remarks = addslashes($_REQUEST["remarks"]);

						VehicleTypeEdited($vehicletypeid, $vehicletype, $remarks);

					}
					else if ($action == "confirmdeletevehicletype") {

						$vehicletypeid = $_REQUEST["vehicletypeid"];

						$key = get_rand_id(32);

						ConfirmDeleteVehicleType($vehicletypeid, $key);

					}
					else if ($action == "deletevehicletype") {

						$vehicletypeid = $_REQUEST["vehicletypeid"];

						$key = $_REQUEST["key"];

						DeleteVehicleType($vehicletypeid, $key);

					}













					// Adverts
					
					else if ($action == "adverts") {
						
						$from = $_REQUEST['from'];

						Adverts($from);

					}
					
					else if ($action == "newadvert") {

						NewAdvert();

					}

					else if ($action == "addnewadvert") {

						$advertname = addslashes($_REQUEST["advertname"]);

						$link = addslashes($_REQUEST["link"]);

						AddNewAdvert($advertname, $link);

					}
					else if ($action == "editadvert") {

						$advertid = $_REQUEST["advertid"];

						EditAdvert($advertid);

					}
					else if ($action == "advertedited") {

						$advertid = $_REQUEST["advertid"];

						$advertname = addslashes($_REQUEST["advertname"]);

						$link = addslashes($_REQUEST["link"]);

						AdvertEdited($advertid, $advertname, $link);

					}
					else if ($action == "confirmdeleteadvert") {

						$advertid = $_REQUEST["advertid"];

						$key = get_rand_id(32);

						ConfirmDeleteAdvert($advertid, $key);

					}
					else if ($action == "deleteadvert") {

						$advertid = $_REQUEST["advertid"];

						$key = $_REQUEST["key"];

						DeleteAdvert($advertid, $key);

					}




/***************************************************************************			

			START OF NEWAREA SECTION

***************************************************************************/



					if ($action == "newarea")

					{

						NewArea();						

					}



/***************************************************************************			

			END OF NEWAREA SECTION

***************************************************************************/





/***************************************************************************			

			START OF ADDNEWAREA SECTION

***************************************************************************/



					if ($action == "addnewarea")

					{

						

						$areaname = addslashes($_REQUEST["areaname"]);

						$remarks = addslashes($_REQUEST["remarks"]);



						AddNewArea($areaname, $remarks);

					}



/***************************************************************************			

			END OF ADDNEWAREA SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITAREA SECTION

***************************************************************************/



					if ($action == "editarea")

					{

						$areaid = $_REQUEST["areaid"];

						

						EditArea($areaid);

					}



/***************************************************************************			

			END OF EDITAREA SECTION

***************************************************************************/	





/***************************************************************************			

			START OF AREAEDITED SECTION

***************************************************************************/



					if ($action == "areaedited")

					{

						$areaid = $_REQUEST["areaid"];

						$areaname = addslashes($_REQUEST["areaname"]);

						$remarks = addslashes($_REQUEST["remarks"]);

						

						AreaEdited($areaid, $areaname, $remarks);

					}



/***************************************************************************			

			END OF AREAEDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEAREA SECTION

***************************************************************************/



					if ($action == "confirmdeletearea")

					{

						$areaid = $_REQUEST["areaid"];

						$key = get_rand_id(32);

						ConfirmDeleteArea($areaid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEAREASECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEAREA SECTION

***************************************************************************/



					if ($action == "deletearea")

					{

					

						$areaid = $_REQUEST["areaid"];

						$key = $_REQUEST["key"];

												

						DeleteArea($areaid, $key);

					}



/***************************************************************************			

			END OF DELETEAREA SECTION

***************************************************************************/





/***************************************************************************			

			START OF BROKERS SECTION

***************************************************************************/				

					

					if ($action == "brokers")

					{

						$from = $_REQUEST["from"];

						

						Brokers($from);

					}	

					

/***************************************************************************			

			END OF BROKERS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWBROKER SECTION

***************************************************************************/



					if ($action == "newbroker")

					{

						NewBroker();						

					}



/***************************************************************************			

			END OF NEWBROKER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWBROKER SECTION

***************************************************************************/



					if ($action == "addnewbroker")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$contact = addslashes($_REQUEST["contactperson"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = addslashes($_REQUEST["email"]);

					

						AddNewBroker($name, $contact, $telno, $faxno, $email, $cellno);



					}



/***************************************************************************			

			END OF ADDNEWBROKER SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITBROKER SECTION

***************************************************************************/




/***************************************************************************			

			END OF EDITBROKER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF BROKEREDITED SECTION

***************************************************************************/



					if ($action == "brokeredited")

					{

						$brokerid = $_REQUEST["brokerid"];

						$name = addslashes($_REQUEST["name"]);

						$contact = addslashes($_REQUEST["contact"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$email = addslashes($_REQUEST["email"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						

						BrokerEdited($brokerid, $name, $contact, $telno, $faxno, $email, $cellno);

					}



/***************************************************************************			

			END OF BROKEREDITED SECTION

***************************************************************************/





/***************************************************************************			

			START OF CONFIRMDELETEBROKER SECTION

***************************************************************************/



					if ($action == "confirmdeletebroker")

					{

						$brokerid = $_REQUEST["brokerid"];

						$key = get_rand_id(32);

						ConfirmDeleteBroker($brokerid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEBROKER SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEBROKER SECTION

***************************************************************************/



					if ($action == "deletebroker")

					{

					

						$brokerid = $_REQUEST["brokerid"];

						$key = $_REQUEST["key"];

												

						DeleteBroker($brokerid, $key);

					}



/***************************************************************************			

			END OF DELETEBROKER SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEBROKER SECTION

***************************************************************************/



					if ($action == "claimsummary")

					{

						ClaimSummary();

					}



/***************************************************************************			

			END OF DELETEBROKER SECTION

***************************************************************************/



/***************************************************************************			

			START OF INSURERS SECTION

***************************************************************************/				

					

					if ($action == "insurers")

					{

						$from = $_REQUEST["from"];

						

						Insurers($from);

					}	

					

/***************************************************************************			

			END OF INSURERS SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWINSURER SECTION

***************************************************************************/



					if ($action == "newinsurer")

					{

						NewInsurer();						

					}



/***************************************************************************			

			END OF NEWINSURER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWINSURER SECTION

***************************************************************************/



					if ($action == "addnewinsurer")

					{

						

						$name = addslashes($_REQUEST["name"]);

						$vatno = addslashes($_REQUEST["vatno"]);

						$contactno = addslashes($_REQUEST["contactno"]);

						$email = addslashes($_REQUEST["email"]);

						$adr2 = addslashes($_REQUEST["adr2"]);

						$adr3 = addslashes($_REQUEST["adr3"]);

						$adr4 = addslashes($_REQUEST["adr4"]);

						$vatno = addslashes($_REQUEST["vatno"]);



						AddNewInsurer($name, $vatno, $contactno, $email);

					}



/***************************************************************************			

			END OF ADDNEWINSURER SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITINSURER SECTION

***************************************************************************/



					if ($action == "editinsurer")

					{

						$insurerid = $_REQUEST["insurerid"];

						

						EditInsurer($insurerid);

					}



/***************************************************************************			

			END OF EDITINSURER SECTION

***************************************************************************/

/***************************************************************************			

			START OF EDITBROKER SECTION

***************************************************************************/



					if ($action == "editbroker")

					{

						$brokerid = $_REQUEST["brokerid"];

						

						Editbroker($brokerid);

					}



/***************************************************************************			

			END OF EDITBROKER SECTION

***************************************************************************/	





/***************************************************************************			

			START OF INSUREREDITED SECTION

***************************************************************************/



					if ($action == "insureredited")

					{

						$insurerid = $_REQUEST["insurerid"];

						$name = addslashes($_REQUEST["name"]);

						$vatno = addslashes($_REQUEST["vatno"]);

						$contactno = addslashes($_REQUEST["contactno"]);

						$email = addslashes($_REQUEST["email"]);

						

						InsurerEdited($insurerid, $name, $vatno, $contactno, $email);

					}



/***************************************************************************			

			END OF INSUREREDITED SECTION

***************************************************************************/


/***************************************************************************			

			START OF BROKEREDITED SECTION

***************************************************************************/




/***************************************************************************			

			END OF BROKEREDITED SECTION

***************************************************************************/


/***************************************************************************			

			START OF CONFIRMDELETEINSURER SECTION

***************************************************************************/



					if ($action == "confirmdeleteinsurer")

					{

						$insurerid = $_REQUEST["insurerid"];

						$key = get_rand_id(32);

						ConfirmDeleteInsurer($insurerid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETEINSURER SECTION

***************************************************************************/





/***************************************************************************			

			START OF DELETEINSURER SECTION

***************************************************************************/



					if ($action == "deleteinsurer")

					{

					

						$insurerid = $_REQUEST["insurerid"];

						$key = $_REQUEST["key"];

												

						DeleteInsurer($insurerid, $key);

					}



/***************************************************************************			

			END OF DELETEINSURER SECTION

***************************************************************************/


					if ($action == "towingoperators") {
						TowingOperators($_REQUEST);
					}

					else if ($action == "newtowingoperator") {

						NewTowingOperator();						

					}
					else if ($action == "addnewtowingoperator") {

						$name = addslashes($_REQUEST["name"]);

						$contactname = addslashes($_REQUEST["contactname"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];
						
						$comments = addslashes($_REQUEST["comments"]);	

						$password = addslashes($_REQUEST["password"]);



						AddNewTowingOperator($name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password);

					}

					if ($action == "edittowingoperator")

					{

						$assid = $_REQUEST["towingoperatorid"];

						EditTowingOperator($assid);							

					}

					if ($action == "towingoperatoredited")
					{

						$towingoperatorid = $_REQUEST["towingoperatorsid"];

						$name = addslashes($_REQUEST["name"]);

						$contactname = addslashes($_REQUEST["contactname"]);

						$telno = addslashes($_REQUEST["telno"]);

						$faxno = addslashes($_REQUEST["faxno"]);

						$cellno = addslashes($_REQUEST["cellno"]);

						$email = $_REQUEST["email"];

						$comments = addslashes($_REQUEST["comments"]);

						$password = addslashes($_REQUEST["password"]);

					
						TowingOperatorEdited($towingoperatorid, $name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password);
					

					}

					if ($action == "confirmdeletetowingoperator")

					{

						$towingoperatorid = $_REQUEST["towingoperatorid"];

						$key = get_rand_id(32);

						ConfirmDeleteTowingOperator($towingoperatorid, $key);

					}


					if ($action == "deletetowingoperators")

					{					

						$towingoperatorid = $_REQUEST["towingoperatorsid"];

						$key = $_REQUEST["key"];

												

						DeleteTowingOperator($towingoperatorid, $key);						

					}


				}

				else

				{

					echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a></h5>";

				}

			

			?>



<script>
$(document).ready(function(){
    $(".newWindow").click(function(e){
        e.preventDefault(); // this will prevent the browser to redirect to the href
        // if js is disabled nothing should change and the link will work normally
        var url = $(this).attr('href');
        var windowName = $(this).attr('id');
        window.open(url, windowName, "height=800,width=600,scrollbars=yes,menubar=yes,toolbar=yes,titlebar=yes");
    });

	$('#saveAndNextBtn').on('click', function() {
		$('input[name=\"next\"]').val('1');
		$('form[name=\"theform\"]').submit();
	});

});

</script>

<script type=\"text/javascript\">

	$(document).ready(function() {
		
	});

</script>

</div>
</body>

</html>
