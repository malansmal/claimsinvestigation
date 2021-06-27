<?php

	$assid = $_REQUEST["assessor"];
	$password = $_REQUEST["aspassword"];
	$loggedinalready = $_REQUEST["lia"];	
	
	if ($loggedinalready == "yes")
	{
		$cookie = $_COOKIE['asloggedincookie'];
		//echo "asdf" . $cookie . " fsda<br>";
		
		setcookie("asloggedincookie", "", mktime(12,0,0,1, 1, 1990));
		setcookie("asloggedincookie", $cookie, time() + 3600);
		
		require_once("connection.php");
		
		//echo "asdf";
	}
	else
	{
		//echo "asdf";

		require_once("connection.php");
		$qry = "select * from assessors where `id` = $assid and `password` = '$password'";
		$qryresults = mysql_query($qry, $db);
		
		//echo "$qry";
		
		if (mysql_num_rows($qryresults) == 1)
		{
			$row = mysql_fetch_row($qryresults);
			$userid = $row[0];
			
			//echo "$qry";
			
			setcookie("asloggedincookie", $assid . "-" . $password, time()+3600);
			$validusernameandpassword = "yes";
		}
		else
		{
			$validusernameandpassword = "hell no";
		}
	}
			
		$assid = $_REQUEST["assessor"];
		$password = $_REQUEST["aspassword"];
		$loggedinalready = $_REQUEST["lia"];

	
		if ($loggedinalready == "yes")
		{
			//echo "yes";
			
			$loggedinassessor = explode("-", $cookie);

			$loggedinassid = $loggedinassessor[0];
			$password = $loggedinassessor[1];

			$qry = "select * from assessors where `id` = $assid and `password` = '$password'";
			$qryresults = mysql_query($qry, $db);
			$row = mysql_fetch_array($qryresults);				
			$count = mysql_num_rows($qryresults);
			
			if ($count == 1)
			{
				$assessor = $row["name"];
				
				//$loggedinusername = $row["username"];
				
				//echo $qry;
				
				//echo "<h5>Assessor $assessor is currently logged in:</h5>";
				
				header("Location: asloggedinaction.php?action=claims&amp;from=1");

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
			  	$pwd = md5($password);
				$qry = "select * from users where `username` = '$username' and `password` = '$pwd'";
				$qryresults = mysql_query($qry, $db);
				$row = mysql_fetch_array($qryresults);
				
				//echo $qry;
				
				$admin = $row["admin"];				

				header("Location: asloggedinaction.php?action=claims&amp;from=1");
				
				
			}
			else
			{
				echo "<h5>Invalid password entered. <a href=\"javascript:history.go(-1)\">Go Back</a></h5>";
			}
		}
	
	?>
