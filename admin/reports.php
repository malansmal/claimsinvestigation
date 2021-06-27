<?php

error_reporting(0);
ini_set('display_errors', 0);

$cookie = $_COOKIE['loggedincookie'];

setcookie("loggedincookie", "", mktime(12, 0, 0, 1, 1, 1990));

setcookie("loggedincookie", $cookie, time() + 1800);

$loggedinuser = explode("-", $cookie);

$loggedinuserid = $loggedinuser[0];

$username = $loggedinuser[1];

$password = $loggedinuser[2];

$cookie2 = $_COOKIE['asloggedincookie'];	

	setcookie("asloggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("asloggedincookie", $cookie2, time() + 3600);

	

	$loggedinassessor = explode("-", $cookie2);



	$loggedinassid = $loggedinassessor[0];

	$password2 = $loggedinassessor[1];
	
	
	$cookie3 = $_COOKIE['ccloggedincookie'];

	

	//echo $cookie;

		

	setcookie("ccloggedincookie", "", mktime(12,0,0,1, 1, 1990));

	setcookie("ccloggedincookie", $cookie3, time() + 3600);

	

	$loggedincc = explode("-", $cookie3);



	$ccid = $loggedincc[0];

	$password3 = $loggedincc[1];



require_once ('connection.php');

include ('functions.php');

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



<?php

if ($_REQUEST["action"] == "auditreport")
{

    echo "



								<script type=\"text/javascript\">



									



function DoCalc()



{



	



	document.theform.total.value = Math.round((((document.theform.authamount.value * 1) + (document.theform.extras.value * 1) - (document.theform.balofsav.value * 1) - (document.theform.acisavings.value * -1)) * 100)) / 100;



	document.theform.total2.value = Math.round((((document.theform.total.value * 1) - (document.theform.betterment.value * 1) - (document.theform.excessbyclient.value * 1)) * 100)) / 100;



	document.theform.total3.value = Math.round((((document.theform.total2.value * 1) + (document.theform.towingother.value * 1)) * 100)) / 100;



	document.theform.discount.value = Math.round(((( (document.theform.percent.value * 1) / 100 ) * (document.theform.total3.value * 1)) * 100)) / 100;



	document.theform.paypanelbeater.value = Math.round((document.theform.total3.value - document.theform.discount.value) * 100) / 100; 



	document.theform.totalclaim.value = Math.round((((document.theform.paypanelbeater.value * 1) + (document.theform.payaci.value * 1) + (document.theform.assessingfees.value * 1) + (document.theform.otherfees.value * 1)) * 100)) / 100;	


}

								</script>



								<body onLoad=\"DoCalc();\">";

}

else
{

    echo "<body>";

}

if (($_REQUEST["action"] == "invoice") || ($_REQUEST["action"] == "pbinvoice"))
{

    echo "<body onLoad=\"DoItNow();\">";

}

else
{

    echo "<body>";

}

if (($_REQUEST["action"] == "assessmentreport"))
{

    echo "<body onLoad=\"AddFields();\">";

}

else
{

    echo "<body>";

}



?>

			<?php







$qry = "select count(id) as userexists from users where `username` = '$username' and `password` = '$password'";

$qryresults = mysql_query($qry, $db);

$row = mysql_fetch_array($qryresults);

if ($cookie2 == "")
{
	$qry2 = "select count(id) as assexists from assessors where `id` = 0 and `password` = ''";
}
else
{

	$qry2 = "select count(id) as assexists from assessors where `id` = $loggedinassid and `password` = '$password2'";
}

				$qryresults2 = mysql_query($qry2, $db);		

				$row2 = mysql_fetch_array($qryresults2);
				
if ($cookie3 == "")
{
	$qry3 = "select count(id) as ccexists from claimsclerks where `id` = 0 and `password` = ''";
}
else
{

	$qry3 = "select count(id) as ccexists from claimsclerks where `id` = $ccid and `password` = '$password3'";
}

				$qryresults3 = mysql_query($qry3, $db);		

				$row3 = mysql_fetch_array($qryresults3);

if (($row["userexists"] == 1) || ($row2["assexists"] == 1) || ($row3["ccexists"] == 1))
{

    //echo "logged in still";

    //echo "<h5>User $username is currently logged in:</h5><h5><a href=\"loggedin.php?lia=yes\">Go back to the main menu</a></h5>";

    $qry = "select * from users where `username` = '$username' and `password` = '$password'";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $admin = $row["admin"];

    $action = $_REQUEST["action"];

    /***************************************************************************



    START OF PBFAX SECTION



    ***************************************************************************/

    if ($action == "pbfax")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:142px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>



										<td style=\"width:172px;\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">A.C.I. (Auto Claims Investigation)</font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339 <br>



											P.O.Box 494, Stellenbosch, 7599</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">admin@aci.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\"><font style=\"font-size:10pt;font-family:Arial;\">Details:</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">The following 
										adjustment will be made to the above claim for parts/labour/paint supplied and/or less paid for parts quoted. 
										Please note that this adjustment amount will be deducted from Original Authorized amount and extra's will be added, 
										where necessary. If you do not agree with this amount please do not hesitate to contact us. PLEASE note that any
										other payments must still be calculated by the Insurer where needed. <strong>Please note that a maximum of 
										25% mark-up will be allowed on parts.</strong></font></td>										



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $theemail .= "	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">Qty</td>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Description</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Quoted</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Actual Cost</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Mark Up</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Adjustment</td>



									</tr>";

        while ($itemrow = mysql_fetch_array($qryitemsresults))
        {

            $qty = $itemrow["qty"];

            $desc = stripslashes($itemrow["description"]);

            $quoted = $itemrow["quoted"];

            $cost = $itemrow["cost"];

            $onetwofive = $itemrow["onetwofive"];

            $adjustment = $itemrow["adjustment"];

            $theemail .= "	<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">$qty</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$desc</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quoted</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$cost</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofive</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustment</td>



									</tr>";

        }

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $theemail .= "		<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Total</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quotedtotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$costtotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofivetotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">VAT</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($onetwofivetotal * 0.15), 2) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Total (Inc VAT)</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($onetwofivetotal * 1.15), 2) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Min Betterment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$betterment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Min Excess</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$excess</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:0px;border-right:1px solid #000000;\">(Payable to Repairer - Excl Towing) <strong>Total</strong></td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" . (round
            (($onetwofivetotal * 1.15), 2) - $betterment - $excess) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td colspan=\"2\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Total Adjustment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustmenttotal</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td colspan=\"2\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Total Adj. (Inc VAT)</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($adjustmenttotal * 1.15), 2) . "</td>



									</tr>



									<tr>



										<td colspan=\"6\" style=\"border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"6\" style=\"border-right:1px solid #000000;border-left:1px solid #000000;border-bottom:1px solid #000000;border-top:1px solid #000000\">In order to expedite your claim, please sign the adjustment email back to us, as soon as possible. malan@aci.co.za</td>



									</tr>



								</table>";

        $theemail .= "<br>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>$pbname</td>



										<td style=\"width:250px;height:50px;border-top:1px solid #000000;border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" border=\"0\" cellspacing=\"0\">&nbsp;</td>



										<td style=\"padding-left:20px;\">Date</td>



										<td><strong>" . date("j M Y") . "</strong></td>										



									</tr>



								</table>";

        echo $theemail;

        echo "<br>	<form method=\"post\" action=\"reports.php?action=pbfaxemail&amp;claimid=$claimid\" name=\"theform\">



									<input type=\"button\" value=\"Printer Friendly\" onClick=\"



												document.theform.email.value = 'no';



												document.theform.submit();\" > <input type=\"button\" value=\"Email Report\" onClick=\"



												document.theform.email.value = 'yes';



												document.theform.submit();\" > <input type=\"hidden\" name=\"email\" value=\"no\">



								</form>";

    }

    /***************************************************************************



    END OF PBFAX SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBFAXEMAIL SECTION



    ***************************************************************************/

    if ($action == "pbfaxemail")
    {

        //echo $_REQUEST["email"];

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);
        
        $pbemail = stripslashes($qrypbrow["email"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:142px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>



										<td style=\"width:172px;\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">A.C.I. (Auto Claims Investigation)</font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339 Fax:086 673-9392<br>



											P.O.Box 494, Stellenbosch, 7599</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">admin@aci.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\"><font style=\"font-size:10pt;font-family:Arial;\">Details:</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">The following
										adjustment will be made to the above claim for parts/labour/paint supplied and/or less paid for parts quoted. 
										Please note that this adjustment amount will be deducted from Original Authorized amount and extra's will be added, 
										where necessary. If you do not agree with this amount please do not hesitate to contact us. PLEASE note that 
										any other payments must still be calculated by the Insurer where needed. <strong>Please note that a maximum of 
										25% mark-up will be allowed on parts.</strong></font></td>										



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $theemail .= "	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">Qty</td>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Description</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Quoted</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Actual Cost</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Mark Up</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Adjustment</td>



									</tr>";

        while ($itemrow = mysql_fetch_array($qryitemsresults))
        {

            $qty = $itemrow["qty"];

            $desc = stripslashes($itemrow["description"]);

            $quoted = $itemrow["quoted"];

            $cost = $itemrow["cost"];

            $onetwofive = $itemrow["onetwofive"];

            $adjustment = $itemrow["adjustment"];

            $theemail .= "	<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">$qty</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$desc</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quoted</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$cost</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofive</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustment</td>



									</tr>";

        }

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $theemail .= "		<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Total</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quotedtotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$costtotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofivetotal</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">VAT</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($onetwofivetotal * 0.15), 2) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Total (Inc VAT)</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($onetwofivetotal * 1.15), 2) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Min Betterment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$betterment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:210px;border-right:1px solid #000000;\">Min Excess</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$excess</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:0px;border-right:1px solid #000000;\">(Payable to Repairer - Excl Towing) <strong>Total</strong></td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" . (round
            (($onetwofivetotal * 1.15), 2) - $betterment - $excess) . "</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"2\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td colspan=\"2\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Total Adjustment</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustmenttotal</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"padding-left:0px;border-right:1px solid #000000;\">&nbsp;</td>



										<td colspan=\"2\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Total Adj. (Inc VAT)</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($adjustmenttotal * 1.15), 2) . "</td>



									</tr>



									<tr>



										<td colspan=\"6\" style=\"border-right:1px solid #000000;\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"6\" style=\"border-right:1px solid #000000;border-left:1px solid #000000;border-bottom:1px solid #000000;border-top:1px solid #000000\">In order to expedite your claim, please sign the adjustment and email back to us, as soon as possible. malan@aci.co.za</td>



									</tr>



								</table>";

        $theemail .= "<br>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>$pbname</td>



										<td style=\"width:250px;height:50px;border-top:1px solid #000000;border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" border=\"0\" cellspacing=\"0\">&nbsp;</td>



										<td style=\"padding-left:20px;\">Date</td>



										<td><strong>" . date("j M Y") . "</strong></td>										



									</tr>



								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {
            
            $emailaddress = $pbemail;

            require_once ('connection.php');

            require ("email_message.php");

            //get from name and address

            $from_address = "malan@aci.co.za";

            $from_name = "A.C.I.";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            $to_name = $pbname;

            $to_address = $emailaddress;

            //get the newsletter info

            $subject = "Adjustment; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);

            $email_message->SetEncodedEmailHeader("To", $to_address, $to_name);
			
			$email_message->SetEncodedEmailHeader("Bcc", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();
//echo "Message sent to $to_name, $to_address:<br />";
            if (strcmp($error, ""))
            {

                echo "Error: <b>$error</b> <br />";

            }

            else
            {

                echo "Panelbeater Re-con E-mailed to $to_name, $to_address:<br />";
                
                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Panelbeaters Re-con emailed to $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF PBFAXEMAIL SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBDOCREQUEST SECTION



    ***************************************************************************/

    if ($action == "pbdocrequest")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbtelno = stripslashes($qrypbrow["contactno"]);

        $pbfaxno = stripslashes($qrypbrow["faxno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:142px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>



										<td style=\"width:172px;\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">A.C.I. </font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339 <br>



											P.O.Box 494, Stellenbosch, 7599</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">admin@aci.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Telephone No:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbtelno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax No:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbfaxno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>

									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>

									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">To whom it may concern:<br><br>



										Please note that in order to finalise the above claim we require a Final Costing and copies of all
										suppliers invoices. Please email all documents to admin@aci.co.za as soon as possible. No payment for the above 
										claim will be made until we have received all such documentation. The claim will be authorised for payment within 48 hours 
										after recieving all relavant documents.<br><br>



										All original documentation must be kept for future reference. Please notify Partfinders.co.za via email to collect salvage 
										parts. Email photo/photos of all salvage parts to collect@partfinders.co.za. <br><br>

										If you have any questions, please do not hesitate to contact us.<br><br>



										Regards</font></td>



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">admin@aci.co.za</font></td>



									</tr>



								</table>";

        echo $theemail;

        echo "<br>	<form method=\"post\" action=\"reports.php?action=pbdocrequestemail&amp;claimid=$claimid\" name=\"theform\">



									<input type=\"button\" value=\"Printer Friendly\" onClick=\"



												document.theform.email.value = 'no';



												document.theform.submit();\" > <input type=\"button\" value=\"Email Report\" onClick=\"



												document.theform.email.value = 'yes';



												document.theform.submit();\" > <input type=\"hidden\" name=\"email\" value=\"no\">



								</form>";

    }

    /***************************************************************************



    END OF PBDOCREQUEST SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBDOCREQUESTEMAIL SECTION



    ***************************************************************************/

    if ($action == "pbdocrequestemail")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbtelno = stripslashes($qrypbrow["contactno"]);

        $pbfaxno = stripslashes($qrypbrow["faxno"]);
        
        $pbemail = stripslashes($qrypbrow["email"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:142px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>



										<td style=\"width:172px;\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">A.C.I. </font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339 <br>



											P.O.Box 494, Stellenbosch, 7599</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">admin@aci.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Telephone No:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbtelno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax No:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbfaxno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>

									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>

									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">To whom it may concern:<br><br>



										Please note that in order to finalise the above claim we require a Final Costing and copies of all
										suppliers invoices. Please email all documents to admin@aci.co.za as soon as possible. No payment for the above 
										claim will be made until we have received all such documentation. The claim will be authorised for payment within 48 hours 
										after recieving all relavant documents.<br><br>



										All original documentation must be kept for future reference. Please notify Partfinders.co.za via email to collect salvage 
										parts. Email photo/photos of all salvage parts to collect@partfinders.co.za. <br><br>



										If you have any questions, please do not hesitate to contact us.<br><br>



										Regards</font></td>



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">admin@aci.co.za</font></td>



									</tr>



								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $emailaddress = $pbemail;

            require_once ('connection.php');

            require ("email_message.php");

            //get from name and address

            $from_address = "admin@aci.co.za";

            $from_name = "ACI";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            $to_name = $pbname;

            $to_address = $emailaddress;

            //get the newsletter info

            $subject = "Document Request; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);

            $email_message->SetEncodedEmailHeader("To", $to_address, $to_name);

            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();

            if (strcmp($error, ""))
            {

                echo "Error: <b>$error</b> <br />";

            }

            else
            {

                echo "Message sent to $to_name<br />";

                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Document Request send to Panelbeater, $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

                $qryupdatedates = "update dates set docreq = NOW() where claimid = $claimid";

                $qryupdatedatesresults = mysql_query($qryupdatedates, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF PBDOCREQUESTEMAIL SECTION



    ***************************************************************************/
	
	/***************************************************************************



    START OF PBPARTSREQUEST SECTION



    ***************************************************************************/

    if ($action == "pbpartsrequest")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbtelno = stripslashes($qrypbrow["contactno"]);

        $pbfaxno = stripslashes($qrypbrow["faxno"]);
		
		$pbemail = stripslashes($qrypbrow["email"]);

        $makemodel = stripslashes($claimrow["makemodel"]);
		
		$vehiclemakeid = stripslashes($claimrow["vehiclemakeid"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);
		
		$vehicledesc =  $makemodel . " (" . $vehicleyear . "), Reg No: " . $vehicleregistrationno .
            ", " . $vehiclecolour ;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>


									


											<td align=\"middle\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">Partfinders.co.za</font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0860017404<br>



											P.O.Box 393, Private Bag X3, Somerset West, 7130</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">collect@partfinders.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:20px;\"><font style=\"font-size:10pt;font-family:Arial;\">To Repairer:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>

										<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Telephone No:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbtelno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Email:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbemail</font></td>



									</tr>


									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\"> $vehicleMake $vehicledesc</font></td>										



									</tr>



									



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Ref:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">To whom it may concern:<br><br>



										<mark>Please note that the above claim was Authorised by the Insurer on condition that all salvage parts are kept for collection.
										The Insurer reserves the right to remove markup on parts where parts where not kept for salvage collection.<br><br>
										</mark>


										Please notify Partfinders.co.za to collect salvage parts with photo off all parts to be collected via email to collect@partfinders.co.za. 
										If parts are not collected within 7 working days, please dispose off.<br><br>
										
										If you can not find used parts as requested, please request parts from webmaster@partfinders.co.za. Please note that the Insurer reserves the right to 
										only allow 2nd hand prices if webmaster@partfinders.co.za was not contacted for parts. Tel:0860017404 <br><br>
										
                                        If you have any questions, please do not hesitate to contact us.<br><br>


										Regards</font></td>



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>


										

										

										
										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\"><img src=\"http://www.a-c-i.co.za/images/partfinders.png\"></font></td>

									

									</tr>



								</table>";

        echo $theemail;

        echo "<br>	<form method=\"post\" action=\"reports.php?action=pbpartsrequestemail&amp;claimid=$claimid\" name=\"theform\">



									<input type=\"button\" value=\"Printer Friendly\" onClick=\"



												document.theform.email.value = 'no';



												document.theform.submit();\" > <input type=\"button\" value=\"Email Report\" onClick=\"



												document.theform.email.value = 'yes';



												document.theform.submit();\" > <input type=\"hidden\" name=\"email\" value=\"no\">



								</form>";

    }

    /***************************************************************************



    END OF PBPARTSREQUEST SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBPARTSREQUESTEMAIL SECTION



    ***************************************************************************/

    if ($action == "pbpartsrequestemail")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbtelno = stripslashes($qrypbrow["contactno"]);

        $pbfaxno = stripslashes($qrypbrow["faxno"]);
        
        $pbemail = stripslashes($qrypbrow["email"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:142px;\"><font style=\"font-size:36pt;font-family:Arial;font-weight:bold;\">Notice</font></td>



										<td style=\"width:100px;\"><img src=\"http://www.a-c-i.co.za/images/blank.gif\"></td>



										<td align=\"middle\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">Partfinders.co.za</font><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0860017404<br>



											P.O.Box 393 Private Bag X3, Somerset West, 7130</font><br>



											<font style=\"font-size:10pt;font-family:Arial;color:blue;\">collect@partfinders.co.za</font>											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:20px;\"><font style=\"font-size:10pt;font-family:Arial;\">To Repairer:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Attention:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>

										<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Telephone No:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbtelno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Email:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbemail</font></td>



									</tr>


									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle:</font></td>



										<td colspan=\"3\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>



									



									



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Insurer:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Ref:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\">To whom it may concern:<br><br>



										<mark>Please note that the above claim was Authorised by the Insurer on condition that all salvage parts are kept for collection.
										The Insurer reserves the right to remove markup on parts where parts where not kept for salvage collection.<br><br>
										</mark>


										Please notify Partfinders.co.za to collect salvage parts with photo off all parts to be collected via email to collect@partfinders.co.za. 
										If parts are not collected within 7 working days, please dispose off.<br><br>
										
										If you can not find used parts as requested, please request parts from sales@partfinders.co.za. Please note that the Insurer reserves the right to 
										only allow 2nd hand prices if sales@partfinders.co.za was not contacted for parts. Tel:0860017404 <br><br>
										
                                        If you have any questions, please do not hesitate to contact us.<br><br>


										Regards</font></td>



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>										



									</tr>



									<tr>


										

										

										
										<td colspan=\"4\" ><font style=\"font-size:10pt;font-family:Arial;\"><img src=\"http://www.a-c-i.co.za/images/partfinders.png\"></font></td>

									

									</tr>




								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $emailaddress = $pbemail;
			
			require_once ('connection.php');

            require ("email_message.php");

            //get from name and address

            $from_address = "collect@partfinders.co.za";

            $from_name = "Partfinders.co.za";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            $to_name = $pbname;

            $to_address = $emailaddress;
			
			$to_name2 = $owneremail;

            $to_address2 = $emailaddress2;
			
			
            //get the newsletter info

            $subject = "Salvage Parts Request; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);

            $email_message->SetEncodedEmailHeader("To", $to_address, $to_name);
			
			$email_message->SetEncodedEmailHeader("Cc", $from_address, $from_name);
			
			$email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();

            if (strcmp($error, ""))
            {

                echo "Error: <b>$error</b> <br />";

            }

            else
            {

                echo "Parts Request Email Message sent to $to_name<br />";

                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Parts Request send to Panelbeater, $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

                $qryupdatedates = "update dates set docreq = NOW() where claimid = $claimid";

                $qryupdatedatesresults = mysql_query($qryupdatedates, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF PBPARTSREQUESTEMAIL SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF AUDITREPORT SECTION



    ***************************************************************************/

    if ($action == "auditreport")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        echo "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold\">CLAIMS RE-ADJUSTMENT REPORT</font><br><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339 </font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Panelbeater No:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Inspection Date:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$inspectiondate</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Panelbeater:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Contact Person:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\" style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



									</tr>



									<tr>



										<td colspan=\"2\">&nbsp;</td>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>								



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">Details:</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">The above vehicle/invoices 
										was inspected. Upon inspection it was found that some parts was not replaced as quoted and/or also less was paid for parts quoted.
										The adjustment is as follows;<br><br><br><br></font></td>										



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        echo "	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">Qty</td>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Description</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Quoted</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Actual Cost</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Mark Up</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Adjustment</td>



									</tr>";

        while ($itemrow = mysql_fetch_array($qryitemsresults))
        {

            $qty = $itemrow["qty"];

            $desc = stripslashes($itemrow["description"]);

            $quoted = $itemrow["quoted"];

            $cost = $itemrow["cost"];

            $onetwofive = $itemrow["onetwofive"];

            $adjustment = $itemrow["adjustment"];

            echo "	<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">$qty</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$desc</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quoted</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$cost</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofive</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustment</td>



									</tr>";

        }

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        echo "		<tr>



										<td colspan=\"3\" style=\"padding-left:0px;\" align=\"left\">Claims Investigator: <strong>$investigator</strong></td>



										<td colspan=\"2\" align=\"right\" style=\"border-right:1px solid #000000;\">Total Adjustment:</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustmenttotal</td>



									</tr>



									<tr>



										<td colspan=\"3\" align=\"left\">&nbsp;</td>



										<td colspan=\"2\" align=\"right\" style=\"border-right:1px solid #000000;\">Total Adj. Inc VAT:</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($adjustmenttotal * 1.15), 2) . "</td>



									</tr>



								</table>";

        $qry = "select * from reportauditreport where `claimid` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $count = mysql_num_rows($qryresults);

        if ($count != 0)
        {

            $therow = mysql_fetch_array($qryresults);

            //$authamount = $therow["authamount"];

            $extras = $therow["extras"];

            $balofsav = $therow["balofsav"];

            $acisavings = $therow["acisavings"];

            $totalsavings = $therow["totalsavings"];

            $towingother = $therow["towingother"];

            $percent = $therow["percent"];

            $assessingfees = $therow["assessingfees"];

            $otherfees = $therow["otherfees"];

            //$total = $therow["total"];

            //$betterment = $therow[""]

        }

        else
        {

            $extras = "0.00";

            $balofsav = "0.00";

            $acisavings = round(($adjustmenttotal * 1.15), 2);

            $totalsavings = round(($adjustmenttotal * 1.15), 2);

            $towingother = "0.00";

            $percent = "0.00";

            $assessingfees = "0.00";

            $otherfees = "0.00";

            //$total = round(($authamount + ($adjustmenttotal * 1.15)), 2);

        }

        echo "	<form method=\"post\" action=\"reports.php?action=auditreportprint&amp;claimid=$claimid\" name=\"theform\">



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Original Authorized Amount</td>



										<td><input type=\"text\" name=\"authamount\" value=\"$authamount\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>(Please change this amount, if necessary)</td>



									</tr>



									<tr>



										<td>+ Extras/Additional (Incl. VAT)</td>



										<td><input type=\"text\" name=\"extras\" value=\"$extras\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>(Please change this amount, if necessary)</td>



									</tr>



									<tr>



										<td>- Bal. of sav. as per Recon (Inc VAT)</td>



										<td><input type=\"text\" name=\"balofsav\" value=\"$balofsav\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>(Not including ACI savings)</td>



									</tr>



									<tr>



										<td>- ACI Savings (Inc. VAT)</td>



										<td><input type=\"text\" name=\"acisavings\" value=\"" . round(($adjustmenttotal * 1.15), 2) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>(Total Savings as per P/B and ACI) <input type=\"text\" name=\"totalsavings\" value=\"$totalsavings\" style=\"text-align:right;width:75px;\" ></td>



									</tr>	



									<tr>



										<td>Total (Inc VAT)</td>



										<td><input type=\"text\" name=\"total\" value=\"$total\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>(Not including ACI savings)</td>



									</tr>



									<tr>



										<td>- Betterment</td>



										<td><input type=\"text\" name=\"betterment\" value=\"$betterment\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>- Excess paid by client</td>



										<td><input type=\"text\" name=\"excessbyclient\" value=\"$excess\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Total (Inc VAT)</td>



										<td><input type=\"text\" name=\"total2\" value=\"" . ((round(($authamount +
            ($adjustmenttotal * 1.15)), 2)) - $betterment - $excess) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Towing & Other Fees (Inc VAT)</td>



										<td><input type=\"text\" name=\"towingother\" value=\"$towingother\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>(Please change this amount, if necessary)</td>



									</tr>



									<tr>



										<td>Total (Inc VAT)</td>



										<td><input type=\"text\" name=\"total3\" value=\"" . ((round(($authamount +
            ($adjustmenttotal * 1.15)), 2)) - $betterment - $excess) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Percentage Discount <input type=\"text\" name=\"percent\" value=\"$percent\" style=\"text-align:right;width:35px;\" onKeyUp=\"DoCalc();\" > %</td>



										<td><input type=\"text\" name=\"discount\" value=\"0.00\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>(Please change percentage discount, where needed)</td>



									</tr>



									<tr>



										<td>Payable to Panelbeater</td>



										<td><input type=\"text\" name=\"paypanelbeater\" value=\"" . ((round(($authamount +
            ($adjustmenttotal * 1.15)), 2)) - $betterment - $excess) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Payable to ACI</td>



										<td><input type=\"text\" name=\"payaci\" value=\"" . round((($adjustmenttotal *
            -0.45) * 1.15), 2) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Assessing Fees</td>



										<td><input type=\"text\" name=\"assessingfees\" value=\"$assessingfees\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Other Fees</td>



										<td><input type=\"text\" name=\"otherfees\" value=\"$otherfees\" style=\"text-align:right;width:75px;\" onKeyUp=\"DoCalc();\" ></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td>Total Claim</td>



										<td><input type=\"text\" name=\"totalclaim\" value=\"" . ((((round(($authamount +
            ($adjustmenttotal * 1.15)), 2)) - $betterment - $excess)) + (round((($adjustmenttotal *
            -0.45) * 1.15), 2))) . "\" style=\"text-align:right;width:75px;\" readonly></td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"3\" align=\"center\"><strong><br>Please note that all Grey areas can be changed to calculated final payments</strong></td>



									</tr>



								</table>



								<br><input type=\"button\" value=\"Printer Friendly\" onClick=\"document.theform.email.value = 'no';



								                                                                document.theform.submit(); \"> 



									<input type=\"button\" value=\"Email Report\" onClick=\"document.theform.email.value = 'yes';



									                                                        document.theform.submit(); \">



									                                                        



									 <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\"> <input type=\"hidden\" name=\"email\">



							</form>";

    }

    /***************************************************************************



    END OF AUDITREPORT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF AUDITREPORTPRINT SECTION



    ***************************************************************************/

    if ($action == "auditreportprint")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"left\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold\">CLAIMS RE-ADJUSTMENT REPORT</font><br><br>



											<font style=\"font-size:10pt;font-family:Arial;\">Tel:0861114339</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Client Name:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Claim Number:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Panelbeater No:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactno</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Inspection Date:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$inspectiondate</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Panelbeater:</font></td>



										<td style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbname</font></td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;\">Contact Person:</font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$pbcontactperson</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Vehicle Description:</font></td>



										<td colspan=\"3\" style=\"width:212px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$vehicledesc</font></td>										



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Assessor:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$assessor</font></td>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">ACI Reference:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



									</tr>



									<tr>



										<td colspan=\"2\">&nbsp;</td>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Date:</font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>								



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">Details:</font></td>										



									</tr>



									<tr>



										<td colspan=\"4\" style=\"text-align:justify;\"><font style=\"font-size:10pt;font-family:Arial;\">The above vehicle/invoices was inspected. 
										Upon inspection it was found that some parts was not replaced as quoted and/or also less was paid for parts quoted. The adjustment is as 
										follows;<br><br><br><br></font></td>										



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $theemail .= "	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">Qty</td>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Description</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Quoted</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Actual Cost</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Mark Up</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">Adjustment</td>



									</tr>";

        while ($itemrow = mysql_fetch_array($qryitemsresults))
        {

            $qty = $itemrow["qty"];

            $desc = stripslashes($itemrow["description"]);

            $quoted = $itemrow["quoted"];

            $cost = $itemrow["cost"];

            $onetwofive = $itemrow["onetwofive"];

            $adjustment = $itemrow["adjustment"];

            $theemail .= "	<tr>



										<td align=\"center\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">$qty</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$desc</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$quoted</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$cost</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$onetwofive</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustment</td>



									</tr>";

        }

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        $theemail .= "		<tr>



										<td colspan=\"3\" style=\"padding-left:0px;\" align=\"left\">Claims Investigator: <strong>$investigator</strong></td>



										<td colspan=\"2\" align=\"right\" style=\"border-right:1px solid #000000;\">Total Adjustment:</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$adjustmenttotal</td>



									</tr>



									<tr>



										<td colspan=\"3\" align=\"left\">&nbsp;</td>



										<td colspan=\"2\" align=\"right\" style=\"border-right:1px solid #000000;\">Total Adj. Inc VAT:</td>



										<td align=\"right\" style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">" .
            round(($adjustmenttotal * 1.15), 2) . "</td>



									</tr>



								</table>";

        $authamount = round($_REQUEST["authamount"], 2);

        $qryupdateclaim = "update claim set `authamount` = '$authamount' where id = $claimid";

        $qryupdateclaimresults = mysql_query($qryupdateclaim, $db);

        $extras = round($_REQUEST["extras"], 2);

        $balofsav = round($_REQUEST["balofsav"], 2);

        $acisavings = round($_REQUEST["acisavings"], 2);

        $totalsavings = round($_REQUEST["totalsavings"], 2);

        $total = round($_REQUEST["total"], 2);

        $betterment = round($_REQUEST["betterment"], 2);

        $excessbyclient = round($_REQUEST["excessbyclient"], 2);

        $total2 = round($_REQUEST["total2"], 2);

        $towingother = round($_REQUEST["towingother"], 2);

        $total3 = round($_REQUEST["total3"], 2);

        $percent = $_REQUEST["percent"];

        $discount = round($_REQUEST["discount"], 2);

        $paypanelbeater = round($_REQUEST["paypanelbeater"], 2);

        $payaci = round($_REQUEST["payaci"], 2);

        $assessingfees = round($_REQUEST["assessingfees"], 2);

        $otherfees = round($_REQUEST["otherfees"], 2);

        $totalclaim = round($_REQUEST["totalclaim"], 2);

        $qrycheckreport = "select * from reportauditreport where claimid = $claimid";

        $qrycheckreportresults = mysql_query($qrycheckreport, $db);

        $count = mysql_num_rows($qrycheckreportresults);

        if ($count == 0)
        {

            $qryinsert = "insert into reportauditreport (`claimid`, `authamount`, `extras`, `balofsav`, `acisavings`, `totalsavings`, 
																		 `total`, `betterment`, `excessbyclient`, `total2`, `towingother`, `total3`,
																		 `percent`, `discount`, `paypanelbeater`, `payaci`, `assessingfees`,
																		 `otherfees`, `totalclaim`) 
															 	 values ($claimid, '$authamount', '$extras', '$balofsav', '$acisavings', 
																         '$totalsavings', '$total', '$betterment', '$excessbyclient', '$total2',
																         '$towingother', '$total3', '$percent', '$discount', '$paypanelbeater',
																         '$payaci', '$assessingfees', '$otherfees', '$totalclaim')";

            $qryinsertresults = mysql_query($qryinsert, $db);

        }

        else
        {

            $qryupdate = "update reportauditreport set `authamount` = '$authamount',     `extras` = '$extras',
																	   `balofsav` = '$balofsav',         `acisavings` = '$acisavings',
																	   `totalsavings` = '$totalsavings', `total` = '$total',
																	   `betterment` = '$betterment',     `excessbyclient` = '$excessbyclient',
																	   `total2` = '$total2',             `towingother` = '$towingother',
																	   `total3` = '$total3',		     `percent` = '$percent',
																	   `discount` = '$discount',  	     `paypanelbeater` = '$paypanelbeater',
																	   `payaci` = '$payaci',		     `assessingfees` = '$assessingfees',
																	   `otherfees` = '$otherfees',	     `totalclaim` = '$totalclaim' 
																 where `claimid` = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);
            
            //echo "<textarea cols=\"150\" rows=\"20\">" . $qryupdate . "</textarea>";

        }

        $theemail .= "	<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Original Authorized Amount</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$authamount</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>+ Extras/Additional (Incl. VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$extras</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>- Bal. of sav. as per Recon (Inc VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$balofsav</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>- ACI Savings (Inc. VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$acisavings</strong></td>



										<td style=\"padding-left:20px;\">(Total Savings as per P/B and ACI) </td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;border-bottom:1px solid #000000;\"><strong>$totalsavings</strong></td>



									</tr>	



									<tr>



										<td>Total (Inc VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$total</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>- Betterment</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$betterment</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>- Excess paid by client</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$excessbyclient</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Total (Inc VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$total2</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Towing & Other Fees (Inc VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$towingother</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Total (Inc VAT)</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$total3</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Percentage Discount <strong>$percent %</strong></td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$discount</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Payable to Panelbeater</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$paypanelbeater</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Payable to ACI</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$payaci</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Assessing Fees</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$assessingfees</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Other Fees</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\"><strong>$otherfees</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



									<tr>



										<td>Total Claim</td>



										<td align=\"right\" style=\"border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;border-bottom:1px solid #000000;\"><strong>$totalclaim</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $qrygetclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

            //echo $qrygetclaimsclerk;

            $qrygetclaimsclerkresults = mysql_query($qrygetclaimsclerk, $db);

            $theccrow = mysql_fetch_array($qrygetclaimsclerkresults);

            $emailaddress = $theccrow["email"];

            $claimsclerk = $theccrow["name"];


            require_once ('connection.php');

            require ("email_message.php");

            //get from name and address

            $from_address = "admin@aci.co.za";

            $from_name = "Auto Claims Investigation";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            //$emailaddress = "rudivdwalt@gmail.com";//comment out this line once the thing is live...

            $to_name = $claimsclerk;

            $to_address = explode(",", $emailaddress);

            //get the newsletter info

            $subject = "Audit Report; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);
			
			foreach ($to_address as $email)
			{
				$email_message->SetEncodedEmailHeader("To", $email, $to_name);
			}
			
            

            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();
            
            echo "<h1>$to_address</h1>";

            if (strcmp($error, ""))
            {

                echo "Error: $error <br />";

            }

            else
            {

                echo "Message sent to $to_name <br />";

                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Audit Report emailed to $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF AUDITREPORTPRINT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF INVOICE SECTION



    ***************************************************************************/

    if ($action == "invoice")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        echo "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"right\">



											<font style=\"font-size:27pt;font-family:Arial;font-weight:bold;color:#c0c0c0;\">INVOICE</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">P.O.Box 494</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>DATE:</strong></em></font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Stellenbosch</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>INVOICE #</strong></em></font></td>



										<td style=\"width:200px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">7599</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Tel: 0861114339</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>FOR:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">Auto Claims Adjustment</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax: 086 673-9392</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>BILL TO:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">VAT 4110230853</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$insurancecomp</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">VAT 4380101289</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>CLAIM NO</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>			



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        $qrycheck = "select * from reportinvoice where `claimid` = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $count = @mysql_num_rows($qrycheckresults);

        if ($count != 0)
        {

            $invoicerow = mysql_fetch_array($qrycheckresults);

            $desc1 = $invoicerow["1d"];

            $desc2 = $invoicerow["2d"];

            $desc3 = $invoicerow["3d"];

            $desc4 = $invoicerow["4d"];

            $desc5 = $invoicerow["5d"];

            $desc6 = $invoicerow["6d"];

            $desc7 = $invoicerow["7d"];

            $desc8 = $invoicerow["8d"];

            $desc9 = $invoicerow["9d"];

            $desc10 = $invoicerow["10d"];

            $desc11 = $invoicerow["11d"];

            $desc12 = $invoicerow["12d"];

            $desc13 = $invoicerow["13d"];

            $desc14 = $invoicerow["14d"];

            $amount1 = $invoicerow["1a"];

            $amount2 = $invoicerow["2a"];

            $amount3 = $invoicerow["3a"];

            $amount4 = $invoicerow["4a"];

            $amount5 = $invoicerow["5a"];

            $amount6 = $invoicerow["6a"];

            $amount7 = $invoicerow["7a"];

            $amount8 = $invoicerow["8a"];

            $amount9 = $invoicerow["9a"];

            $amount10 = $invoicerow["10a"];

            $amount11 = $invoicerow["11a"];

            $amount12 = $invoicerow["12a"];

            $amount13 = $invoicerow["13a"];

            $amount14 = $invoicerow["14a"];

            $other = $invoicerow["other"];

        }

        else
        {

            $desc1 = "";

            $desc2 = "";

            $desc3 = "";

            $desc4 = "";

            $desc5 = "";

            $desc6 = "";

            $desc7 = "";

            $desc8 = "";

            $desc9 = "";

            $desc10 = "";

            $desc11 = "";

            $desc12 = "";

            $desc13 = "";

            $desc14 = "";

            $amount1 = "";

            $amount2 = "";

            $amount3 = "";

            $amount4 = "";

            $amount5 = "";

            $amount6 = "";

            $amount7 = "";

            $amount8 = "";

            $amount9 = "";

            $amount10 = "";

            $amount11 = "";

            $amount12 = "";

            $amount13 = "";

            $amount14 = "";

            $other = "";

        }

        echo "<form method=\"post\" action=\"reports.php?action=invoiceprint&amp;claimid=$claimid\" name=\"theform\">	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>DESCRIPTION</strong></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;background-color:#C0C0C0;\"><strong>AMOUNT</strong></td>										



									</tr>



									<tr>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">45% of Saving</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$clientname</td>



										<td style=\"border-bottom:1px solid #000000;\">$adjustmenttotal</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">" .
            round(($adjustmenttotal * -0.45), 2) . "</td>



									</tr>



									



<script type=\"text/javascript\">



	function DoItNow()



	{



		var total;



		total = " . round(($adjustmenttotal * -0.45), 2) .
            " + (document.theform.amount1.value * 1) + 



		                                                       (document.theform.amount2.value * 1) +



		                                                       (document.theform.amount3.value * 1) +



		                                                       (document.theform.amount4.value * 1) +



		                                                       (document.theform.amount5.value * 1) +



		                                                       (document.theform.amount6.value * 1) +



		                                                       (document.theform.amount7.value * 1) +



		                                                       (document.theform.amount8.value * 1) +



		                                                       (document.theform.amount9.value * 1) +



		                                                       (document.theform.amount10.value * 1) +



		                                                       (document.theform.amount11.value * 1) +



		                                                       (document.theform.amount12.value * 1) +



		                                                       (document.theform.amount13.value * 1) +



		                                                       (document.theform.amount14.value * 1);



		total = Math.round(total * 100) / 100;



		                                                       



		//alert(total);



		



		document.theform.subtotal.value = total;



		



		var totalincvat;



		



		totalincvat = total * 0.15;



		totalincvat = Math.round(totalincvat * 100) / 100;



		



		document.theform.vat.value = totalincvat;



		



		var bigtotal;



		



		bigtotal = total + totalincvat + (document.theform.other.value * 1);



		



		bigtotal = Math.round(bigtotal * 100) / 100;



		



		document.theform.total.value = bigtotal;







	}



</script>									



									



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc1\" style=\"width:99%\" value=\"$desc1\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" width=\"125\" align=\"right\"><input type=\"text\" name=\"amount1\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount1\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc2\" style=\"width:99%\" value=\"$desc2\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount2\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount2\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc3\" style=\"width:99%\" value=\"$desc3\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount3\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount3\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc4\" style=\"width:99%\" value=\"$desc4\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount4\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount4\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc5\" style=\"width:99%\" value=\"$desc5\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount5\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount5\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc6\" style=\"width:99%\" value=\"$desc6\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount6\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount6\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc7\" style=\"width:99%\" value=\"$desc7\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount7\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount7\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc8\" style=\"width:99%\" value=\"$desc8\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount8\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount8\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc9\" style=\"width:99%\" value=\"$desc9\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount9\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount9\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc10\" style=\"width:99%\" value=\"$desc10\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount10\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount10\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc11\" style=\"width:99%\" value=\"$desc11\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount11\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount11\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc12\" style=\"width:99%\" value=\"$desc12\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount12\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount12\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc13\" style=\"width:99%\" value=\"$desc13\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount13\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount13\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc14\" style=\"width:99%\" value=\"$desc14\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount14\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount14\"></td>



									</tr>



									<tr>



										<td colspan=\"2\" rowspan=\"4\">Bank Details; FIRST NATIONAL BANK No:62094861418, FLORIDA 250-141 If you have  any questions concerning this invoice, contact Irene 0861114339, admin@aci.co.za<br> <strong>THANK YOU FOR YOUR BUSINESS</strong></td>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>SUBTOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"subtotal\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45), 2) . "\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>VAT</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"vat\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45 * 0.15), 2) . "\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>OTHER</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"other\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$other\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>TOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"total\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45 * 1.15), 2) . "\"></td>



									</tr>



								</table>



								<br><br><input type=\"button\" value=\"Printer Friendly\" onClick=\"document.theform.email.value = 'no';



								                                                                document.theform.submit(); \"> 



									<input type=\"button\" value=\"Email Report\" onClick=\"document.theform.email.value = 'yes';



									                                                        document.theform.submit(); \"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"email\">



							</form>";

    }

    /***************************************************************************



    END OF INVOICE SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF INVOICEPRINT SECTION



    ***************************************************************************/

    if ($action == "invoiceprint")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\"><img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"></td>



										<td align=\"right\">



											<font style=\"font-size:27pt;font-family:Arial;font-weight:bold;color:#c0c0c0;\">INVOICE</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">P.O.Box 494</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>DATE:</strong></em></font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Stellenbosch</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>INVOICE #</strong></em></font></td>



										<td style=\"width:200px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$clientno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">7599</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Tel: 0861114339</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>FOR:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">Auto Claims Adjustment</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax: 086 673-9392</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>BILL TO:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">VAT 4110230853</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$insurancecomp</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">VAT 4380101289</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>CLAIM NO</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>			



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        $desc1 = $_REQUEST["desc1"];

        $desc2 = $_REQUEST["desc2"];

        $desc3 = $_REQUEST["desc3"];

        $desc4 = $_REQUEST["desc4"];

        $desc5 = $_REQUEST["desc5"];

        $desc6 = $_REQUEST["desc6"];

        $desc7 = $_REQUEST["desc7"];

        $desc8 = $_REQUEST["desc8"];

        $desc9 = $_REQUEST["desc9"];

        $desc10 = $_REQUEST["desc10"];

        $desc11 = $_REQUEST["desc11"];

        $desc12 = $_REQUEST["desc12"];

        $desc13 = $_REQUEST["desc13"];

        $desc14 = $_REQUEST["desc14"];

        $amount1 = $_REQUEST["amount1"];

        $amount2 = $_REQUEST["amount2"];

        $amount3 = $_REQUEST["amount3"];

        $amount4 = $_REQUEST["amount4"];

        $amount5 = $_REQUEST["amount5"];

        $amount6 = $_REQUEST["amount6"];

        $amount7 = $_REQUEST["amount7"];

        $amount8 = $_REQUEST["amount8"];

        $amount9 = $_REQUEST["amount9"];

        $amount10 = $_REQUEST["amount10"];

        $amount11 = $_REQUEST["amount11"];

        $amount12 = $_REQUEST["amount12"];

        $amount13 = $_REQUEST["amount13"];

        $amount14 = $_REQUEST["amount14"];

        $other = $_REQUEST["other"];

        $qrycheck = "select * from reportinvoice where `claimid` = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $count = @mysql_num_rows($qrycheckresults);

        if ($count == 0)
        {

            $qryinsert = "insert into reportinvoice (`claimid`, `1d`, `1a`, `2d`, `2a`, `3d`, `3a`, `4d`, `4a`, `5d`, `5a`, `6d`, `6a`, `7d`, `7a`, `8d`, `8a`, `9d`, `9a`, `10d`, `10a`, `11d`, `11a`, `12d`, `12a`, `13d`, `13a`, `14d`, `14a`, `other`) values ($claimid, '$desc1', '$amount1', '$desc2', '$amount2', '$desc3', '$amount3', '$desc4', '$amount4', '$desc5', '$amount5', '$desc6', '$amount6', '$desc7', '$amount7', '$desc8', '$amount8', '$desc9', '$amount9', '$desc10', '$amount10', '$desc11', '$amount11', '$desc12', '$amount12', '$desc13', '$amount13', '$desc14', '$amount14', '$other')";

            $qryinsertresults = mysql_query($qryinsert, $db);

            //echo $qryinsert;

        }

        else
        {

            $qryupdate = "update reportinvoice set `1d` = '$desc1', `1a` = '$amount1', `2d` = '$desc2', `2a` = '$amount2', 



							                                       `3d` = '$desc3', `3a` = '$amount3', `4d` = '$desc4', `4a` = '$amount4',



																   `5d` = '$desc5', `5a` = '$amount5', `6d` = '$desc6', `6a` = '$amount6',



																   `7d` = '$desc7', `7a` = '$amount7', `8d` = '$desc8', `8a` = '$amount8',



																   `9d` = '$desc9', `9a` = '$amount9', `10d` = '$desc10', `10a` = '$amount10',



																   `11d` = '$desc11', `11a` = '$amount11', `12d` = '$desc12', `12a` = '$amount12',



																   `13d` = '$desc13', `13a` = '$amount13', `14d` = '$desc14', `14a` = '$amount14',



																   `other` = '$other' where `claimid` = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);

            //echo $qryupdate;

        }

        $theemail .= "<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>DESCRIPTION</strong></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>AMOUNT</strong></td>										



									</tr>



									<tr>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\">45% of Saving</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;\">$clientname</td>



										<td style=\"border-bottom:1px solid #000000;\">$adjustmenttotal</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">" .
            round(($adjustmenttotal * -0.45), 2) . "</td>



									</tr>







									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc1"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" width=\"125\" align=\"right\">&nbsp;" .
            $_REQUEST["amount1"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc2"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount2"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc3"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount3"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc4"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount4"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc5"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount5"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc6"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount6"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc7"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount7"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc8"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount8"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc9"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount9"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc10"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount10"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc11"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount11"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc12"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount12"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc13"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount13"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc14"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount14"] . "</td>



									</tr>



									<tr>



										<td colspan=\"2\" rowspan=\"4\">Bank Details; FIRST NATIONAL BANK No:62094861418, FLORIDA 250-141 If you have  any questions concerning this invoice, contact Irene 0861114339, admin@aci.co.za<br> <strong>THANK YOU FOR YOUR BUSINESS</strong></td>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>SUBTOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["subtotal"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>VAT</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["vat"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>OTHER</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["other"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>TOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["total"] . "</td>



									</tr>



								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $claimsclerkid = $claimrow["claimsclerkid"];

            $qrygetclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

            //echo $qrygetclaimsclerk;

            $qrygetclaimsclerkresults = mysql_query($qrygetclaimsclerk, $db);

            $theccrow = @mysql_fetch_array($qrygetclaimsclerkresults);

            $emailaddress = $theccrow["email"];

            $claimsclerk = $theccrow["name"];

            if (strlen($emailaddress) == 0)
            {

                //$emailaddress = "rudivdwalt@gmail.com";

            }

            require_once ('connection.php');

            require ("email_message.php");

            //get from name and address

            $from_address = "admin@aci.co.za";

            $from_name = "Auto Claims Investigation";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            //$emailaddress = "rudivdwalt@gmail.com";//comment out this line once the thing is live...

            $to_name = $claimsclerk;

            $to_address = $emailaddress;

            //get the newsletter info
            
            $to_address = explode(",", $emailaddress);

            //get the newsletter info

            $subject = "Invoice; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);
			
			foreach ($to_address as $email)
			{
				$email_message->SetEncodedEmailHeader("To", $email, $to_name);
			}



            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();

            if (strcmp($error, ""))
            {

                echo "Error: $error <br />";

            }

            else
            {

                echo "Message sent to $to_name <br />";

                $qrysent = "insert into emailssent (`id`, `claimid`, `reportname`, `recipient`, `subjectline`, `senttoemail`, `datesent`, `timesent`)



								                                   ('', $claimid, 'Invoice', '$to_name', '$subject', '$to_address', CURDATE(), CURTIME())";

                $qrysentresults = mysql_query($qrysent, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF INVOICEPRINT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBINVOICE SECTION



    ***************************************************************************/

    if ($action == "pbinvoice")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        //get assessors details because they are supposed to be on top of this invoice

        $assadr1 = stripslashes($assrow["adr1"]);

        $assadr2 = stripslashes($assrow["adr2"]);

        $assadr3 = stripslashes($assrow["adr3"]);

        $assadr4 = stripslashes($assrow["adr4"]);

        $bankdetails = stripslashes($assrow["bankdetails"]);
        
        $assessorcompany = $assrow["company"];
		$assessortelno = $assrow["telno"];
		$assessorcellno = $assrow["cellno"];
		$assessorfaxno = $assrow["faxno"];
		$assessoradr1 = $assrow["adr1"];
		$assessoradr2 = $assrow["adr2"];
		$assessoradr3 = $assrow["adr3"];
		$assessoradr4 = $assrow["adr4"];
		$assessorvatno = $assrow["vatno"];

        echo "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\">";

        if (file_exists("../images/assessors/$assessorid.jpg"))
        {

            echo "<img src=\"../images/assessors/$assessorid.jpg\"><br />";

        }
        else
        {
			echo "<img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"><br />"; 
		}

        echo "       <font style=\"font-size:10pt;font-family:Arial;\">$assessorcompany</font></td>



										<td align=\"right\">



											<font style=\"font-size:27pt;font-family:Arial;font-weight:bold;color:#c0c0c0;\">INVOICE</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr1</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>DATE:</strong></em></font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr2</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>INVOICE #</strong></em></font></td>



										<td style=\"width:200px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">A$clientno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr3</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr4</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Tel: $assessortelno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>FOR:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">Assessment Fee</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax: $assessorfaxno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>BILL TO:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">VAT $assessorvatno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$insurancecomp</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessor</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">VAT 4380101289</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>CLAIM NO</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>			



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        $qrycheck = "select * from reportinvoice where `claimid` = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $count = @mysql_num_rows($qrycheckresults);

        if ($count != 0)
        {

            $invoicerow = mysql_fetch_array($qrycheckresults);

            $desc1 = $invoicerow["1d"];

            $desc2 = $invoicerow["2d"];

            $desc3 = $invoicerow["3d"];

            $desc4 = $invoicerow["4d"];

            $desc5 = $invoicerow["5d"];

            $desc6 = $invoicerow["6d"];

            $desc7 = $invoicerow["7d"];

            $desc8 = $invoicerow["8d"];

            $desc9 = $invoicerow["9d"];

            $desc10 = $invoicerow["10d"];

            $desc11 = $invoicerow["11d"];

            $desc12 = $invoicerow["12d"];

            $desc13 = $invoicerow["13d"];

            $desc14 = $invoicerow["14d"];

            $amount1 = $invoicerow["1a"];

            $amount2 = $invoicerow["2a"];

            $amount3 = $invoicerow["3a"];

            $amount4 = $invoicerow["4a"];

            $amount5 = $invoicerow["5a"];

            $amount6 = $invoicerow["6a"];

            $amount7 = $invoicerow["7a"];

            $amount8 = $invoicerow["8a"];

            $amount9 = $invoicerow["9a"];

            $amount10 = $invoicerow["10a"];

            $amount11 = $invoicerow["11a"];

            $amount12 = $invoicerow["12a"];

            $amount13 = $invoicerow["13a"];

            $amount14 = $invoicerow["14a"];

            $other = $invoicerow["other"];

        }

        else
        {

            $desc1 = "";

            $desc2 = "";

            $desc3 = "";

            $desc4 = "";

            $desc5 = "";

            $desc6 = "";

            $desc7 = "";

            $desc8 = "";

            $desc9 = "";

            $desc10 = "";

            $desc11 = "";

            $desc12 = "";

            $desc13 = "";

            $desc14 = "";

            $amount1 = "";

            $amount2 = "";

            $amount3 = "";

            $amount4 = "";

            $amount5 = "";

            $amount6 = "";

            $amount7 = "";

            $amount8 = "";

            $amount9 = "";

            $amount10 = "";

            $amount11 = "";

            $amount12 = "";

            $amount13 = "";

            $amount14 = "";

            $other = "";

        }

        echo "<form method=\"post\" action=\"reports.php?action=pbinvoiceprint&amp;claimid=$claimid\" name=\"theform\">	<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>DESCRIPTION</strong></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;background-color:#C0C0C0;\"><strong>AMOUNT</strong></td>										



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-right:0px solid #000000;border-left:1px solid #000000;\">Assessment Fee</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">750.00</td>



									</tr>



									



<script type=\"text/javascript\">



	function DoItNow()



	{



		var total;



		total = 750 + (document.theform.amount1.value * 1) + 



		                                                       (document.theform.amount2.value * 1) +



		                                                       (document.theform.amount3.value * 1) +



		                                                       (document.theform.amount4.value * 1) +



		                                                       (document.theform.amount5.value * 1) +



		                                                       (document.theform.amount6.value * 1) +



		                                                       (document.theform.amount7.value * 1) +



		                                                       (document.theform.amount8.value * 1) +



		                                                       (document.theform.amount9.value * 1) +



		                                                       (document.theform.amount10.value * 1) +



		                                                       (document.theform.amount11.value * 1) +



		                                                       (document.theform.amount12.value * 1) +



		                                                       (document.theform.amount13.value * 1) +



		                                                       (document.theform.amount14.value * 1);



		total = Math.round(total * 100) / 100;

		//total = total.toFixed(2);

		                                                       



		//alert(total);



		



		document.theform.subtotal.value = total;



		



		var totalincvat;



		



		totalincvat = total * 0.15;



		totalincvat = Math.round(totalincvat * 100) / 100;



		



		document.theform.vat.value = totalincvat;



		



		var bigtotal;



		



		bigtotal = total + totalincvat + (document.theform.other.value * 1);



		



		bigtotal = Math.round(bigtotal * 100) / 100;



		



		document.theform.total.value = bigtotal;







	}



</script>									



									



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc1\" style=\"width:99%\" value=\"$desc1\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" width=\"125\" align=\"right\"><input type=\"text\" name=\"amount1\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount1\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc2\" style=\"width:99%\" value=\"$desc2\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount2\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount2\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc3\" style=\"width:99%\" value=\"$desc3\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount3\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount3\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc4\" style=\"width:99%\" value=\"$desc4\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount4\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount4\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc5\" style=\"width:99%\" value=\"$desc5\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount5\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount5\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc6\" style=\"width:99%\" value=\"$desc6\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount6\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount6\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc7\" style=\"width:99%\" value=\"$desc7\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount7\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount7\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc8\" style=\"width:99%\" value=\"$desc8\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount8\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount8\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc9\" style=\"width:99%\" value=\"$desc9\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount9\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount9\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc10\" style=\"width:99%\" value=\"$desc10\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount10\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount10\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc11\" style=\"width:99%\" value=\"$desc11\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount11\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount11\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc12\" style=\"width:99%\" value=\"$desc12\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount12\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount12\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc13\" style=\"width:99%\" value=\"$desc13\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount13\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount13\"></td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\"><input type=\"text\" name=\"desc14\" style=\"width:99%\" value=\"$desc14\"></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"amount14\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$amount14\"></td>



									</tr>



									<tr>



										<td colspan=\"2\" rowspan=\"4\">Bank Details: $bankdetails<br> <strong>THANK YOU FOR YOUR BUSINESS</strong></td>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>SUBTOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"subtotal\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45), 2) . "\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>VAT</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"vat\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45 * 0.15), 2) . "\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>OTHER</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"other\" style=\"text-align:right\" onKeyUp=\"DoItNow()\" value=\"$other\"></td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>TOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\"><input type=\"text\" name=\"total\" style=\"text-align:right\" readonly value=\"" .
            round(($adjustmenttotal * -0.45 * 1.15), 2) . "\"></td>



									</tr>



								</table>



								<br><br><input type=\"button\" value=\"Printer Friendly\" onClick=\"document.theform.email.value = 'no';



								                                                                document.theform.submit(); \"> 



									<input type=\"button\" value=\"Email Report\" onClick=\"document.theform.email.value = 'yes';



									                                                        document.theform.submit(); \"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"email\">



							</form>";

    }

    /***************************************************************************



    END OF PBINVOICE SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF PBINVOICEPRINT SECTION



    ***************************************************************************/

    if ($action == "pbinvoiceprint")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $assessorid = $claimrow["assessorid"];

        $administratorid = $claimrow["administratorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $assadr1 = stripslashes($assrow["adr1"]);

        $assadr2 = stripslashes($assrow["adr2"]);

        $assadr3 = stripslashes($assrow["adr3"]);

        $assadr4 = stripslashes($assrow["adr4"]);

        $bankdetails = stripslashes($assrow["bankdetails"]);
        
        $assessorcompany = $assrow["company"];
		$assessortelno = $assrow["telno"];
		$assessorcellno = $assrow["cellno"];
		$assessorfaxno = $assrow["faxno"];
		$assessoradr1 = $assrow["adr1"];
		$assessoradr2 = $assrow["adr2"];
		$assessoradr3 = $assrow["adr3"];
		$assessoradr4 = $assrow["adr4"];
		$assessorvatno = $assrow["vatno"];

        $theemail = "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:250px;\" align=\"center\">";

        if (file_exists("../images/assessors/$assessorid.jpg"))
        {

            $theemail .= "<img src=\"http://www.a-c-i.co.za/images/assessors/$assessorid.jpg\"><br />";

        }
        else
        {
			$theemail .= "<img src=\"http://www.a-c-i.co.za/images/acilogo1.jpg\"><br />"; 
		}

        $theemail .= "<font style=\"font-size:10pt;font-family:Arial;\">$assessor</font></td>



										<td align=\"right\">



											<font style=\"font-size:27pt;font-family:Arial;font-weight:bold;color:#c0c0c0;\">INVOICE</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr1</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>DATE:</strong></em></font></td>



										<td style=\"width:138px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">" .
            date("j M Y") . "</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr2</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>INVOICE #</strong></em></font></td>



										<td style=\"width:200px;\"><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">A$clientno</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr3</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessoradr4</font></td>



										<td style=\"width:212px;\" colspan=\"3\">&nbsp;</td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Tel: $assessortelno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>FOR:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">Assessment Fee</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">Fax: $assessorfaxno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>BILL TO:</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$administrator</font></td>



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">VAT $assessorvatno</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$insurancecomp</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">$assessor</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>&nbsp;</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">VAT 4380101289</font></td>								



									</tr>



									<tr>



										<td style=\"width:120px;\"><font style=\"font-size:10pt;font-family:Arial;\">&nbsp;</font></td>



										<td>&nbsp;</td>



										<td style=\"width:100px;\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>CLAIM NO</strong></em></font></td>



										<td><font style=\"font-size:10pt;font-family:Arial;font-weight:bold;\">$claimno</font></td>			



									</tr>



								</table>";

        $qryitems = "select * from items where claimid = $claimid";

        $qryitemsresults = mysql_query($qryitems, $db);

        $qrytotals = "select sum(quoted) as quotedtotal, sum(cost) as costtotal, sum(onetwofive) as onetwofivetotal, sum(adjustment) as adjustmenttotal from items where claimid = $claimid";

        $qrytotalsresults = mysql_query($qrytotals, $db);

        $totalrow = mysql_fetch_array($qrytotalsresults);

        $quotedtotal = $totalrow["quotedtotal"];

        $costtotal = $totalrow["costtotal"];

        $onetwofivetotal = $totalrow["onetwofivetotal"];

        $adjustmenttotal = $totalrow["adjustmenttotal"];

        $excess = $claimrow["excess"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"];

        $desc1 = $_REQUEST["desc1"];

        $desc2 = $_REQUEST["desc2"];

        $desc3 = $_REQUEST["desc3"];

        $desc4 = $_REQUEST["desc4"];

        $desc5 = $_REQUEST["desc5"];

        $desc6 = $_REQUEST["desc6"];

        $desc7 = $_REQUEST["desc7"];

        $desc8 = $_REQUEST["desc8"];

        $desc9 = $_REQUEST["desc9"];

        $desc10 = $_REQUEST["desc10"];

        $desc11 = $_REQUEST["desc11"];

        $desc12 = $_REQUEST["desc12"];

        $desc13 = $_REQUEST["desc13"];

        $desc14 = $_REQUEST["desc14"];

        $amount1 = $_REQUEST["amount1"];

        $amount2 = $_REQUEST["amount2"];

        $amount3 = $_REQUEST["amount3"];

        $amount4 = $_REQUEST["amount4"];

        $amount5 = $_REQUEST["amount5"];

        $amount6 = $_REQUEST["amount6"];

        $amount7 = $_REQUEST["amount7"];

        $amount8 = $_REQUEST["amount8"];

        $amount9 = $_REQUEST["amount9"];

        $amount10 = $_REQUEST["amount10"];

        $amount11 = $_REQUEST["amount11"];

        $amount12 = $_REQUEST["amount12"];

        $amount13 = $_REQUEST["amount13"];

        $amount14 = $_REQUEST["amount14"];

        $other = $_REQUEST["other"];

        $qrycheck = "select * from reportinvoice where `claimid` = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $count = @mysql_num_rows($qrycheckresults);

        if ($count == 0)
        {

            $qryinsert = "insert into reportinvoice (`claimid`, `1d`, `1a`, `2d`, `2a`, `3d`, `3a`, `4d`, `4a`, `5d`, `5a`, `6d`, `6a`, `7d`, `7a`, `8d`, `8a`, `9d`, `9a`, `10d`, `10a`, `11d`, `11a`, `12d`, `12a`, `13d`, `13a`, `14d`, `14a`, `other`) values ($claimid, '$desc1', '$amount1', '$desc2', '$amount2', '$desc3', '$amount3', '$desc4', '$amount4', '$desc5', '$amount5', '$desc6', '$amount6', '$desc7', '$amount7', '$desc8', '$amount8', '$desc9', '$amount9', '$desc10', '$amount10', '$desc11', '$amount11', '$desc12', '$amount12', '$desc13', '$amount13', '$desc14', '$amount14', '$other')";

            $qryinsertresults = mysql_query($qryinsert, $db);

            //echo $qryinsert;

        }

        else
        {

            $qryupdate = "update reportinvoice set `1d` = '$desc1', `1a` = '$amount1', `2d` = '$desc2', `2a` = '$amount2', 



							                                       `3d` = '$desc3', `3a` = '$amount3', `4d` = '$desc4', `4a` = '$amount4',



																   `5d` = '$desc5', `5a` = '$amount5', `6d` = '$desc6', `6a` = '$amount6',



																   `7d` = '$desc7', `7a` = '$amount7', `8d` = '$desc8', `8a` = '$amount8',



																   `9d` = '$desc9', `9a` = '$amount9', `10d` = '$desc10', `10a` = '$amount10',



																   `11d` = '$desc11', `11a` = '$amount11', `12d` = '$desc12', `12a` = '$amount12',



																   `13d` = '$desc13', `13a` = '$amount13', `14d` = '$desc14', `14a` = '$amount14',



																   `other` = '$other' where `claimid` = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);

            //echo $qryupdate;

        }

        $theemail .= "<table style=\"width:600px;font-family:Arial;font-size:10pt;border-top:1px solid #000000;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>DESCRIPTION</strong></td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;background-color:#C0C0C0;\"><strong>AMOUNT</strong></td>										



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-right:0px solid #000000;border-left:1px solid #000000;\">Assessment Fee</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">750.00</td>



									</tr>







									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc1"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" width=\"125\" align=\"right\">&nbsp;" .
            $_REQUEST["amount1"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc2"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount2"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc3"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount3"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc4"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount4"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc5"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount5"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc6"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount6"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc7"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount7"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc8"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount8"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc9"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount9"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc10"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount10"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc11"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount11"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc12"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount12"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc13"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount13"] . "</td>



									</tr>



									<tr>



										<td colspan=\"3\" style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;\">&nbsp;" .
            $_REQUEST["desc14"] . "</td>



										<td style=\"border-bottom:1px solid #000000;border-right:1px solid #000000;border-left:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["amount14"] . "</td>



									</tr>



									<tr>



										<td colspan=\"2\" rowspan=\"4\">Bank Details: $bankdetails<br> <strong>THANK YOU FOR YOUR BUSINESS</strong></td>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>SUBTOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["subtotal"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>VAT</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["vat"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>OTHER</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["other"] . "</td>



									</tr>



									<tr>



										<td align=\"right\"><font style=\"font-size:10pt;font-family:Arial;color:#C0C0C0;\"><em><strong>TOTAL</strong></em></font></td>



										<td style=\"border-bottom:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;\" align=\"right\">&nbsp;" .
            $_REQUEST["total"] . "</td>



									</tr>



								</table>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $qrygetadministrator = "select * from administrators where `id` = $administratorid";

            //echo $qrygetclaimsclerk;

            $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

            $theadrow = @mysql_fetch_array($qrygetadministratorresults);

            $emailaddress = $theadrow["email"];

            //$claimsclerk = $theccrow["name"];

            $claimsclerkid = $claimrow["claimsclerkid"];

            $qrygetclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

            //echo $qrygetclaimsclerk;

            $qrygetclaimsclerkresults = mysql_query($qrygetclaimsclerk, $db);

            $theccrow = @mysql_fetch_array($qrygetclaimsclerkresults);

            $emailaddress = $theccrow["email"];

            require ("email_message.php");

            //get from name and address

            $from_address = "$assessoremail";

            $from_name = "$assessor";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            //$emailaddress = "rudivdwalt@gmail.com"; //comment out this line once the thing is live...

            $to_name = $claimsclerk;

            $to_address = explode(",", $emailaddress);

            //get the newsletter info

            $subject = "Assessor Invoice; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);
			
			foreach ($to_address as $email)
			{
				$email_message->SetEncodedEmailHeader("To", $email, $to_name);
			}
            
            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();

            if (strcmp($error, ""))
            {

                echo "Error: $error <br />";

            }

            else
            {

                echo "Message sent to $to_name, $to_address <br />";

				$now = time() + (7 * 3600);
            
            	$now = date("Y-m-d H:i:00", $now);

                $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) value ('', $claimid, '$now', 'Assessors invoice emailed to $to_address', $loggedinuserid)";
                $qryinsertreportresults = mysql_query($qryinsertreport, $db);

                $qrysentresults = mysql_query($qrysent, $db);

                $qryupdate = "update dates set assessmentinvtoinsurer = NOW() where claimid = $claimid";

                $qryupdateresults = mysql_query($qryupdate, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF PBINVOICEPRINT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF ASSESSMENTINSTRUCTION SECTION



    ***************************************************************************/

    if ($action == "assessmentinstruction")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $excess = $claimrow["excess"];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $qrygetdateofloss = "select * from dates where claimid = $claimid";

        $qrygetdateoflossresults = mysql_query($qrygetdateofloss, $db);

        $dolrow = mysql_fetch_array($qrygetdateoflossresults);

        $loss = $dolrow["loss"];

        $adminid = $claimrow["administratorid"];

        $qryadministrator = "select * from administrators where `id` = $adminid";

        $qryadministratorresults = mysql_query($qryadministrator, $db);

        $adminrow = mysql_fetch_array($qryadministratorresults);

        $administrator = stripslashes($adminrow["name"]);

        $admintelno = stripslashes($adminrow["telno"]);

        $adminfaxno = stripslashes($adminrow["faxno"]);

        $adminadr1 = stripslashes($adminrow["adr1"]);

        $adminadr2 = stripslashes($adminrow["adr2"]);

        $adminadr3 = stripslashes($adminrow["adr3"]);

        $adminadr4 = stripslashes($adminrow["adr4"]);

        $adminemail = stripslashes($adminrow["email"]);

        $adminvatno = stripslashes($adminrow["vatno"]);
        
        $instruction = stripslashes($adminrow["instruction"]);
        
        

        if (file_exists("../images/administrators/$adminid.jpg"))
        {

            $logo = "<img src=\"../images/administrators/$adminid.jpg\"><br />$administrator";

        }

        else
        {

            $logo = $administrator;

        }

        echo "<form method=\"post\" action=\"reports.php?action=assessmentinstructionprint\" name=\"theform\">	



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:150px;\" align=\"center\">$logo</td>



										<td align=\"center\">



											<font style=\"font-size:10pt;font-family:Times New Roman;\">\"WITHOUT PREJUDICE\"</font><br>



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">ASSESSMENT INSTRUCTION</font><br><br>



											<font style=\"font-size:12pt;font-family:Arial;\">Tel: $admintelno Fax: $adminfaxno</font><br><br>



											<font style=\"font-size:12pt;font-family:Arial;\">$adminadr1, $adminadr2, $adminadr3, $adminadr4 $adminemail</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>To Assessor:</td>



										<td><strong>$assessor</strong></td>



										<td>Fax/E-Mail</td>



										<td><strong>$assessorfax / $assessoremail</strong></td>



									</tr>



									<tr>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



										<td>Instruction Date</td>



										<td><strong>$authdate2</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Client Name:</td>



										<td><strong>$clientname</strong></td>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



									</tr>



									<tr>



										<td>Client Contact No:</td>



										<td><strong>$clientcontactno</strong></td>



										<td>Type of Claim:</td>



										<td><strong>Motor Accident</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Make/Model:</td>



										<td><strong>$makemodel</strong></td>



										<td>Registration No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>									



									<tr>



										<td>Year:</td>



										<td><strong>$vehicleyear</strong></td>



										<td>Date of Loss:</td>



										<td><strong>$loss</strong></td>



									</tr>	



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Panelbeater:</td>



										<td width=\"200\"d><strong>$pbname</strong></td>



										<td>Contact Person:</td>



										<td><strong>$pbcontactperson</strong></td>



									</tr>	



									<tr>



										<td>Panelbeater No:</td>



										<td><strong>$pbcontactno</strong></td>



										<td>Quote No:</td>



										<td><strong>$quoteno</strong></td>



									</tr>	



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Claims Adjustor:</td>



										<td><strong>$claimsclerk</strong></td>



										<td>ACI Reference:</td>



										<td><strong>$clientno</strong></td>



									</tr>	



									<tr>



										<td>Administrator:</td>



										<td><strong>$administrator</strong></td>



										<td>Excess:</td>



										<td><strong>$excess</strong></td>



									</tr>							



									<tr>



										<td colspan=\"4\">

											<br>

											<p style=\"text-align:justify;\">Assessor to arrange the following:</p>
											
											$instruction



											<!--<p style=\"text-align:justify;\">1. Maximum of 25% Mark-up will be allowed on all parts replaced, 
											except if confirmed in writing. 2. All EXTRAS must be confirmed in writing with the assessor and such 
											parts must be kept for 10 working days.  3. Second Hand parts are preferred over Pirate parts when needed. 
											4.All MOTORCYCLE PARTS must be kept by repairer and must notify A.C.I. 0861114339 as soon as parts can be 
											collected.</p>



											<p style=\"text-align:justify;\">Invoices to be made out to: $administrator VAT Reg: $adminvatno and send to $adminadr1, $adminadr2, $adminadr3, $adminadr4 or Fax No $adminfaxno</p>



											<p>If you have any questions, please do not hesitate to contact us.</p>



											<p>Best Regards</p>-->



											<br><br>



											<p>$claimsclerk<br>$administrator<br>Tel no: $admintelno</p>											



										</td>



									</tr>



								</table>



								<input type=\"submit\" value=\"Send Email\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



							</form>";

    }

    /***************************************************************************



    END OF ASSESSMENTINSTRUCTION SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF ASSESSMENTINSTRUCTIONPRINT SECTION



    ***************************************************************************/

    if ($action == "assessmentinstructionprint")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $excess = $claimrow["excess"];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        $qrygetdateofloss = "select * from dates where claimid = $claimid";

        $qrygetdateoflossresults = mysql_query($qrygetdateofloss, $db);

        $dolrow = mysql_fetch_array($qrygetdateoflossresults);

        $loss = $dolrow["loss"];

        $adminid = $claimrow["administratorid"];

        $qryadministrator = "select * from administrators where `id` = $adminid";

        $qryadministratorresults = mysql_query($qryadministrator, $db);

        $adminrow = mysql_fetch_array($qryadministratorresults);

        $administrator = stripslashes($adminrow["name"]);

        $admintelno = stripslashes($adminrow["telno"]);

        $adminfaxno = stripslashes($adminrow["faxno"]);

        $adminadr1 = stripslashes($adminrow["adr1"]);

        $adminadr2 = stripslashes($adminrow["adr2"]);

        $adminadr3 = stripslashes($adminrow["adr3"]);

        $adminadr4 = stripslashes($adminrow["adr4"]);

        $adminemail = stripslashes($adminrow["email"]);

        $adminvatno = stripslashes($adminrow["vatno"]);
        
        $instruction = stripslashes($adminrow["instruction"]);

        if (file_exists("../images/administrators/$adminid.jpg"))
        {

            $logo = "<img src=\"http://www.a-c-i.co.za/images/administrators/$adminid.jpg\"><br />$administrator";

        }

        else
        {

            $logo = $administrator;

        }

        $theemail = "



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:150px;\" align=\"center\">$logo</td>



										<td align=\"center\">



											<font style=\"font-size:10pt;font-family:Times New Roman;\">\"WITHOUT PREJUDICE\"</font><br>



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">ASSESSMENT INSTRUCTION</font><br><br>



											<font style=\"font-size:12pt;font-family:Arial;\">Tel: $admintelno Fax: $adminfaxno</font><br><br>



											<font style=\"font-size:12pt;font-family:Arial;\">$adminadr1, $adminadr2, $adminadr3, $adminadr4 $adminemail</font>



										</td>



									</tr>



								</table>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>To Assessor:</td>



										<td><strong>$assessor</strong></td>



										<td>Fax/E-Mail</td>



										<td><strong>$assessorfax / $assessoremail</strong></td>



									</tr>



									<tr>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



										<td>Instruction Date</td>



										<td><strong>$authdate2</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Client Name:</td>



										<td><strong>$clientname</strong></td>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



									</tr>



									<tr>



										<td>Client Contact No:</td>



										<td><strong>$clientcontactno</strong></td>



										<td>Type of Claim:</td>



										<td><strong>Motor Accident</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Make/Model:</td>



										<td><strong>$makemodel</strong></td>



										<td>Registration No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>									



									<tr>



										<td>Year:</td>



										<td><strong>$vehicleyear</strong></td>



										<td>Date of Loss:</td>



										<td><strong>$loss</strong></td>



									</tr>	



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Panelbeater:</td>



										<td width=\"200\"d><strong>$pbname</strong></td>



										<td>Contact Person:</td>



										<td><strong>$pbcontactperson</strong></td>



									</tr>	



									<tr>



										<td>Panelbeater No:</td>



										<td><strong>$pbname</strong></td>



										<td>Quote No:</td>



										<td><strong>$quoteno</strong></td>



									</tr>	



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Claims Adjustor:</td>



										<td><strong>$claimsclerk</strong></td>



										<td>ACI Reference:</td>



										<td><strong>$clientno</strong></td>



									</tr>	



									<tr>



										<td>Administrator:</td>



										<td><strong>$administrator</strong></td>



										<td>Excess:</td>



										<td><strong>$excess</strong></td>



									</tr>							



									<tr>



										<td colspan=\"4\">

											<br>

											<p style=\"text-align:justify;\">Assessor to arrange the following:</p>
											
											$instruction






											<br><br>



											<p>$claimsclerk<br>$administrator<br>Tel no: $admintelno</p>											



										</td>



									</tr>



								</table>



							</form>";

        $emailaddress = $assrow["email"];

        require_once ('connection.php');

        require ("email_message.php");

        //get from name and address

        $from_address = "admin@aci.co.za";

        $from_name = "Auto Claims Investigation";

        $reply_name = $from_name;

        $reply_address = $from_address;

        $error_delivery_name = $from_name;

        $error_delivery_address = $from_address;

        //get the subscriber info

        //		echo "<br>" . $qrysubscriber;

        $to_name = $assessor;

        $to_address = $emailaddress;

        //get the newsletter info

        $subject = "Assessment Instruction; Claimno: $claimno; Client: $clientname";

        $email_message = new email_message_class;

        $email_message->SetBulkMail(1);

        $email_message->SetEncodedEmailHeader("To", $to_address, $to_name);
        
        $email_message->SetEncodedEmailHeader("Cc", "auth@aci.co.za", "Authorisation");

        $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

        $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

        $email_message->SetHeader("Sender", $from_address);
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////////////////////////SMS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
        {

            $email_message->SetHeader("Return-Path", $error_delivery_address);

        }

        $email_message->SetEncodedHeader("Subject", $subject);

        $html_message = $theemail;

        $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

        $related_parts = array($html_part);

        $email_message->CreateRelatedMultipart($related_parts, $html_parts);

        $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

        $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
            "", $text_part);

        $alternative_parts = array($text_part, $html_parts);

        $email_message->AddAlternativeMultipart($alternative_parts);

        $error = $email_message->Send();

        if (strcmp($error, ""))
        {

            echo "Error: <b>$error</b> <br />";

        }

        else
        {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            echo "Message sent to $to_name:<br />";
            
            $now = time() + (7 * 3600);
            
            $now = date("Y-m-d H:i:00", $now);

            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Assessors Appointment sent to $to_address', $loggedinuserid)";
            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

            $qryupdatedates = "update dates set assappointed = NOW() where claimid = $claimid";

            $qryupdatedatesresults = mysql_query($qryupdatedates, $db);

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF ASSESSMENTINSTRUCTION SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF ASSESSMENTREPORT SECTION



    ***************************************************************************/

    if ($action == "assessmentreport")
    {

        $claimid = $_REQUEST["claimid"];
        
        //get assessment report date from dates table
        
        $qrygetassessmentreportdate = "select * from `dates` where `claimid` = $claimid";
        $qrygetassessmentreportdateresults = mysql_query($qrygetassessmentreportdate, $db);
        
        $assessmentreportdaterow = mysql_fetch_array($qrygetassessmentreportdateresults);
        
        $assessmentreportdate = $assessmentreportdaterow["assessmentreport"];
        
        $year = substr($assessmentreportdate, 0, 4);
        $month = substr($assessmentreportdate, 5, 2);
        $day = substr($assessmentreportdate, 8, 2);
        
        $thedate = mktime(0,0,0,$month, $day, $year);
        
        $assessmentreportdate = date("j M Y", $thedate);

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $betterment = $claimrow["betterment"];

        $excess = $claimrow["excess"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $excess = $claimrow["excess"];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $assessorcontactno = $assrow["telno"];
        
        /*$assessorcompany<br>$assessorname<br>Tel: $assessortelno Fax: $assessorfaxno<br><br>
											
											$assessoradr1, $assessoradr2, $assessoradr3, $assessoradr4<br>$assessoremail*/
											
		$assessorcompany = $assrow["company"];
		$assessortelno = $assrow["telno"];
		$assessorcellno = $assrow["cellno"];
		$assessorfaxno = $assrow["faxno"];
		$assessoradr1 = $assrow["adr1"];
		$assessoradr2 = $assrow["adr2"];
		$assessoradr3 = $assrow["adr3"];
		$assessoradr4 = $assrow["adr4"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        //check to see if this one has been saved, and if it has, load the values into the text boxes

        $qrycheck = "select * from reportassessmentreport where claimid = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        if (mysql_num_rows($qrycheckresults) == 0)
        {

            $assessmentdate = "";

            $chassisno = "";

            $engineno = "";
            
            $vehiclecolour = "";

            $odometer = "";

            $license = "";

            $tyresfrontleft = "";

            $tyresfrontright = "";

            $tyresbackleft = "";

            $tyresbackright = "";

            $condition = "";

            $mechanical = "";

            $handbrake = "";

            $footbrake = "";

            $immobilizer = "";

            $radiocd = "";

            $radiomake = "";

            $gearlock = "";

            $tracking = "";

            $trackingmake = "";

            $accessories = "";

            $bookvaluecode = "";

            $trade = "";

            $retail = "";

            $market = "";

            $suminsuredfor = "";

            $stockno = "";

            $salvagedealer = "";

            $remarks = "";

            $parts = "";

            $labour = "";

            $paint = "";
            
            

        }

        else
        {

            $darow = mysql_fetch_array($qrycheckresults);

            $assessmentdate = stripslashes($darow["assessmentdate"]);

            $chassisno = stripslashes($darow["chassisno"]);

            $engineno = stripslashes($darow["engineno"]);
            
            $vehiclecolour = stripslashes($darow["vehiclecolour"]);

            $odometer = stripslashes($darow["odometer"]);

            $license = stripslashes($darow["license"]);

            $tyresfrontleft = stripslashes($darow["leftfront"]);

            $tyresfrontright = stripslashes($darow["rightfront"]);

            $tyresbackleft = stripslashes($darow["leftback"]);

            $tyresbackright = stripslashes($darow["rightback"]);

            $condition = stripslashes($darow["conditionofvehicle"]);

            $mechanical = stripslashes($darow["mechanical"]);

            $handbrake = stripslashes($darow["handbrake"]);

            $footbrake = stripslashes($darow["footbrake"]);

            $immobilizer = stripslashes($darow["immobilizer"]);

            $radiocd = stripslashes($darow["radiocd"]);

            $radiomake = stripslashes($darow["radiocdmakemodel"]);

            $gearlock = stripslashes($darow["gearlock"]);

            $tracking = stripslashes($darow["tracking"]);

            $trackingmake = stripslashes($darow["trackingmakemodel"]);

            $accessories = stripslashes($darow["accessories"]);

            $bookvaluecode = stripslashes($darow["bookvaluecode"]);

            $trade = stripslashes($darow["trade"]);

            $retail = stripslashes($darow["retail"]);

            $market = stripslashes($darow["market"]);

            $suminsuredfor = stripslashes($darow["suminsuredfor"]);

            $stockno = stripslashes($darow["stocknowriteoff"]);

            $salvagedealer = stripslashes($darow["salvagedealer"]);

            $remarks = stripslashes($darow["remarks"]);

            $parts = stripslashes($darow["parts"]);

            $labour = stripslashes($darow["labour"]);

            $paint = stripslashes($darow["paint"]);

        }
        
        $todaysdate = date("j F Y");

        echo "<form method=\"post\" action=\"reports.php?action=assessmentreportprint\" name=\"theform\">	



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:150px;\" align=\"center\"><img src=\"../images/acilogo.gif\"></td>



										<td align=\"center\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">ASSESSMENT REPORT</font><br><br>

											

											<font style=\"font-size:12pt;font-family:Arial;\">$assessorcompany<br>$assessorname<br>Tel: $assessortelno Fax: $assessorfaxno<br><br>
											
											$assessoradr1, $assessoradr2, $assessoradr3, $assessoradr4<br>$assessoremail</font>										
											
											
											



										</td>



									</tr>



								</table>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Client Name:</td>



										<td><strong>$clientname</strong></td>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



									</tr>		



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>							



									<tr>



										<td>Panelbeater No:</td>



										<td><strong>$pbcontactno</strong></td>



										<td>Assessment Date:</td>



										<td><input type=\"text\" name=\"assessmentdate\" value=\"$todaysdate\"></td>



									</tr>



									<tr>



										<td>Panelbeaters:</td>



										<td><strong>$pbname</strong></td>



										<td>Contact Person:</td>



										<td><strong>$pbcontactperson</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>	



									<tr>



										<td>Make/Model:</td>



										<td><strong>$makemodel</strong></td>



										<td>Registration No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>



									<tr>



										<td>Chassis No:</td>



										<td><input type=\"text\" name=\"chassisno\" value=\"$chassisno\"></td>



										<td>Engine No:</td>



										<td><input type=\"text\" name=\"engineno\" value=\"$engineno\"></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Colour of Vehicle:</td>



										<td><input name=\"vehiclecolour\" type=\"text\" maxlength=\"50\" style=\"width:75px;\" value=\"$vehiclecolour\"> </td>



										<td>Odometer:</td>



										<td><input type=\"text\" name=\"odometer\" value=\"$odometer\" style=\"width:75px;\"></td>



										<td>Year:</td>



										<td><strong>$vehicleyear</strong></td>



										<td>License</td>



										<td><input type=\"text\" name=\"license\" value=\"$license\" style=\"width:75px;\"></td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Tyres:&nbsp;Left/Front</td>



										<td><input type=\"text\" name=\"tyresfrontleft\" value=\"$tyresfrontleft\" style=\"width:75px;\"></td>



										<td>Right/Front</td>



										<td><input type=\"text\" name=\"tyresfrontright\" value=\"$tyresfrontright\" style=\"width:75px;\"></td>



										<td>Left/Back</td>



										<td><input type=\"text\" name=\"tyresbackleft\" value=\"$tyresbackleft\" style=\"width:75px;\"></td>



										<td>Right/Back</td>



										<td><input type=\"text\" name=\"tyresbackright\" value=\"$tyresbackright\" style=\"width:75px;\"></td>



									</tr>



									<tr>



										<td>Condition of Vehicle:</td>



										<td><input type=\"text\" name=\"condition\" value=\"$condition\" style=\"width:75px;\"></td>



										<td>Mechanical:</td>



										<td><input type=\"text\" name=\"mechanical\" value=\"$mechanical\" style=\"width:75px;\"></td>



										<td>Handbrake:</td>



										<td><input type=\"text\" name=\"handbrake\" value=\"$handbrake\" style=\"width:75px;\"></td>



										<td>Footbrake:</td>



										<td><input type=\"text\" name=\"footbrake\" value=\"$footbrake\" style=\"width:75px;\"></td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Immobilizer:</td>



										<td><input type=\"text\" name=\"immobilizer\" value=\"$immobilizer\"></td>



										<td>Radio/CD:</td>



										<td><input type=\"text\" name=\"radiocd\" value=\"$radiocd\"></td>



										<td>Make/Model</td>



										<td><input type=\"text\" name=\"radiomake\" value=\"$radiomake\"></td>										



									</tr>	



									<tr>



										<td>Gearlock:</td>



										<td><input type=\"text\" name=\"gearlock\" value=\"$immobilizer\"></td>



										<td>Tracking:</td>



										<td><input type=\"text\" name=\"tracking\" value=\"$tracking\"></td>



										<td>Make/Model:</td>



										<td><input type=\"text\" name=\"trackingmake\" value=\"$trackingmake\"></td>



									</tr>	



									<tr>



										<td>Accessories:</td>



										<td colspan=\"5\"><input type=\"text\" name=\"accessories\" value=\"$accessories\" style=\"width:475px;\"></td>										



									</tr>				



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>			



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Book Value: Code</td>



										<td><input type=\"text\" name=\"bookvaluecode\" value=\"$bookvaluecode\" style=\"width:75px;\"></td>



										<td>Trade R:</td>



										<td><input type=\"text\" name=\"trade\" value=\"$trade\" style=\"width:75px;\"></td>



										<td>Retail R:</td>



										<td><input type=\"text\" name=\"retail\" value=\"$retail\" style=\"width:75px;\"></td>



										<td>Market R:</td>



										<td><input type=\"text\" name=\"market\" value=\"$market\" style=\"width:75px;\"></td>



									</tr>



									<tr>



										<td>Sum Insured for:</td>



										<td><input type=\"text\" name=\"suminsuredfor\" value=\"$suminsuredfor\" style=\"width:75px;\"></td>



										<td colspan=\"4\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Stock No (Write Off):</td>



										<td><input type=\"text\" name=\"stockno\" value=\"$stockno\" value=\"N/A\"></td>



										<td>Salvage Dealer:</td>



										<td><input type=\"text\" name=\"salvagedealer\" value=\"$salvagedealer\" value=\"N/A\"></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Claims Adjustor:</td>



										<td><strong>$claimsclerk</strong></td>



										<td>ACI Reference:</td>



										<td><strong>$clientno</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td colspan=\"4\">Remarks:</td>										



									</tr>



									<tr>



										<td colspan=\"4\">
										<textarea name=\"remarks\" style=\"width:600px;height:80px;\">$remarks</textarea></td>										



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



								</table>



								



<script>







	function AddFields()



	{



		var total;



		



		total = Math.round(((document.theform.parts.value * 1) + (document.theform.paint.value * 1) + (document.theform.labour.value * 1)) * 100) / 100;



		



		document.theform.total.value = total;



		



		var vat;



		



		vat = Math.round((total * 0.15) * 100) / 100;



		



		document.theform.vat.value = vat;



		



		var totalincvat;



		



		totalincvat = Math.round((total * 1.15) * 100) / 100;



		



		document.theform.totalincvat.value = totalincvat;



		



		var total2;



		



		total2 = Math.round((($betterment * 1) + totalincvat) * 100) / 100;



		



		document.theform.total2.value = total2;



		



		var total3;



		



		total3 = Math.round((total2 - $excess) * 100) / 100;



		



		document.theform.total3.value = total3;



	}







</script>								



								



								<table style=\"font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Parts</td>



										<td><input type=\"text\" name=\"parts\" value=\"$parts\" style=\"text-align:right;\" onkeyup=\"AddFields();\"></td>



									</tr>



									<tr>



										<td>Labour</td>



										<td><input type=\"text\" name=\"labour\" value=\"$labour\" style=\"text-align:right;\" onkeyup=\"AddFields();\"></td>



									</tr>



									<tr>



										<td>Paint</td>



										<td><input type=\"text\" name=\"paint\" value=\"$paint\" style=\"text-align:right;\" onkeyup=\"AddFields();\"></td>



									</tr>



									<tr>



										<td>Total</td>



										<td><input type=\"text\" name=\"total\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>VAT</td>



										<td><input type=\"text\" name=\"vat\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>Total Inc VAT</td>



										<td><input type=\"text\" name=\"totalincvat\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>Min Betterment</td>



										<td><input type=\"text\" name=\"betterment\" value=\"$betterment\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>Total</td>



										<td><input type=\"text\" name=\"total2\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>Min Excess</td>



										<td><input type=\"text\" name=\"excess\" value=\"$excess\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td>Total</td>



										<td><input type=\"text\" name=\"total3\" style=\"text-align:right;\" readonly></td>



									</tr>



									<tr>



										<td colspan=\"2\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"font-family:Arial;font-size:10pt;width:600px;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Assessor</td>



										<td><strong>$assessor</strong></td>



										<td>Contact No</td>



										<td><strong>$assessorcellno</strong>



									</tr>



									<tr>



										<td>Date:</td>



										<td><strong>$todaysdate</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



								</table>



								



								<br><input type=\"button\" value=\"Printer Friendly\" onClick=\"document.theform.email.value = 'no';



								                                                                document.theform.submit(); \"> 



									<input type=\"button\" value=\"Email Report\" onClick=\"document.theform.email.value = 'yes';



									                                                        document.theform.submit(); \"> <input type=\"button\" value=\"Save\" onClick=\"document.theform.email.value = 'no';



								                                                                document.theform.submit(); \">  <input type=\"hidden\" name=\"email\">



								<input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



							</form>";

    }

    /***************************************************************************



    END OF ASSESSMENTREPORT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF ASSESSMENTREPORTPRINT SECTION



    ***************************************************************************/

    if ($action == "assessmentreportprint")
    {

        $claimid = $_REQUEST["claimid"];
        
        //get assessment report date from dates table
        
        $qrygetassessmentreportdate = "select * from `dates` where `claimid` = $claimid";
        $qrygetassessmentreportdateresults = mysql_query($qrygetassessmentreportdate, $db);
        
        $assessmentreportdaterow = mysql_fetch_array($qrygetassessmentreportdateresults);
        
        $assessmentreportdate = $assessmentreportdaterow["assessmentreport"];
        
        $year = substr($assessmentreportdate, 0, 4);
        $month = substr($assessmentreportdate, 5, 2);
        $day = substr($assessmentreportdate, 8, 2);
        
        $thedate = mktime(0,0,0,$month, $day, $year);
        
        $assessmentreportdate = date("j M Y", $thedate);

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = $_REQUEST["vehiclecolour"];

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $betterment = $claimrow["betterment"];

        $excess = $claimrow["excess"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $excess = $claimrow["excess"];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $assessorcontactno = $assrow["telno"];

        $administratorid = $claimrow["administratorid"];
        
        $assessorcompany = $assrow["company"];
		$assessortelno = $assrow["telno"];
		$assessorcellno = $assrow["cellno"];
		$assessorfaxno = $assrow["faxno"];
		$assessoradr1 = $assrow["adr1"];
		$assessoradr2 = $assrow["adr2"];
		$assessoradr3 = $assrow["adr3"];
		$assessoradr4 = $assrow["adr4"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;

        //create the query to store all the values in the database:                                                           !

        //first, get all the values...

        $assessmentdate = addslashes($_REQUEST["assessmentdate"]);

        $chassisno = addslashes($_REQUEST["chassisno"]);

        $engineno = addslashes($_REQUEST["engineno"]);

        $odometer = addslashes($_REQUEST["odometer"]);

        $license = addslashes($_REQUEST["license"]);

        $tyresfrontleft = addslashes($_REQUEST["tyresfrontleft"]);

        $tyresfrontright = addslashes($_REQUEST["tyresfrontright"]);

        $tyresbackleft = addslashes($_REQUEST["tyresbackleft"]);

        $tyresbackright = addslashes($_REQUEST["tyresbackright"]);

        $condition = addslashes($_REQUEST["condition"]);

        $mechanical = addslashes($_REQUEST["mechanical"]);

        $handbrake = addslashes($_REQUEST["handbrake"]);

        $footbrake = addslashes($_REQUEST["footbrake"]);

        $immobilizer = addslashes($_REQUEST["immobilizer"]);

        $radiocd = addslashes($_REQUEST["radiocd"]);

        $radiomake = addslashes($_REQUEST["radiomake"]);

        $gearlock = addslashes($_REQUEST["gearlock"]);

        $tracking = addslashes($_REQUEST["tracking"]);

        $trackingmake = addslashes($_REQUEST["trackingmake"]);

        $accessories = addslashes($_REQUEST["accessories"]);

        $bookvaluecode = addslashes($_REQUEST["bookvaluecode"]);

        $trade = addslashes($_REQUEST["trade"]);

        $retail = addslashes($_REQUEST["retail"]);

        $market = addslashes($_REQUEST["market"]);

        $suminsuredfor = addslashes($_REQUEST["suminsuredfor"]);

        $stockno = addslashes($_REQUEST["stockno"]);

        $salvagedealer = addslashes($_REQUEST["salvagedealer"]);

        $remarks = addslashes($_REQUEST["remarks"]);

        $parts = addslashes($_REQUEST["parts"]);

        $labour = addslashes($_REQUEST["labour"]);

        $paint = addslashes($_REQUEST["paint"]);

        //now do the query to store the values in the database:

        $qrycheck = "select count(claimid) as counted from reportassessmentreport where claimid = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $checkrow = mysql_fetch_array($qrycheckresults);

        if ($checkrow[counted] == 1)
        {

            $qryupdate = "update reportassessmentreport set assessmentdate = '$assessmentdate',



								                                                chassisno = '$chassisno',



																				engineno = '$engineno',
																				
																				
																				vehiclecolour = '$vehiclecolour',



																				odometer = '$odometer',



																				license = '$license',



																				leftfront = '$tyresfrontleft',



																				rightfront = '$tyresfrontright',



																				leftback = '$tyresbackleft',



																				rightback = '$tyresbackright',



																				conditionofvehicle = '$condition',



																				mechanical = '$mechanical',



																				handbrake = '$handbrake',



																				footbrake = '$footbrake',



																				immobilizer = '$immobilizer',



																				radiocd = '$radiocd',



																				radiocdmakemodel = '$radiomake',



																				gearlock = '$gearlock',



																				tracking = '$tracking',



																				trackingmakemodel = '$trackingmake',



																				accessories = '$accessories',



																				bookvaluecode = '$bookvaluecode',



																				trade = '$trade',



																				retail = '$retail',



																				market = '$market',



																				suminsuredfor = '$suminsuredfor',



																				stocknowriteoff = '$stockno',



																				salvagedealer = '$salvagedealer',



																				remarks = '$remarks',



																				parts = '$parts',



																				labour = '$labour',



																				paint = '$paint' where claimid = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);

        }

        else
        {

            $qryupdate = "INSERT INTO `reportassessmentreport` (`claimid`, `assessmentdate`, `chassisno`, `engineno`, `odometer`, `license`, `leftfront`, `rightfront`, `leftback`, `rightback`, `conditionofvehicle`, `mechanical`, `handbrake`, `footbrake`, `immobilizer`, `radiocd`, `radiocdmakemodel`, `gearlock`, `tracking`, `trackingmakemodel`, `accessories`, `bookvaluecode`, `trade`, `retail`, `market`, `suminsuredfor`, `stocknowriteoff`, `salvagedealer`, `remarks`, `parts`, `labour`, `paint`) VALUES ($claimid, '$assessmentdate', '$chassisno', '$engineno', '$odometer', '$license', '$tyresfrontleft', '$tyresfrontright', '$tyresbackleft', '$tyresbackright', '$condition', '$mechanical', '$handbrake', '$footbrake', '$immobilizer', '$radiocd', '$radiomake', '$gearlock', '$tracking', '$trackingmake', '$accessories', '$bookvaluecode', '$trade', '$retail', '$market', '$suminsuredfor', '$stockno', '$salvagedealer', '$remarks', '$parts', '$labour', '$paint');";

            $qryupdateresults = mysql_query($qryupdate, $db);

        }

        //create the query to store all the values in the database:                                                           !

        $theemail = "



								<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:150px;\" align=\"center\"><img src=\"../images/acilogo.gif\"></td>



										<td align=\"center\">



											<font style=\"font-size:12pt;font-family:Arial;font-weight:bold;\">ASSESSMENT REPORT</font><br><br>

											

											<font style=\"font-size:12pt;font-family:Arial;\">$assessorcompany<br>$assessorname<br>Tel: $assessortelno Fax: $assessorfaxno<br><br>
											
											$assessoradr1, $assessoradr2, $assessoradr3, $assessoradr4<br>$assessoremail</font>	



										</td>



									</tr>



								</table>



								<br>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Client Name:</td>



										<td><strong>$clientname</strong></td>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



									</tr>		



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>							



									<tr>



										<td>Panelbeater No:</td>



										<td><strong>$pbcontactno</strong></td>



										<td>Assessment Date:</td>



										<td><strong>$assessmentdate</strong></td>



									</tr>



									<tr>



										<td>Panelbeaters:</td>



										<td><strong>$pbname</strong></td>



										<td>Contact Person:</td>



										<td><strong>$pbcontactperson</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>	



									<tr>



										<td>Make/Model:</td>



										<td><strong>$makemodel</strong></td>



										<td>Registration No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>



									<tr>



										<td>Chassis No:</td>



										<td><strong>$chassisno</strong></td>



										<td>Engine No:</td>



										<td><strong>$engineno</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Colour of Vehicle:</td>



										<td><strong>$vehiclecolour</strong></td>



										<td>Odometer:</td>



										<td><strong>$odometer</strong></td>



										<td>Year:</td>



										<td><strong>$vehicleyear</strong></td>



										<td>License</td>



										<td><strong>$license</strong></td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Tyres:&nbsp;Left/Front</td>



										<td><strong>$tyresfrontleft</strong></td>



										<td>Right/Front</td>



										<td><strong>$tyresfrontright</strong></td>



										<td>Left/Back</td>



										<td><strong>$tyresbackleft</strong></td>



										<td>Right/Back</td>



										<td><strong>$tyresbackright</strong></td>



									</tr>



									<tr>



										<td>Condition of Vehicle:</td>



										<td><strong>$condition</strong></td>



										<td>Mechanical:</td>



										<td><strong>$mechanical</strong></td>



										<td>Handbrake:</td>



										<td><strong>$handbrake</strong></td>



										<td>Footbrake:</td>



										<td><strong>$footbrake</strong></td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Immobilizer:</td>



										<td><strong>$immobilizer</strong></td>



										<td>Radio/CD:</td>



										<td><strong>$radiocd</strong></td>



										<td>Make/Model</td>



										<td><strong>$radiomake</strong></td>



									</tr>	



									<tr>



										<td>Gearlock:</td>



										<td><strong>$gearlock</strong></td>



										<td>Tracking:</td>



										<td><strong>$tracking</strong></td>



										<td>Make/Model:</td>



										<td><strong>$trackingmake</strong></td>



									</tr>	



									<tr>



										<td>Accessories:</td>



										<td colspan=\"5\"><strong>$accessories</strong></td>										



									</tr>				



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>			



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Book Value: Code</td>



										<td><strong>$bookvaluecode</strong></td>



										<td>Trade R:</td>



										<td><strong>$trade</strong></td>



										<td>Retail R:</td>



										<td><strong>$retail</strong></td>



										<td>Market R:</td>



										<td><strong>$market</strong></td>



									</tr>



									<tr>



										<td>Sum Insured for:</td>



										<td><strong>$suminsuredfor</strong></td>



										<td colspan=\"4\">&nbsp;</td>



									</tr>



									<tr>



										<td colspan=\"6\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Stock No (Write Off):</td>



										<td><strong>$stockno</strong></td>



										<td>Salvage Dealer:</td>



										<td><strong>$salvagedealer</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td>Claims Adjustor:</td>



										<td><strong>$claimsclerk</strong></td>



										<td>ACI Reference:</td>



										<td><strong>$clientno</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



									<tr>



										<td colspan=\"4\">Remarks:</td>										



									</tr>



									<tr>



										<td colspan=\"4\"><p style=\"text-align:justify\"><strong>$remarks</strong></td>



									</tr>



									<tr>



										<td colspan=\"4\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Parts</td>



										<td style=\"text-align:right;\"><strong>$parts</strong></td>



									</tr>



									<tr>



										<td>Labour</td>



										<td style=\"text-align:right;\"><strong>$labour</strong></td>



									</tr>



									<tr>



										<td>Paint</td>



										<td style=\"text-align:right;\"><strong>$paint</strong></td>



									</tr>



									<tr>



										<td>Total</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["total"] .
            "</strong></td>



									</tr>



									<tr>



										<td>VAT</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["vat"] .
            "</strong></td>



									</tr>



									<tr>



										<td>Total Inc VAT</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["totalincvat"] .
            "</strong></td>



									</tr>



									<tr>



										<td>Min Betterment</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["betterment"] .
            "</strong></td>



									</tr>



									<tr>



										<td>Total</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["total2"] .
            "</strong></td>



									</tr>



									<tr>



										<td>Min Excess</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["excess"] .
            "</strong></td>



									</tr>



									<tr>



										<td>Total</td>



										<td style=\"text-align:right;\"><strong>" . $_REQUEST["total3"] .
            "</strong></td>



									</tr>



									<tr>



										<td colspan=\"2\">&nbsp;</td>										



									</tr>



								</table>



								



								<table style=\"font-family:Arial;font-size:10pt;width:600px;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td>Assessor</td>



										<td><strong>$assessor</strong></td>



										<td>Contact No</td>



										<td><strong>$assessorcellno</strong>



									</tr>



									<tr>



										<td>Date:</td>



										<td><strong>$assessmentreportdate</strong></td>



										<td colspan=\"2\">&nbsp;</td>



									</tr>



								</table>



								



								<br>





							</form>";

        $sendemail = $_REQUEST["email"];

        if ($sendemail == "yes")
        {

            $qrygetadministrator = "select * from administrators where `id` = $administratorid";

            //echo $qrygetclaimsclerk;

            $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

            $theadrow = @mysql_fetch_array($qrygetadministratorresults);

            $emailaddress = $theadrow["email"];

            //$claimsclerk = $theccrow["name"];

            $claimsclerkid = $claimrow["claimsclerkid"];

            $qrygetclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

            //echo $qrygetclaimsclerk;

            $qrygetclaimsclerkresults = mysql_query($qrygetclaimsclerk, $db);

            $theccrow = @mysql_fetch_array($qrygetclaimsclerkresults);

            $emailaddress = $theccrow["email"];

            require ("email_message.php");

            //get from name and address

            $from_address = "$assessoremail";

            $from_name = "$assessor";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

            //get the subscriber info

            //		echo "<br>" . $qrysubscriber;

            //$emailaddress = "rudivdwalt@gmail.com"; //comment out this line once the thing is live...

            $to_name = $claimsclerk;

            $to_address = $emailaddress;
            
            $to_address = explode(",", $emailaddress);

            //get the newsletter info

            $subject = "Assessment Report; Claimno: $claimno; Client: $clientname";

            $email_message = new email_message_class;

            $email_message->SetBulkMail(1);
			
			foreach ($to_address as $email)
			{
				$email_message->SetEncodedEmailHeader("To", $email, $to_name);
			}

            //get the newsletter info
            
            $email_message->SetEncodedEmailHeader("Cc", "admin@aci.co.za", "ACI");

            $email_message->SetEncodedEmailHeader("From", $from_address, $from_name);

            $email_message->SetEncodedEmailHeader("Reply-To", $reply_address, $reply_name);

            $email_message->SetHeader("Sender", $from_address);

            if (defined("PHP_OS") && strcmp(substr(PHP_OS, 0, 3), "WIN"))
            {

                $email_message->SetHeader("Return-Path", $error_delivery_address);

            }

            $email_message->SetEncodedHeader("Subject", $subject);

            //echo "asdf " . $newsletter . " asdf";//*/

            $html_message = $theemail;

            $email_message->CreateQuotedPrintableHTMLPart($html_message, "", $html_part);

            $related_parts = array($html_part);

            $email_message->CreateRelatedMultipart($related_parts, $html_parts);

            $text_message = "This is an HTML message. Please use an HTML capable mail program to read this message.";

            $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),
                "", $text_part);

            $alternative_parts = array($text_part, $html_parts);

            $email_message->AddAlternativeMultipart($alternative_parts);

            $error = $email_message->Send();

            if (strcmp($error, ""))
            {

                echo "Error: $error <br />";

            }

            else
            {

                echo "Message sent to $to_name <br />";

                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Assessment Report emailed to $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);
                
                

                //$qryupdateresults = mysql_query($qryupdate, $db);

            }

        }

        echo $theemail;

    }

    /***************************************************************************



    END OF ASSESSMENTREPORTPRINT SECTION



    ***************************************************************************/

    /***************************************************************************



    START OF AUTHORISATION SECTION



    ***************************************************************************/

    if ($action == "authorization")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_assoc($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_assoc($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_assoc($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $pbfax = stripslashes($qrypbrow["faxno"]);

        $pbadr1 = stripslashes($qrypbrow["adr1"]);

        $pbadr2 = stripslashes($qrypbrow["adr2"]);

        $pbadr3 = stripslashes($qrypbrow["adr3"]);

        $pbadr4 = stripslashes($qrypbrow["adr4"]);

		$pbemail = $qrypbrow['email'];

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

		
		$vehicleMake = '';
		if (!empty($claimrow['vehiclemakeid'])) {
			$vehiclemakesql = "SELECT vehiclemake FROM vehiclemake WHERE id='" . $claimrow['vehiclemakeid'] . "'";

			$vehiclemakeresults = mysql_query($vehiclemakesql, $db);

			$vehiclemakerow = mysql_fetch_assoc($vehiclemakeresults);
			$vehicleMake = $vehiclemakerow['vehiclemake'];
		}


		// get dates

		$datesql = " SELECT * FROM `dates` WHERE `claimid`='" . $claimrow["id"] . "' ";
		$dateresult = mysql_query($datesql);
		$datesInfo = mysql_fetch_assoc($dateresult);


        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

		//echo '<pre>';print_r($claimrow);die;

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

		if (isset($datesInfo['auth']) && $datesInfo['auth'] !== '0000-00-00') {
			$authdate2 = date('d M Y');
		}


        $daterec = $claimrow["datereceived"];

        $daterec = explode("-", $daterec);

        if ($daterec[1] == "01")
        {
            $month = "Jan";
        }

        if ($daterec[1] == "02")
        {
            $month = "Feb";
        }

        if ($daterec[1] == "03")
        {
            $month = "Mar";
        }

        if ($daterec[1] == "04")
        {
            $month = "Apr";
        }

        if ($daterec[1] == "05")
        {
            $month = "May";
        }

        if ($daterec[1] == "06")
        {
            $month = "Jun";
        }

        if ($daterec[1] == "07")
        {
            $month = "Jul";
        }

        if ($daterec[1] == "08")
        {
            $month = "Aug";
        }

        if ($daterec[1] == "09")
        {
            $month = "Sep";
        }

        if ($daterec[1] == "10")
        {
            $month = "Oct";
        }

        if ($daterec[1] == "11")
        {
            $month = "Nov";
        }

        if ($daterec[1] == "12")
        {
            $month = "Dec";
        }

        $datereceived = $daterec[2] . " " . $month . " " . $daterec[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"] * 1;

        $excess = $claimrow["excess"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $excess = $claimrow["excess"];

		$excess_description = $claimrow['excess_description'];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];
        $ccemail = $ccrow["email"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $assessorcontactno = $assrow["telno"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;
            
    	$qrygetadministrator = "select * from `administrators` where `id` = $administratorid";
    	$qrygetadministratorresults = mysql_query($qrygetadministrator, $db);
    	
    	$administratorrow = mysql_fetch_array($qrygetadministratorresults);
    	
    	$administratorname = stripslashes($administratorrow["name"]);
    	$administratoradr1 = stripslashes($administratorrow["adr1"]);
    	$administratoradr2 = stripslashes($administratorrow["adr2"]);
    	$administratoradr3 = stripslashes($administratorrow["adr3"]);
    	$administratoradr4 = stripslashes($administratorrow["adr4"]);
    	$administratortelno = stripslashes($administratorrow["telno"]);
    	$administratorfaxno = stripslashes($administratorrow["faxno"]);
    	$administratoremail = stripslashes($administratorrow["email"]);
    	$administratorvatno = stripslashes($administratorrow["vatno"]);
    	$auth = stripslashes($administratorrow["auth"]);

        echo "<table style=\"width:600px;\" border=\"0\">



									<tr>



										<td style=\"width:150px;\" align=\"center\">";
										
		if (file_exists("../images/administrators/$administratorid.jpg"))
		{
			echo "<img src=\"http://a-c-i.co.za/images/administrators/$administratorid.jpg\">";
		}
		else
		{
			echo '<img src="http://a-c-i.co.za/images/acilogo.gif">';
		}
										
		echo "</td>



										<td align=\"left\">



											<font style=\"font-size:10pt;font-family:Arial;\">$administratorname<br>



											$administratoradr1, $administratoradr2, $administratoradr3, $administratoradr4<br>



											Tel: $administratortelno<br>



											Fax: $administratorfaxno<br>



										<!--	<font color=\"blue\">$administratoremail</font></font> -->



										</td>



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\"><font style=\"font-size:14pt;font-weight:bold;\">AUTHORISATION FOR REPAIRS</font><br>   $insurancecomp,C/O $administratorname, VAT number:  $administratorvatno</td>



									</tr>										



								</table>



								<br>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">

									<tr>



										<td>Repairer:</td>



										<td><strong>$pbname</strong></td>
										
										
										
										<td>Auth Date:</td>



										<td><strong>$authdate2</strong></td>


										<td>&nbsp;</td>
										
										<td>&nbsp;</td>



									</tr>

									<tr>



										<td>Repairer E-Mail:</td>



										<td><strong>$pbemail</strong></td>
										

										<td>Fax No:</td>



										<td><strong>$pbfax</strong></td>



									</tr>



									<tr>



										<td>Repairer Address:</td>



										<td><strong>$pbadr1, $pbadr2, $pbadr3, $pbadr4</strong></td>



										<td>Client:</td>



										<td><strong>$clientname</strong></td>



									</tr>



									<tr>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



										<td>Quote No:</td>



										<td><strong>$quoteno</strong></td>



									</tr>



									<tr>



										<td>Vehicle:</td>



										<td><strong>$vehicleMake $makemodel</strong></td>



										<td>Reg No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>
									
									<tr>



										<td>Assessor:</td>



										<td><strong>$assessor</strong></td>



										<td>Assessor No:</td>



										<td><strong>$assessorcontactno</strong></td>



									</tr>



								</table>



								<br>



								<table style=\"width:500px;margin-left:100px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:180px;\">Agreed Amount (Incl. VAT): R</td>



										<td><strong>$authamount</strong></td>



									</tr>	



									<tr>



										<td>Less Excess (Incl. VAT):   R</td>



										<td><strong>$excess</strong>
										
										&nbsp;&nbsp;&nbsp; <strong>[$excess_description]</strong>
										
										</td>



									</tr>		



									<tr>



										<td>Total (Incl. VAT):       R</td>



										<td><strong>" . ($authamount - $excess) . "</strong></td>



									</tr>										



								</table>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"4\">



								<p style=\"text-align:justify;\">The above <strong>authorisation</strong> is subject to the following <strong>terms and conditions:</strong></p>



							<p>	$auth </P>



										</td>									



									</tr>


									<tr>



										<td><strong>$claimsclerk</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td><strong>Claims Department</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>
									
									<tr>



										<td><strong>$ccemail</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>



								</table>
								
								<br><br>

								<form name=\"theform1\" action=\"reports.php?action=authorizationemail\" method=\"post\" enctype=\"multipart/form-data\">
									
									Attach file to this email &nbsp;&nbsp; <input type='file' name='file' /> <br /><br />
									
									
																	
									<input type=\"submit\" value=\"Email Report\"><input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
								
								</form>
								
								

								";

    }

    /***************************************************************************



    END OF AUTHORISATION SECTION



    ***************************************************************************/


	    /***************************************************************************



    START OF AUTHORISATIONEMAIL SECTION



    ***************************************************************************/

    if ($action == "authorizationemail")
    {

        $claimid = $_REQUEST["claimid"];

        $qry = "select * from claim where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $claimrow = mysql_fetch_array($qryresults);

        //echo $qry;

        $qry = "select * from footer where `id` = $claimid";

        $qryresults = mysql_query($qry, $db);

        $footerrow = mysql_fetch_array($qryresults);

        $investigator = $footerrow["investigator"];

        $pbid = $claimrow["panelbeaterid"];

        $qrypb = "select * from panelbeaters where `id` = $pbid";

        $qrypbresults = mysql_query($qrypb, $db);

        $qrypbrow = mysql_fetch_array($qrypbresults);

        $pbname = stripslashes($qrypbrow["name"]);
        
        $pbemail = stripslashes($qrypbrow["email"]);

        $pbcontactperson = stripslashes($qrypbrow["contactperson"]);

        $pbcontactno = stripslashes($qrypbrow["contactno"]);

        $pbfax = stripslashes($qrypbrow["faxno"]);

        $pbadr1 = stripslashes($qrypbrow["adr1"]);

        $pbadr2 = stripslashes($qrypbrow["adr2"]);

        $pbadr3 = stripslashes($qrypbrow["adr3"]);

        $pbadr4 = stripslashes($qrypbrow["adr4"]);

        $makemodel = stripslashes($claimrow["makemodel"]);

        $vehicleyear = stripslashes($claimrow["vehicleyear"]);

        $vehicleregistrationno = stripslashes($claimrow["vehicleregistrationno"]);

        $vehiclecolour = stripslashes($claimrow["vehiclecolour"]);

        $inspectiondate = $claimrow["inspdate"];

        $inspdate = explode("-", $inspectiondate);

		$brokerEmail = "";
		$brokerName = "";

		if (!empty($claimrow["brokerid"])) {
			$brokersql = " SELECT name, email FROM `brokers` WHERE `id`='" . $claimrow["brokerid"] . "' ";
			$brokersql = mysql_query($brokersql);
			$brokerInfo = mysql_fetch_assoc($brokersql);

			$brokerEmail = $brokerInfo['email'];
			$brokerName = $brokerInfo['name'];
		}

		$vehicleMake = '';
		if (!empty($claimrow['vehiclemakeid'])) {
			$vehiclemakesql = "SELECT vehiclemake FROM vehiclemake WHERE id='" . $claimrow['vehiclemakeid'] . "'";

			$vehiclemakeresults = mysql_query($vehiclemakesql, $db);

			$vehiclemakerow = mysql_fetch_assoc($vehiclemakeresults);
			$vehicleMake = $vehiclemakerow['vehiclemake'];
		}

		$datesql = " SELECT * FROM `dates` WHERE `claimid`='" . $claimrow["id"] . "' ";
		$dateresult = mysql_query($datesql);
		$datesInfo = mysql_fetch_assoc($dateresult);


        if ($inspdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($inspdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($inspdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($inspdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($inspdate[1] == "05")
        {
            $month = "May";
        }

        if ($inspdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($inspdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($inspdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($inspdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($inspdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($inspdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($inspdate[1] == "12")
        {
            $month = "Dec";
        }

        $inspectiondate = $inspdate[2] . " " . $month . " " . $inspdate[0];

        $authdate = $claimrow["dateauth"];

        $authdate = explode("-", $authdate);

        if ($authdate[1] == "01")
        {
            $month = "Jan";
        }

        if ($authdate[1] == "02")
        {
            $month = "Feb";
        }

        if ($authdate[1] == "03")
        {
            $month = "Mar";
        }

        if ($authdate[1] == "04")
        {
            $month = "Apr";
        }

        if ($authdate[1] == "05")
        {
            $month = "May";
        }

        if ($authdate[1] == "06")
        {
            $month = "Jun";
        }

        if ($authdate[1] == "07")
        {
            $month = "Jul";
        }

        if ($authdate[1] == "08")
        {
            $month = "Aug";
        }

        if ($authdate[1] == "09")
        {
            $month = "Sep";
        }

        if ($authdate[1] == "10")
        {
            $month = "Oct";
        }

        if ($authdate[1] == "11")
        {
            $month = "Nov";
        }

        if ($authdate[1] == "12")
        {
            $month = "Dec";
        }

        $authdate2 = $authdate[2] . " " . $month . " " . $authdate[0];

		
		if (isset($datesInfo['auth']) && $datesInfo['auth'] !== '0000-00-00') {
			$authdate2 = date('d M Y');
		}

        $daterec = $claimrow["datereceived"];

        $daterec = explode("-", $daterec);

        if ($daterec[1] == "01")
        {
            $month = "Jan";
        }

        if ($daterec[1] == "02")
        {
            $month = "Feb";
        }

        if ($daterec[1] == "03")
        {
            $month = "Mar";
        }

        if ($daterec[1] == "04")
        {
            $month = "Apr";
        }

        if ($daterec[1] == "05")
        {
            $month = "May";
        }

        if ($daterec[1] == "06")
        {
            $month = "Jun";
        }

        if ($daterec[1] == "07")
        {
            $month = "Jul";
        }

        if ($daterec[1] == "08")
        {
            $month = "Aug";
        }

        if ($daterec[1] == "09")
        {
            $month = "Sep";
        }

        if ($daterec[1] == "10")
        {
            $month = "Oct";
        }

        if ($daterec[1] == "11")
        {
            $month = "Nov";
        }

        if ($daterec[1] == "12")
        {
            $month = "Dec";
        }

        $datereceived = $daterec[2] . " " . $month . " " . $daterec[0];

        $clientname = stripslashes($claimrow["clientname"]);

        $clientcontactno = stripslashes($claimrow["clientcontactno"]);

        $claimno = stripslashes($claimrow["claimno"]);

        $quoteno = stripslashes($claimrow["quoteno"]);

        $assessorid = $claimrow["assessorid"];

        $betterment = $claimrow["betterment"];

        $authamount = $claimrow["authamount"] * 1;

        $excess = $claimrow["excess"];

		$excess_description = $claimrow["excess_description"];

        $qryassessor = "select * from assessors where `id` = $assessorid";

        $qryassessorresults = mysql_query($qryassessor, $db);

        $assrow = mysql_fetch_array($qryassessorresults);

        $claimsclerkid = $claimrow["claimsclerkid"];

        $qryclaimsclerk = "select * from claimsclerks where `id` = $claimsclerkid";

        $qryclaimsclerkresults = mysql_query($qryclaimsclerk, $db);

        $ccrow = mysql_fetch_array($qryclaimsclerkresults);

        $claimsclerk = $ccrow["name"];
        $ccemail = $ccrow["email"];

        //echo $qryassessor;

        $assessor = $assrow["name"];

        $assessorfax = $assrow["faxno"];

        $assessoremail = $assrow["email"];

        $assessorcontactno = $assrow["telno"];

        $administratorid = $claimrow["administratorid"];

        $qrygetadministrator = "select * from `administrators` where `id` = $administratorid";

        $qrygetadministratorresults = mysql_query($qrygetadministrator, $db);

        $administratorrow = mysql_fetch_array($qrygetadministratorresults);

        $administrator = stripslashes($administratorrow["name"]);

        $insurerid = stripslashes($claimrow["insurerid"]);

        $qrygetinsurer = "select * from `insurers` where `id` = $insurerid";

        $qrygetinsurerresults = mysql_query($qrygetinsurer, $db);

        $insrow = mysql_fetch_array($qrygetinsurerresults);

        $insurancecomp = stripslashes($insrow["name"]);

        $clientno = stripslashes($claimrow["clientno"]);

        $vehicledesc = $makemodel . " (" . $vehicleyear . "); " . $vehicleregistrationno .
            "; " . $vehiclecolour;
            
    	$qrygetadministrator = "select * from `administrators` where `id` = $administratorid";
    	$qrygetadministratorresults = mysql_query($qrygetadministrator, $db);
    	
    	$administratorrow = mysql_fetch_array($qrygetadministratorresults);

		$administratoremail = stripslashes($administratorrow["email"]);
    	
    	$administratorname = stripslashes($administratorrow["name"]);
    	$administratoradr1 = stripslashes($administratorrow["adr1"]);
    	$administratoradr2 = stripslashes($administratorrow["adr2"]);
    	$administratoradr3 = stripslashes($administratorrow["adr3"]);
    	$administratoradr4 = stripslashes($administratorrow["adr4"]);
    	$administratortelno = stripslashes($administratorrow["telno"]);
    	$administratorfaxno = stripslashes($administratorrow["faxno"]);
    	$administratoremail = stripslashes($administratorrow["email"]);
    	$administratorvatno = stripslashes($administratorrow["vatno"]);
    	$auth = stripslashes($administratorrow["auth"]);
	
		
		$attachmentPath = '';

		/*
		if(isset($_FILES['file'])) {

			$file_tmp  = $_FILES['file']['tmp_name'];
			$file_name = $_FILES['file']['name'];

			$mail->AddAttachment($file_tmp, $file_name);
			


			$errors= array();
			$file_name = $_FILES['file']['name'];
			$file_size = $_FILES['file']['size'];
			$file_tmp = $_FILES['file']['tmp_name'];
			$file_type = $_FILES['file']['type'];

			

			$file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));

			$expensions= array("jpeg","jpg","png","pdf");

			if(in_array($file_ext,$expensions)=== false){
			$errors[]="extension not allowed, please choose a PDF, JPEG or PNG file.";
			}

			if($file_size > 2097152) {
				$errors[]='File size must be excately 2 MB';
			}

			

			if ( !file_exists('../uploads') ) {
				mkdir('../uploads/', 0777);
			}
			chown('../uploads/', 0777);

			if (empty($errors)==true) {
				
				$attachmentPath = "uploads/" . $file_name;

				@move_uploaded_file($file_tmp, $attachmentPath); //The folder where you would like your file to be saved
			}


		}
		*/


        $theemail = "<table style=\"width:600px;\" border=\"0\">

									<tr>


										<td style=\"width:150px;\" align=\"center\">";
										
		if (file_exists("../images/administrators/$administratorid.jpg"))
		{
			$theemail .= "<img src=\"http://a-c-i.co.za/images/administrators/$administratorid.jpg\">";
		}
		else
		{
			$theemail .= "<img src=\"http://www.a-c-i,co.za/images/acilogo1.jpg\">";
		}
	

		$attachmentName = "";

		if (isset($_FILES['file']) && $_FILES['file']['error']===UPLOAD_ERR_OK) {
			
			$attachmentName = $_FILES['file']['name'];

		}
										
		$theemail .= "</td>
													



										<td align=\"left\">



											<font style=\"font-size:10pt;font-family:Arial;\">$administratorname<br>



											$administratoradr1, $administratoradr2, $administratoradr3, $administratoradr4<br>



											Tel: $administratortelno<br>



											Fax: $administratorfaxno<br>



											<!-- <font color=\"blue\">$administratoremail</font></font> -->



										</td>



									</tr>



								</table>



								



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td align=\"center\"><font style=\"font-size:14pt;font-weight:bold;\">AUTHORISATION FOR REPAIRS</font><br>$insurancecomp, C/O $administratorname, VAT number:  $administratorvatno</td>



									</tr>										



								</table>



								<br>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">

									<tr>



										<td>Repairer:</td>



										<td><strong>$pbname</strong></td>
										
										<td>Auth Date:</td>



										<td><strong>$authdate2</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>

									<tr>



										<td>Repairer E-Mail:</td>



										<td><strong>$pbemail</strong></td>



										<td> Repairer Fax:</td>



										<td><strong>$pbfax</strong></td>



									</tr>



									<tr>



										<td>Repairer Address:</td>



										<td><strong>$pbadr1, $pbadr2, $pbadr3, $pbadr4</strong></td>



										<td>Client:</td>



										<td><strong>$clientname</strong></td>



									</tr>



									<tr>



										<td>Claim No:</td>



										<td><strong>$claimno</strong></td>



										<td>Quote No:</td>



										<td><strong>$quoteno</strong></td>



									</tr>



									<tr>



										<td>Vehicle Make/Model:</td>



										<td><strong>$vehicleMake $makemodel</strong></td>



										<td>Reg No:</td>



										<td><strong>$vehicleregistrationno</strong></td>



									</tr>
									
									<tr>



										<td>Assessor:</td>



										<td><strong>$assessor</strong></td>



										<td>Assessor No:</td>



										<td><strong>$assessorcontactno</strong></td>



									</tr>



								</table>



								<br>



								<table style=\"width:500px;margin-left:100px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td style=\"width:180px;\">Agreed Cost (Incl. VAT): R</td>



										<td><strong>$authamount</strong></td>



									</tr>	



									<tr>



										<td>Less Excess (Incl. VAT): R</td>



										<td><strong>$excess</strong>
										
										&nbsp;&nbsp;&nbsp; <strong>[$excess_description]</strong>
										
										</td>



									</tr>		



									<tr>



										<td>Total (Incl. VAT):     R</td>



										<td><strong>" . ($authamount - $excess) . "</strong></td>



									</tr>										



								</table>



								<table style=\"width:600px;font-family:Arial;font-size:10pt;\" border=\"0\" cellspacing=\"0\">



									<tr>



										<td colspan=\"4\">



								<p style=\"text-align:justify;\">The above <strong>authorisation</strong> is subject to the following <strong>terms and conditions:</strong></p>



								<p> $auth </p>



										</td>									



									</tr>



									<tr>



										<td><strong>$claimsclerk</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>



									<tr>



										<td><strong>Claims Department</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr>
									
									<tr>



										<td><strong>$ccemail</strong></td>



										<td>&nbsp;</td>



										<td>&nbsp;</td>



									</tr> ";
					

					if (!empty($attachmentName)) {
						$theemail .= " 
							
							<tr><td colspan='3'>&nbsp;</td></tr>
							<tr>
							
							<td colspan='3'>
								Attachment : $attachmentName
							</td>

						</tr>";
					
					}



				$theemail .= "				</table>



								";
								
			$emailaddress = $pbemail;


            //get from name and address

            $from_address = "auth@aci.co.za";//"$ccemail";

            $from_name = "Authorisation";//"$claimsclerk";

            $reply_name = $from_name;

            $reply_address = $from_address;

            $error_delivery_name = $from_name;

            $error_delivery_address = $from_address;

			$to_name = $pbname;

            $to_address = $emailaddress;

            //get the newsletter info

            $subject = "Authorisation for Repairs from $administrator ; Claimno: $claimno; Client: $clientname; Vehicle Reg No: $vehicleregistrationno"; 


			require_once "../vendor/autoload.php";

			$mail = new PHPMailer;

			$mail->setFrom('auth@aci.co.za', 'Authorisation');

			$mail->addAddress($to_address, $to_name); // Add a recipient

			$mail->addCC($ccemail, $claimsclerk);

			$mail->addCC($assessoremail, $assessor);
								
			$mail->addCC($administratoremail);

			if ( !empty($brokerEmail) ) {
				$mail->addCC($brokerEmail, $brokerName);
			}

			$mail->addReplyTo($reply_address, $reply_name);

			$mail->ReturnPath = $error_delivery_address;


			$mail->isHTML(true);

			$mail->Subject = $subject;

			$mail->Body    = $theemail;
			$mail->AltBody = "This is an HTML message. Please use an HTML capable mail program to read this message.";

			if (isset($_FILES['file']) && $_FILES['file']['error']===UPLOAD_ERR_OK) {

				$file_tmp  = $_FILES['file']['tmp_name'];
				$file_name = $_FILES['file']['name'];

				$mail->addAttachment($file_tmp, $file_name);

			}

            $error = $mail->send();

            if (!$error)
            {

                echo "Error: Email not sent. <br />";

            }

            else
            {

                echo "Authotisation emailed to $to_name, $to_address <br />";

                $now = time() + (7 * 3600);
            
	            $now = date("Y-m-d H:i:00", $now);
	
	            $qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', 'Authorisation sent to $to_address', $loggedinuserid)";
	            $qryinsertreportresults = mysql_query($qryinsertreport, $db);

            }
            
        echo $theemail;

    }

    /***************************************************************************



    END OF AUTHORISATIONEMAIL SECTION



    ***************************************************************************/


}

else
{

    echo "<h5>You have been logged out. <a href=\"index.php\">Login here</a></h5>";

}







?>











</body>



</html>







