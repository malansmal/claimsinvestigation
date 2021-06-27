<?php

	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	$loggedinalready = $_REQUEST["lia"];	
	
	if ($loggedinalready == "yes")
	{
		$cookie = $_COOKIE['loggedincookie'];
		//echo "asdf" . $cookie . " fsda<br>";
		
		setcookie("loggedincookie", "", mktime(12,0,0,1, 1, 1990));
		setcookie("loggedincookie", $cookie, time() + 3600);
		
		require_once("connection.php");
		
		//echo "asdf";
	}
	else
	{
		//echo "asdf";
		$pwd = md5($password);
		require_once("connection.php");
		$qry = "select * from users where `username` = '$username' and `password` = '$pwd'";
		$qryresults = mysql_query($qry, $db);
		
		//echo "$qry";
		
		if (mysql_num_rows($qryresults) == 1)
		{
			$row = mysql_fetch_row($qryresults);
			$userid = $row[0];
			
			//echo "$qry";
			
			setcookie("loggedincookie", $userid . "-" . $username . "-" . md5($password), time()+3600);
			$validusernameandpassword = "yes";
		}
		else
		{
			$validusernameandpassword = "hell no";
		}
	}
			
		$username = $_REQUEST["username"];
		$password = $_REQUEST["password"];
		$loggedinalready = $_REQUEST["lia"];
	
		if ($loggedinalready == "yes")
		{
			//echo "yes";
			
			$loggedinuser = explode("-", $cookie);

			$loggedinuserid = $loggedinuser[0];
			$username = $loggedinuser[1];
			$password = $loggedinuser[2];

			$qry = "select * from users where `username` = '$username' and `password` = '$password' and `id` = $loggedinuserid";
			//echo "<br>$qry<br>";
			$qryresults = mysql_query($qry, $db);
			$row = mysql_fetch_array($qryresults);				
			$count = mysql_num_rows($qryresults);
			
			if ($count == 1)
			{
				$pwd = $password;
				$qry = "select * from users where `username` = '$username' and `password` = '$pwd'";
				$qryresults = mysql_query($qry, $db);
				$row = mysql_fetch_array($qryresults);
				
				$admin = $row["admin"];
				
				//$loggedinusername = $row["username"];
				
				//echo $qry;
				
				echo "<h4>User $username is currently logged in:</h4>";
				
				if ($admin == 1)
				{
					echo "<p>
							  What would you like to do?<br><br>
							  <a href=\"loggedinaction.php?action=claims&amp;from=1\">Claims</a> || 
							  <a href=\"loggedinaction.php?action=panelbeaters&amp;from=1\">Panel Beaters</a> || 
							  <a href=\"loggedinaction.php?action=claimsclerks&amp;from=1\">Claims Technicians</a> ||
							  <a href=\"loggedinaction.php?action=assessors&amp;from=1\">Assessors</a> ||							  
							  <a href=\"loggedinaction.php?action=claimsinvestigators&amp;from=1\">Claims Investigators</a> ||
							  <a href=\"loggedinaction.php?action=administrators&amp;from=1\">Administrators</a> ||
							  <a href=\"loggedinaction.php?action=brokers&amp;from=1\">Brokers</a> ||
							  <a href=\"loggedinaction.php?action=insurers&amp;from=1\">Insurers</a> ||
							   <a href=\"loggedinaction.php?action=areas&amp;from=1\">Assessor Areas</a> ||		
							  <a href=\"loggedinaction.php?action=users&amp;from=1\">Users</a> ||
							  <a href=\"loggedinaction.php?action=vehiclemake&amp;from=1\">Vehicle Make</a> ||		
							  <a href=\"loggedinaction.php?action=partssuppliers&amp;from=1\">Parts Suppliers</a> ||
							  <a href=\"loggedinaction.php?action=towingoperators&amp;from=1\">Towing Operators</a> ||
							  <a href=\"loggedinaction.php?action=vehicletype&amp;from=1\">Vehicle Type</a>

						  </p>";
				}
				else
				{
					echo "<p>
							  What would you like to do?<br><br>
							  <a href=\"loggedinaction.php?action=claims&amp;from=1\">Claims</a> || 
							  <a href=\"loggedinaction.php?action=panelbeaters&amp;from=1\">Panel Beaters</a> || 
							  <a href=\"loggedinaction.php?action=claimsclerks&amp;from=1\">Claims Technicians</a> ||
							  <a href=\"loggedinaction.php?action=assessors&amp;from=1\">Assessors</a>
						  </p>";
				}
			}
			else
			{
				echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a><h5>";
			}					
		}
		else
		{
			//echo "not yes $validusernameandpassword";
			
			if ($validusernameandpassword == "yes")
			{					
			  	$pwd = md5($password);
				$qry = "select * from users where `username` = '$username' and `password` = '$pwd'";
				$qryresults = mysql_query($qry, $db);
				$row = mysql_fetch_array($qryresults);
				
				//echo $qry;
				
				$admin = $row["admin"];				
				
				
				if ($admin == 1)
				{
					echo "<!DOCTYPE html
PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<html>
<head>
<title>What would you like to do?</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>

<body>";
					echo "<h4>User $username is currently logged in:</h4>";
					echo "<p>
							  What would you like to do?<br><br>
							  <a href=\"loggedinaction.php?action=claims&amp;from=1\">Claims</a> || 
							  <a href=\"loggedinaction.php?action=panelbeaters&amp;from=1\">Panel Beaters</a> || 
							  <a href=\"loggedinaction.php?action=claimsclerks&amp;from=1\">Claims Technicians</a> ||
							  <a href=\"loggedinaction.php?action=assessors&amp;from=1\">Assessors</a> ||							  
							  <a href=\"loggedinaction.php?action=claimsinvestigators&amp;from=1\">Claims Investigators</a> ||
							  <a href=\"loggedinaction.php?action=administrators&amp;from=1\">Administrators</a> ||
							  <a href=\"loggedinaction.php?action=brokers&amp;from=1\">Brokers</a> ||
							  <a href=\"loggedinaction.php?action=insurers&amp;from=1\">Insurers</a> ||
							  <a href=\"loggedinaction.php?action=areas&amp;from=1\">Assessor Areas</a> ||	
							  <a href=\"loggedinaction.php?action=users&amp;from=1\">Users</a> ||
							  <a href=\"loggedinaction.php?action=vehiclemake&amp;from=1\">Vehicle Make</a> ||		
							  <a href=\"loggedinaction.php?action=partssuppliers&amp;from=1\">Parts Suppliers</a> ||
							  <a href=\"loggedinaction.php?action=towingoperators&amp;from=1\">Towing Operators</a> ||
							  <a href=\"loggedinaction.php?action=vehicletype&amp;from=1\">Vehicle Type</a>
						  </p>";
					echo "
</body>
</html>";
				}
				else
				{
					/**
* echo "<p>
* 									  What would you like to do?<br><br>
* 									  <a href=\"loggedinaction.php?action=claims&amp;from=1\">Claims</a> || 
* 									  <a href=\"loggedinaction.php?action=panelbeaters&amp;from=1\">Panel Beaters</a> || 
* 									  <a href=\"loggedinaction.php?action=claimsclerks&amp;from=1\">Claims Clerks</a> ||
* 									  <a href=\"loggedinaction.php?action=assessors&amp;from=1\">Assessors</a>
* 								  </p>";
*/
					header("Location: loggedinaction.php?action=claims&amp;from=1");
				
					
				}
			}
			else
			{
				echo "<h5>Invalid username and/or password entered. <a href=\"javascript:history.go(-1)\">Go Back</a></h5>";
			}
		}
	
	?>
