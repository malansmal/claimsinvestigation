<?php



	$claimnumber = $_REQUEST["claimnumber"];

	$regno = $_REQUEST["regno"];

	$pbid = $_REQUEST["selectpb"];

	$loggedinalready = $_REQUEST["lia"];	

	

	//echo "asdf $claimnumber asdf $regno asdf $pbid asdf";

	

	if ($loggedinalready == "yes")

	{

		$cookie = $_COOKIE['pbloggedincookie'];

		//echo "asdf" . $cookie . " fsda<br>";

		

		setcookie("pbloggedincookie", "", mktime(12,0,0,1, 1, 1990));

		setcookie("pbloggedincookie", $cookie, time() + 3600);

		

		require_once("connection.php");

		

		//echo "asdf";

	}

	else

	{

		//echo "asdf";



		require_once("connection.php");

		$qry = "select * from claim where `vehicleregistrationno` = '$regno' and `panelbeaterid` = $pbid";

		//echo $qry . "###################";

		$qryresults = mysql_query($qry, $db);

		

		//echo "$qry";

		

		if (mysql_num_rows($qryresults) == 1)

		{

			$row = mysql_fetch_row($qryresults);

			$userid = $row[0];

			

			//echo "$qry";

			

			setcookie("pbloggedincookie", $regno . "-" . $pbid, time()+3600);

			$validusernameandpassword = "yes";

		}

		else

		{

			$validusernameandpassword = "hell no";

		}

	}

			

		$claimnumber = $_REQUEST["claimnumber"];

		$regno = $_REQUEST["regno"];

		$loggedinalready = $_REQUEST["lia"];



	

		if ($loggedinalready == "yes")

		{

			//echo "yes";

			

			$loggedinpb = explode("-", $cookie);



			$loggedinclaimno = $loggedinpb[0];

			$loggedinregno = $loggedinpb[1];

			$loggedinpb = $loggedinpb[2];



			$qry = "select * from claim where `claimno` = '$loggedinclaimno' and `vehicleregistrationno` = '$loggedinregno' and panelbeaterid = $loggedinpb";

			$qryresults = mysql_query($qry, $db);

			$row = mysql_fetch_array($qryresults);				

			$count = mysql_num_rows($qryresults);

			

			if ($count == 1)

			{

				$claimid = $row["claimid"];

				

				//$loggedinusername = $row["username"];

				

				//echo $qry;

				

				//echo "<h5>Assessor $assessor is currently logged in:</h5>";

				

				header("Location: pbloggedinaction.php?action=claims&amp;from=1");



			}

			else

			{

				echo "<h5>You have been logged out. <a href=\"../index.php\">Login here</a><h5>";

			}					

		}

		else

		{

			//echo "not yes $validusernameandpassword";

			

			if ($validusernameandpassword == "yes")

			{					

			



				header("Location: pbloggedinaction.php?action=claims&amp;from=1");

				

				

			}

			else

			{

				echo "<h5>Invalid password entered. <a href=\"javascript:history.go(-1)\">Go Back</a></h5>";

			}

		}

	

	?>