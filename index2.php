<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head>	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">	<title>Auto Claims Investigations</title></head><body><?php	function FormatDate($date, $monthformat)	{		$thedate = explode("-", $date);				if ($thedate[1] == "01") 		{ 			if ($monthformat == "M")			{				$month = "January";			}						if ($monthformat == "m")			{				$month = "Jan";			}		}				if ($thedate[1] == "02") 		{ 			if ($monthformat == "M")			{				$month = "February";			}						if ($monthformat == "m")			{				$month = "Feb";			}		}				if ($thedate[1] == "03") 		{ 			if ($monthformat == "M")			{				$month = "March";			}						if ($monthformat == "m")			{				$month = "Mar";			}		}				if ($thedate[1] == "04") 		{ 			if ($monthformat == "M")			{				$month = "April";			}						if ($monthformat == "m")			{				$month = "Apr";			}		}				if ($thedate[1] == "05") 		{ 			if ($monthformat == "M")			{				$month = "May";			}						if ($monthformat == "m")			{				$month = "May";			}		}				if ($thedate[1] == "06") 		{ 			if ($monthformat == "M")			{				$month = "June";			}						if ($monthformat == "m")			{				$month = "Jun";			}		}				if ($thedate[1] == "07") 		{ 			if ($monthformat == "M")			{				$month = "July";			}						if ($monthformat == "m")			{				$month = "Jul";			}		}				if ($thedate[1] == "08") 		{ 			if ($monthformat == "M")			{				$month = "August";			}						if ($monthformat == "m")			{				$month = "Aug";			}		}				if ($thedate[1] == "09") 		{ 			if ($monthformat == "M")			{				$month = "September";			}						if ($monthformat == "m")			{				$month = "Sep";			}		}				if ($thedate[1] == "10") 		{ 			if ($monthformat == "M")			{				$month = "October";			}						if ($monthformat == "m")			{				$month = "Oct";			}		}				if ($thedate[1] == "11") 		{ 			if ($monthformat == "M")			{				$month = "November";			}						if ($monthformat == "m")			{				$month = "Nov";			}		}				if ($thedate[1] == "12") 		{ 			if ($monthformat == "M")			{				$month = "December";			}						if ($monthformat == "m")			{				$month = "Dec";			}		}				return $thedate[2] . " " . $month . " " . $thedate[0];	}	$claimno = $_REQUEST["claimno"];			echo "	<form action=\"index.php\" method=\"POST\" name=\"form1\">				<table>					<tr>						<td>Claims Enquiry: (enter claim number)</td>						<td><input type=\"text\" name=\"claimno\" value=\"$claimno\" title=\"Enter Claim Number here\" /> <input type=\"submit\" value=\"Search for Claim\" /> <input type=\"reset\" value=\"Clear\" /></td>					</tr>				</table>			</form>";						if (isset($claimno))	{		require("admin/connection.php");				$qry = "select * from claim where claimno = '$claimno'";		$qryresults = mysql_query($qry, $db);		$count = mysql_num_rows($qryresults);				if ($count != 0)		{					$therow = mysql_fetch_array($qryresults);									$claimid = $therow["id"];						//echo $claimid;						$datereceived = FormatDate($therow["datereceived"], "M");			$dateauth = FormatDate($therow["dateauth"], "M");			$wpdate = FormatDate($therow["wpdate"], "M");			$docreq = FormatDate($therow["docreq"], "M");			$inspdate = FormatDate($therow["inspdate"], "M");						echo "	<table>						<tr>							<td>Date Received:</td>							<td><strong>$datereceived</strong></td>						</tr>						<tr>							<td>Date Authorized:</td>							<td><strong>$dateauth</strong></td>						</tr>						<tr>							<td>WP Date:</td>							<td><strong>$wpdate</strong></td>						</tr>						<tr>							<td>Document Request:</td>							<td><strong>$docreq</strong></td>						</tr>						<tr>							<td>Inspection Date:</td>							<td><strong>$inspdate</strong></td>						</tr>					</table>";						$qryreports = "select * from report where claimid = $claimid";			$qryreportsresults = mysql_query($qryreports, $db);						$countreports = mysql_num_rows($qryreportsresults);						if ($countreports != 0)			{							echo "<p>Reports for the claim <strong>$claimno</strong></p>					<table border=\"1\">						<tr>							<td><strong>Date</strong></td>							<td><strong>Description</strong></td>						</tr>				";								while ($reportrow = mysql_fetch_array($qryreportsresults))				{					$reportdate = FormatDate($reportrow["reportdate"], "M");					$description = stripslashes($reportrow["description"]);										echo "	<tr>								<td>$reportdate</td>								<td>$description</td>							</tr>";								}								echo "</table>";			}			else			{				echo "<p>There are no reports for this claim</p>";			}		}		else		{			echo "<p>Sorry, the claim number you entered didn't return any results</p>";		}	}		echo "<h3>Administrators Login:</h3>		<form action=\"admin/loggedin.php\" method=\"post\">		<table id=\"logintable\">			<tr>				<td>Username</td>				<td><input type=\"text\" name=\"username\"></td>								</tr>			<tr>				<td>Password</td>				<td><input type=\"password\" name=\"password\"></td>			</tr>			<tr>				<td>&nbsp;</td>				<td><input type=\"submit\" value=\"Login\"> <input type=\"reset\" value=\"Clear\"></td>			</tr>		</table>	</form>";			echo "<h3>Claimsclerks Login:</h3>		<form action=\"admin/loggedin.php\" method=\"post\">		<table id=\"logintable\">			<tr>				<td>Username</td>				<td><input type=\"text\" name=\"username\"></td>								</tr>			<tr>				<td>Password</td>				<td><input type=\"password\" name=\"password\"></td>			</tr>			<tr>				<td>&nbsp;</td>				<td><input type=\"submit\" value=\"Login\"> <input type=\"reset\" value=\"Clear\"></td>			</tr>		</table>	</form>";			echo "<h3>Assessors Login:</h3>		<form action=\"admin/loggedin.php\" method=\"post\">		<table id=\"logintable\">			<tr>				<td>Username</td>				<td><input type=\"text\" name=\"username\"></td>								</tr>			<tr>				<td>Password</td>				<td><input type=\"password\" name=\"password\"></td>			</tr>			<tr>				<td>&nbsp;</td>				<td><input type=\"submit\" value=\"Login\"> <input type=\"reset\" value=\"Clear\"></td>			</tr>		</table>	</form>";?></body></html>