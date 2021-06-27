<?php



	$cookie = $_COOKIE['pbloggedincookie'];

		

	setcookie("pbloggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("pbloggedincookie", $cookie, time() + 3600);

	

	//echo $cookie;

	

	$loggedinpb = explode("-", $cookie);



	$regno = $loggedinpb[0];

	$pbid = $loggedinpb[1];

	

	//echo "asdf $claimnumber asdf $regno asdf $pbid asdf";

	

	require_once('connection.php');

	

	include('claims.php');

	include('functions.php');

	include('panelbeaters.php');

	include('claimsclerks.php');

	include('users.php');

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

						

				$qry = "select count(id) as `exists` from claim where `panelbeaterid` = $pbid and `vehicleregistrationno` = '$regno'";

				//echo $qry;

				$qryresults = mysql_query($qry, $db);		

				$row = mysql_fetch_array($qryresults);

				

				if ($row["exists"] == 1)

				{

					//echo "logged in still";

										

					$qry = "select * from claim where `claimno` = '$claimnumber' and `vehicleregistrationno` = '$regno' and `panelbeaterid` = $pbid";

					$qryresults = mysql_query($qry, $db);		

					$row = mysql_fetch_array($qryresults);

					

					$clientname = $row["clientname"];

					$claimid = $row["id"];



					echo "<h5>Details for $clientname with Claim Number $claimnumber and registration number $regno</h5>";

					

					$action = $_REQUEST["action"];

					

/***************************************************************************			

			START OF CLAIMS SECTION

***************************************************************************/				

					

					if ($action == "claims")

					{
						
						$qryclaim = "select * from `claim` where `panelbeaterid` = $pbid and `vehicleregistrationno` = '$regno'";
						
						//echo $qryclaim;
						
						$qryclaimresults = mysql_query($qryclaim, $db);
						
						$claimrow = mysql_fetch_array($qryclaimresults);
						
						$claimid = $claimrow["id"];
						
						
						pbClaims($claimid);							

					}	

					

/***************************************************************************			

			END OF CLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF NEWITEM SECTION

***************************************************************************/



					if ($action == "newitem")

					{					

						$claimid = $_REQUEST["claimid"];

						

												

						pbNewItem($claimid);						

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

						

						

						//print_r($quoted);

						

						pbAddNewItem($claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment, $loggedinuserid, $count);		

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

						

						pbEditItem($itemid, $pbid);

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

						$pbid = $_REQUEST["pbid"];

						//echo "ASDFFSDAFSDAFSDFSDA;";

						

						pbItemEdited($itemid, $claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment, $pbid);

					}



/***************************************************************************			

			END OF ITEMEDITED SECTION

***************************************************************************/


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

						

						pbClaims($claimid);

						

					}



/***************************************************************************			

			END OF SAVETHEITEMS SECTION

***************************************************************************/




				}

				else

				{

					echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a></h5>";

				}

			

			?>





</body>

</html>