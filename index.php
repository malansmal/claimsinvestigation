<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">





<head>



  <title>Auto Claims Investigation</title>



  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />







  <!-- **** layout stylesheet **** -->



  <link rel="stylesheet" type="text/css" href="style/style.css" />







  <!-- **** colour scheme stylesheet **** -->



  <link rel="stylesheet" type="text/css" href="style/orange.css" />

  

  <!--[if lt ie 7]>

  <style type="text/css" media="screen">

#topsep

{

  background: #ffffff;

  height:40px;

  width:766px;

  margin-left:7px;

  color: #D5D2D6;

  border-color: #FF9C27;

}



#menu

{ height: 25px;

  width: 766px;

  margin-top: 0px;

  margin-left: 7px;

  position: relative;

}



#allbelowmenu {

position: relative; 

top: 170px;

left: -0px;

z-index:1;

}

  </style>

<![endif]-->

  

 <!-- *****************************--> 

 

<!--[if ie 7]>

  <style type="text/css" media="screen">

#logo

{ margin-left:1px;

  margin-right:auto;

  text-align: center;

  border:0px;

}

</style>

  <![endif]-->





<script type="text/javascript">

<!--

window.onload=show;

function show(id) {

var d = document.getElementById(id);

	for (var i = 1; i<=10; i++) {

		if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}

	}

if (d) {d.style.display='block';}

}

//-->

</script>



</head>





<body>



  <div id="main">



	<div id="tophalf">

    

	<div id="topsep"></div>



    <div id="logo" onmouseover="javascript:show('');"></div>



    <div id="menu">



      <ul>



        <!-- **** INSERT NAVIGATION ITEMS HERE (use id="selected" to identify the page you're on **** -->



        <li><a id="first" href="index.php">Home</a></li>



        <li><a id="big" href="http://www.a-c-i.co.za">Login</a></li>



        <li><a id="big" href="contact.html" >Contact Us</a></li>



        <li><a id="big" href="aboutus.html">About Us</a></li>



        <li><a id="last" href="mailto:aci@aci.co.za">E-Mail Us</a></li>



      </ul>



    </div>

</div>

    

<div id="allbelowmenu">

    <!--<div id="bar"><span id="barcontent">Home</span></div>-->



    <div id="content">



      <div id="column2">



        <h1> Auto Claims Investigation (ACI)</h1>

        

        <p>

		<?php



	require("admin/connection.php");



	function FormatDate($date, $monthformat)

	{

		$thedate = explode("-", $date);

		

		if ($thedate[1] == "01") 

		{ 

			if ($monthformat == "M")

			{

				$month = "January";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jan";

			}

		}

		

		if ($thedate[1] == "02") 

		{ 

			if ($monthformat == "M")

			{

				$month = "February";

			}

			

			if ($monthformat == "m")

			{

				$month = "Feb";

			}

		}

		

		if ($thedate[1] == "03") 

		{ 

			if ($monthformat == "M")

			{

				$month = "March";

			}

			

			if ($monthformat == "m")

			{

				$month = "Mar";

			}

		}

		

		if ($thedate[1] == "04") 

		{ 

			if ($monthformat == "M")

			{

				$month = "April";

			}

			

			if ($monthformat == "m")

			{

				$month = "Apr";

			}

		}

		

		if ($thedate[1] == "05") 

		{ 

			if ($monthformat == "M")

			{

				$month = "May";

			}

			

			if ($monthformat == "m")

			{

				$month = "May";

			}

		}

		

		if ($thedate[1] == "06") 

		{ 

			if ($monthformat == "M")

			{

				$month = "June";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jun";

			}

		}

		

		if ($thedate[1] == "07") 

		{ 

			if ($monthformat == "M")

			{

				$month = "July";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jul";

			}

		}

		

		if ($thedate[1] == "08") 

		{ 

			if ($monthformat == "M")

			{

				$month = "August";

			}

			

			if ($monthformat == "m")

			{

				$month = "Aug";

			}

		}

		

		if ($thedate[1] == "09") 

		{ 

			if ($monthformat == "M")

			{

				$month = "September";

			}

			

			if ($monthformat == "m")

			{

				$month = "Sep";

			}

		}

		

		if ($thedate[1] == "10") 

		{ 

			if ($monthformat == "M")

			{

				$month = "October";

			}

			

			if ($monthformat == "m")

			{

				$month = "Oct";

			}

		}

		

		if ($thedate[1] == "11") 

		{ 

			if ($monthformat == "M")

			{

				$month = "November";

			}

			

			if ($monthformat == "m")

			{

				$month = "Nov";

			}

		}

		

		if ($thedate[1] == "12") 

		{ 

			if ($monthformat == "M")

			{

				$month = "December";

			}

			

			if ($monthformat == "m")

			{

				$month = "Dec";

			}

		}

		

		return $thedate[2] . " " . $month . " " . $thedate[0];

	}



	$claimno = isset($_REQUEST["claimno"]) ? $_REQUEST["claimno"] : '';

		

	echo "	<form action=\"index.php\" method=\"POST\" name=\"form1\">

				<table>

					<tr>

						<td>Claims Enquiry: (enter claim number)</td>

						<td><input type=\"text\" name=\"claimno\" value=\"$claimno\" title=\"Enter Claim Number here\" /> <input type=\"submit\" value=\"Search for Claim\" /> <input type=\"reset\" value=\"Clear\" /></td>

					</tr>

				</table>

			</form>";

					

	if ( isset($claimno) && !empty($claimno) ) {

		

		

		$qry = "select * from claim where claimno = '$claimno'";

		$qryresults = mysql_query($qry, $db);

		$count = mysql_num_rows($qryresults);

		

		if ($count != 0)

		{		

			$therow = mysql_fetch_array($qryresults);			

			

			$claimid = $therow["id"];

			

			//echo $claimid;

			

			$qrydates = "select * from dates where claimid = $claimid";

			$qrydatesresults = mysql_query($qrydates, $db);

			$datesrow = mysql_fetch_array($qrydatesresults);

			

  			$received = FormatDate($datesrow["received"], "M");

  			$loss = FormatDate($datesrow["loss"], "M");

  			$assappointed = FormatDate($datesrow["assappointed"], "M");

  			$assessment = FormatDate($datesrow["assessment"], "M");

  			$assessmentreport = FormatDate($datesrow["assessmentreport"], "M");

  			$assessmentinvtoinsurer = FormatDate($datesrow["assessmentinvtoinsurer"], "M");

  			$auth = FormatDate($datesrow["auth"], "M");

  			$wp = FormatDate($datesrow["wp"], "M");

  			$docreq = FormatDate($datesrow["docreq"], "M");

  			$workinprogressinsp = FormatDate($datesrow["workinprogressinsp"], "M");

  			$dod = FormatDate($datesrow["dod"], "M");

  			$finalcosting = FormatDate($datesrow["finalcosting"], "M");

  			$acirepsentinsurer = FormatDate($datesrow["acirepsentinsurer"], "M");

  			$invoicesent = FormatDate($datesrow["invoicesent"], "M");

  			$assfeereceivedfrominsurer = FormatDate($datesrow["assfeereceivedfrominsurer"], "M");

  			$acipaymentreceived = FormatDate($datesrow["acipaymentreceived"], "M");

			

			echo "	<table>

						<tr>

							<td>Date Received:</td>

							<td><strong>$received</strong></td>

						</tr>

						<tr>

							<td>Date of Loss:</td>

							<td><strong>$loss</strong></td>

						</tr>

						<tr>

							<td>Assessor Appointed:</td>

							<td><strong>$assappointed</strong></td>

						</tr>

						<tr>

							<td>Assessment:</td>

							<td><strong>$assessment</strong></td>

						</tr>

						<tr>

							<td>Assessment Report:</td>

							<td><strong>$assessmentreport</strong></td>

						</tr>

						<tr>

							<td>Assessment Invoice to Insurer:</td>

							<td><strong>$assessmentinvtoinsurer</strong></td>

						</tr>

						<tr>

							<td>Authorized date</td>

							<td><strong>$auth</strong></td>

						</tr>

						<tr>

							<td>WP Date:</td>

							<td><strong>$wp</strong></td>

						</tr>

						<tr>

							<td>Document Request Sent:</td>

							<td><strong>$docreq</strong></td>

						</tr>

						<tr>

							<td>Work in progress Inspection:</td>

							<td><strong>$workinprogressinsp</strong></td>

						</tr>

						<tr>

							<td>Date of Delivery:</td>

							<td><strong>$dod</strong></td>

						</tr>

						<tr>

							<td>Final Costing:</td>

							<td><strong>$finalcosting</strong></td>

						</tr>

						<tr>

							<td>ACI Report sent to Insurer</td>

							<td><strong>$acirepsentinsurer</strong></td>

						</tr>

						<tr>

							<td>Invoice sent:</td>

							<td><strong>$invoicesent</strong></td>

						</tr>

						<tr>

							<td>Assessor fee received from insurer</td>

							<td><strong>$assfeereceivedfrominsurer</strong></td>

						</tr>

						<tr>

							<td>ACI Payment received</td>

							<td><strong>$acipaymentreceived</strong></td>

						</tr>

					</table>";

			

			$qryreports = "select reportdate, description, username from report join users on report.userid = users.id and claimid = $claimid";

			echo $qryreports;

			$qryreportsresults = mysql_query($qryreports, $db);

			

			$countreports = mysql_num_rows($qryreportsresults);

			

			if ($countreports != 0)

			{			

				echo "<p>Reports for the claim <strong>$claimno</strong></p>

					<table border=\"1\">

						<tr>

							<td><strong>Date and Time</strong></td>

							<td><strong>Description</strong></td>

							<td><strong>User</strong></td>

						</tr>

				";

				

				while ($reportrow = mysql_fetch_array($qryreportsresults))

				{

					$reportdate = $reportrow["reportdate"];

					$description = stripslashes($reportrow["description"]);

					$username = $reportrow["username"];

					

					echo "	<tr>

								<td>$reportdate</td>

								<td>$description</td>

								<td>$username</td>

							</tr>";				

				}

				

				echo "</table>";

			}

			else

			{

				echo "<p>There are no reports for this claim</p>";

			}

		}

		else

		{

			echo "<p>Sorry, the claim number you entered didn't return any results</p>";

		}

	}

	

	echo "<h3>Login:</h3>

		<form action=\"admin/loggedin.php\" method=\"post\">

		<table id=\"logintable\">

			<tr>

				<td>Username</td>

				<td><input type=\"text\" name=\"username\"></td>					

			</tr>

			<tr>

				<td>Password</td>

				<td><input type=\"password\" name=\"password\"></td>

			</tr>

			<tr>

				<td>&nbsp;</td>

				<td><input type=\"submit\" value=\"Login\"> <input type=\"reset\" value=\"Clear\"></td>

			</tr>

		</table>

	</form>";


//



?>

		</p>

	  </div>



    </div>



    <div id="footer">



      &copy;  Auto Claims Investigation all rights reserved | <a href="http://www.aci.co.za" target="_blank">ACI</a>



    </div>



  </div>

</div>



</body>



</html>
