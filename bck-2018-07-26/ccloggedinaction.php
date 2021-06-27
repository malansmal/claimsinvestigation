<?php



	$cookie = $_COOKIE['ccloggedincookie'];

	

	//echo $cookie;

		

	setcookie("ccloggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("ccloggedincookie", $cookie, time() + 3600);

	

	$loggedincc = explode("-", $cookie);



	$ccid = $loggedincc[0];

	$password = $loggedincc[1];

	

	require_once('connection.php');

	

	include('claims.php');

	include('functions.php');

	include('panelbeaters.php');

	include('claimsclerks.php');

	include('assessors.php');

	include('claimsinvestigators.php');

	include('administrators.php');

	



?>



<!DOCTYPE html

PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"

"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<html>

<head>

<title>ACI Administration Section</title>

<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body>





			<?php 

						

				$qry = "select count(id) as ccexists from claimsclerks where `id` = $ccid and `password` = '$password'";

				$qryresults = mysql_query($qry, $db);		

				$row = mysql_fetch_array($qryresults);

				

				if ($row["ccexists"] == 1)

				{

					//echo "logged in still";

										

					$qry = "select * from claimsclerks where `id` = $ccid and `password` = '$password'";

					$qryresults = mysql_query($qry, $db);		

					$row = mysql_fetch_array($qryresults);

					

					$name = $row["name"];

					

					echo "<h4>Claims Technician $name is currently logged in:</h4><h5><a href=\"ccloggedin.php?lia=yes\">Go back to the main menu</a> | <a href=\"ccloggedinaction.php?action=ccclaims&amp;from=1\">Go back to Claims</a></h5>";



					

					$action = $_REQUEST["action"];

					

/***************************************************************************			

			START OF CLAIMS SECTION

***************************************************************************/				

					

					if ($action == "ccclaims")

					{

						$from = $_REQUEST["from"];

												

						ccClaims($from, $admin, $ccid);

					}	

					

/***************************************************************************			

			END OF CLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF SEARCHCLAIMS SECTION

***************************************************************************/				

					

					if ($action == "ccsearchclaims")

					{

						$claimno = $_REQUEST["claimno"];

						$clientno = $_REQUEST["clientno"];

						$clientname = $_REQUEST["clientname"];

						

						$from = $_REQUEST["from"];

						

						ccSearchClaims($claimno, $clientno, $clientname, $from, $admin, $ccid);

					}	

					

/***************************************************************************			

			END OF SEARCHCLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF NEWCLAIM SECTION

***************************************************************************/



					if ($action == "ccnewclaim")

					{

						ccNewClaim(0);

					}



/***************************************************************************			

			END OF NEWCLAIM SECTION

***************************************************************************/	





/***************************************************************************			

			START OF ADDNEWCLAIM SECTION

***************************************************************************/



					if ($action == "ccaddnewclaim")

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

						

						$administratorid = $_REQUEST["administrator"];

						

						$quoteno = addslashes($_REQUEST["quoteno"]);

						$insurancecomp = addslashes($_REQUEST["insurancecomp"]);

						$claimsclerkid = $ccid;

						$authamount = $_REQUEST["authamount"] * 1;

						$excess = $_REQUEST["excess"] * 1;

						$betterment = $_REQUEST["betterment"] * 1;

						$quoteamount = $_REQUEST["quoteamount"] * 1;

						

						$assessorid = $_REQUEST["assessor"];

						

						if ($admin == 0)

						{						

							echo "<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">

									<strong>Search for a claim:</strong><br>

									Client Number: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> <input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

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

																		

						ccAddNewClaim($clientname, $clientno, $claimno, $clientcontactno1, $clientcontactno2, $panelbeaterid, $vehiclemakemodel, 

						 $vehicleregistrationno, $vehicleyear, $vehicletype, $administratorid, $quoteno, $insurancecomp, $claimsclerkid, 

						 $authamount, $excess, $betterment, $quoteamount, $assessorid, $pbname, $pbowner, $pbcostingclerk, $pbcontactperson, $workshopmanager,

						 $pbcontactnumber, $pbfaxno, $pbemail, $pbadr1, $pbadr2, $pbadr3, $pbadr4);

					}



/***************************************************************************			

			END OF ADDNEWCLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF EDITCLAIM SECTION

***************************************************************************/



					if ($action == "cceditclaim")

					{					

						$claimid = $_REQUEST["claimid"];

						$stepto = $_REQUEST["stepto"];

						

						ccEditClaim($claimid, $stepto, $ccid);		

											

					}



/***************************************************************************			

			END OF EDITCLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF NEWITEM SECTION

***************************************************************************/



					if ($action == "newitem")

					{					

						$claimid = $_REQUEST["claimid"];

												

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

						

						ItemEdited($itemid, $claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment, 0);

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

												

						ccNewReport($claimid);

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



						ccAddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours, $reportminutes, $description, 0);

						

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



						DeleteReport($reportid, $claimid, $key);						

					}



/***************************************************************************			

			END OF DELETEREPORT SECTION

***************************************************************************/







/***************************************************************************			

			START OF CONFIRMDELETECLAIM SECTION

***************************************************************************/



					if ($action == "confirmdeleteclaim")

					{

						$claimid = $_REQUEST["claimid"];	

						$key = get_rand_id(32);			

							

						ConfirmDeleteClaim($claimid, $key);

					}



/***************************************************************************			

			END OF CONFIRMDELETECLAIM SECTION

***************************************************************************/





/***************************************************************************			

			START OF REPORTS SECTION

***************************************************************************/



					if ($action == "reports")

					{

						$claimid = $_REQUEST["claimid"];

						

						echo "	<p>Select which report you want to view:<br><br>

									<a href=\"reports.php?action=assessmentinstruction&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Instruction</a>

									|| <a href=\"reports.php?action=assessmentreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Report</a>

									|| <a href=\"reports.php?action=pbinvoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessor Invoice</a>

									|| <a href=\"reports.php?action=authorization&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Authorization for Repairs</a>

									|| <a href=\"reports.php?action=pbdocrequest&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panel Beater Document Request</a>

									|| <a href=\"reports.php?action=pbfax&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panel Beater Fax</a> 								

									|| <a href=\"reports.php?action=auditreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Audit Report</a> 

									|| <a href=\"reports.php?action=invoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Invoice</a></p>";

					}



/***************************************************************************			

			END OF REPORTS SECTION

***************************************************************************/







				}

				else

				{

					echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a></h5>";

				}

			

			?>





</body>

</html>