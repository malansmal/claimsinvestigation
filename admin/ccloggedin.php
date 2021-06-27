<?php

	$ccid = $_REQUEST["cc"];
	$password = $_REQUEST["ccpassword"];
	$loggedinalready = $_REQUEST["lia"];	
	
	if ($loggedinalready == "yes")
	{
		$cookie = $_COOKIE['ccloggedincookie'];
		//echo "asdf" . $cookie . " fsda<br>";
		
		setcookie("ccloggedincookie", "", mktime(12,0,0,1, 1, 1990));
		setcookie("ccloggedincookie", $cookie, time() + 3600);
		
		require_once("connection.php");
		
		//echo "asdf";
	}
	else
	{
		//echo "asdf";
		$pwd = $password;
		require_once("connection.php");
		$qry = "select * from claimsclerks where `id` = $ccid and `password` = '$pwd'";
		$qryresults = mysql_query($qry, $db);
		
		//echo "$qry";
		
		if (mysql_num_rows($qryresults) == 1)
		{
			$row = mysql_fetch_row($qryresults);
			$userid = $row[0];
			
			//echo "$qry";
			
			setcookie("ccloggedincookie", $ccid . "-" . $password, time()+3600);
			$validusernameandpassword = "yes";
		}
		else
		{
			$validusernameandpassword = "hell no";
		}
	}
			
		$ccid = $_REQUEST["cc"];
		$password = $_REQUEST["ccpassword"];
		$loggedinalready = $_REQUEST["lia"];
	
		if ($loggedinalready == "yes")
		{
			//echo "yes";
			
			//echo $cookie;
			
			$loggedincc = explode("-", $cookie);

			$ccid = $loggedincc[0];
			$password = $loggedincc[1];

			$qry = "select * from claimsclerks where `id` = $ccid and `password` = '$password'";
			$qryresults = mysql_query($qry, $db);
			$row = mysql_fetch_array($qryresults);				
			$count = mysql_num_rows($qryresults);
			
			if ($count == 1)
			{
				$pwd = $password;
				$qry = "select * from claimsclerks where `id` = $ccid and `password` = '$pwd'";
				$qryresults = mysql_query($qry, $db);
				$row = mysql_fetch_array($qryresults);
				
				$ccname = $row["name"];
				
				//$loggedinusername = $row["username"];
				
				//echo $qry;
				
				echo "<h4>Claims Technician $ccname is currently logged in:</h4>";
				
				echo "<p>
						  What would you like to do?<br><br>
						  <a href=\"ccloggedinaction.php?action=ccclaims&amp;from=1\">Claims</a>
					  </p>";
			}
			else
			{
				echo "<h5>You have been logged out... <a href=\"../index.php\">Login here</a><h5>";
			}					
		}
		else
		{
			//echo "not yes $validusernameandpassword";
			
			if ($validusernameandpassword == "yes")
			{					
			  	$pwd = $password;
				$qry = "select * from claimsclerks where `id` = $ccid and `password` = '$pwd'";
				$qryresults = mysql_query($qry, $db);
				$row = mysql_fetch_array($qryresults);
				
				//echo $qry;
				
				$ccname = $row["name"];

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
				echo "<h4>Claims Technician $ccname is currently logged in:</h4>";
				echo "<p>
						  What would you like to do?<br><br>
						  <a href=\"ccloggedinaction.php?action=ccclaims&amp;from=1\">Claims</a>
					  </p>";
				echo "
</body>
</html>";
				
			}
			else
			{
				echo "<h5>Invalid password entered. <a href=\"javascript:history.go(-1)\">Go Back</a></h5>";
			}
		}
	
	?>
