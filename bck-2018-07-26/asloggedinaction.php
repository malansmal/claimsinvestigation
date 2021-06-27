<?php



	$cookie = $_COOKIE['asloggedincookie'];

		

	setcookie("asloggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("asloggedincookie", $cookie, time() + 3600);

	

	$loggedinassessor = explode("-", $cookie);



	$loggedinassid = $loggedinassessor[0];

	$password = $loggedinassessor[1];

	

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

						

				$qry = "select count(id) as assexists from assessors where `id` = $loggedinassid and `password` = '$password'";

				$qryresults = mysql_query($qry, $db);		

				$row = mysql_fetch_array($qryresults);

				

				if ($row["assexists"] == 1)

				{

					//echo "logged in still";

										

					$qry = "select * from assessors where `id` = $loggedinassid and `password` = '$password'";

					$qryresults = mysql_query($qry, $db);		

					$row = mysql_fetch_array($qryresults);

					

					$assname = $row["name"];

					



					echo "<h4>Assessor $assname is currently logged in:</h4>";



					

					$action = $_REQUEST["action"];

					

/***************************************************************************			

			START OF CLAIMS SECTION

***************************************************************************/				

					

					if ($action == "claims")

					{

						$from = $_REQUEST["from"];

												

						asClaims($from, $admin, $loggedinassid);							

					}	

					

/***************************************************************************			

			END OF CLAIMS SECTION

***************************************************************************/	





/***************************************************************************			

			START OF EDITCLAIM SECTION

***************************************************************************/



					if ($action == "editclaim")

					{					

						$claimid = $_REQUEST["claimid"];

						

						$stepto = $_REQUEST["stepto"];

						

						echo "<p><a href=\"asloggedinaction.php?action=claims&amp;from=1\">Go back to Claims</a></p>";

						

						asEditClaim($claimid, $stepto, $loggedinassid);		

											

					}



/***************************************************************************			

			END OF EDITCLAIM SECTION

***************************************************************************/


/***************************************************************************			

			START OF NEWREPORT SECTION

***************************************************************************/

					if ($action == "newreport")
					{					

						$claimid = $_REQUEST["claimid"];


						asNewReport($claimid);

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

									Client Number: <input type=\"text\" name=\"clientno\"> 

									Client Name: <input type=\"text\" name=\"clientname\">

									Claim Number: <input type=\"text\" name=\"claimno\"> <input type=\"submit\" value=\"Search\">

									<input type=\"hidden\" name=\"from\" value=\"1\">

								</form>";

						}

						

						asAddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours, $reportminutes, $description, $loggedinuserid);

						

					}



/***************************************************************************			

			END OF ADDNEWREPORT SECTION

***************************************************************************/


				}

				else

				{

					echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a></h5>";

				}

			

			?>





</body>

</html>