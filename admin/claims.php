<?php




function humanFileSize($size,$unit="") {
	if ($size == 0) {
		return 'Unknown';
	}

	if( (!$unit && $size >= 1<<30) || $unit == "GB")
		return number_format($size/(1<<30),2)."GB";
	if( (!$unit && $size >= 1<<20) || $unit == "MB")
		return number_format($size/(1<<20),2)."MB";
	if( (!$unit && $size >= 1<<10) || $unit == "KB")
		return number_format($size/(1<<10),2)."KB";
	return number_format($size)." bytes";
}




//echo "test";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Claims($from, $admin)

{

    require ('connection.php');

    //$from = $_REQUEST["from"];

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT * FROM claim order by clientno LIMIT $frm , 30";

    }//end else


	// finding panel beaters

	$qrypanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

    $respanelbeaters = mysql_query($qrypanelbeaters, $db);

	$panelbeatersArray = [];
	
	while($row = mysql_fetch_array($respanelbeaters)) {
		$panelbeatersArray[] = '"' . $row['name'] . '"';
	}

	$panelbeatersArray = implode(',', $panelbeatersArray);


    $qrycountclaims = "select * from claim";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims in the database. Click <a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Claim\" title=\"Add new Claim\"></a> to add one.</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "<a href=\"loggedinaction.php?action=claims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=claims&amp;from=" .
                    $fromrecord . "\">Page $pagenumber</a> || ";

            }//end for loop

        }//end if

        $pageslinks = substr($pageslinks, 0, -4);

        echo "<a href=\"loggedinaction.php?action=claimsummary\">View Summary of Outstanding work on Claims</a><br><br>


			<script type='text/javascript' src='MSelectDBox.js'></script>


			<script type='text/javascript'>
				
				$(document).ready(function() {
					$('#panelbeatername').mSelectDBox({
						\"list\": [".$panelbeatersArray."], // Array of list items,
						\"autoComplete\": true,
						\"embeddedInput\": true
					});
				});
			
			</script>
			



				<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">


						<strong>Search for a claims:</strong><br>
						
                        File Id: <input type=\"text\" name=\"fieldId\"> 
						
						Client NumberA: <input type=\"text\" name=\"clientno\"> 

						Client Name: <input type=\"text\" name=\"clientname\">

						Claim Number: <input type=\"text\" name=\"claimno\"> 
						
						Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

						Panelbeater: <input type=\"text\" name=\"panelbeatername\" id=\"panelbeatername\">

						<input type=\"submit\" value=\"search \">

						<input type=\"hidden\" name=\"from\" value=\"1\">



					<br><br>


					</form>



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" class='table table-striped'>



						  <tr>

                              <td><strong>File Id</strong></td>

							  <td><strong>Client Number</strong></td>

							  <td><strong>Client Name</strong></td>											

							  <td><strong>Claim Number</strong></td>
							  
							  <td><strong>Vehicle Registration Number</strong></td>
							  
							  ";

        if ($admin == 1)
        {

            echo "



								  <td colspan=\"3\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

        }

        else
        {

            echo "



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

        }

        while ($row = mysql_fetch_array($qryclaims))
        {

            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];

			$vehicleregistrationno = $row["vehicleregistrationno"];


            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>

						  <td>$claim_id</td>

						  <td>$clientno</td>

						  <td>$clientname</td>

						  <td>$claimno</td>

						  <td>$vehicleregistrationno</td>";

            if ($admin == 1)
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteclaim&amp;claimid=$claim_id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Claim\" border=\"0\" title=\"Delete this Claim\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

            else
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

        }//end while loop

        echo "<tr>



					  <td colspan=\"5\">&nbsp;</td>";

        if ($admin == 1)
        {

            echo "



						  <td colspan=\"5\" align=\"center\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

        else
        {

            echo "



						  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SearchClaims($claimno, $clientno, $clientname, $vehicleregistrationno, $from, $admin, $panelbeatername)
{

    require ('connection.php');

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim 
				
				LEFT JOIN panelbeaters ON panelbeaters.id = claim.panelbeaterid 

				where clientno like '%" . $clientno . "%' 

				and clientname like '%" . $clientname . "%' 

				and claimno like '%" . $claimno . "%' 

				and vehicleregistrationno like '%" . $vehicleregistrationno . "%'

				";

				if (!empty($panelbeatername)) {
					$qry .= " and TRIM(panelbeaters.name) = '" . $panelbeatername . "' ";
				}


				$qry .= " order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT claim.*, pb.name as pbname, pb.owner FROM claim 
				LEFT JOIN panelbeaters as pb
				ON claim.panelbeaterid = pb.id
				where clientno like '%" . $clientno . "%' 


										and clientname like '%" . $clientname . "%' 

										and claimno like '%" . $claimno . "%' 

										and vehicleregistrationno like '%" . $vehicleregistrationno . "%' ";

				if (!empty($panelbeatername)) {
					$qry .= " and TRIM(pb.name) = '" . $panelbeatername . "' ";
				}

			$qry .=	"order by clientno LIMIT $frm , 30";

    }//end else

    $qrycountclaims = "SELECT * FROM claim where clientno like '%" . $clientno .
        "%' 


										and clientname like '%" . $clientname . "%' 

										and claimno like '%" . $claimno . "%' 

										and vehicleregistrationno like '%" . $vehicleregistrationno . "%'

										order by clientno";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims in the database with these search criteria. <a href=\"javascript:history.go(-1);\">Go Back to Claims</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "



			



						   <form style=\"display:inline\" action=\"loggedinaction.php?action=searchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page 1\"> 

																									<input type=\"hidden\" name=\"clientid\" value=\"$clientid\">

																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">

																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">

																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">

																									<input type=\"hidden\" name=\"vehicleregistrationno\" value=\"$vehicleregistrationno\">

																									<input type=\"hidden\" name=\"from\" value=\"1\"></form>&nbsp;";

        //<a href=\"loggedinaction.php?action=claims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<form style=\"display:inline\" action=\"loggedinaction.php?action=searchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page $pagenumber\"> 



																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">


																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">


																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">


																									<input type=\"hidden\" name=\"vehicleregistrationno\" value=\"$vehicleregistrationno\">
																									

																									<input type=\"hidden\" name=\"from\" value=\"$fromrecord\"></form>&nbsp;";

            }//end for loop

        }//end if


		// finding panel beaters

		$qrypanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

		$respanelbeaters = mysql_query($qrypanelbeaters, $db);

		$panelbeatersArray = [];
		
		while($row = mysql_fetch_array($respanelbeaters)) {
			$panelbeatersArray[] = '"' . $row['name'] . '"';
		}

		$panelbeatersArray = implode(',', $panelbeatersArray);

        //$pageslinks = substr($pageslinks, 0, -4);

        echo "Search results for Client NumberB: <strong>$clientno</strong>, Client Name: <strong>$clientname</strong>, Claim Number: <strong>$claimno</strong> and  Vehicle Registration Number: <strong>$vehicleregistrationno</strong><br><br>
		
		
		<script type='text/javascript' src='MSelectDBox.js'></script>


		<script type='text/javascript'>
			
			$(document).ready(function() {
				$('#panelbeatername').mSelectDBox({
					\"list\": [".$panelbeatersArray."], // Array of list items,
					\"autoComplete\": true,
					\"embeddedInput\": true
				});
			});
		
		</script>
		
		
			<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">


						<strong>Search for a claim:</strong><br>

                        File Id: <input type=\"text\" name=\"fieldId\"> 
						
						Client NumberC: <input type=\"text\" name=\"clientno\"> 

						Client Name: <input type=\"text\" name=\"clientname\">

						Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

						Claim Number: <input type=\"text\" name=\"claimno\"> 

						Panelbeater: <input type=\"text\" name=\"panelbeatername\" id=\"panelbeatername\">

						<input type=\"submit\" value=\"Search\" >  



					<br><br>


					</form>



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">



						  <tr>


							  <td><strong>File Id</strong></td>
							  
							  <td><strong>Client Number</strong></td>

							  <td><strong>Client Name</strong></td>

							  <td><strong>Claim Number</strong></td>

							  <td><strong>Panelbeater</strong></td>
							  
							  <td><strong>Vehicle Registration Number</strong></td>

							  ";

        if ($admin == 1)
        {

            echo "



								  <td colspan=\"3\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

        }

        else
        {

            echo "



								  <td align=\"center\" colspan=\"2\"><strong>Actions</strong></td>



							  </tr>";

        }

        while ($row = mysql_fetch_array($qryclaims))
        {
            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];
			
			$vehicleregistrationno = $row["vehicleregistrationno"];

			$panelbeaterName = $row["pbname"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>


						  <td>$claim_id</td>
						  
						  <td>$clientno</td>

						  <td>$clientname</td>

						  <td>$claimno</td>

						  <td>$panelbeaterName</td>

						  <td>$vehicleregistrationno</td>						  
						  
						  ";

            if ($admin == 1)
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>


							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteclaim&amp;claimid=$claim_id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Claim\" border=\"0\" title=\"Delete this Claim\"></td>


							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

            else
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>											  


							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

        }//end while loop

        echo "<tr>



					  <td colspan=\"6\">&nbsp;</td>";

        if ($admin == 1)
        {

            echo "		  



						  <td colspan=\"3\" align=\"center\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

        else
        {

            echo "		  



						  <td align=\"center\" colspan=\"2\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

    }

}

function SearchClaimsWithId($fieldId, $claimno, $clientno, $clientname, $vehicleregistrationno, $from, $admin, $panelbeatername)
{

    require ('connection.php');

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim 
				
				LEFT JOIN panelbeaters ON panelbeaters.id = claim.panelbeaterid 
                where claim.id  like '%" . $fieldId . "%'
				and clientno like '%" . $clientno . "%' 

				and clientname like '%" . $clientname . "%' 

				and claimno like '%" . $claimno . "%' 

				and vehicleregistrationno like '%" . $vehicleregistrationno . "%'

				";

        if (!empty($panelbeatername)) {
            $qry .= " and TRIM(panelbeaters.name) = '" . $panelbeatername . "' ";
        }


        $qry .= " order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT claim.*, pb.name as pbname, pb.owner FROM claim 
				LEFT JOIN panelbeaters as pb
				ON claim.panelbeaterid = pb.id
				where claim.id like '%" . $fieldId . "%' 
				and clientno like '%" . $clientno . "%' 



										and clientname like '%" . $clientname . "%' 

										and claimno like '%" . $claimno . "%' 

										and vehicleregistrationno like '%" . $vehicleregistrationno . "%' ";

        if (!empty($panelbeatername)) {
            $qry .= " and TRIM(pb.name) = '" . $panelbeatername . "' ";
        }

        $qry .=	"order by clientno LIMIT $frm , 30";

    }//end else

    $qrycountclaims = "SELECT * FROM claim where clientno like '%" . $clientno .
        "%' 


										and id like '%" . $fieldId . "%'
				                        and  clientname like '%" . $clientname . "%' 

										and claimno like '%" . $claimno . "%' 

										and vehicleregistrationno like '%" . $vehicleregistrationno . "%'

										order by clientno";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims in the database with these search criteria. <a href=\"javascript:history.go(-1);\">Go Back to Claims</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "



			



						   <form style=\"display:inline\" action=\"loggedinaction.php?action=searchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page 1\"> 


																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">

																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">

																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">

																									<input type=\"hidden\" name=\"vehicleregistrationno\" value=\"$vehicleregistrationno\">

																									<input type=\"hidden\" name=\"from\" value=\"1\"></form>&nbsp;";

        //<a href=\"loggedinaction.php?action=claims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<form style=\"display:inline\" action=\"loggedinaction.php?action=searchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page $pagenumber\"> 


																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">


																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">


																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">


																									<input type=\"hidden\" name=\"vehicleregistrationno\" value=\"$vehicleregistrationno\">
																									

																									<input type=\"hidden\" name=\"from\" value=\"$fromrecord\"></form>&nbsp;";

            }//end for loop

        }//end if


        // finding panel beaters

        $qrypanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

        $respanelbeaters = mysql_query($qrypanelbeaters, $db);

        $panelbeatersArray = [];

        while($row = mysql_fetch_array($respanelbeaters)) {
            $panelbeatersArray[] = '"' . $row['name'] . '"';
        }

        $panelbeatersArray = implode(',', $panelbeatersArray);

        //$pageslinks = substr($pageslinks, 0, -4);

        echo "Search results for File Id: <strong>$fieldId</strong>, Client NumberD: <strong>$clientno</strong>, Client Name: <strong>$clientname</strong>, Claim Number: <strong>$claimno</strong> and  Vehicle Registration Number: <strong>$vehicleregistrationno</strong><br><br>
		
		
		<script type='text/javascript' src='MSelectDBox.js'></script>


		<script type='text/javascript'>
			
			$(document).ready(function() {
				$('#panelbeatername').mSelectDBox({
					\"list\": [".$panelbeatersArray."], // Array of list items,
					\"autoComplete\": true,
					\"embeddedInput\": true
				});
			});
		
		</script>
		
		
			<form action=\"loggedinaction.php?action=searchclaims\" method=\"post\" name=\"searchform\">


						<strong>Search for a claim:</strong><br>

                        File Id: <input type=\"text\" name=\"fieldId\"> 
						
						Client NumberE: <input type=\"text\" name=\"clientno\"> 

						Client Name: <input type=\"text\" name=\"clientname\">

						Vehicle Registration Number: <input type=\"text\" name=\"vehicleregistrationno\">

						Claim Number: <input type=\"text\" name=\"claimno\"> 

						Panelbeater: <input type=\"text\" name=\"panelbeatername\" id=\"panelbeatername\">

						<input type=\"submit\" value=\"Search\">



					<br><br>

					</form>



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">



						  <tr>

                              <td><strong>File Id</strong></td>
							  
							  <td><strong>Client Number</strong></td>

							  <td><strong>Client Name</strong></td>

							  <td><strong>Claim Number</strong></td>

							  <td><strong>Panelbeater</strong></td>
							  
							  <td><strong>Vehicle Registration Number</strong></td>

							  ";

        if ($admin == 1)
        {

            echo "



								  <td colspan=\"3\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

        }

        else
        {

            echo "



								  <td align=\"center\" colspan=\"2\"><strong>Actions</strong></td>



							  </tr>";

        }

        while ($row = mysql_fetch_array($qryclaims))
        {
            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];

            $vehicleregistrationno = $row["vehicleregistrationno"];

            $panelbeaterName = $row["pbname"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>
						  <td>$claim_id</td>
						  
						  <td>$clientno</td>

						  <td>$clientname</td>

						  <td>$claimno</td>

						  <td>$panelbeaterName</td>

						  <td>$vehicleregistrationno</td>						  
						  
						  ";

            if ($admin == 1)
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteclaim&amp;claimid=$claim_id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Claim\" border=\"0\" title=\"Delete this Claim\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

            else
            {

                echo "



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>											  



							  <td align=\"center\"><a href=\"loggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

            }

        }//end while loop

        echo "<tr>



					  <td colspan=\"6\">&nbsp;</td>";

        if ($admin == 1)
        {

            echo "		  



						  <td colspan=\"3\" align=\"center\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

        else
        {

            echo "		  



						  <td align=\"center\" colspan=\"2\"><a href=\"loggedinaction.php?action=newclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

        }

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function EditClaim($id, $step)
{
	//print '<pre>'; print_r($_POST);exit();
	//print_r($id); 
	//print_r($step); exit;
    
    require ('connection.php');

    $claimid = $id;

    $qryclaimdetails = "select * from `claim` where `id` = $claimid";
    
	$qryclaimdetailsresults = mysql_query($qryclaimdetails, $db);
	
    $claimdetailsrow = $qryclaimdetailsresults ? mysql_fetch_array($qryclaimdetailsresults) : array();

    $clientname = stripslashes($claimdetailsrow["clientname"]);
    
	$clientnumber2 = stripslashes($claimdetailsrow["clientno"]);
    
	$claimnumber = stripslashes($claimdetailsrow["claimno"]);

	$vehicleregistrationno = stripslashes($claimdetailsrow["vehicleregistrationno"]);

    //echo "WERQWERQ " . $step . " GFBGFDNYETRH";

    $fromstep = $_REQUEST["fromstep"];

	//echo '<pre>';print_r($fromstep);exit();

    if ($fromstep == 1)
    {

        SaveStep($claimid, 1, "no", 0);

    }

    if ($fromstep == 3)
    {

        SaveStep($claimid, 3, "no", 0);

    }

    if ($fromstep == 5)
    {

        SaveStep($claimid, 5, "no", 0);

    }

    if ($step == 1) //Claim Details
    {

        echo "
		<style>
			
			form * {font-size:14px;}

		</style>
		
		<form class='no-show-in-print'><input type=\"button\" value=\"Claim Details\" disabled />			



						<input type=\"button\" value=\"Parts\" onClick=\"document.theform.next.value = 1; document.theform.stepto.value = 2;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Dates\" onClick=\"document.theform.stepto.value = 3;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Reports\" onClick=\"document.theform.stepto.value = 4;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.theform.stepto.value = 5;



																		 document.theform.submit();\" />	

																		 										 

						<input type=\"button\" value=\"Attachments\" onClick=\"document.theform.stepto.value = 6;



																		 document.theform.submit();\" />	
																		 
																		 
						<input type=\"button\" value=\"Quote\" onClick=\"document.theform.next.value = 1; document.theform.stepto.value = 7;



																		 document.theform.submit();\" />

																		 </form>";

        $qry = "select c.*, p.name as panelbeater from claim as c left join panelbeaters as p ON c.panelbeaterid = p.id where c.id = $claimid";

        $qryresults = mysql_query($qry, $db) or die('error: ' . mysql_error());

        $row = mysql_fetch_array($qryresults);

        $reloaded = $_REQUEST["reload"];
        
        //print_r($_REQUEST); exit();

        if ($reloaded == 1)
        {

            $clientname = $_REQUEST["clientname"];

            $clientno = $_REQUEST["clientno"];

            $claimno = $_REQUEST["claimno"];

            $clientcontactno1 = $_REQUEST["clientcontactno1"];

            $clientcontactno2 = $_REQUEST["clientcontactno2"];

			$clientemail = $_REQUEST["clientemail"];

            $panelbeaterid = $_REQUEST["pbid"];

            $vehiclemakemodel = $_REQUEST["vehiclemakemodel"];

            $vehicleyear = $_REQUEST["vehicleyear"];

            $vehicleregistrationno = $_REQUEST["vehicleregistrationno"];

            $vehicletype = $_REQUEST["vehicletype"];
			
			$vehiclevin = $_REQUEST["vehiclevin"];

            $administratorid = $_REQUEST["adminid"];

            $quoteno = $_REQUEST["quoteno"];

            $insurerid = $_REQUEST["insurerid"];

			$brokerid = $_REQUEST["brokerid"];

            $claimsclerkid = $_REQUEST["claimsclerk"];

            $authamount = $_REQUEST["authamount"];

            $excess = $_REQUEST["excess"];

			$excess_description = $_REQUEST["excess_description"];

            $betterment = $_REQUEST["betterment"];

            $quoteamount = $_REQUEST["quoteamount"];

            $assessorid = $_REQUEST["assid"];

            $area = $_REQUEST["area"];

			$vehiclemakeid = $_REQUEST["vehiclemake"];

			$panelbeater = "";


            if ($area == 0)
            {

                $assessorid = 0;

            }

        }
        else
        {

            $clientname = stripslashes($row["clientname"]);

            $clientno = $row["clientno"];

            $claimno = stripslashes($row["claimno"]);

            $clientcontactno1 = stripslashes($row["clientcontactno"]);

            $clientcontactno2 = stripslashes($row["clientcontactno2"]);

			$clientemail = stripslashes($row["clientemail"]);

            $panelbeaterid = $row["panelbeaterid"];

            $vehiclemakemodel = stripslashes($row["makemodel"]);

            $vehicleyear = stripslashes($row["vehicleyear"]);

            $vehicleregistrationno = stripslashes($row["vehicleregistrationno"]);

			$panelbeater= stripslashes($row["panelbeater"]);

            $vehicletype = stripslashes($row["vehicletype"]);

			$vehiclevin = stripslashes($row["vehiclevin"]);

            $administratorid = $row["administratorid"];

            $quoteno = stripslashes($row["quoteno"]);

            $insurerid = $row["insurerid"];

			$brokerid = $row["brokerid"];

            $claimsclerkid = $row["claimsclerkid"];

            $authamount = $row["authamount"];

            $quoteamount = $row["quoteamount"];

            $excess = $row["excess"];
			
			$excess_description = $row["excess_description"];

            $betterment = $row["betterment"];

            $assessorid = $row["assessorid"];

			$vehiclemakeid = $row["vehiclemakeid"];

            $area = 0;

        }

        echo "<script type=\"text/javascript\">



		



		function ReloadThisPage()



		{



			var pbid = document.theform.panelbeater.value;
			var adminid = document.theform.administrator.value;

			var assid = document.theform.assessor.value;

			document.hiddenform.clientno.value = document.theform.clientno.value;
			document.hiddenform.clientname.value = document.theform.clientname.value;
			document.hiddenform.claimno.value = document.theform.claimno.value;
			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;
			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;
			document.hiddenform.clientemail.value = document.theform.clientemail.value;

			document.hiddenform.pbid.value = pbid;

			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;
			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;
			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;

			// document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;

			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;
			document.hiddenform.adminid.value = adminid;

			document.hiddenform.quoteno.value = document.theform.quoteno.value;
			document.hiddenform.insurerid.value = document.theform.insurerid.value;
			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;
			document.hiddenform.authamount.value = document.theform.authamount.value;
			document.hiddenform.betterment.value = document.theform.betterment.value;
			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;
			document.hiddenform.assid.value = assid;

			document.hiddenform.reload.value = 1;

			document.hiddenform.dothepb.value = 0;

			document.hiddenform.area.value = document.theform.area.value;
			
			document.hiddenform.excess.value = document.theform.excess.value;
			
			document.hiddenform.excess_description.value = document.theform.excess_description.value;
			
			document.hiddenform.brokerid.value = document.theform.brokerid.value;

			document.hiddenform.assessor.value = document.theform.assessor.value;

			//alert(id);

			document.hiddenform.submit();

		}



		



		function ReloadThisPagePB(area)
		{


			var pbid = document.theform.panelbeater.value;

			var adminid = document.theform.administrator.value;

			var assid = document.theform.assessor.value;

			document.hiddenform.clientno.value = document.theform.clientno.value;

			document.hiddenform.clientname.value = document.theform.clientname.value;

			document.hiddenform.claimno.value = document.theform.claimno.value;

			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;

			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;

			document.hiddenform.pbid.value = pbid;	

			document.hiddenform.pbname.value = document.theform.pbname.value;	

			document.hiddenform.pbowner.value = document.theform.pbowner.value;
			
			document.hiddenform.pbworkshopmanageremail.value = document.theform.pbworkshopmanageremail.value;
			
			document.hiddenform.pbcostingclerkemail.value = document.theform.pbcostingclerkemail.value;
			
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;
			
			document.hiddenform.pbowneremail.value = document.theform.pbowneremail.value;
			
			document.hiddenform.pbownercel.value = document.theform.pbownercel.value;
			
			document.hiddenform.pbestimator.value = document.theform.pbestimator.value;
			
			document.hiddenform.pbestimatorcel.value = document.theform.pbestimatorcel.value;
			
			document.hiddenform.pbestimatoremail.value = document.theform.pbestimatoremail.value;
			
			document.hiddenform.pbpartsmanager.value = document.theform.pbpartsmanager.value;
			
			document.hiddenform.pbpartsmanagercel.value = document.theform.pbpartsmanagercel.value;
			
			document.hiddenform.pbpartsmanageremail.value = document.theform.pbpartsmanageremail.value;
			
			document.hiddenform.pbdms.value = document.theform.pbdms.value;
			
			document.hiddenform.pbmember.value = document.theform.pbmember.value;
			
			document.hiddenform.pbfactoring.value = document.theform.pbfactoring.value;
			
			document.hiddenform.pbsize.value = document.theform.pbsize.value;
			
			document.hiddenform.latitude.value = document.theform.latitude.value;
			
			document.hiddenform.longitude.value = document.theform.longitude.value;

			document.hiddenform.pbcostingclerk.value = document.theform.pbcostingclerk.value;
			
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;

			document.hiddenform.pbcontactnumber.value = document.theform.pbcontactnumber.value;

			document.hiddenform.pbcontactnumber2.value = document.theform.pbcontactnumber2.value;

			document.hiddenform.pbcontactperson.value = document.theform.pbcontactperson.value;

			document.hiddenform.pbfaxno.value = document.theform.pbfaxno.value;

			document.hiddenform.pbemail.value = document.theform.pbemail.value;

			document.hiddenform.pbadr1.value = document.theform.pbadr1.value;

			document.hiddenform.pbadr2.value = document.theform.pbadr2.value;

			document.hiddenform.pbadr3.value = document.theform.pbadr3.value;

			document.hiddenform.pbadr4.value = document.theform.pbadr4.value;

			document.hiddenform.notes.value = document.theform.notes.value;

			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;
			
			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;

			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;

			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		

			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;
			document.hiddenform.adminid.value = adminid;
			document.hiddenform.quoteno.value = document.theform.quoteno.value;
			document.hiddenform.insurerid.value = document.theform.insurerid.value;
			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;
			document.hiddenform.authamount.value = document.theform.authamount.value;
			document.hiddenform.excess.value = document.theform.excess.value;
			document.hiddenform.excess_description.value = document.theform.excess_description.value;
			document.hiddenform.brokerid.value = document.theform.brokerid.value;

			document.hiddenform.assessor.value = document.theform.assessor.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;

			document.hiddenform.assid.value = assid;

			document.hiddenform.reload.value = 1;
			document.hiddenform.dothepb.value = 1;	

			if (area == 1)

			{

				document.hiddenform.assid.value = 0;
			}



			document.hiddenform.area.value = document.theform.area.value;


			//alert(id);

			document.hiddenform.submit();

		}



	</script>";

		$emailSubject = ucwords("$clientnumber2, $clientname, $claimnumber, $vehicleregistrationno, $vehiclemakemodel");


		$vehicleTypesList = getVehicleTypesList();

        echo "



					<form method=\"post\" action=\"loggedinaction.php?action=editclaim\" name=\"theform\">
					
						<p>File IDF: <strong>$id</strong>; Client NumberF: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong>; Panelbeater: <strong>$panelbeater</strong></p>
					<table>
					


					<tr>



						<td>	

							<table bgcolor=\"#E7E7FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"blue-bg\">
								<tr>
								
									
									<td colspan=\"6\" class='pad-10'><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\" /><img src=\"../images/man.png\"
										style=\"height:35px;vertical-align:middle;\" /> <strong>$id</strong> </h4>

										<div style=\"display:inline-block;\">
											Client NumberG: <input type=\"text\" value=\"$clientno\" maxlength=\"50\" name=\"clientno\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Name: <input type=\"text\" value=\"$clientname\" maxlength=\"50\" name=\"clientname\" />
										</div>

										<div style=\"display:inline-block;\">
											Claim Number: <input type=\"text\" value=\"$claimno\" maxlength=\"50\" name=\"claimno\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Contact No: <input type=\"text\" value=\"$clientcontactno1\" maxlength=\"50\" name=\"clientcontactno1\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Contact No 2: <input type=\"text\" value=\"$clientcontactno2\" maxlength=\"50\" name=\"clientcontactno2\" />
										</div>

										<div style=\"display:inline-block;\">
											Email Address: <input type=\"text\" value=\"$clientemail\" maxlength=\"50\" name=\"clientemail\" /> 
											
											<a href=\"mailto:$clientemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Client\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>

										</div>

									</td>
									<tr>
									<td colspan=\"5\" class='pad-10'>
										Tel: $clientcontactno1, 
										Tel2: $clientcontactno2
										";
										
									if ( !empty($clientcontactno1) ) { echo $clientcontactno2; }
								echo "
									</td>
									</tr>
								</tr>
								
							</table>



							<br />



							<table bgcolor=\"#D3D3FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">

								<tr>

									<td colspan=\"10\" class='pad-10'><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\"><img src=\"../images/phone.png\" style=\"height:35px;vertical-align:middle;\" /> </h4>
										
										<div style=\"display:inline-block;\">
											Panelbeater: 

											<select name=\"panelbeater\" onChange=\"ReloadThisPage();\">";

											$qrygetpanelbeaters = "select * from panelbeaters order by `name`";
											$qrygetpanelbeatersresults = mysql_query($qrygetpanelbeaters, $db);

											while ($row = mysql_fetch_array($qrygetpanelbeatersresults))
											{
												$pbid = $row["id"];
												$pbname = stripslashes($row["name"]);
												if ($pbid == $panelbeaterid) {
													echo "<option value=\"$pbid\" selected>$pbname</option>";
												}
												else {
													echo "<option value=\"$pbid\">$pbname</option>";
												}
											}

											$qrygetpanelbeaterinfo = "select * from panelbeaters where `id` = $panelbeaterid";
											$qrygetpanelbeaterinforesults = mysql_query($qrygetpanelbeaterinfo, $db);
											$selectedpbrow = mysql_fetch_array($qrygetpanelbeaterinforesults);
											$dothepb = $_REQUEST["dothepb"];
											//echo "asdfasdf $dothepb ASDFSADF";
											
											$pbname = $selectedpbrow["name"];
											
											$pbowner = $selectedpbrow["owner"];
											$pbownercel = $selectedpbrow["ownercel"];
											$pbownercel = $selectedpbrow["ownercel"];
											$pbowneremail = $selectedpbrow["owneremail"];
											
											$pbcontactperson = $selectedpbrow["contactperson"];
											
											$pbcostingclerk = $selectedpbrow["costingclerk"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbcostingclerkemail = $selectedpbrow["costingclerkemail"];
											
											$pbworkshopmanager = $selectedpbrow["workshopmanager"];
											$pbworkshopmanageremail = $selectedpbrow["workshopmanageremail"];
											$pbworkshopmanagercel = $selectedpbrow["workshopmanagercel"];

											$pbestimator = $selectedpbrow["estimator"];
											$pbestimatorcel = $selectedpbrow["estimatorcel"];
											$pbestimatoremail = $selectedpbrow["estimatoremail"];
											
											$pbpartsmanager = $selectedpbrow["partsmanager"];
											$pbpartsmanagercel = $selectedpbrow["partsmanagercel"];
											$pbpartsmanageremail = $selectedpbrow["partsmanageremail"];
											
											$pbdms = $selectedpbrow["dms"];
											$pbmember = $selectedpbrow["member"];
											$pbfactoring = $selectedpbrow["factoring"];
											$pbsize = $selectedpbrow["size"];
											
											$latitude = $selectedpbrow["latitude"];
											$longitude = $selectedpbrow["longitude"];

											$pbcontactnumber = $selectedpbrow["contactno"];
											$pbcontactnumber2 = $selectedpbrow["contactno2"];
											
											$pbfaxno = $selectedpbrow["faxno"];
											$pbemail = $selectedpbrow["email"];
											$pbadr1 = $selectedpbrow["adr1"];
											$pbadr2 = $selectedpbrow["adr2"];
											$pbadr3 = $selectedpbrow["adr3"];
											$pbadr4 = $selectedpbrow["adr4"];
											
											$notes = $selectedpbrow["notes"];
											
																														

											if ($dothepb == 1)
											{
												$pbname = $_REQUEST["pbname"];
												
												$pbcontactperson = $_REQUEST["pbcontactperson"];
												$pbcontactperson = $_REQUEST["pbcontactperson"];
												
												$pbcontactnumber = $_REQUEST["pbcontactnumber"];
												$pbcontactnumber2 = $_REQUEST["pbcontactnumber2"];
												
												$pbowner = $_REQUEST["pbowner"];
												$pbownercel = $_REQUEST["pbownercel"];
												$pbowneremail = $_REQUEST["pbowneremail"];
												
												$pbcostingclerk = $_REQUEST["pbcostingclerk"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbcostingclerkemail = $_REQUEST["pbcostingclerkemail"];
												
												$pbworkshopmanager = $_REQUEST["pbworkshopmanager"];
												$pbworkshopmanagercel = $_REQUEST["pbworkshopmanagercel"];
												$pbworkshopmanageremail = $_REQUEST["pbworkshopmanageremail"];
												
												$pbestimator = $_REQUEST["pbestimator"];
												$pbestimatorcel = $_REQUEST["pbestimatorcel"];
												$pbestimatoremail = $_REQUEST["pbestimatoremail"];
												
												$pbpartsmanager = $_REQUEST["pbpartsmanager"];
												$pbpartsmanagercel = $_REQUEST["pbpartsmanagercel"];
												$pbpartsmanageremail = $_REQUEST["pbpartsmanageremail"];
												
												
												$pbdms = $_REQUEST["pbdms"];
												$pbmember = $_REQUEST["pbmember"];
												$pbfactoring = $selectedpbrow["pbfactoring"];
											    $pbsize = $selectedpbrow["pbsize"];
												
												$pbfaxno = $_REQUEST["pbfaxno"];
												$pbemail = $_REQUEST["pbemail"];
												$pbadr1 = $_REQUEST["pbadr1"];
												$pbadr2 = $_REQUEST["pbadr2"];
												$pbadr3 = $_REQUEST["pbadr3"];
												$pbadr4 = $_REQUEST["pbadr4"];
												$notes = $_REQUEST["notes"];
												
												$latitude = $_REQUEST["latitude"];
												$longitude = $_REQUEST["longitude"];
												
											}

											$vehicleMakeInfo = get_vehicle_make_by_id($db, $vehiclemakeid);

											$vehiclemakeName = $vehicleMakeInfo['vehiclemake'];

											$emailSubject = ucwords("$clientnumber2, $clientname, $claimnumber, $vehicleregistrationno, $vehiclemakeName, $vehiclemakemodel");

											echo "					</select>

										</div>

										<div style=\"display:inline-block;\">
											Panelbeater: 
											<input type=\"text\" value=\"$pbname\" maxlength=\"50\" name=\"pbname\" style='width:300px;'  />
										</div>
									</td>
										</tr>
								<tr>
									<td colspan=\"8\" class='pad-10'>
										Tel: $pbcontactnumber, 
										Tel2: $pbcontactnumber2,
										Address: $pbadr1, $pbadr2, $pbadr3 ";
										
									if ( !empty($pbadr4) ) { echo $pbadr4; }
								echo "
									</td>
								</tr>
								<tr>
									<td colspan=\"10\" class='pad-10'>
										<div style=\"display:inline-block;width:20%;\">
											Tel: <input type=\"text\" value=\"$pbcontactnumber\" maxlength=\"50\" name=\"pbcontactnumber\"   />
										</div>

										<div style=\"display:inline-block;width:20%;\">
											Tel 2: <input type=\"text\" value=\"$pbcontactnumber2\" maxlength=\"50\" name=\"pbcontactnumber2\"   />
										</div>
										
										
										<div style=\"display:inline-block;width:20%;\">
											Fax: <input type=\"text\" value=\"$pbfaxno\" maxlength=\"50\" name=\"pbfaxno\"  />
										</div>
										
										<div style=\"display:inline-block;width:37%;\">
											Email: <input type=\"text\" value=\"$pbemail\" maxlength=\"255\" name=\"pbemail\"  class='textinput-lg'  />
											<a href=\"mailto:$pbemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
										</div>

										
									</td>
								</tr>

								<tr>
									<td>Owner/Manager:</td>
									<td><input type=\"text\" value=\"$pbowner\" maxlength=\"50\" name=\"pbowner\"  /></td>

									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbownercel\" maxlength=\"50\" name=\"pbownercel\"  /></td>

									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbowneremail\" maxlength=\"50\" name=\"pbowneremail\" class='textinput-lg' />
										<a href=\"mailto:$pbowneremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>
									
									<td>Contact: </td>
									<td><input type=\"text\" value=\"$pbcontactperson\" maxlength=\"50\" name=\"pbcontactperson\" /></td>
							
								</tr>

								<tr>
									<td>Costing Clerk:</td>
									<td><input type=\"text\" value=\"$pbcostingclerk\" maxlength=\"50\" name=\"pbcostingclerk\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbcostingclerkcel\" maxlength=\"50\" name=\"pbcostingclerkcel\"  /></td>
									
									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbcostingclerkemail\" maxlength=\"50\" name=\"pbcostingclerkemail\" class='textinput-lg' />
										<a href=\"mailto:$pbcostingclerkemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Street: </td>
									<td><input type=\"text\" value=\"$pbadr1\" maxlength=\"50\" name=\"pbadr1\"  /></td>
								</tr>

								<tr>
									<td>Workshop Manager:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanager\" maxlength=\"50\" name=\"pbworkshopmanager\"  /></td>

									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanagercel\" maxlength=\"50\" name=\"pbworkshopmanagercel\"  /></td>

									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbworkshopmanageremail\" maxlength=\"50\" name=\"pbworkshopmanageremail\" class='textinput-lg' />
										<a href=\"mailto:$pbworkshopmanageremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Suburb: </td>
									<td><input type=\"text\" value=\"$pbadr2\" maxlength=\"50\" name=\"pbadr2\"  /></td>
								</tr>

								<tr>
									<td>Estimator: </td>
									<td><input type=\"text\" value=\"$pbestimator\" maxlength=\"50\" name=\"pbestimator\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbestimatorcel\" maxlength=\"50\" name=\"pbestimatorcel\"  /></td>
									
									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbestimatoremail\" maxlength=\"50\" name=\"pbestimatoremail\" class='textinput-lg' />
										<a href=\"mailto:$pbestimatoremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>
									<td>Province: </td>
									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\"  /></td>
								</tr>
								
								<tr>
									<td>Parts Manager: </td>
									<td><input type=\"text\" value=\"$pbpartsmanager\" maxlength=\"50\" name=\"pbpartsmanager\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbpartsmanagercel\" maxlength=\"50\" name=\"pbpartsmanagercel\"  /></td>
									
									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbpartsmanageremail\" maxlength=\"50\" name=\"pbpartsmanageremail\" class='textinput-lg' />
										<a href=\"mailto:$pbpartsmanageremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>
									
									<td>Area Code: </td>
									<td><input type=\"text\" value=\"$pbadr4\" maxlength=\"50\" name=\"pbadr4\"  /></td>
									<!--- <td>Psrts Managers Mobile: </td>
									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\"  /></td> --->
								</tr>
								
								<tr>
									<td>DMS: </td>
									<td><input type=\"text\" value=\"$pbdms\" maxlength=\"50\" name=\"pbdms\" /><br>TMS, Vantage, Dr Smash, Veculim</td>
									<td>Member: </td>
									<td><input type=\"text\" value=\"$pbmember\" maxlength=\"50\" name=\"pbmember\"  /><br>SAMBRA, RMI, CRA, SAARSA
											</td>
									<td>Factoring: </td>
									<td><input type=\"text\" value=\"$pbfactoring\" maxlength=\"50\" name=\"pbfactoring\" /><br>Praxis, AAF, Mettle</td>
									<td>Size (Jobs/week): </td>
									<td><input type=\"text\" value=\"$pbsize\" maxlength=\"50\" name=\"pbsize\" /> <br>Small - <10,  Medium - 10 - 20<br> Large - 20 - 30,  Ext Large - <30</td>
									
									
								
								<tr>
									<td rowspan=\"3\">Notes:</td>
									<td rowspan=\"3\" colspan=\"3\">
										<textarea name=\"notes\" style='width:400px;height:85px;'>$notes </textarea>
										


									</td>
									
								</tr>

								<tr>
									<td style='padding-left:0;'>Latitude: </td>
									<td><input type=\"text\" value=\"$latitude\" maxlength=\"50\" name=\"latitude\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Longitude: </td>
									<td><input type=\"text\" value=\"$longitude\" maxlength=\"50\" name=\"longitude\"  /></td>
								</tr>

							</table>

							<br />

							<table bgcolor=\"#BFBFFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>
									<td colspan=\"5\" class='pad-10'><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\"><img src=\"../images/cab.png\" style=\"height:35px;vertical-align:middle;\" /> </h4>
									
										<div style=\"display:inline-block;\">
											Vehicle Type: 
											<select name=\"vehicletype\">";
												
												foreach ($vehicleTypesList as $vType) {
													$isSelected = ($vehicletype == $vType) ? 'selected="selected"' : '';
													echo '<option value="'.$vType.'" '.$isSelected.'>'.$vType.'</option>';
												}
											echo "</select>
										</div>
                                        
										<div style=\"display:inline-block;\">
											Vehicle Make
											" . get_vehicle_make_listing($db, $vehiclemakeid) . "
										</div>
										<div style=\"display:inline-block;\">
											Vehicle Model: 
											<input type=\"text\" value=\"$vehiclemakemodel\" maxlength=\"50\" name=\"vehiclemakemodel\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle Year: 
											<input type=\"text\" value=\"$vehicleyear\" maxlength=\"10\" name=\"vehicleyear\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle Registration No: 
											<input type=\"text\" value=\"$vehicleregistrationno\" maxlength=\"50\" name=\"vehicleregistrationno\" />
										</div>

										<div style=\"display:inline-block;\">
											VIN Number: 
											<input type=\"text\" value=\"$vehiclevin\" maxlength=\"50\" name=\"vehiclevin\" />
										</div>

									</td>

								</tr>


							</table>



							<br />



							<table bgcolor=\"#ABABFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>

									<td colspan=\"6\" class='pad-10'><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\"><img src=\"../images/folder.png\" style=\"height:35px;vertical-align:middle;\" /> </h4>
										
										<select name=\"administrator\" onChange=\"ReloadThisPagePB(0);\">";

										$qrygetadministrators = "select * from administrators order by `name`";

										$qrygetadministratorsresults = mysql_query($qrygetadministrators, $db);

										while ($administratorrow = mysql_fetch_array($qrygetadministratorsresults))
										{

											$adminid = $administratorrow["id"];

											$adminname = $administratorrow["name"];

											if ($administratorid == $adminid)
											{

												echo "<option value=\"$adminid\" selected>$adminname</option>";

											}

											else
											{

												echo "<option value=\"$adminid\">$adminname</option>";

											}

										}

										$qrygetadministratorinfo = "select * from administrators where `id` = $administratorid";

										$qrygetadministratorinforesults = mysql_query($qrygetadministratorinfo, $db);

										$administratorinforow = mysql_fetch_array($qrygetadministratorinforesults);

										$admintelno = stripslashes($administratorinforow["telno"]);

										$adminfaxno = stripslashes($administratorinforow["faxno"]);

										$adminadr1 = stripslashes($administratorinforow["adr1"]);

										$adminadr2 = stripslashes($administratorinforow["adr2"]);

										$adminadr3 = stripslashes($administratorinforow["adr3"]);

										$adminadr4 = stripslashes($administratorinforow["adr4"]);

										$vatno = stripslashes($administratorinforow["vatno"]);

										echo "					</select>


										Insurance Company:

										<select name=\"insurerid\"><option value=\"0\">Select one</option>";

										$qryinsurers = "select * from `insurers` order by `name`";

										$qryinsurersresults = mysql_query($qryinsurers, $db);

										while ($insrow = mysql_fetch_array($qryinsurersresults))
										{

											$insid = $insrow["id"];

											$insurancecompname = stripslashes($insrow["name"]);

											if ($insid == $insurerid)
											{

												echo "<option value=\"$insid\" selected>$insurancecompname</option>";

											}

											else
											{

												echo "<option value=\"$insid\">$insurancecompname</option>";

											}

										}

										echo " </select>


										Broker: 
										<select name=\"brokerid\"><option value=\"0\">Select one</option>
										";
											
											$qrybrokers = "select * from `brokers` order by `name`";

										$qrybrokersresults = mysql_query($qrybrokers, $db);

										while ($brokerrow = mysql_fetch_array($qrybrokersresults))
										{

											$brokerName = stripslashes($brokerrow["name"]);

											if ($brokerrow["id"] == $brokerid)
											{

												echo '<option value="'. $brokerrow["id"] .'" selected>' . $brokerName. ' </option>';

											}

											else
											{

												echo '<option value="'. $brokerrow["id"] .'" >'. $brokerName. '</option>';

											}

										}

										echo " </select>
									
									Claim Technician: 

										<select name=\"claimsclerk\" id=\"claimsclerk\">";

										$qryclaimsclerks = "select * from claimsclerks order by `name`";

										$qryclaimsclerksresults = mysql_query($qryclaimsclerks, $db);
										
										$defaultEmail = '';
										$counter = 0;
										while ($ccrow = mysql_fetch_array($qryclaimsclerksresults))
										{

											$ccid = $ccrow["id"];

											$ccname = stripslashes($ccrow["name"]);
											$ccemailid = stripslashes($ccrow["email"]);

											if ($counter==0) {
												$defaultEmail = $ccemailid;
											}

											if ($claimsclerkid == $ccid)
											{
												$defaultEmail = $ccemailid;

												echo "<option value=\"$ccid\" selected email=\"$ccemailid\" >$ccname</option>";

											}

											else
											{

												echo "<option value=\"$ccid\" email=\"$ccemailid\">$ccname</option>";

											}

											$counter++;

										}

										echo " </select>

										<a href=\"mailto:$defaultEmail?subject=$emailSubject\"  type=\"Claim\" claimId=\"$claimid\" class=\"send-email\" emailpart=\"subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" id=\"claimTechnicianEmailLink\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a> 
										";
				
										global $admin;
										if ( $admin == 1 ) { echo " VAT&nbsp;Number: $vatno "; }

										echo "
									</td>

								</tr>

								
								

								<tr>
									<td colspan=\"5\" class='pad-10'>
										Tel: $admintelno, 
										Fax: $adminfaxno,
										P.O.Box: $adminadr1, $adminadr2, $adminadr3 ";
										
									if ( !empty($adminadr4) ) { echo $adminadr4; }
								echo "
									</td>
								</tr>
							</table>

							<br />

							<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>
								<tr>
									<td colspan=\"5\" class='pad-10'><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\"><img src=\"../images/record.png\" style=\"height:35px;vertical-align:middle;\" /> </h4>										
										<div style=\"display:inline-block;\">
											Quote&nbsp;Number: <input type=\"text\" value=\"$quoteno\" maxlength=\"50\" name=\"quoteno\" />
										</div>
										<div style=\"display:inline-block;\">
											Quoted&nbsp;Amount: <input type=\"text\" value=\"$quoteamount\" maxlength=\"11\" name=\"quoteamount\" />
										</div>
										<div style=\"display:inline-block;\">
											Authorised&nbsp;Amount: <input type=\"text\" value=\"$authamount\" maxlength=\"11\" name=\"authamount\" />
										</div>
										<div style=\"display:inline-block;\">
											Excess: <input type=\"number\" value=\"$excess\" maxlength=\"11\" name=\"excess\" />
										</div>
										<div style=\"display:inline-block;\">
											Excess Description: <input type=\"text\" value=\"$excess_description\" style='width:300px;' name=\"excess_description\" />
										</div>
										<div style=\"display:inline-block;\">
											Betterment: <input type=\"text\" value=\"$betterment\" maxlength=\"11\" name=\"betterment\" />
										</div>
										
								";
								
								$res = mysql_query("SELECT `received` FROM `dates` WHERE claimid='$claimid' ", $db);

							    $daterow = mysql_fetch_array($res);

								//$receivedDate = date('d/m/Y', strtotime($daterow['received']));

								$received = explode('-', $daterow['received']);

								echo "
										<div style=\"display:inline-block;\">
											Date Received:

											<input type=\"text\" style=\"width:25px;\" value=\"" . $received[2] . "\" name=\"receivedday\" readonly> -	<input type=\"text\" style=\"width:25px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> -  <input type=\"text\" style=\"width:40px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly> 
											<a href=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\"  title=\"cal1.showCalendar('anchor1'); return false;\" name=\"anchor1\" id=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></a>

											<script type='text/javascript'>	
												var cal1 = new CalendarPopup();
												cal1.setReturnFunction(\"setMultipleValues1\");

												function setMultipleValues1(y,m,d) {
													document.theform.receivedyear.value=y;
													document.theform.receivedmonth.value=LZ(m);
													document.theform.receivedday.value=LZ(d);
												}
											</script>

										</div>
									</td>
								</tr>
							</table>

							<br />



							<table bgcolor=\"#8383FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">



								<tr>



									<td colspan=\"5\" class='pad-10'><h4 style=\"margin-top:0px;display:inline-block;margin-bottom:0;\"> <img src=\"../images/pen.png\"
										style=\"height:35px;vertical-align:middle;\" /></h4>
										
										Select&nbsp;Assessor&nbsp;Area:

										<select name=\"area\" onChange=\"ReloadThisPagePB(1);\">
										<option value=\"0\">Select Assessor Area</option>";

										$qryareas = "select * from areas order by areaname";

										$qryareasresults = mysql_query($qryareas, $db);

										while ($arearow = mysql_fetch_array($qryareasresults))
										{

											$areaid = $arearow["id"];

											$areaname = $arearow["areaname"];

											$isSelected = ($areaid==$area) ? 'selected="selected"' : '';

											echo "<option value=\"$areaid\" $isSelected >$areaname</option>";

										}

									echo "</select>";


									echo "  Select&nbsp;Assessor:


									<select name=\"assessor\" onChange=\"ReloadThisPagePB(0);\"><option value=\"0\">Select Assessor</option>";

										$qryassessors = "select * from assessors order by `company`";

										$qryassessorsresults = mysql_query($qryassessors, $db);

										while ($assrow = mysql_fetch_array($qryassessorsresults))
										{

											$assid = $assrow["id"];

											$assname = $assrow["name"];
											$asscompanyoption = $assrow["company"] . ' (' . $assname . ')';

											//check to see if this assessor is in the selected Area

											if ($area != 0)
											{

												$qrycheckarea = "select count(assessorid) as counted from assessor_area where assessorid = $assid and areaid = $area";

												$qrycheckarearesults = mysql_query($qrycheckarea, $db);

												$checkarearow = mysql_fetch_array($qrycheckarearesults);

												$count = $checkarearow["counted"];

												if ($count == 1)
												{

													if ($assessorid == $assid)
													{

														echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

													}

													else
													{

														echo "<option value=\"$assid\">$asscompanyoption</option>";

													}

												}

											}

											else
											{

												if ($assessorid == $assid)
												{

													echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

												}

												else
												{

													echo "<option value=\"$assid\">$asscompanyoption</option>";

												}

											}

										}

										$qrygetassessorinfo = "select * from assessors where `id` = $assessorid";

										$qrygetassessorinforesults = mysql_query($qrygetassessorinfo, $db);

										$assessorrow = mysql_fetch_array($qrygetassessorinforesults);

										$assname = stripslashes($assessorrow["name"]);

										$asscompany = stripslashes($assessorrow["company"]);

										$asstelno = stripslashes($assessorrow["telno"]);

										$assfaxno = stripslashes($assessorrow["faxno"]);

										$asscellno = stripslashes($assessorrow["cellno"]);

										$assemail = stripslashes($assessorrow["email"]);

										$asscomments = stripslashes($assessorrow["comments"]);

									echo " </select>";
									
									echo "</td>									



								</tr>";

								

								if ( !empty($assname) ) {

									$ass_string = ucwords(trim($asscompany)) . ', ' . ucwords(trim($assname)) . ', Tel:' . $asstelno . ', Fax:' . $assfaxno . ', Cel:' . $asscellno . ', Email:' . $assemail;

									echo "<tr>
										<td colspan=\"5\"> $ass_string 
										
										<div style=\"display: inline;\"><a href=\"mailto:$assemail?subject=$emailSubject\" type=\"Assessors\" claimId=\"$claimid\" class=\"send-email\" alt=\"Send Email\" title=\"Send Email\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a></div>

										</td>
									</tr>";
								
								}



								echo "<tr>

									<td>Comments:</td>

									<td colspan='4'>
										<textarea name=\"asscomments\" style='width:660px;height:40px;' >$asscomments</textarea>
									</td>

								</tr>



							</table>	
							
							<br />

							<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>
								<tr>
									<td class=\"pad-10\"> <strong>Parts Suppliers : </strong></td>
								</tr>";

								if ( isset($_POST['area']) && !empty($_POST['area']) ) {

									$partsuppliers = get_partsuppliers_for_vehicle_make($db, $vehiclemakeid, $_POST['area']);

									$allemails = [];

									foreach($partsuppliers as $supplier) {

										$ps_string = ucwords(trim($supplier["name"])) . ', ' . ucwords(trim($supplier["contactname"])) . ', Tel:' . $supplier["telno"] . ', Fax:' . $supplier["faxno"] . ', Cel:' . $supplier["cellno"] . ', Email:' . $supplier["email"];

										$supplier["email2"] = trim($supplier["email2"]);

										$allemails[] = $supplier["email"];

										if (!empty($supplier["email2"])) {
											$ps_string .= ', ' . $supplier["email2"];
											$allemails[] = $supplier["email2"];
										}
										
										echo "<tr><td style='padding:10px;'>";
										echo $ps_string ;

										echo '<div style="display: inline;">
												<input type="checkbox" name="partssupplierfortender" value="'.$supplier["id"].'" /> '.$supplier["contactname"].'
											</div>';

										echo '<div style="display: inline;"><a href="mailto:?bcc='.$supplier["email"].','.$supplier["email2"].'&subject='.$emailSubject.'" type="PartSuppliers" claimId="$claimid" class="send-email" alt="Send Email" title="Send Email" ><img src="../images/email-send.png" style="height:20px;vertical-align:middle;" /></a></div>';

										echo "</td></tr>";

									}
									
									$allemailslist = implode(',', $allemails);

									echo '<tr><td style="padding:10px;">
											<strong>Email all listed suppliers to tender on parts wanted: </strong>
										<div style="display: inline;"><a href="mailto:?bcc='.$allemailslist.'&subject='.$emailSubject.'" type="PartSuppliers" claimId="$claimid" class="send-email" alt="Send Email" title="Send Email" ><img src="../images/email-send.png" style="height:20px;vertical-align:middle;" /></a></div>
									
									</td></tr>';

								}

							echo "
							</table>



						</td>



					</tr>



					</table>



					<br />

					<div class='no-show-in-print'>
						<p style=\"display:inline-block;\">Make the desired changes to the claim and click Next</p>

						<button type=\"button\" id=\"saveAndNextBtn\">Next &gt; &gt; </button>
						<input type=\"hidden\" value=\"1\" name=\"fromstep\" />
						<input type=\"hidden\" name=\"stepto\" value=\"2\" />
						<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
						<input type=\"hidden\" value=\"0\" name=\"next\" />
						&nbsp; &nbsp; <input type=\"submit\" value=\"Save\" />

					</div>

					<script type=\"text/javascript\">

						$(document).ready(function() {
							$('#saveAndNextBtn').on('click', function() {
								$('input[name=\"next\"]').val('1');
								$('form[name=\"theform\"]').submit();
							});
						});
					
					</script>


					</form>";

        echo "<form action=\"loggedinaction.php?action=editclaim\" method=\"POST\" name=\"hiddenform\">



<input type=\"hidden\" name=\"clientname\">



<input type=\"hidden\" name=\"clientno\">



<input type=\"hidden\" name=\"claimno\">



<input type=\"hidden\" name=\"clientcontactno1\">

<input type=\"hidden\" name=\"clientcontactno2\">

<input type=\"hidden\" name=\"clientemail\">


<input type=\"hidden\" name=\"pbid\">



<input type=\"hidden\" name=\"pbname\">



<input type=\"hidden\" name=\"pbowner\">

<input type=\"hidden\" name=\"pbworkshopmanageremail\">
<input type=\"hidden\" name=\"pbcostingclerkemail\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">

<input type=\"hidden\" name=\"pbowneremail\">
<input type=\"hidden\" name=\"pbownercel\">

<input type=\"hidden\" name=\"pbestimator\">
<input type=\"hidden\" name=\"pbestimatorcel\">
<input type=\"hidden\" name=\"pbdms\">
<input type=\"hidden\" name=\"pbmember\">
<input type=\"hidden\" name=\"pbfactoring\">
<input type=\"hidden\" name=\"pbsize\">

<input type=\"hidden\" name=\"assessor\">

<input type=\"hidden\" name=\"pbcostingclerk\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">

<input type=\"hidden\" name=\"pbcontactperson\">



<input type=\"hidden\" name=\"pbcontactnumber\">

<input type=\"hidden\" name=\"pbcontactnumber2\">



<input type=\"hidden\" name=\"pbfaxno\">



<input type=\"hidden\" name=\"pbemail\">



<input type=\"hidden\" name=\"pbadr1\">



<input type=\"hidden\" name=\"pbadr2\">



<input type=\"hidden\" name=\"pbadr3\">



<input type=\"hidden\" name=\"pbadr4\">


<input type=\"hidden\" name=\"notes\">







<input type=\"hidden\" name=\"vehiclemakemodel\">



<input type=\"hidden\" name=\"vehicleyear\">



<input type=\"hidden\" name=\"vehicleregistrationno\">


<input type=\"hidden\" name=\"vehiclemake\">
<input type=\"hidden\" name=\"vehicletype\">

<input type=\"hidden\" name=\"vehiclevin\">







<input type=\"hidden\" name=\"adminid\">







<input type=\"hidden\" name=\"quoteno\">



<input type=\"hidden\" name=\"insurerid\">



<input type=\"hidden\" name=\"claimsclerk\">



<input type=\"hidden\" name=\"authamount\">



<input type=\"hidden\" name=\"excess\">
<input type=\"hidden\" name=\"excess_description\">
<input type=\"hidden\" name=\"brokerid\">



<input type=\"hidden\" name=\"betterment\">



<input type=\"hidden\" name=\"quoteamount\">







<input type=\"hidden\" name=\"assid\">



<input type=\"hidden\" name=\"reload\">



<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



<input type=\"hidden\" name=\"stepto\" value=\"1\" />



<input type=\"hidden\" name=\"dothepb\" />



<input type=\"hidden\" name=\"area\" />


<input type=\"hidden\" name=\"estimator\" />
<input type=\"hidden\" name=\"pbestimatoremail\" />

<input type=\"hidden\" name=\"pbpartsmanager\" />
<input type=\"hidden\" name=\"pbpartsmanagercel\" />
<input type=\"hidden\" name=\"pbpartsmanageremail\" />

<input type=\"hidden\" name=\"latitude\" />
<input type=\"hidden\" name=\"longitude\" />




			</form>";

    }

    if ($step == 2) //parts
    {
        //show the items for this claim:

        //echo "</tr><tr><td>";

        echo "<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"topform\" class='no-show-in-print'>
						<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;

																		 document.topform.submit();\" />

						<input type=\"button\" value=\"Parts\" disabled />
						<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;
																		 document.topform.submit();\" />

						<input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 6;
																		 document.topform.submit();\" />


						<input type=\"button\" value=\"Quote\" onClick=\"document.topform.stepto.value = 7;
																		 document.topform.submit();\" />
																		 
						<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"2\" /> 		 



																		 </form>";

        $qry = "SELECT * FROM items where claimid = $claimid";

        //echo $qry;

        $qrycount = mysql_query($qry, $db);

        $qryitems = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br><form action=\"loggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">

						<p>Client IDH: <strong>$id</strong>; Client NumberH: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>

						There are no Items in the database. Click <input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" /> 

						<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" /> to add one.

						<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />

				  </form>";

        }

        else
        {

            echo "
				
				<p>Client NumberJ: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
				<p>



						<form name=\"theitems\" method=\"post\" action=\"loggedinaction.php?action=savetheitems\">



						  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">



							  <tr>



								  <td><strong>Qty</strong></td>



								  <td><strong>Description</strong></td>



								  <td><strong>Quoted</strong></td>



								  <td><strong>Cost</strong></td>



								  <td><strong>1.25</strong></td>



								  <td><strong>Adjustment</strong></td>



								  <td><strong>User</strong>



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

            while ($row = mysql_fetch_array($qryitems))
            {

                // give a name to the fields

                $itemid = $row["id"];

                $qty = $row["qty"];

                $desc = stripslashes($row["description"]);

                $quoted = $row["quoted"];

                $cost = $row["cost"];

                $onetwofive = $row["onetwofive"];

                $adjustment = $row["adjustment"];

                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";

                $qrygetusernameresults = mysql_query($qrygetusername, $db);

                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen

                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td align=\"center\">$qty</td>



							  <td style=\"width:250px;\">$desc</td>



							  <td align=\"right\">$quoted</td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"cost_" .
                    $itemid . "\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theitems.cost_" . $itemid .
                    ".value * 1.25))



																															   {



																																	document.theitems.onetwofive_" . $itemid .
                    ".value = (Math.round((document.theitems.cost_" . $itemid .
                    ".value * 1.25) * 100) / 100);  



																																	document.theitems.adjustment_" . $itemid .
                    ".value = (Math.round((document.theitems.onetwofive_" . $itemid . ".value - $quoted) * 100) / 100);



																															   }



																															   \"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"onetwofive_" .
                    $itemid . "\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theitems.onetwofive_" .
                    $itemid . ".value - $quoted))



																																	 {



																																		document.theitems.adjustment_" . $itemid .
                    ".value = document.theitems.onetwofive_" . $itemid . ".value - $quoted;



																																	 }



																																		\"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"adjustment_" .
                    $itemid . "\" value=\"$adjustment\"></td>



							  <td>$user</td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=edititem&amp;itemid=$itemid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Item\" border=\"0\" title=\"Edit this Item\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteitem&amp;itemid=$itemid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Item\" border=\"0\" title=\"Delete this Item\"></td>



						  </tr>";

            }//end while loop

            $qrysum = "select sum(adjustment) as totaladjustment, sum(onetwofive) as totalonetwofive, sum(cost) as totalcost, sum(quoted) as totalquoted from items where claimid = $claimid";

            $qrysumresults = mysql_query($qrysum, $db);

            $totalrow = mysql_fetch_array($qrysumresults);

            $total = $totalrow["totaladjustment"];

            $onetwofive = $totalrow["totalonetwofive"];

            $quoted = $totalrow["totalquoted"];

            $cost = $totalrow["totalcost"];

            echo "	<tr>



							<td colspan=\"2\" align=\"right\">TOTALS:</td>										



							<td align=\"right\">$quoted</td>



							<td align=\"right\">$cost</td>



							<td align=\"right\">$onetwofive</td>



							<td align=\"right\">$total</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



							<td colspan=\"2\" align=\"right\">TOTALS INC VAT:</td>										



							<td align=\"right\">" . round($quoted * 1.15, 2) . "</td>



							<td align=\"right\">" . round($cost * 1.15, 2) . "</td>



							<td align=\"right\">" . round($onetwofive * 1.15, 2) . "</td>



							<td align=\"right\">" . round($total * 1.15, 2) . "</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



						  <td colspan=\"6\">&nbsp;<input type=\"submit\" value=\"Save Items\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\"></form></td>



						  <td colspan=\"3\" align=\"center\">



						  



						  <form action=\"loggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">



								<input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" />



								<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" />



								<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



						  </form>					  



						  </td>



					  </tr>



				</table>



				



					</p>";

        }

        echo "<br>



				<form>



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 1;



																	   document.topform.submit();\" >



					<input type=\"button\" value=\"Next >>\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" >



				</form>";

    }

    if ($step == 3)
    {

        echo "<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" class='no-show-in-print'>


					<input type=\"button\" value=\"Claim Details\" onClick=\"document.mainform.stepto.value = 1;



																	 document.mainform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.mainform.stepto.value = 2;



																	 document.mainform.submit();\" />



					<input type=\"button\" value=\"Dates\" disabled />



					<input type=\"button\" value=\"Reports\" onClick=\"document.mainform.stepto.value = 4;



																	 document.mainform.submit();\" />



					<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.mainform.stepto.value = 5;



																	 document.mainform.submit();\" />

                    <input type=\"button\" value=\"Attachments\" onClick=\"document.mainform.stepto.value = 6;



																	 document.mainform.submit();\" />

					<input type=\"button\" value=\"Quote\" onClick=\"document.mainform.stepto.value = 7;



																	 document.mainform.submit();\" />												 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">											 



																	 </form>";

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        //$received = "2007-05-21";

        $received = explode("-", $received);

        $loss = $datesrow["loss"];

        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];

        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];

        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];

        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];

        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $auth = explode("-", $auth);

        $wp = $datesrow["wp"];

        $wp = explode("-", $wp);

        $docreq = $datesrow["docreq"];

        $docreq = explode("-", $docreq);

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $workinprogressinsp = explode("-", $workinprogressinsp);

        $dod = $datesrow["dod"];

        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $finalcosting = explode("-", $finalcosting);

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $acirepsentinsurer = explode("-", $acirepsentinsurer);

        $invoicesent = $datesrow["invoicesent"];

        $invoicesent = explode("-", $invoicesent);

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];

        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];

        $acipaymentreceived = explode("-", $acipaymentreceived);

        echo "



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">

	var cal1 = new CalendarPopup();
	cal1.setReturnFunction(\"setMultipleValues1\");

	function setMultipleValues1(y,m,d) 
	{
		document.mainform.receivedyear.value=y;
		document.mainform.receivedmonth.value=LZ(m);
		document.mainform.receivedday.value=LZ(d);
	}

	var cal2 = new CalendarPopup();
	cal2.setReturnFunction(\"setMultipleValues2\");

	function setMultipleValues2(y,m,d) 
	{
		document.mainform.lossyear.value=y;
		document.mainform.lossmonth.value=LZ(m);
		document.mainform.lossday.value=LZ(d);
	}

	var cal3 = new CalendarPopup();
	cal3.setReturnFunction(\"setMultipleValues3\");

	function setMultipleValues3(y,m,d) 
	{
		document.mainform.assappointedyear.value=y;
		document.mainform.assappointedmonth.value=LZ(m);
		document.mainform.assappointedday.value=LZ(d);
	}

	var cal4 = new CalendarPopup();
	cal4.setReturnFunction(\"setMultipleValues4\");

	function setMultipleValues4(y,m,d) 
	{
		document.mainform.assessmentyear.value=y;
		document.mainform.assessmentmonth.value=LZ(m);
		document.mainform.assessmentday.value=LZ(d);
	}
	
	var cal5 = new CalendarPopup();
	cal5.setReturnFunction(\"setMultipleValues5\");

	function setMultipleValues5(y,m,d) 
	{
		document.mainform.assessmentreportyear.value=y;
		document.mainform.assessmentreportmonth.value=LZ(m);
		document.mainform.assessmentreportday.value=LZ(d);
	}
	
	var cal6 = new CalendarPopup();
	cal6.setReturnFunction(\"setMultipleValues6\");

	function setMultipleValues6(y,m,d) 
	{
		document.mainform.assessmentinvtoinsureryear.value=y;
		document.mainform.assessmentinvtoinsurermonth.value=LZ(m);
		document.mainform.assessmentinvtoinsurerday.value=LZ(d);
	}
	
	var cal7 = new CalendarPopup();
	cal7.setReturnFunction(\"setMultipleValues7\");

	function setMultipleValues7(y,m,d) 
	{
		document.mainform.authyear.value=y;
		document.mainform.authmonth.value=LZ(m);
		document.mainform.authday.value=LZ(d);
	}
	
	var cal8 = new CalendarPopup();
	cal8.setReturnFunction(\"setMultipleValues8\");

	function setMultipleValues8(y,m,d) 
	{
		document.mainform.wpyear.value=y;
		document.mainform.wpmonth.value=LZ(m);
		document.mainform.wpday.value=LZ(d);
	}
	
	var cal9 = new CalendarPopup();
	cal9.setReturnFunction(\"setMultipleValues9\");

	function setMultipleValues9(y,m,d) 
	{
		document.mainform.docreqyear.value=y;
		document.mainform.docreqmonth.value=LZ(m);
		document.mainform.docreqday.value=LZ(d);
	}
	
	var cal10 = new CalendarPopup();
	cal10.setReturnFunction(\"setMultipleValues10\");

	function setMultipleValues10(y,m,d) 
	{
		document.mainform.workinprogressinspyear.value=y;
		document.mainform.workinprogressinspmonth.value=LZ(m);
		document.mainform.workinprogressinspday.value=LZ(d);
	}
	
	var cal11 = new CalendarPopup();
	cal11.setReturnFunction(\"setMultipleValues11\");

	function setMultipleValues11(y,m,d) 
	{
		document.mainform.dodyear.value=y;
		document.mainform.dodmonth.value=LZ(m);
		document.mainform.dodday.value=LZ(d);
	}
	
	var cal12 = new CalendarPopup();
	cal12.setReturnFunction(\"setMultipleValues12\");

	function setMultipleValues12(y,m,d) 
	{
		document.mainform.finalcostingyear.value=y;
		document.mainform.finalcostingmonth.value=LZ(m);
		document.mainform.finalcostingday.value=LZ(d);
	}
	
	var cal13 = new CalendarPopup();
	cal13.setReturnFunction(\"setMultipleValues13\");

	function setMultipleValues13(y,m,d) 
	{
		document.mainform.acirepsentinsureryear.value=y;
		document.mainform.acirepsentinsurermonth.value=LZ(m);
		document.mainform.acirepsentinsurerday.value=LZ(d);
	}

	var cal14 = new CalendarPopup();



	cal14.setReturnFunction(\"setMultipleValues14\");







	function setMultipleValues14(y,m,d) 



	{



		document.mainform.invoicesentyear.value=y;



		document.mainform.invoicesentmonth.value=LZ(m);



		document.mainform.invoicesentday.value=LZ(d);



	}



	



	var cal15 = new CalendarPopup();



	cal15.setReturnFunction(\"setMultipleValues15\");







	function setMultipleValues15(y,m,d) 



	{



		document.mainform.assfeereceivedfrominsureryear.value=y;



		document.mainform.assfeereceivedfrominsurermonth.value=LZ(m);



		document.mainform.assfeereceivedfrominsurerday.value=LZ(d);



	}



	



	var cal16 = new CalendarPopup();



	cal16.setReturnFunction(\"setMultipleValues16\");







	function setMultipleValues16(y,m,d) 



	{



		document.mainform.acipaymentreceivedyear.value=y;



		document.mainform.acipaymentreceivedmonth.value=LZ(m);



		document.mainform.acipaymentreceivedday.value=LZ(d);



	}







</SCRIPT>		  



			  ";

        echo "	<p>File IDK: <strong>$id</strong>; Client NumberK: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
			
			<br /><form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"mainform\">



					<table>



						<tr>



							<td>Date received</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $received[2] .
            "\" name=\"receivedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly>



								 <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" 



								 	TITLE=\"cal1.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" 



								 	ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>



						<tr>



							<td>Date of loss</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $loss[2] . "\" name=\"lossday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $loss[1] . "\" name=\"lossmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $loss[0] . "\" name=\"lossyear\" readonly>



								<A HREF=\"#\" onClick=\"cal2.showCalendar('anchor2'); return false;\" 



								 	TITLE=\"cal2.showCalendar('anchor2'); return false;\" NAME=\"anchor2\" 



								 	ID=\"anchor2\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>



						<tr>



							<td>Assessor appointed</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assappointed[2] .
            "\" name=\"assappointedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assappointed[1] .
            "\" name=\"assappointedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assappointed[0] .
            "\" name=\"assappointedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal3.showCalendar('anchor3'); return false;\" 



								 	TITLE=\"cal3.showCalendar('anchor3'); return false;\" NAME=\"anchor3\" 



								 	ID=\"anchor3\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td> 



						</tr>



						<tr>



							<td>Date of assessment</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessment[2] .
            "\" name=\"assessmentday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessment[1] .
            "\" name=\"assessmentmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessment[0] .
            "\" name=\"assessmentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal4.showCalendar('anchor4'); return false;\" 



								 	TITLE=\"cal4.showCalendar('anchor4'); return false;\" NAME=\"anchor4\" 



								 	ID=\"anchor4\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Assessment report date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentreport[2] .
            "\" name=\"assessmentreportday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentreport[1] .
            "\" name=\"assessmentreportmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessmentreport[0] .
            "\" name=\"assessmentreportyear\" readonly>



								<A HREF=\"#\" onClick=\"cal5.showCalendar('anchor5'); return false;\" 



								 	TITLE=\"cal5.showCalendar('anchor5'); return false;\" NAME=\"anchor5\" 



								 	ID=\"anchor5\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Assessment invoice sent to insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentinvtoinsurer[2] .
            "\" name=\"assessmentinvtoinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentinvtoinsurer[1] .
            "\" name=\"assessmentinvtoinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessmentinvtoinsurer[0] .
            "\" name=\"assessmentinvtoinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal6.showCalendar('anchor6'); return false;\" 



								 	TITLE=\"cal6.showCalendar('anchor6'); return false;\" NAME=\"anchor6\" 



								 	ID=\"anchor6\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Authorise date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $auth[2] . "\" name=\"authday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $auth[1] . "\" name=\"authmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $auth[0] . "\" name=\"authyear\" readonly>



								<A HREF=\"#\" onClick=\"cal7.showCalendar('anchor7'); return false;\" 



								 	TITLE=\"cal7.showCalendar('anchor7'); return false;\" NAME=\"anchor7\" 



								 	ID=\"anchor7\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Withhold payment date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $wp[2] . "\" name=\"wpday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $wp[1] . "\" name=\"wpmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $wp[0] . "\" name=\"wpyear\" readonly>



								<A HREF=\"#\" onClick=\"cal8.showCalendar('anchor8'); return false;\" 



								 	TITLE=\"cal8.showCalendar('anchor8'); return false;\" NAME=\"anchor8\" 



								 	ID=\"anchor8\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Document Request</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $docreq[2] . "\" name=\"docreqday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $docreq[1] . "\" name=\"docreqmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $docreq[0] . "\" name=\"docreqyear\" readonly>



								<A HREF=\"#\" onClick=\"cal9.showCalendar('anchor9'); return false;\" 



								 	TITLE=\"cal9.showCalendar('anchor9'); return false;\" NAME=\"anchor9\" 



								 	ID=\"anchor9\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Work in progress inspection date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $workinprogressinsp[2] .
            "\" name=\"workinprogressinspday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $workinprogressinsp[1] .
            "\" name=\"workinprogressinspmonth\" readonly> -



								<input type=\"text\" style=\"width:60px;\" value=\"" . $workinprogressinsp[0] .
            "\" name=\"workinprogressinspyear\" readonly>



								<A HREF=\"#\" onClick=\"cal10.showCalendar('anchor10'); return false;\" 



								 	TITLE=\"cal10.showCalendar('anchor10'); return false;\" NAME=\"anchor10\" 



								 	ID=\"anchor10\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Expected date of delivery</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $dod[2] . "\" name=\"dodday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $dod[1] . "\" name=\"dodmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $dod[0] . "\" name=\"dodyear\" readonly>



								<A HREF=\"#\" onClick=\"cal11.showCalendar('anchor11'); return false;\" 



								 	TITLE=\"cal11.showCalendar('anchor11'); return false;\" NAME=\"anchor11\" 



								 	ID=\"anchor11\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Final costing</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $finalcosting[2] .
            "\" name=\"finalcostingday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $finalcosting[1] .
            "\" name=\"finalcostingmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $finalcosting[0] .
            "\" name=\"finalcostingyear\" readonly>



								<A HREF=\"#\" onClick=\"cal12.showCalendar('anchor12'); return false;\" 



								 	TITLE=\"cal12.showCalendar('anchor12'); return false;\" NAME=\"anchor12\" 



								 	ID=\"anchor12\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>ACI report sent to insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $acirepsentinsurer[2] .
            "\" name=\"acirepsentinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $acirepsentinsurer[1] .
            "\" name=\"acirepsentinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $acirepsentinsurer[0] .
            "\" name=\"acirepsentinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal13.showCalendar('anchor13'); return false;\" 



								 	TITLE=\"cal13.showCalendar('anchor13'); return false;\" NAME=\"anchor13\" 



								 	ID=\"anchor13\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Date invoice sent</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $invoicesent[2] .
            "\" name=\"invoicesentday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $invoicesent[1] .
            "\" name=\"invoicesentmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $invoicesent[0] .
            "\" name=\"invoicesentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal14.showCalendar('anchor14'); return false;\" 



								 	TITLE=\"cal14.showCalendar('anchor14'); return false;\" NAME=\"anchor14\" 



								 	ID=\"anchor14\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Assessment fee received from insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assfeereceivedfrominsurer[2] .
            "\" name=\"assfeereceivedfrominsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assfeereceivedfrominsurer[1] .
            "\" name=\"assfeereceivedfrominsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assfeereceivedfrominsurer[0] .
            "\" name=\"assfeereceivedfrominsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal15.showCalendar('anchor15'); return false;\" 



								 	TITLE=\"cal15.showCalendar('anchor15'); return false;\" NAME=\"anchor15\" 



								 	ID=\"anchor15\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>ACI payment received from insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $acipaymentreceived[2] .
            "\" name=\"acipaymentreceivedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $acipaymentreceived[1] .
            "\" name=\"acipaymentreceivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $acipaymentreceived[0] .
            "\" name=\"acipaymentreceivedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal16.showCalendar('anchor16'); return false;\" 



								 	TITLE=\"cal16.showCalendar('anchor16'); return false;\" NAME=\"anchor16\" 



								 	ID=\"anchor16\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



					</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.mainform.stepto.value = 2;



																	   document.mainform.submit();\" />


					<input type=\"button\" value=\"Next >>\" onClick=\"document.mainform.stepto.value = 4;



																	   document.mainform.submit();\" /> 



					<input type=\"reset\" value=\"Reset\" /> <input type=\"hidden\" value=\"3\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }

    if ($step == 4) //Reports
    {
        echo "<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"topform\" class='no-show-in-print'>



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																	 document.topform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Reports\" disabled  />



					<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;



																	 document.topform.submit();\" />

																	 

		            <input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 6;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Quote\" onClick=\"document.topform.stepto.value = 7;



																	 document.topform.submit();\" />												



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">										<input type=\"hidden\" name=\"fromstep\" value=\"4\">		 



																	 </form>";

        echo "	<p>FileIDL: <strong>$id</strong>; Client NumberL: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p> 
				<p>Select which report you want to view:<br><br>



								 <a href=\"reports.php?action=assessmentinstruction&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Instruction</a>


								 || <a href=\"reports.php?action=assessmentreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Report</a>


								|| <a href=\"reports.php?action=pbinvoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessor Invoice</a>


								|| <a href=\"reports.php?action=authorization&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Authorization for Repairs</a>


								|| <a href=\"reports.php?action=pbpartsrequest&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Parts Request</a>


								|| <a href=\"reports.php?action=pbdocrequest&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Document Request</a>


								|| <a href=\"reports.php?action=auditreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Audit Report</a> 




								|| <a href=\"reports.php?action=pbfax&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Fax</a>  
								
								
																							
								 || <a href=\"reports.php?action=invoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Invoice</a>

									||

								</p>";



								 



								 
        $qry = "SELECT * FROM report where claimid = $claimid";

        $qrycount = mysql_query($qry, $db);

        $qryreports = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br /><p>There are no Reports in the database. Click <a href=\"loggedinaction.php?action=newreport&amp;claimid=$claimid\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Report for this Claim\" title=\"Add new Report for this Claim\"></a> to add one.</p>";

        }

        else
        {

            echo "<br />



					  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\" class=\"table table-striped\">

						  <tr>

								  <td><strong>Report Date and Time</strong></td>



								  <td><strong>Description</strong></td>



								  <td><strong>User</strong></td>



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

            while ($row = @mysql_fetch_array($qryreports))
            {

                // give a name to the fields

                $reportid = $row['id'];

                $reportdate = $row['reportdate'];

                $year = substr($reportdate, 0, 4);
                $month = substr($reportdate, 5, 2);
                $day = substr($reportdate, 8, 2);

                $hour = substr($reportdate, 11, 2);
                $minute = substr($reportdate, 14, 2);

                $ourtime = mktime($hour, $minute, 0, $month, $day, $year);

                $reportdate = date("j M Y H:i", $ourtime);

                $reportdesc = $row['description'];

                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";

                $qrygetusernameresults = mysql_query($qrygetusername, $db);

                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen

                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td>$reportdate</td>



							  <td>$reportdesc</td>



							  <td>$user</td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=editreport&amp;reportid=$reportid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Report\" border=\"0\" title=\"Edit this Report\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletereport&amp;reportid=$reportid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Report\" border=\"0\" title=\"Delete this Report\"></td>



						  </tr>";

            }//end while loop

            echo "<tr>



						  <td colspan=\"3\">&nbsp;</td>



						  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newreport&amp;claimid=$claimid\"><img src=\"../images/admin/add.gif\" alt=\"Add new Report for this Claim\" border=\"0\" title=\"Add new Report for this Claim\"></a></td>



					  </tr>



				</table>



					";

        }

        echo "<br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" /> 



					<input type=\"button\" value=\"Next >>\" onClick=\"document.topform.stepto.value = 5;



																	   document.topform.submit();\" /> 



					 <input type=\"hidden\" value=\"4\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }

    if ($step == 5)
    {

        echo "<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" class='no-show-in-print'>



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.datesform.stepto.value = 1;



																	 document.datesform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.datesform.stepto.value = 2;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.datesform.stepto.value = 3;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Reports\" onClick=\"document.datesform.stepto.value = 4;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Outstanding Reports\" disabled />

					

					<input type=\"button\" value=\"Attachments\" onClick=\"document.datesform.stepto.value = 6;



																	 document.datesform.submit();\" />


					<input type=\"button\" value=\"Quote\" onClick=\"document.datesform.stepto.value = 7;



																	 document.datesform.submit();\" />

																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">											 



																	 </form>";

        echo "<p>File IDM: <strong>$id</strong>; Client NumberM: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
			
			<h3>The following actions is outstanding:</h3>";

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        //$received = "2007-05-21";

        $received = explode("-", $received);

        $loss = $datesrow["loss"];

        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];

        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];

        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];

        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];

        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $auth = explode("-", $auth);

        $wp = $datesrow["wp"];

        $wp = explode("-", $wp);

        $docreq = $datesrow["docreq"];

        $docreq = explode("-", $docreq);

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $workinprogressinsp = explode("-", $workinprogressinsp);

        $dod = $datesrow["dod"];

        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $finalcosting = explode("-", $finalcosting);

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $acirepsentinsurer = explode("-", $acirepsentinsurer);

        $invoicesent = $datesrow["invoicesent"];

        $invoicesent = explode("-", $invoicesent);

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];

        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];

        $acipaymentreceived = explode("-", $acipaymentreceived);

        echo "



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">







	var cal1 = new CalendarPopup();



	cal1.setReturnFunction(\"setMultipleValues1\");







	function setMultipleValues1(y,m,d) 



	{



		document.datesform.receivedyear.value=y;



		document.datesform.receivedmonth.value=LZ(m);



		document.datesform.receivedday.value=LZ(d);



	}



	



	var cal2 = new CalendarPopup();



	cal2.setReturnFunction(\"setMultipleValues2\");







	function setMultipleValues2(y,m,d) 



	{



		document.datesform.lossyear.value=y;



		document.datesform.lossmonth.value=LZ(m);



		document.datesform.lossday.value=LZ(d);



	}



	



	var cal3 = new CalendarPopup();



	cal3.setReturnFunction(\"setMultipleValues3\");







	function setMultipleValues3(y,m,d) 



	{



		document.datesform.assappointedyear.value=y;



		document.datesform.assappointedmonth.value=LZ(m);



		document.datesform.assappointedday.value=LZ(d);



	}



	



	var cal4 = new CalendarPopup();



	cal4.setReturnFunction(\"setMultipleValues4\");







	function setMultipleValues4(y,m,d) 



	{



		document.datesform.assessmentyear.value=y;



		document.datesform.assessmentmonth.value=LZ(m);



		document.datesform.assessmentday.value=LZ(d);



	}



	



	var cal5 = new CalendarPopup();



	cal5.setReturnFunction(\"setMultipleValues5\");







	function setMultipleValues5(y,m,d) 



	{



		document.datesform.assessmentreportyear.value=y;



		document.datesform.assessmentreportmonth.value=LZ(m);



		document.datesform.assessmentreportday.value=LZ(d);



	}



	



	var cal6 = new CalendarPopup();



	cal6.setReturnFunction(\"setMultipleValues6\");







	function setMultipleValues6(y,m,d) 



	{



		document.datesform.assessmentinvtoinsureryear.value=y;



		document.datesform.assessmentinvtoinsurermonth.value=LZ(m);



		document.datesform.assessmentinvtoinsurerday.value=LZ(d);



	}



	



	var cal7 = new CalendarPopup();



	cal7.setReturnFunction(\"setMultipleValues7\");







	function setMultipleValues7(y,m,d) 



	{



		document.datesform.authyear.value=y;



		document.datesform.authmonth.value=LZ(m);



		document.datesform.authday.value=LZ(d);



	}



	



	var cal8 = new CalendarPopup();



	cal8.setReturnFunction(\"setMultipleValues8\");







	function setMultipleValues8(y,m,d) 



	{



		document.datesform.wpyear.value=y;



		document.datesform.wpmonth.value=LZ(m);



		document.datesform.wpday.value=LZ(d);



	}



	



	var cal9 = new CalendarPopup();



	cal9.setReturnFunction(\"setMultipleValues9\");







	function setMultipleValues9(y,m,d) 



	{



		document.datesform.docreqyear.value=y;



		document.datesform.docreqmonth.value=LZ(m);



		document.datesform.docreqday.value=LZ(d);



	}



	



	var cal10 = new CalendarPopup();



	cal10.setReturnFunction(\"setMultipleValues10\");







	function setMultipleValues10(y,m,d) 



	{



		document.datesform.workinprogressinspyear.value=y;



		document.datesform.workinprogressinspmonth.value=LZ(m);



		document.datesform.workinprogressinspday.value=LZ(d);



	}



	



	var cal11 = new CalendarPopup();



	cal11.setReturnFunction(\"setMultipleValues11\");







	function setMultipleValues11(y,m,d) 



	{



		document.datesform.dodyear.value=y;



		document.datesform.dodmonth.value=LZ(m);



		document.datesform.dodday.value=LZ(d);



	}



	



	var cal12 = new CalendarPopup();



	cal12.setReturnFunction(\"setMultipleValues12\");







	function setMultipleValues12(y,m,d) 



	{



		document.datesform.finalcostingyear.value=y;



		document.datesform.finalcostingmonth.value=LZ(m);



		document.datesform.finalcostingday.value=LZ(d);



	}



	



	var cal13 = new CalendarPopup();



	cal13.setReturnFunction(\"setMultipleValues13\");







	function setMultipleValues13(y,m,d) 



	{



		document.datesform.acirepsentinsureryear.value=y;



		document.datesform.acirepsentinsurermonth.value=LZ(m);



		document.datesform.acirepsentinsurerday.value=LZ(d);



	}



	



	var cal14 = new CalendarPopup();



	cal14.setReturnFunction(\"setMultipleValues14\");







	function setMultipleValues14(y,m,d) 



	{



		document.datesform.invoicesentyear.value=y;



		document.datesform.invoicesentmonth.value=LZ(m);



		document.datesform.invoicesentday.value=LZ(d);



	}



	



	var cal15 = new CalendarPopup();



	cal15.setReturnFunction(\"setMultipleValues15\");







	function setMultipleValues15(y,m,d) 



	{



		document.datesform.assfeereceivedfrominsureryear.value=y;



		document.datesform.assfeereceivedfrominsurermonth.value=LZ(m);



		document.datesform.assfeereceivedfrominsurerday.value=LZ(d);



	}



	



	var cal16 = new CalendarPopup();



	cal16.setReturnFunction(\"setMultipleValues16\");







	function setMultipleValues16(y,m,d) 



	{



		document.datesform.acipaymentreceivedyear.value=y;



		document.datesform.acipaymentreceivedmonth.value=LZ(m);



		document.datesform.acipaymentreceivedday.value=LZ(d);



	}







</SCRIPT>		  



			  ";

        echo "<br /><form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"datesform\">



					<table>";

        $icount = 0;

		$calanderFields = array();

        if ($received[0] == "0000")
        {
			$calanderFields[] = array('receivedday', 'receivedmonth', 'receivedyear');

            echo "	<tr>



							<td>Date received</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $received[2] .
                "\" name=\"receivedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly>



								 <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" 



								 	TITLE=\"cal1.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" 



								 	ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>";

            $icount++;

        }

        if ($loss[0] == "0000")
        {
			$calanderFields[] = array('lossday', 'lossmonth', 'lossyear');

            echo "	<tr>



							<td>Date of loss</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $loss[2] . "\" name=\"lossday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $loss[1] . "\" name=\"lossmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $loss[0] . "\" name=\"lossyear\" readonly>



								<A HREF=\"#\" onClick=\"cal2.showCalendar('anchor2'); return false;\" 



								 	TITLE=\"cal2.showCalendar('anchor2'); return false;\" NAME=\"anchor2\" 



								 	ID=\"anchor2\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>";

            $icount++;

        }

        if ($assappointed[0] == "0000")
        {
			$calanderFields[] = array('assappointedday', 'assappointedmonth', 'assappointedyear');

            echo "	<tr>



							<td>Assessor appointed</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assappointed[2] .
                "\" name=\"assappointedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assappointed[1] .
                "\" name=\"assappointedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assappointed[0] .
                "\" name=\"assappointedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal3.showCalendar('anchor3'); return false;\" 



								 	TITLE=\"cal3.showCalendar('anchor3'); return false;\" NAME=\"anchor3\" 



								 	ID=\"anchor3\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td> 



						</tr>";

            $icount++;

        }

        if ($assessment[0] == "0000")
        {
			$calanderFields[] = array('assessmentday', 'assessmentmonth', 'assessmentyear');

            echo "	<tr>



							<td>Date of assessment</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessment[2] .
                "\" name=\"assessmentday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessment[1] .
                "\" name=\"assessmentmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessment[0] .
                "\" name=\"assessmentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal4.showCalendar('anchor4'); return false;\" 



								 	TITLE=\"cal4.showCalendar('anchor4'); return false;\" NAME=\"anchor4\" 



								 	ID=\"anchor4\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($assessmentreport[0] == "0000")
        {
			$calanderFields[] = array('assessmentreportday', 'assessmentreportmonth', 'assessmentreportyear');

            echo "	<tr>



							<td>Assessment report date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentreport[2] .
                "\" name=\"assessmentreportday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentreport[1] .
                "\" name=\"assessmentreportmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessmentreport[0] .
                "\" name=\"assessmentreportyear\" readonly>



								<A HREF=\"#\" onClick=\"cal5.showCalendar('anchor5'); return false;\" 



								 	TITLE=\"cal5.showCalendar('anchor5'); return false;\" NAME=\"anchor5\" 



								 	ID=\"anchor5\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($assessmentinvtoinsurer == "0000")
        {

            echo "	<tr>



							<td>Assessment invoice sent to insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentinvtoinsurer[2] .
                "\" name=\"assessmentinvtoinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assessmentinvtoinsurer[1] .
                "\" name=\"assessmentinvtoinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assessmentinvtoinsurer[0] .
                "\" name=\"assessmentinvtoinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal6.showCalendar('anchor6'); return false;\" 



								 	TITLE=\"cal6.showCalendar('anchor6'); return false;\" NAME=\"anchor6\" 



								 	ID=\"anchor6\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($auth[0] == "0000")
        {
			$calanderFields[] = array('authday', 'authmonth', 'authyear');

            echo "	<tr>



							<td>Authorise date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $auth[2] . "\" name=\"authday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $auth[1] . "\" name=\"authmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $auth[0] . "\" name=\"authyear\" readonly>



								<A HREF=\"#\" onClick=\"cal7.showCalendar('anchor7'); return false;\" 



								 	TITLE=\"cal7.showCalendar('anchor7'); return false;\" NAME=\"anchor7\" 



								 	ID=\"anchor7\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($wp[0] == "0000")
        {
			$calanderFields[] = array('wpday', 'wpmonth', 'wpyear');

            echo "	<tr>



							<td>Withhold payment date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $wp[2] . "\" name=\"wpday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $wp[1] . "\" name=\"wpmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $wp[0] . "\" name=\"wpyear\" readonly>



								<A HREF=\"#\" onClick=\"cal8.showCalendar('anchor8'); return false;\" 



								 	TITLE=\"cal8.showCalendar('anchor8'); return false;\" NAME=\"anchor8\" 



								 	ID=\"anchor8\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($docreq[0] == "0000")
        {
			$calanderFields[] = array('docreqday', 'docreqmonth', 'docreqyear');

            echo " 	<tr>



							<td>Document Request</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $docreq[2] . "\" name=\"docreqday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $docreq[1] . "\" name=\"docreqmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $docreq[0] . "\" name=\"docreqyear\" readonly>



								<A HREF=\"#\" onClick=\"cal9.showCalendar('anchor9'); return false;\" 



								 	TITLE=\"cal9.showCalendar('anchor9'); return false;\" NAME=\"anchor9\" 



								 	ID=\"anchor9\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($workinprogressinsp[0] == "0000")
        {
			$calanderFields[] = array('workinprogressinspday', 'workinprogressinspmonth', 'workinprogressinspyear');

            echo "	<tr>



							<td>Work in progress inspection date</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $workinprogressinsp[2] .
                "\" name=\"workinprogressinspday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $workinprogressinsp[1] .
                "\" name=\"workinprogressinspmonth\" readonly> -



								<input type=\"text\" style=\"width:60px;\" value=\"" . $workinprogressinsp[0] .
                "\" name=\"workinprogressinspyear\" readonly>



								<A HREF=\"#\" onClick=\"cal10.showCalendar('anchor10'); return false;\" 



								 	TITLE=\"cal10.showCalendar('anchor10'); return false;\" NAME=\"anchor10\" 



								 	ID=\"anchor10\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($dod[0] == "0000")
        {
			$calanderFields[] = array('dodday', 'dodmonth', 'dodyear');

            echo "	<tr>



							<td>Expected date of delivery</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $dod[2] . "\" name=\"dodday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $dod[1] . "\" name=\"dodmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $dod[0] . "\" name=\"dodyear\" readonly>



								<A HREF=\"#\" onClick=\"cal11.showCalendar('anchor11'); return false;\" 



								 	TITLE=\"cal11.showCalendar('anchor11'); return false;\" NAME=\"anchor11\" 



								 	ID=\"anchor11\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($finalcosting[0] == "0000")
        {
			$calanderFields[] = array('finalcostingday', 'finalcostingmonth', 'finalcostingyear');

            echo " 	<tr>



							<td>Final costing</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $finalcosting[2] .
                "\" name=\"finalcostingday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $finalcosting[1] .
                "\" name=\"finalcostingmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $finalcosting[0] .
                "\" name=\"finalcostingyear\" readonly>



								<A HREF=\"#\" onClick=\"cal12.showCalendar('anchor12'); return false;\" 



								 	TITLE=\"cal12.showCalendar('anchor12'); return false;\" NAME=\"anchor12\" 



								 	ID=\"anchor12\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($acirepsentinsurer[0] == "0000")
        {
			$calanderFields[] = array('acirepsentinsurerday', 'acirepsentinsurermonth', 'acirepsentinsureryear');

            echo "	<tr>



							<td>ACI report sent to insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $acirepsentinsurer[2] .
                "\" name=\"acirepsentinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $acirepsentinsurer[1] .
                "\" name=\"acirepsentinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $acirepsentinsurer[0] .
                "\" name=\"acirepsentinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal13.showCalendar('anchor13'); return false;\" 



								 	TITLE=\"cal13.showCalendar('anchor13'); return false;\" NAME=\"anchor13\" 



								 	ID=\"anchor13\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($invoicesent[0] == "0000")
        {
			$calanderFields[] = array('invoicesentday', 'invoicesentmonth', 'invoicesentyear');

            echo "	<tr>



							<td>Date invoice sent</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $invoicesent[2] .
                "\" name=\"invoicesentday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $invoicesent[1] .
                "\" name=\"invoicesentmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $invoicesent[0] .
                "\" name=\"invoicesentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal14.showCalendar('anchor14'); return false;\" 



								 	TITLE=\"cal14.showCalendar('anchor14'); return false;\" NAME=\"anchor14\" 



								 	ID=\"anchor14\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($assfeereceivedfrominsurer[0] == "0000")
        {
			$calanderFields[] = array('assfeereceivedfrominsurerday', 'assfeereceivedfrominsurermonth', 'assfeereceivedfrominsureryear');

            echo "	<tr>



							<td>Assessment fee received from insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $assfeereceivedfrominsurer[2] .
                "\" name=\"assfeereceivedfrominsurerday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $assfeereceivedfrominsurer[1] .
                "\" name=\"assfeereceivedfrominsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $assfeereceivedfrominsurer[0] .
                "\" name=\"assfeereceivedfrominsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal15.showCalendar('anchor15'); return false;\" 



								 	TITLE=\"cal15.showCalendar('anchor15'); return false;\" NAME=\"anchor15\" 



								 	ID=\"anchor15\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($acipaymentreceived[0] == "0000")
        {
			$calanderFields[] = array('acipaymentreceivedday', 'acipaymentreceivedmonth', 'acipaymentreceivedyear');

            echo " 	<tr>



							<td>ACI payment received from insurer</td>



							<td><input type=\"text\" style=\"width:30px;\" value=\"" . $acipaymentreceived[2] .
                "\" name=\"acipaymentreceivedday\" readonly> - 



								<input type=\"text\" style=\"width:30px;\" value=\"" . $acipaymentreceived[1] .
                "\" name=\"acipaymentreceivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:60px;\" value=\"" . $acipaymentreceived[0] .
                "\" name=\"acipaymentreceivedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal16.showCalendar('anchor16'); return false;\" 



								 	TITLE=\"cal16.showCalendar('anchor16'); return false;\" NAME=\"anchor16\" 



								 	ID=\"anchor16\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($icount == 0)
        {

            echo "<h5>There are now outstanding dates</h5>";

        }

        echo "



					</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.datesform.stepto.value = 4;



																	   document.datesform.submit();\" /> 
					
					<input type=\"button\" id=\"completeAllToday\" value=\"Complete All Today\">



					<input type=\"reset\" value=\"Reset\" /> <input type=\"hidden\" value=\"5\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"4\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }
    
    if ($step == 6)
    {
		echo "<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"topform\" class='no-show-in-print'>
						<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;

																		 document.topform.submit();\" />

						<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;

																		 document.topform.submit();\" /> 
						<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;
																		 document.topform.submit();\" />
																 
						<input type=\"button\" value=\"Attachments\" disabled />

						<input type=\"button\" value=\"OQuote\" onClick=\"document.topform.stepto.value = 7;
																		 document.topform.submit();\" />												 



						<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"2\" /> 		 



																		 </form><p>File IDN: <strong>$id</strong>; Client NumberN: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";
																		 
		$qryclaimfiles = "select f.*, u.username from `files` as f left join users as u on f.userid =u.id where f.`claimid` = $claimid order by f.`datetime`";
		$qryclaimfilesresults = mysql_query($qryclaimfiles, $db);
		
		if (mysql_num_rows($qryclaimfilesresults) == 0)
		{
			echo "<p>There are no files in the database for this claim.</p>
			
				<form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>
					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"user\">
				</form>
			
			";			
		}
		else
		{
			echo "<Br><form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>

					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"user\">
				</form><br>";
			
			echo "<table border=\"1\">
					<tr>
						<td><strong>Date and Time Uploaded:</strong></td>
						<td><strong>File:</strong></td>
						<td><strong>Description:</strong></td>
						<td><strong>File Size:</strong></td>
						<td><strong>User:</strong></td>
						<td><strong>Actions:</strong></td>
					</tr>";
				
			while ($filerow = mysql_fetch_array($qryclaimfilesresults))
			{
				$fileid = $filerow["id"];
				$filename = $filerow["filename"];
				$datetime = date('d/m/Y h:i A', strtotime($filerow["datetime"]));
				$desc = $filerow["description"];
				$fileSize = $filerow["filesize"];
				
				echo "<tr>
						<td>$datetime</td>
						<td><a href=\"claims/$claimid/$fileid-$filename\" target=\"_blank\" class=\"newWindow\">$filename</a></td>
						<td>$desc</td>
						<td>" . humanFileSize($fileSize) . "</td>
						<td>" . $filerow["username"] . "</td>
						<td align=\"center\"><form method=\"post\" action=\"deletefile.php\" name=\"file$fileid\"><input type=\"image\" src=\"../images/admin/delete.gif\" />
						<input type=\"hidden\" name=\"action\" value=\"confirmdeletefile\"><input type=\"hidden\" name=\"fileid\" value=\"$fileid\"></form></td>
					  </tr>";
				
			}
			
			echo "</table>";
		}
	}
	
	//echo '<pre>';print_r($calanderFields);

	echo '
		<script type="text/javascript">
		$(document).ready(function() {
			$(\'#completeAllToday\').click(function() {
		
				var dateObj = new Date();
				var month = dateObj.getUTCMonth() + 1; //months from 1-12
				var day = dateObj.getUTCDate();
				var year = dateObj.getUTCFullYear();
	';
	
	foreach ($calanderFields as $calfield) {
		echo '$(\'input[name="'.$calfield[0].'"]\').val(day);';
		echo '$(\'input[name="'.$calfield[1].'"]\').val(month);';
		echo '$(\'input[name="'.$calfield[2].'"]\').val(year);';

	}
	
	echo 'return false;
			});	
		});
	</script>
	';


}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function NewClaim($pbid)
{

    require ('connection.php');

    $reloaded = $_REQUEST["reload"];

    if ($reloaded == 1)
    {

        $clientname = $_REQUEST["clientname"];

        $clientno = $_REQUEST["clientno"];

        $claimno = $_REQUEST["claimno"];

        $clientcontactno1 = $_REQUEST["clientcontactno1"];

        $clientcontactno2 = $_REQUEST["clientcontactno2"];

		$clientemail		= $_REQUEST["clientemail"];

        $panelbeaterid = $_REQUEST["pbid"];

        $vehiclemakemodel = $_REQUEST["vehiclemakemodel"];

        $vehicleyear = $_REQUEST["vehicleyear"];

        $vehicleregistrationno = $_REQUEST["vehicleregistrationno"];

        $vehicletype = $_REQUEST["vehicletype"];

		$vehiclevin = $_REQUEST["vehiclevin"];

        $administratorid = $_REQUEST["adminid"];

        $quoteno = $_REQUEST["quoteno"];

        $insurerid = $_REQUEST["insurerid"];

        $claimsclerkid = $_REQUEST["claimsclerk"];

        $authamount = $_REQUEST["authamount"];

        $excess = $_REQUEST["excess"];

        $betterment = $_REQUEST["betterment"];

        $quoteamount = $_REQUEST["quoteamount"];

        $assessorid = $_REQUEST["assid"];

        $area = $_REQUEST["area"];

        if ($area == 0)
        {

            $assessorid = 0;

        }

    }

    else
    {

        $clientname = "";

        $clientno = "";

        $claimno = "";

        $qrygetclaimno = "select * from claimnumber";

        $qrygetclaimnoresults = mysql_query($qrygetclaimno, $db);

        $claimnorow = mysql_fetch_array($qrygetclaimnoresults);

        $clientno = $claimnorow["clientno"];

        //echo "sadfsdaf $claimno afsdfsdawerq";

        $clientcontactno1 = "";

        $clientcontactno2 = "";

		$clientemail = "";

        $panelbeaterid = "";

        $vehiclemakemodel = "";
		
		$vehiclevin = "";

        $vehicleyear = "";

        $vehicleregistrationno = "";

        $vehicletype = "";

        $administratorid = "";

        $quoteno = "";

        $insurerid = 0;

		$brokerid = 0;

        $claimsclerkid = "";

        $authamount = "";

        $quoteamount = "";

        $excess = "";

		$excess_description = "";

        $betterment = "";

        $assessorid = "";

        $area = 0;

    }

    echo "<script type=\"text/javascript\">


		function ReloadThisPage()



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;



			



			document.hiddenform.pbid.value = pbid;	



			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;

			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;		
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;



			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;



			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 0;



			document.hiddenform.area.value = document.theform.area.value;



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



		function ReloadThisPagePB(area)



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;


			



			document.hiddenform.pbid.value = pbid;	



			document.hiddenform.pbname.value = document.theform.pbname.value;	



			document.hiddenform.pbowner.value = document.theform.pbowner.value;
			document.hiddenform.pbworkshopmanageremail.value = document.theform.pbworkshopmanageremail.value;
			document.hiddenform.pbcostingclerkemail.value = document.theform.pbcostingclerkemail.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;
			document.hiddenform.pbowneremail.value = document.theform.pbowneremail.value;
			document.hiddenform.pbownercel.value = document.theform.pbownercel.value;



			document.hiddenform.pbcostingclerk.value = document.theform.pbcostingclerk.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;



			document.hiddenform.pbcontactnumber.value = document.theform.pbcontactnumber.value;

			document.hiddenform.pbcontactnumber2.value = document.theform.pbcontactnumber2.value;

			document.hiddenform.pbcontactperson.value = document.theform.pbcontactperson.value;



			document.hiddenform.pbfaxno.value = document.theform.pbfaxno.value;



			document.hiddenform.pbemail.value = document.theform.pbemail.value;



			document.hiddenform.pbadr1.value = document.theform.pbadr1.value;



			document.hiddenform.pbadr2.value = document.theform.pbadr2.value;



			document.hiddenform.pbadr3.value = document.theform.pbadr3.value;



			document.hiddenform.pbadr4.value = document.theform.pbadr4.value;

			document.hiddenform.notes.value = document.theform.notes.value;



			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;	
			
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;		



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;



			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;



			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 1;	



			



			if (area == 1)



			{



				document.hiddenform.assid.value = 0;



			}



			document.hiddenform.area.value = document.theform.area.value;		



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



	</script>";

	$vehicleTypesList = getVehicleTypesList();

    echo "
			<style>
			
			form * {font-size:14px;}

		</style>


				<form method=\"post\" action=\"loggedinaction.php?action=addnewclaim\" name=\"theform\">


				<table>



				<tr>



					<td>	



						<table bgcolor=\"#E7E7FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">
							<tr>
								<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Client:</h4>
								
									<div style=\"display:inline-block;\">
										Client NumberO: <input type=\"text\" value=\"$clientno\" maxlength=\"50\" name=\"clientno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Name: <input type=\"text\" value=\"$clientname\" maxlength=\"50\" name=\"clientname\" />
									</div>

									<div style=\"display:inline-block;\">
										Claim Number: <input type=\"text\" value=\"$claimno\" maxlength=\"50\" name=\"claimno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No: <input type=\"text\" value=\"$clientcontactno1\" maxlength=\"50\" name=\"clientcontactno1\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No 2: <input type=\"text\" value=\"$clientcontactno2\" maxlength=\"50\" name=\"clientcontactno2\" />
									</div>
									
									<div style=\"display:inline-block;\">
										Email Address: <input type=\"text\" value=\"$clientemail\" maxlength=\"50\" name=\"clientemail\" />
										<a href=\"mailto:$clientemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Client\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</div>

								</td>
							</tr>
						</table>



						<br />



						<table bgcolor=\"#D3D3FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">

								<tr>

									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Panelbeater:</h4>
										
										<div style=\"display:inline-block;\">
											Panelbeater: 

											<select name=\"panelbeater\" onChange=\"ReloadThisPage();\">";

											$qrygetpanelbeaters = "select * from panelbeaters order by `name`";
											$qrygetpanelbeatersresults = mysql_query($qrygetpanelbeaters, $db);

											while ($row = mysql_fetch_array($qrygetpanelbeatersresults))
											{
												$pbid = $row["id"];
												$pbname = stripslashes($row["name"]);
												if ($pbid == $panelbeaterid) {
													echo "<option value=\"$pbid\" selected>$pbname</option>";
												}
												else {
													echo "<option value=\"$pbid\">$pbname</option>";
												}
											}

											$qrygetpanelbeaterinfo = "select * from panelbeaters where `id` = $panelbeaterid";
											$qrygetpanelbeaterinforesults = mysql_query($qrygetpanelbeaterinfo, $db);
											$selectedpbrow = mysql_fetch_array($qrygetpanelbeaterinforesults);
											$dothepb = $_REQUEST["dothepb"];
											//echo "asdfasdf $dothepb ASDFSADF";
											$pbname = $selectedpbrow["name"];
											$pbowner = $selectedpbrow["owner"];
											$ownercel = $selectedpbrow["ownercel"];
											
											$pbcostingclerk = $selectedpbrow["costingclerk"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											
											$pbcontactperson = $selectedpbrow["contactperson"];
											
											$pbworkshopmanager = $selectedpbrow["workshopmanager"];
											$pbworkshopmanagercel = $selectedpbrow["workshopmanagercel"];
											
											$pbcontactnumber = $selectedpbrow["contactno"];
											$pbcontactnumber2 = $selectedpbrow["contactno2"];
											$pbfaxno = $selectedpbrow["faxno"];
											$pbemail = $selectedpbrow["email"];
											$pbadr1 = $selectedpbrow["adr1"];
											$pbadr2 = $selectedpbrow["adr2"];
											$pbadr3 = $selectedpbrow["adr3"];
											$pbadr4 = $selectedpbrow["adr4"];
											$notes = $selectedpbrow["notes"];
											$pbowneremail = $selectedpbrow["owneremail"];
											$pbownercel = $selectedpbrow["ownercel"];
											
											$pbcostingclerkemail = $selectedpbrow["costingclerkemail"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbworkshopmanageremail = $selectedpbrow["workshopmanageremail"];

											$pbestimator = $selectedpbrow["estimator"];
											$pbestimatorcel = $selectedpbrow["estimatorcel"];
											$pbestimatoremail = $selectedpbrow["estimatoremail"];
											
											$pbpartsmanager = $selectedpbrow["partsmanager"];
											$pbpartsmanagercel = $selectedpbrow["partsmanagercel"];
											$pbpartsmanageremail = $selectedpbrow["partsmanageremail"];
											
											$latitude = $selectedpbrow["latitude"];
											$longitude = $selectedpbrow["longitude"];
											
											$pbdms = $selectedpbrow["dms"];
											$pbmember = $selectedpbrow["member"];
											$pbfactoring = $selectedpbrow["factoring"];
											$pbsize = $selectedpbrow["size"];


											if ($dothepb == 1)
											{
												$pbname = $_REQUEST["pbname"];
												$pbowner = $_REQUEST["pbowner"];
												$ownercel = $_REQUEST["ownercel"];
												
												$pbcostingclerk = $_REQUEST["pbcostingclerk"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												
												$pbcontactperson = $_REQUEST["pbcontactperson"];
												
												$pbworkshopmanager = $_REQUEST["pbworkshopmanager"];
												$pbworkshopmanagercel = $_REQUEST["pbworkshopmanagercel"];
												
												$pbcontactnumber = $_REQUEST["pbcontactnumber"];
												$pbcontactnumber2 = $_REQUEST["pbcontactnumber2"];
												
												$pbfaxno = $_REQUEST["pbfaxno"];
												$pbemail = $_REQUEST["pbemail"];
												$pbadr1 = $_REQUEST["pbadr1"];
												$pbadr2 = $_REQUEST["pbadr2"];
												$pbadr3 = $_REQUEST["pbadr3"];
												$pbadr4 = $_REQUEST["pbadr4"];
												
												$notes = $_REQUEST["notes"];
												
												$pbowneremail = $_REQUEST["pbowneremail"];
												$pbownercel = $_REQUEST["pbownercel"];
												
												$pbcostingclerkemail = $_REQUEST["pbcostingclerkemail"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbworkshopmanageremail = $_REQUEST["pbworkshopmanageremail"];

												$pbestimator = $_REQUEST["pbestimator"];
												$pbestimatorcel = $_REQUEST["pbestimatorcel"];
												$pbestimatoremail = $_REQUEST["pbestimatoremail"];
												
												$pbpartsmanager = $_REQUEST["pbpartsmanager"];
												$pbpartsmanagercel = $_REQUEST["pbpartsmanagercel"];
												$pbpartsmanageremail = $_REQUEST["pbpartsmanageremail"];
												
												$latitude = $_REQUEST["latitude"];
												$longitude = $_REQUEST["longitude"];
												
												$pbdms = $selectedpbrow["pbdms"];
												$pbmember = $selectedpbrow["pbmember"];
												$pbfactoring = $selectedpbrow["pbfactoring"];
											    $pbsize = $selectedpbrow["pbsize"];
	
											}
											
											$emailSubject = ucwords("$clientnumber2, $clientname, $claimnumber, $vehicleregistrationno, $vehiclemakemodel");

											echo "					</select>

										</div>

										<div style=\"display:inline-block;\">
											Panelbeater: 
											<input type=\"text\" value=\"$pbname\" maxlength=\"50\" name=\"pbname\" style='width:300px;' />
										</div>
									
									</td>

								</tr>

								<tr>
									<td colspan=\"6\">
										<div style=\"display:inline-block;width:24%;\">
											Tel: <input type=\"text\" value=\"$pbcontactnumber\" maxlength=\"50\" name=\"pbcontactnumber\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Tel 2: <input type=\"text\" value=\"$pbcontactnumber2\" maxlength=\"50\" name=\"pbcontactnumber2\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Fax: <input type=\"text\" value=\"$pbfaxno\" maxlength=\"50\" name=\"pbfaxno\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Email: <input type=\"text\" value=\"$pbemail\" maxlength=\"255\" name=\"pbemail\" class='textinput-lg' />
											<a href=\"mailto:$pbemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
										</div>
									</td>
								</tr>

								<tr>
									<td>Owner/Manager:</td>
									<td><input type=\"text\" value=\"$pbowner\" maxlength=\"50\" name=\"pbowner\"  /></td>
									
									<td>Man Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbownercel\" maxlength=\"50\" name=\"pbownercel\"  /></td>


									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbowneremail\" maxlength=\"50\" name=\"pbowneremail\" class='textinput-lg'  />
										<a href=\"mailto:$pbowneremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Contact: </td>
									<td><input type=\"text\" value=\"$pbcontactperson\" maxlength=\"50\" name=\"pbcontactperson\"  /></td>
								</tr>

								<tr>
									<td>Costing Clerk:</td>
									<td><input type=\"text\" value=\"$pbcostingclerk\" maxlength=\"50\" name=\"pbcostingclerk\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbcostingclerkcel\" maxlength=\"50\" name=\"pbcostingclerkcel\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbcostingclerkemail\" maxlength=\"50\" name=\"pbcostingclerkemail\" class='textinput-lg'/>
										<a href=\"mailto:$pbcostingclerkemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Address: </td>
									<td><input type=\"text\" value=\"$pbadr1\" maxlength=\"50\" name=\"pbadr1\"  /></td>
								</tr>

								<tr>
									<td>Workshop Manager:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanager\" maxlength=\"50\" name=\"pbworkshopmanager\"  /></td>
									
									<td>Workshop Manager:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanagercel\" maxlength=\"50\" name=\"pbworkshopmanagercel\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbworkshopmanageremail\" maxlength=\"50\" name=\"pbworkshopmanageremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbworkshopmanageremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>&nbsp;</td>
									<td><input type=\"text\" value=\"$pbadr2\" maxlength=\"50\" name=\"pbadr2\"  /></td>
								</tr>

								<tr>
									<td>Estimator: </td>
									<td><input type=\"text\" value=\"$pbestimator\" maxlength=\"50\" name=\"pbestimator\"  /></td>
									
									<td>Estimator: </td>
									<td><input type=\"text\" value=\"$pbestimatorcel\" maxlength=\"50\" name=\"pbestimatorcel\"  /></td>
									
									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbestimatoremail\" maxlength=\"50\" name=\"pbestimatoremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbestimatoremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>
									<td>Province: </td>
									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\"  /></td>
								</tr>
								
								<tr>
									<td rowspan=\"3\">Notes:</td>
									<td rowspan=\"3\" colspan=\"3\">
										<textarea name=\"notes\" style='width:400px;height:85px;'>$notes</textarea>
									</td>
									
									<td>Area Code: </td>
									<td><input type=\"text\" value=\"$pbadr4\" maxlength=\"50\" name=\"pbadr4\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Latitude: </td>
									<td><input type=\"text\" value=\"$latitude\" maxlength=\"50\" name=\"latitude\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Longitude: </td>
									<td><input type=\"text\" value=\"$longitude\" maxlength=\"50\" name=\"longitude\"  /></td>
								</tr>

							</table>



						<br />



						<table bgcolor=\"#BFBFFF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">

								<tr>
									<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Vehicle:</h4>
									
										<div style=\"display:inline-block;\">
											Vehicle Type: 
											<select name=\"vehicletype\">";

											foreach ($vehicleTypesList as $vType) {
												$isSelected = ($vehicletype == $vType) ? 'selected="selected"' : '';
												echo '<option value="'.$vType.'" '.$isSelected.'>'.$vType.'</option>';
											}

											echo "					</select>
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Make/Model: 
											<input type=\"text\" value=\"$vehiclemakemodel\" maxlength=\"50\" name=\"vehiclemakemodel\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Year: 
											<input type=\"text\" value=\"$vehicleyear\" maxlength=\"10\" name=\"vehicleyear\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Registration&nbsp;No: 
											<input type=\"text\" value=\"$vehicleregistrationno\" maxlength=\"50\" name=\"vehicleregistrationno\" />
										</div>

										<div style=\"display:inline-block;\">
											VIN Number: 
											<input type=\"text\" value=\"$vehiclevin\" maxlength=\"50\" name=\"vehiclevin\" />
										</div>

									</td>

								</tr>


							</table>



						<br />



						<table bgcolor=\"#ABABFF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">

							<tr>

								<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Administrator:</h4>
									
									<select name=\"administrator\" onChange=\"ReloadThisPagePB(0);\">";

									$qrygetadministrators = "select * from administrators order by `name`";

									$qrygetadministratorsresults = mysql_query($qrygetadministrators, $db);

									while ($administratorrow = mysql_fetch_array($qrygetadministratorsresults))
									{

										$adminid = $administratorrow["id"];

										$adminname = $administratorrow["name"];

										if ($administratorid == $adminid)
										{

											echo "<option value=\"$adminid\" selected>$adminname</option>";

										}

										else
										{

											echo "<option value=\"$adminid\">$adminname</option>";

										}

									}

									$qrygetadministratorinfo = "select * from administrators where `id` = $administratorid";

									$qrygetadministratorinforesults = mysql_query($qrygetadministratorinfo, $db);

									$administratorinforow = mysql_fetch_array($qrygetadministratorinforesults);

									$admintelno = stripslashes($administratorinforow["telno"]);

									$adminfaxno = stripslashes($administratorinforow["faxno"]);

									$adminadr1 = stripslashes($administratorinforow["adr1"]);

									$adminadr2 = stripslashes($administratorinforow["adr2"]);

									$adminadr3 = stripslashes($administratorinforow["adr3"]);

									$adminadr4 = stripslashes($administratorinforow["adr4"]);

									$vatno = stripslashes($administratorinforow["vatno"]);

									echo "					</select>


									Insurance Company:

									<select name=\"insurerid\"><option value=\"0\">Select one</option>";

									$qryinsurers = "select * from `insurers` order by `name`";

									$qryinsurersresults = mysql_query($qryinsurers, $db);

									while ($insrow = mysql_fetch_array($qryinsurersresults))
									{

										$insid = $insrow["id"];

										$insurancecompname = stripslashes($insrow["name"]);

										if ($insid == $insurerid)
										{

											echo "<option value=\"$insid\" selected>$insurancecompname</option>";

										}

										else
										{

											echo "<option value=\"$insid\">$insurancecompname</option>";

										}

									}

									echo " </select>

									Broker: 
									<select name=\"brokerid\"><option value=\"0\">Select one</option>
									";
										
										$qrybrokers = "select * from `brokers` order by `name`";

									$qrybrokersresults = mysql_query($qrybrokers, $db);

									while ($brokerrow = mysql_fetch_array($qrybrokersresults))
									{

										$brokerName = stripslashes($brokerrow["name"]);

										if ($brokerrow["id"] == $brokerid)
										{

											echo '<option value="'. $brokerrow["id"] .'" selected>' . $brokerName. ' </option>';

										}

										else
										{

											echo '<option value="'. $brokerrow["id"] .'" >'. $brokerName. '</option>';

										}

									}

									echo " </select>
								
								</td>

							</tr>

							<tr>
								<td colspan=\"5\">
									Tel: $admintelno, 
									Fax: $adminfaxno,
									P.O.Box: $adminadr1, $adminadr2, $adminadr3 ";
									
								if ( !empty($adminadr4) ) { echo $adminadr4; }
							echo "
								</td>
							</tr>
							
							<tr>
								<td colspan=\"5\">
									Claim Technician: 

									<select name=\"claimsclerk\" id=\"claimsclerk\">";

									$qryclaimsclerks = "select * from claimsclerks order by `name`";

									$qryclaimsclerksresults = mysql_query($qryclaimsclerks, $db);
									
									$defaultEmail = '';
									$counter = 0;
									while ($ccrow = mysql_fetch_array($qryclaimsclerksresults))
									{

										$ccid = $ccrow["id"];

										$ccname = stripslashes($ccrow["name"]);
										$ccemailid = stripslashes($ccrow["email"]);

										if ($counter==0) {
											$defaultEmail = $ccemailid;
										}

										if ($claimsclerkid == $ccid)
										{
											$defaultEmail = $ccemailid;

											echo "<option value=\"$ccid\" selected email=\"$ccemailid\" >$ccname</option>";

										}

										else
										{

											echo "<option value=\"$ccid\" email=\"$ccemailid\">$ccname</option>";

										}

										$counter++;

									}

									echo " </select>

									<a href=\"mailto:$defaultEmail?subject=$emailSubject\"  type=\"Claim\" claimId=\"$claimid\" class=\"send-email\" emailpart=\"subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" id=\"claimTechnicianEmailLink\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									";
				
									global $admin;
									if ( $admin == 1 ) { echo " VAT&nbsp;Number: $vatno "; }

									echo "
								</td>
							</tr>


						</table>



						<br />



						<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">
							<tr>
								<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Claim:</h4>										
									<div style=\"display:inline-block;\">
										Quote&nbsp;Number: <input type=\"text\" value=\"$quoteno\" maxlength=\"50\" name=\"quoteno\" />
									</div>
									<div style=\"display:inline-block;\">
										Authorised&nbsp;Amount: <input type=\"text\" value=\"$authamount\" maxlength=\"11\" name=\"authamount\" />
									</div>
									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess\" maxlength=\"11\" name=\"excess\" />
									</div>
									<div style=\"display:inline-block;\">
											Excess Description: <input type=\"text\" value=\"$excess_description\" style='width:300px;' name=\"excess_description\" />
										</div>
									<div style=\"display:inline-block;\">
										Betterment: <input type=\"text\" value=\"$betterment\" maxlength=\"11\" name=\"betterment\" />
									</div>
									<div style=\"display:inline-block;\">
										Quoted&nbsp;Amount: <input type=\"text\" value=\"$quoteamount\" maxlength=\"11\" name=\"quoteamount\" />
									</div>
									";
								
									$res = mysql_query("SELECT `received` FROM `dates` WHERE claimid='$claimid' ", $db);

									$daterow = mysql_fetch_array($res);

									$received = explode('-', $daterow['received']);

									echo "
										<div style=\"display:inline-block;\">
											Date Received:

											<input type=\"text\" style=\"width:25px;\" value=\"" . $received[2] . "\" name=\"receivedday\" readonly> -	<input type=\"text\" style=\"width:25px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> -  <input type=\"text\" style=\"width:40px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly> 
											<a href=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\"  title=\"cal1.showCalendar('anchor1'); return false;\" name=\"anchor1\" id=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></a>

											<script type='text/javascript'>	
												var cal1 = new CalendarPopup();
												cal1.setReturnFunction(\"setMultipleValues1\");

												function setMultipleValues1(y,m,d) {
													document.theform.receivedyear.value=y;
													document.theform.receivedmonth.value=LZ(m);
													document.theform.receivedday.value=LZ(d);
												}
											</script>

										</div>
								</td>
							</tr>
						</table>



						<br />



						<table bgcolor=\"#8383FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"pad-table blue-bg\">



								<tr>



									<td colspan=\"5\"><h4 style=\"margin-top:0px;display:inline-block;margin-bottom:0;\"> Assessor:</h4>
										
										Select&nbsp;Assessor&nbsp;Area:

										<select name=\"area\" onChange=\"ReloadThisPagePB(1);\">
										<option value=\"0\">Select Assessor Area</option>";

										$qryareas = "select * from areas order by areaname";

										$qryareasresults = mysql_query($qryareas, $db);

										while ($arearow = mysql_fetch_array($qryareasresults))
										{

											$areaid = $arearow["id"];

											$areaname = $arearow["areaname"];

											if ($areaid == $area)
											{

												echo "<option value=\"$areaid\" selected>$areaname</option>";

											}

											else
											{

												echo "<option value=\"$areaid\">$areaname</option>";

											}

										}

									echo "</select>";


									echo "  Select&nbsp;Assessor:


									<select name=\"assessor\" onChange=\"ReloadThisPagePB(0);\"><option value=\"0\">Select Assessor</option>";

										$qryassessors = "select * from assessors order by `company`";

										$qryassessorsresults = mysql_query($qryassessors, $db);

										while ($assrow = mysql_fetch_array($qryassessorsresults))
										{

											$assid = $assrow["id"];

											$assname = $assrow["name"];
											$asscompanyoption = $assrow["company"] . ' (' . $assname . ')';

											//check to see if this assessor is in the selected Area

											if ($area != 0)
											{

												$qrycheckarea = "select count(assessorid) as counted from assessor_area where assessorid = $assid and areaid = $area";

												$qrycheckarearesults = mysql_query($qrycheckarea, $db);

												$checkarearow = mysql_fetch_array($qrycheckarearesults);

												$count = $checkarearow["counted"];

												if ($count == 1)
												{

													if ($assessorid == $assid)
													{

														echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

													}

													else
													{

														echo "<option value=\"$assid\">$asscompanyoption</option>";

													}

												}

											}

											else
											{

												if ($assessorid == $assid)
												{

													echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

												}

												else
												{

													echo "<option value=\"$assid\">$asscompanyoption</option>";

												}

											}

										}

										$qrygetassessorinfo = "select * from assessors where `id` = $assessorid";

										$qrygetassessorinforesults = mysql_query($qrygetassessorinfo, $db);

										$assessorrow = mysql_fetch_array($qrygetassessorinforesults);

										$assname = stripslashes($assessorrow["name"]);

										$asscompany = stripslashes($assessorrow["company"]);

										$asstelno = stripslashes($assessorrow["telno"]);

										$assfaxno = stripslashes($assessorrow["faxno"]);

										$asscellno = stripslashes($assessorrow["cellno"]);

										$assemail = stripslashes($assessorrow["email"]);

										$asscomments = stripslashes($assessorrow["comments"]);

									echo " </select>";
									
									echo "</td>									



								</tr>";

								

								if ( !empty($assname) ) {

									$ass_string = ucwords(trim($asscompany)) . ', ' . ucwords(trim($assname)) . ', Tel:' . $asstelno . ', Fax:' . $assfaxno . ', Cel/Ext:' . $asscellno . ', Email:' . $assemail;

									echo "<tr>
										<td colspan=\"5\"> $ass_string 
										
										<div style=\"display: inline; \"><a href=\"mailto:$assemail?subject=$emailSubject\" type=\"Assessors\" claimId=\"$claimid\" class=\"send-email\" alt=\"Send Email\" title=\"Send Email\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a></div>

										</td>
									</tr>";
								
								}



								echo "<tr>

									<td>Comments:</td>

									<td colspan='4'>
										<textarea name=\"asscomments\" style='width:660px;height:40px;' >$asscomments</textarea>
									</td>

								</tr>



							</table>



					</td>



				</tr>



				</table>



				<br />



				<input type=\"submit\" value=\"Save Claim\" /> <input type=\"reset\" value=\"Reset\" /> 



				</form>";

    echo "<form action=\"loggedinaction.php?action=newclaim\" method=\"POST\" name=\"hiddenform\">



<input type=\"hidden\" name=\"clientname\">



<input type=\"hidden\" name=\"clientno\">



<input type=\"hidden\" name=\"claimno\">



<input type=\"hidden\" name=\"clientcontactno1\">



<input type=\"hidden\" name=\"clientcontactno2\">

<input type=\"hidden\" name=\"clientemail\">







<input type=\"hidden\" name=\"pbid\">



<input type=\"hidden\" name=\"pbname\">



<input type=\"hidden\" name=\"pbowner\">
<input type=\"hidden\" name=\"pbworkshopmanageremail\">
<input type=\"hidden\" name=\"pbcostingclerkemail\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">
<input type=\"hidden\" name=\"pbowneremail\">
<input type=\"hidden\" name=\"pbownercel\">

<input type=\"hidden\" name=\"pbestimator\">
<input type=\"hidden\" name=\"pbestimatorcel\">
<input type=\"hidden\" name=\"pbdms\">
<input type=\"hidden\" name=\"pbmember\">
<input type=\"hidden\" name=\"pbfactoring\">
<input type=\"hidden\" name=\"pbsize\">

<input type=\"hidden\" name=\"pbcostingclerk\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">



<input type=\"hidden\" name=\"pbcontactperson\">



<input type=\"hidden\" name=\"pbcontactnumber\">

<input type=\"hidden\" name=\"pbcontactnumber2\">



<input type=\"hidden\" name=\"pbfaxno\">



<input type=\"hidden\" name=\"pbemail\">



<input type=\"hidden\" name=\"pbadr1\">



<input type=\"hidden\" name=\"pbadr2\">



<input type=\"hidden\" name=\"pbadr3\">



<input type=\"hidden\" name=\"pbadr4\">


<input type=\"hidden\" name=\"notes\">






<input type=\"hidden\" name=\"vehiclemakemodel\">



<input type=\"hidden\" name=\"vehicleyear\">



<input type=\"hidden\" name=\"vehicleregistrationno\">



<input type=\"hidden\" name=\"vehiclemake\" >
<input type=\"hidden\" name=\"vehicletype\">
<input type=\"hidden\" name=\"vehiclevin\">







<input type=\"hidden\" name=\"adminid\">







<input type=\"hidden\" name=\"quoteno\">



<input type=\"hidden\" name=\"insurerid\">



<input type=\"hidden\" name=\"claimsclerk\">



<input type=\"hidden\" name=\"authamount\">



<input type=\"hidden\" name=\"excess\">

<input type=\"hidden\" name=\"excess_description\">



<input type=\"hidden\" name=\"betterment\">



<input type=\"hidden\" name=\"quoteamount\">







<input type=\"hidden\" name=\"assid\">



<input type=\"hidden\" name=\"reload\">



<input type=\"hidden\" name=\"dothepb\">



<input type=\"hidden\" name=\"area\" />







		</form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function AddNewClaim($clientname, $clientno, $claimno, $clientcontactno1, $clientcontactno2, $clientemail,
    $panelbeaterid, $vehiclemakemodel, $vehicleregistrationno, $vehicleyear, $vehicletype,
    $administratorid, $quoteno, $insurerid, $claimsclerkid, $authamount, $excess, $betterment,
    $quoteamount, $assessorid, $pbname, $pbowner, $ownercel, $pbcostingclerk, $pbcostingclerkcel, $pbcontactperson, $pbworkshopmanager, 
	$pbworkshopmanagercel, $pbestimator, $pbestimatorcel, $pbpartsmanager, $pbpartsmanagercel, $pbpartsmanageremail, $pbdms, $pbmember,
	$pbfactoring, $pbsize,
	$pbcontactnumber, $pbcontactnumber2, $pbfaxno, $pbemail, $pbadr1, $pbadr2, $pbadr3, $pbadr4, $notes, $vehiclevin='')
{

    require ('connection.php');

    if ($panelbeaterid == 0)
    {

        $qryinsert = "insert into panelbeaters (`id`, `name`, `owner`, `costingclerk`, `costingclerkcel`, `contactperson`, `workshopmanager`, 
		`workshopmanagercel`, `estimator`, `estimatorcel`, `partsmanager`, `partsmanagercel`, `dms`, `member`,`adr1`, `adr2`, `adr3`, `adr4`, 



			                                        `contactno`, `contactno2`, `faxno`, `email`, `notes`)



									 	    values ('', '$pbname', '$pbowner', '$pbownercel', '$pbcostingclerk', '$pbcostingclerkcel', '$pbcontactperson',
											'$pbworkshopmanager', '$pbworkshopmanagercel', '$pbestimator', '$pbestimatorcel', '$pbpartsmanager',
											'$pbpartsmanagercel', '$pbdms', '$pbmember', '$pbfactoring', '$pbsize', '$pbadr1', '$pbadr2',



											        '$pbadr3', '$pbadr4', '$pbcontactnumber', '$pbcontactnumber2', '$pbfaxno', '$pbemail', '$notes')";

        $qryinsertresults = mysql_query($qryinsert, $db);

        $qrygetnewid = "select max(`id`) as newid from panelbeaters";

        $qrygetnewidresults = mysql_query($qrygetnewid, $db);

        $newidrow = mysql_fetch_array($qrygetnewidresults);

        $panelbeaterid = $newidrow["newid"];

    }

    else
    {

        $qryupdate = "update panelbeaters set `name` = '$pbname',



											  `owner` = '$pbowner',
											  `ownercel` = '$pbownercel',
											  `ownercel` = '$pbowneremail',



											  `costingclerk` = '$pbcostingclerk',
											  `costingclerkcel` = '$pbcostingclerkcel',
											  `costingclerkcel` = '$pbcostingclerkemail',



											  `contactperson` = '$pbcontactperson',

											  `workshopmanager` = '$pbworkshopmanager',
											  `workshopmanager` = '$pbworkshopmanagercel',
											  
											  `estimator` = '$pbestimator',
											  `estimatorcel` = '$pbestimatorcel',
											  
											  `partsmanager` = '$pbpartsmanager',
											  `partsmanagercel` = '$pbpartsmanagercel',
											  
											  `dms` = '$pbdms',
											  `member` = '$pbmember',
											  `factoring` = '$pbfactoring',
											  `size` = '$pbsize',



											  `adr1` = '$pbadr1',

											  `adr2` = '$pbadr2',

											  `adr3` = '$pbadr3',

											  `adr4` = '$pbadr4',

											  `notes` = '$notes,'



											  `contactno` = '$pbcontactnumber',

											  `contactno2` = '$pbcontactnumber2',



											  `faxno`= '$pbfaxno',



											  `email` = '$pbemail' where `id` = $panelbeaterid";

        $qryupdateresults = mysql_query($qryupdate, $db);

    }

    $qrygetclaimno = "select * from claimnumber";

    $qrygetclaimnoresults = mysql_query($qrygetclaimno, $db);

    $claimnorow = mysql_fetch_array($qrygetclaimnoresults);

    $dbclientno = $claimnorow["clientno"];

    if ($clientno == $dbclientno)
    {

        $dbclientno++;

        $upd = "update claimnumber set clientno = $dbclientno";

        $updres = mysql_query($upd, $db);

    }

	$assessor_area_id = $_POST['area'];

	$brokerid = $_POST['brokerid'];

	$excess_description = $_POST['excess_description'];

    $qryinsert = "insert into claim (`clientname`, `clientno`, `claimno`, `clientcontactno`, `clientcontactno2`, `clientemail`, `panelbeaterid`, `makemodel`,



		                                 `vehicleregistrationno`, `vehicleyear`, `vehicletype`, `vehiclevin`, `administratorid`, `quoteno`, `insurerid`, `brokerid`, `claimsclerkid`,



										 `authamount`, `excess`, `excess_description`, `betterment`, `quoteamount`, `assessorid`, `assessor_area_id`)



							     values ('$clientname', '$clientno', '$claimno', '$clientcontactno1', '$clientcontactno2', '$clientemail', $panelbeaterid, '$vehiclemakemodel',



								         '$vehicleregistrationno', '$vehicleyear', '$vehicletype', '$vehiclevin', $administratorid, '$quoteno', '$insurerid', '$brokerid', $claimsclerkid,



										 $authamount, $excess, '$excess_description', $betterment, $quoteamount, $assessorid, $assessor_area_id)";

    $qryinsertresults = mysql_query($qryinsert, $db);

    $qrygetmax = "select max(id) as newid from claim";

    $qrygetmaxresults = mysql_query($qrygetmax, $db);

    $newidrow = mysql_fetch_array($qrygetmaxresults);

    $newid = $newidrow["newid"];

    $qryinsertdates = "INSERT INTO `dates` ( `claimid` , `received` , `loss` , `assappointed` , `assessment` , `assessmentreport` , 



		                   `assessmentinvtoinsurer` , `auth` , `wp` , `docreq` , `workinprogressinsp` , `dod` , `finalcosting` , 



						   `acirepsentinsurer` , `invoicesent` , `assfeereceivedfrominsurer` , `acipaymentreceived` )



					VALUES ($newid, CURDATE(), '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 



							'0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 



							'0000-00-00', '0000-00-00')";

    $qryinsertdatesresults = mysql_query($qryinsertdates, $db);

    //echo $qryinsert;

    echo "<p>The claim was successfully entered into the database. <a href=\"loggedinaction.php?action=claims&amp;from=1\">Go back to Claims</a>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function FooterEdited($claimid, $investigator, $dateday, $datemonth, $dateyear,
    $reportsentday, $reportsentmonth, $reportsentyear, $invoicesentday, $invoicesentmonth,
    $invoicesentyear)
{

    require ('connection.php');

    $date = $dateyear . "-" . $datemonth . "-" . $dateday;

    $reportsent = $reportsentyear . "-" . $reportsentmonth . "-" . $reportsentday;

    $invoicesent = $invoicesentyear . "-" . $invoicesentmonth . "-" . $invoicesentday;

    $qry = "update footer set `investigator` = '$investigator',



								 `claimdate` = '$date',



								 `reportsent` = '$reportsent',



								 `invoicesent` = '$invoicesent' where `id` = $claimid";

    $qryresults = mysql_query($qry, $db);

    echo "<p>The Claim Footer was edited successfully.</p>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function NewItem($claimid)
{

    require ('connection.php');

    $qry = "select * from claim where id = $claimid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];

    $claimno = $row["claimno"];

    $count = $_REQUEST["qty"];

    echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewitem\" name=\"theform\">



				  <p>Enter the new Item for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table border=\"1\" cellspacing=\"1\">



						<tr>



							<td>Qty</td>



							<td>Description</td>



							<td>Quoted</td>



							<td>Cost</td>



							<td>1.25</td>



							<td>Adjustment</td>



						</tr>";

    for ($i = 1; $i <= $count; $i++)
    {

        echo "



					<tr>



						<td><input type=\"text\" name=\"qty" . $i . "\" maxlength=\"11\" style=\"width:75px\" value=\"1\"></td>



							<td><input type=\"text\" name=\"description" . $i . "\" size=\"100\" maxlength=\"255\"></td>



							<td><input type=\"text\" name=\"quoted" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.onetwofive" .
            $i . ".value - document.theform.quoted" . $i . ".value))



																																 {



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"cost" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.cost" .
            $i . ".value * 1.25))



																															   {



																																	document.theform.onetwofive" . $i .
            ".value = document.theform.cost" . $i . ".value * 1.25;  



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value; 



																															   }											



																																	\"></td>



							<td><input type=\"text\" name=\"onetwofive" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.onetwofive" .
            $i . ".value - document.theform.quoted" . $i . ".value))



																																 {



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"adjustment" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\"></td></tr>	



			";

    }

    echo "



						



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\"> <input type=\"hidden\" value=\"$count\" name=\"hoeveelheid\" />



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function AddNewItem($claimid, $qty, $description, $quoted, $cost, $onetwofive, $adjustment,
    $loggedinuserid, $count)
{

    require ('connection.php');

    //echo "asdf " . $claimid . " asdf";

    for ($i = 1; $i <= $count; $i++)
    {

        //echo $description[$i] . " asdf <br>";

        $qty2 = $qty[$i];

        $description2 = $description[$i];

        $quoted2 = $quoted[$i];

        $cost2 = $cost[$i];

        $onetwofive2 = $onetwofive[$i];

        $adjustment2 = $adjustment[$i];

        if (($description2 == "") && ($quoted2 == 0) && ($cost2 == 0))
        {

        }

        else
        {

            $qryinsert = "insert into items (`id`, `claimid`, `qty`, `description`, `quoted`, `cost`, `onetwofive`, `adjustment`, `userid`)



										 values ('', $claimid, $qty2, '$description2', $quoted2, $cost2, $onetwofive2, $adjustment2, $loggedinuserid)";

            $qryinsertresults = mysql_query($qryinsert, $db);

        }

        //echo $qryinsert . " <br>";

    }

    /**



    * $qryinsert = "insert into items (`id`, `claimid`, `qty`, `description`, `quoted`, `cost`, `onetwofive`, `adjustment`, `userid`) 



    * 								values ('', $claimid, $qty, '$description', $quoted, $cost, $onetwofive, $adjustment, $loggedinuserid)";	



    * 		$qryinsertresults = mysql_query($qryinsert, $db);



    */

    echo "<p>The Item/s have been saved successfully.</p>";

    EditClaim($claimid, 2);

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function EditItem($itemid)
{

    require ('connection.php');

    $qry = "select * from items where id = $itemid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $qty = $row["qty"];

    $desc = stripslashes($row["description"]);

    $quoted = $row["quoted"];

    $cost = $row["cost"];

    $onetwofive = $row["onetwofive"];

    $adjustment = $row["adjustment"];

    $claimid = $row["claimid"];

    $qryclaim = "select * from claim where id = $claimid";

    $qryclaimresults = mysql_query($qryclaim, $db);

    $claimrow = mysql_fetch_array($qryclaimresults);

    $clientname = $claimrow["clientname"];

    $claimno = $claimrow["claimno"];

    echo "<form method=\"post\" action=\"loggedinaction.php?action=itemedited\" name=\"theform\">



				  <p>Make the desired changes to the Item for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table border=\"1\" cellspacing=\"1\">



						<tr>



							<td>Qty</td>



							<td>Description</td>



							<td>Quoted</td>



							<td>Cost</td>



							<td>1.25</td>



							<td>Adjustment</td>



						</tr>



						<tr>



							<td><input type=\"text\" name=\"qty\" maxlength=\"11\" style=\"width:75px\" value=\"$qty\"></td>



							<td><input type=\"text\" name=\"description\" maxlength=\"255\" value=\"$desc\"></td>



							<td><input type=\"text\" name=\"quoted\" maxlength=\"11\" style=\"width:75px\" value=\"$quoted\" onKeyUp=\"if (!isNaN(document.theform.onetwofive.value - document.theform.quoted.value))



																																 {



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"cost\" maxlength=\"11\" style=\"width:75px\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theform.cost.value * 1.25))



																															   {



																																	document.theform.onetwofive.value = document.theform.cost.value * 1.25;  



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value; 



																															   }											



																																	\"></td>



							<td><input type=\"text\" name=\"onetwofive\" maxlength=\"11\" style=\"width:75px\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theform.onetwofive.value - document.theform.quoted.value))



																																 {



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"adjustment\" maxlength=\"11\" style=\"width:75px\" value=\"$adjustment\"></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"itemid\" value=\"$itemid\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ItemEdited($itemid, $claimid, $qty, $description, $quoted, $cost, $onetwofive,
    $adjustment, $loggedinuserid)
{

    require ('connection.php');

    if (strlen($description) == 0)
    {

        echo "<p>You must enter a description for the item. <a href=\"javascript:history.go(-1)\">Go back</a></p>";

    }

    else
    {

        $qry = "update items set `qty` = $qty,



									 `description` = '$description',



									 `quoted` = $quoted,



									 `cost` = $cost,



									 `onetwofive` = $onetwofive,



									 `adjustment` = $adjustment,



									 `userid` = $loggedinuserid where `id` = $itemid;



										";

        $qryresults = mysql_query($qry, $db);

        echo "<p>The item was edited successfully. </p>";

        EditClaim($claimid, 2);

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ConfirmDeleteItem($itemid, $key)
{

    require ('connection.php');

    //include('functions.php');

    $qry = "select * from items where id = $itemid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $description = $row["description"];

    $claimid = $row["claimid"];

    //$key = get_rand_id(32);

    $qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteitem')";

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>Are you sure you want to delete the item with description <strong>$description</strong>?<br> <a href=\"loggedinaction.php?action=deleteitem&amp;itemid=$itemid&amp;claimid=$claimid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function DeleteItem($itemid, $claimid, $key)
{

    require ('connection.php');

    $qry = "select * from `key` where `action` = 'deleteitem' and `key` = '$key'";

    $qryresults = mysql_query($qry, $db);

    $keyrow = mysql_fetch_array($qryresults);

    $keyid = $keyrow["id"];

    $count = mysql_num_rows($qryresults);

    if ($count == 1)
    {

        $qrydelete = "delete from `key` where `id` = $keyid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from items where `id` = $itemid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        echo "<p>The item has been deleted successfully.</p>";

        EditClaim($claimid, 2);

    }

    else
    {

        echo "<p>It wont work if you enter the url just like that to delete a item...</p>";

        EditClaim($claimid, 2);

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function NewReport($claimid)
{
    require ('connection.php');

    $qry = "select * from claim where id = $claimid";
    $qryresults = mysql_query($qry, $db);
    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];
    $claimno = $row["claimno"];

    $today = time() + (7 * 3600);//time + 7 hours in this case (7 hours is 7 x 3600)

    $todayday = date("d", $today);//will give 01 - 31
    $todaymonth = date("m", $today);// will give 01 - 12
    $todayyear = date("Y", $today);// will give 1999, 2003, 2008, etc.

    $thehour = date("H", $today);
    $theminute = date("i", $today);

    echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewreport\" name=\"reportform\">


<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">



var cal1 = new CalendarPopup();



cal1.setReturnFunction(\"setMultipleValues2\");



function setMultipleValues2(y,m,d) {



document.reportform.reportyear.value=y;



document.reportform.reportmonth.value=LZ(m);



document.reportform.reportday.value=LZ(d);



}



</SCRIPT>						



		



				  <p>Enter the new Report for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table>



						<tr>



							<td>Date:</td>



							<td><input name=\"reportday\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todayday\"> / <input name=\"reportmonth\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todaymonth\"> / <input name=\"reportyear\" type=\"text\" style=\"width:40px;\" maxlength=\"50\"  value=\"$todayyear\"> <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" TITLE=\"cal10.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A> 



								Hour: 	<select name=\"hours\">";

    for ($i = 0; $i < 24; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$thehour")
            {
                echo "<option value=\"0$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">$i</option>";
            }
        }
        else
        {
            if ("$i" == "$thehour")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }
    }

    echo "									
									</select> Minutes: 	<select name=\"minutes\">";

    for ($i = 0; $i < 60; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$theminute")
            {
                echo "<option value=\"0$i\" selected>0$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">0$i</option>";
            }
        }
        else
        {
            if ("$i" == "$theminute")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }

    }

    echo "



														</select>															



							</td>



						</tr>



						<tr>



							<td>Description:</td>



							<td><textarea name=\"description\" style=\"width:350px;height:70px;\"></textarea></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\">



			  </form>";

}

function ccNewReport($claimid)
{
    require ('connection.php'); 

    $qry = "select * from claim where id = $claimid";
    $qryresults = mysql_query($qry, $db);
    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];
    $claimno = $row["claimno"];

    $today = time() + (7 * 3600);//time + 7 hours in this case (7 hours is 7 x 3600)

    $todayday = date("d", $today);//will give 01 - 31
    $todaymonth = date("m", $today);// will give 01 - 12
    $todayyear = date("Y", $today);// will give 1999, 2003, 2008, etc.

    $thehour = date("H", $today);
    $theminute = date("i", $today);

    echo "<form method=\"post\" action=\"ccloggedinaction.php?action=addnewreport\" name=\"reportform\">


<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">



var cal1 = new CalendarPopup();



cal1.setReturnFunction(\"setMultipleValues2\");



function setMultipleValues2(y,m,d) {



document.reportform.reportyear.value=y;



document.reportform.reportmonth.value=LZ(m);



document.reportform.reportday.value=LZ(d);



}



</SCRIPT>						



		



				  <p>Enter the new Report for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table>



						<tr>



							<td>Date:</td>



							<td><input name=\"reportday\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todayday\"> / <input name=\"reportmonth\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todaymonth\"> / <input name=\"reportyear\" type=\"text\" style=\"width:40px;\" maxlength=\"50\"  value=\"$todayyear\"> <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" TITLE=\"cal10.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A> 



								Hour: 	<select name=\"hours\">";

    for ($i = 0; $i < 24; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$thehour")
            {
                echo "<option value=\"0$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">$i</option>";
            }
        }
        else
        {
            if ("$i" == "$thehour")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }
    }

    echo "									
									</select> Minutes: 	<select name=\"minutes\">";

    for ($i = 0; $i < 60; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$theminute")
            {
                echo "<option value=\"0$i\" selected>0$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">0$i</option>";
            }
        }
        else
        {
            if ("$i" == "$theminute")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }

    }

    echo "



														</select>															



							</td>



						</tr>



						<tr>



							<td>Description:</td>



							<td><textarea name=\"description\" style=\"width:350px;height:70px;\"></textarea></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\">



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function asNewReport($claimid)
{
    require ('connection.php');

    $qry = "select * from claim where id = $claimid";
    $qryresults = mysql_query($qry, $db);
    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];
    $claimno = $row["claimno"];

    $today = time() + (7 * 3600);//time + 7 hours in this case (7 hours is 7 x 3600)

    $todayday = date("d", $today);//will give 01 - 31
    $todaymonth = date("m", $today);// will give 01 - 12
    $todayyear = date("Y", $today);// will give 1999, 2003, 2008, etc.

    $thehour = date("H", $today);
    $theminute = date("i", $today);

    echo "<form method=\"post\" action=\"asloggedinaction.php?action=addnewreport\" name=\"reportform\">


<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">



var cal1 = new CalendarPopup();



cal1.setReturnFunction(\"setMultipleValues2\");



function setMultipleValues2(y,m,d) {



document.reportform.reportyear.value=y;



document.reportform.reportmonth.value=LZ(m);



document.reportform.reportday.value=LZ(d);



}



</SCRIPT>						



		



				  <p>Enter the new Report for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table>



						<tr>



							<td>Date:</td>



							<td><input name=\"reportday\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todayday\"> / <input name=\"reportmonth\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"$todaymonth\"> / <input name=\"reportyear\" type=\"text\" style=\"width:40px;\" maxlength=\"50\"  value=\"$todayyear\"> <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" TITLE=\"cal10.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A> 



								Hour: 	<select name=\"hours\">";

    for ($i = 0; $i < 24; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$thehour")
            {
                echo "<option value=\"0$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">$i</option>";
            }
        }
        else
        {
            if ("$i" == "$thehour")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }
    }

    echo "									
									</select> Minutes: 	<select name=\"minutes\">";

    for ($i = 0; $i < 60; $i++)
    {
        if ($i < 10)
        {
            if ("0$i" == "$theminute")
            {
                echo "<option value=\"0$i\" selected>0$i</option>";
            }
            else
            {
                echo "<option value=\"0$i\">0$i</option>";
            }
        }
        else
        {
            if ("$i" == "$theminute")
            {
                echo "<option value=\"$i\" selected>$i</option>";
            }
            else
            {
                echo "<option value=\"$i\">$i</option>";
            }
        }

    }

    echo "



														</select>															



							</td>



						</tr>



						<tr>



							<td>Description:</td>



							<td><input type=\"text\" name=\"description\" maxlength=\"255\"></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\">



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function AddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours,
    $reportminutes, $description, $loggedinuserid)
{

    require ('connection.php');

    $reportdate = $reportyear . "-" . $reportmonth . "-" . $reportday . " " . $reporthours .
        ":" . $reportminutes . ":00";

    if (strlen($reportdate) == 0)
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, NOW(), '$description', $loggedinuserid)";

    }

    else
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$reportdate', '$description', $loggedinuserid)";

    }

    //echo $qryinsert;

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>The Report have been saved successfully. <a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Go Back to the Claim</a></p>";

    EditClaim($claimid, 4);

}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function asAddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours,
    $reportminutes, $description, $loggedinuserid)
{

    require ('connection.php');

    $reportdate = $reportyear . "-" . $reportmonth . "-" . $reportday . " " . $reporthours .
        ":" . $reportminutes . ":00";

    if (strlen($reportdate) == 0)
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, NOW(), '$description', 0)";

    }

    else
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$reportdate', '$description', 0)";

    }

    //echo $qryinsert;

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>The Report have been saved successfully.</p>";

    asEditClaim($claimid, 4, $loggedinassid);

}

function ccAddNewReport($claimid, $reportday, $reportmonth, $reportyear, $reporthours,
    $reportminutes, $description, $loggedinuserid)
{

    require ('connection.php');

    $reportdate = $reportyear . "-" . $reportmonth . "-" . $reportday . " " . $reporthours .
        ":" . $reportminutes . ":00";

    if (strlen($reportdate) == 0)
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, NOW(), '$description', 0)";

    }

    else
    {

        $qryinsert = "insert into report (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$reportdate', '$description', 0)";

    }

    //echo $qryinsert;

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>The Report have been saved successfully.</p>";

    ccEditClaim($claimid, 4, $loggedinassid);

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function EditReport($reportid)
{

    require ('connection.php');

    $qry = "select * from report where id = $reportid order by reportdate";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $reportdate = $row["reportdate"];

    $date = substr($reportdate, 0, 10);

    $date = explode("-", $date);

    $time = substr($reportdate, 11, 8);

    $time = explode(":", $time);

    $description = $row["description"];

    $claimid = $row["claimid"];

    $qry = "select * from claim where id = $claimid";

    $qryresults = mysql_query($qry, $db);

    $rowclaim = mysql_fetch_array($qryresults);

    $claimno = $rowclaim["claimno"];

    $clientname = $rowclaim["clientname"];

    echo "<form method=\"post\" action=\"loggedinaction.php?action=reportedited\" name=\"reportform\">



		



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">



var cal1 = new CalendarPopup();



cal1.setReturnFunction(\"setMultipleValues2\");



function setMultipleValues2(y,m,d) {



document.reportform.reportyear.value=y;



document.reportform.reportmonth.value=LZ(m);



document.reportform.reportday.value=LZ(d);



}



</SCRIPT>								



		



				  <p>Make the desired changes on the Report for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table>



						<tr>



							<td>Date:</td>



							<td><input name=\"reportday\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"" .
        $date[2] . "\"> / <input name=\"reportmonth\" type=\"text\" style=\"width:25px;\" maxlength=\"50\" value=\"" .
        $date[1] . "\"> / <input name=\"reportyear\" type=\"text\" style=\"width:40px;\" maxlength=\"50\" value=\"" .
        $date[0] . "\"> <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" TITLE=\"cal10.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							



							Hour: 	<select name=\"hours\">";

    if ($time[0] == "00")
    {
        echo "<option value=\"00\" selected>0</option>";
    }
    else
    {
        echo "<option value=\"00\">0</option>";
    }

    if ($time[0] == "01")
    {
        echo "<option value=\"01\" selected>1</option>";
    }
    else
    {
        echo "<option value=\"01\">1</option>";
    }

    if ($time[0] == "02")
    {
        echo "<option value=\"02\" selected>2</option>";
    }
    else
    {
        echo "<option value=\"02\">2</option>";
    }

    if ($time[0] == "03")
    {
        echo "<option value=\"03\" selected>3</option>";
    }
    else
    {
        echo "<option value=\"03\">3</option>";
    }

    if ($time[0] == "04")
    {
        echo "<option value=\"04\" selected>4</option>";
    }
    else
    {
        echo "<option value=\"04\">4</option>";
    }

    if ($time[0] == "05")
    {
        echo "<option value=\"05\" selected>5</option>";
    }
    else
    {
        echo "<option value=\"05\">5</option>";
    }

    if ($time[0] == "06")
    {
        echo "<option value=\"06\" selected>6</option>";
    }
    else
    {
        echo "<option value=\"06\">6</option>";
    }

    if ($time[0] == "07")
    {
        echo "<option value=\"07\" selected>7</option>";
    }
    else
    {
        echo "<option value=\"07\">7</option>";
    }

    if ($time[0] == "08")
    {
        echo "<option value=\"08\" selected>8</option>";
    }
    else
    {
        echo "<option value=\"08\">8</option>";
    }

    if ($time[0] == "09")
    {
        echo "<option value=\"09\" selected>9</option>";
    }
    else
    {
        echo "<option value=\"09\">9</option>";
    }

    if ($time[0] == "10")
    {
        echo "<option value=\"10\" selected>10</option>";
    }
    else
    {
        echo "<option value=\"10\">10</option>";
    }

    if ($time[0] == "11")
    {
        echo "<option value=\"11\" selected>11</option>";
    }
    else
    {
        echo "<option value=\"11\">11</option>";
    }

    if ($time[0] == "12")
    {
        echo "<option value=\"12\" selected>12</option>";
    }
    else
    {
        echo "<option value=\"12\">12</option>";
    }

    if ($time[0] == "13")
    {
        echo "<option value=\"13\" selected>13</option>";
    }
    else
    {
        echo "<option value=\"13\">13</option>";
    }

    if ($time[0] == "13")
    {
        echo "<option value=\"13\" selected>13</option>";
    }
    else
    {
        echo "<option value=\"13\">13</option>";
    }

    if ($time[0] == "14")
    {
        echo "<option value=\"14\" selected>14</option>";
    }
    else
    {
        echo "<option value=\"14\">14</option>";
    }

    if ($time[0] == "15")
    {
        echo "<option value=\"15\" selected>15</option>";
    }
    else
    {
        echo "<option value=\"15\">15</option>";
    }

    if ($time[0] == "16")
    {
        echo "<option value=\"16\" selected>16</option>";
    }
    else
    {
        echo "<option value=\"16\">16</option>";
    }

    if ($time[0] == "17")
    {
        echo "<option value=\"17\" selected>17</option>";
    }
    else
    {
        echo "<option value=\"17\">17</option>";
    }

    if ($time[0] == "18")
    {
        echo "<option value=\"18\" selected>18</option>";
    }
    else
    {
        echo "<option value=\"18\">18</option>";
    }

    if ($time[0] == "19")
    {
        echo "<option value=\"19\" selected>19</option>";
    }
    else
    {
        echo "<option value=\"19\">19</option>";
    }

    if ($time[0] == "20")
    {
        echo "<option value=\"20\" selected>20</option>";
    }
    else
    {
        echo "<option value=\"20\">20</option>";
    }

    if ($time[0] == "21")
    {
        echo "<option value=\"21\" selected>21</option>";
    }
    else
    {
        echo "<option value=\"21\">21</option>";
    }

    if ($time[0] == "22")
    {
        echo "<option value=\"22\" selected>22</option>";
    }
    else
    {
        echo "<option value=\"22\">22</option>";
    }

    if ($time[0] == "23")
    {
        echo "<option value=\"23\" selected>23</option>";
    }
    else
    {
        echo "<option value=\"23\">23</option>";
    }

    echo "</select> Minutes: <select name=\"minutes\">";

    if ($time[1] == "00")
    {
        echo "<option value=\"00\" selected>00</option>";
    }
    else
    {
        echo "<option value=\"00\">00</option>";
    }

    if ($time[1] == "01")
    {
        echo "<option value=\"01\" selected>01</option>";
    }
    else
    {
        echo "<option value=\"01\">01</option>";
    }

    if ($time[1] == "02")
    {
        echo "<option value=\"02\" selected>02</option>";
    }
    else
    {
        echo "<option value=\"02\">02</option>";
    }

    if ($time[1] == "03")
    {
        echo "<option value=\"03\" selected>03</option>";
    }
    else
    {
        echo "<option value=\"03\">03</option>";
    }

    if ($time[1] == "04")
    {
        echo "<option value=\"04\" selected>04</option>";
    }
    else
    {
        echo "<option value=\"04\">04</option>";
    }

    if ($time[1] == "05")
    {
        echo "<option value=\"05\" selected>05</option>";
    }
    else
    {
        echo "<option value=\"05\">05</option>";
    }

    if ($time[1] == "06")
    {
        echo "<option value=\"06\" selected>06</option>";
    }
    else
    {
        echo "<option value=\"06\">06</option>";
    }

    if ($time[1] == "07")
    {
        echo "<option value=\"07\" selected>07</option>";
    }
    else
    {
        echo "<option value=\"07\">07</option>";
    }

    if ($time[1] == "08")
    {
        echo "<option value=\"08\" selected>08</option>";
    }
    else
    {
        echo "<option value=\"08\">08</option>";
    }

    if ($time[1] == "09")
    {
        echo "<option value=\"09\" selected>09</option>";
    }
    else
    {
        echo "<option value=\"09\">09</option>";
    }

    if ($time[1] == "10")
    {
        echo "<option value=\"10\" selected>10</option>";
    }
    else
    {
        echo "<option value=\"10\">10</option>";
    }

    if ($time[1] == "11")
    {
        echo "<option value=\"11\" selected>11</option>";
    }
    else
    {
        echo "<option value=\"11\">11</option>";
    }

    if ($time[1] == "12")
    {
        echo "<option value=\"12\" selected>12</option>";
    }
    else
    {
        echo "<option value=\"12\">12</option>";
    }

    if ($time[1] == "13")
    {
        echo "<option value=\"13\" selected>13</option>";
    }
    else
    {
        echo "<option value=\"13\">13</option>";
    }

    if ($time[1] == "14")
    {
        echo "<option value=\"14\" selected>14</option>";
    }
    else
    {
        echo "<option value=\"14\">14</option>";
    }

    if ($time[1] == "15")
    {
        echo "<option value=\"15\" selected>15</option>";
    }
    else
    {
        echo "<option value=\"15\">15</option>";
    }

    if ($time[1] == "16")
    {
        echo "<option value=\"16\" selected>16</option>";
    }
    else
    {
        echo "<option value=\"16\">16</option>";
    }

    if ($time[1] == "17")
    {
        echo "<option value=\"17\" selected>17</option>";
    }
    else
    {
        echo "<option value=\"17\">17</option>";
    }

    if ($time[1] == "18")
    {
        echo "<option value=\"18\" selected>18</option>";
    }
    else
    {
        echo "<option value=\"18\">18</option>";
    }

    if ($time[1] == "19")
    {
        echo "<option value=\"19\" selected>19</option>";
    }
    else
    {
        echo "<option value=\"19\">19</option>";
    }

    if ($time[1] == "20")
    {
        echo "<option value=\"20\" selected>20</option>";
    }
    else
    {
        echo "<option value=\"20\">20</option>";
    }

    if ($time[1] == "21")
    {
        echo "<option value=\"21\" selected>21</option>";
    }
    else
    {
        echo "<option value=\"21\">21</option>";
    }

    if ($time[1] == "22")
    {
        echo "<option value=\"22\" selected>22</option>";
    }
    else
    {
        echo "<option value=\"22\">22</option>";
    }

    if ($time[1] == "23")
    {
        echo "<option value=\"23\" selected>23</option>";
    }
    else
    {
        echo "<option value=\"23\">23</option>";
    }

    if ($time[1] == "24")
    {
        echo "<option value=\"24\" selected>24</option>";
    }
    else
    {
        echo "<option value=\"24\">24</option>";
    }

    if ($time[1] == "25")
    {
        echo "<option value=\"25\" selected>25</option>";
    }
    else
    {
        echo "<option value=\"25\">25</option>";
    }

    if ($time[1] == "26")
    {
        echo "<option value=\"26\" selected>26</option>";
    }
    else
    {
        echo "<option value=\"26\">26</option>";
    }

    if ($time[1] == "27")
    {
        echo "<option value=\"27\" selected>27</option>";
    }
    else
    {
        echo "<option value=\"27\">27</option>";
    }

    if ($time[1] == "28")
    {
        echo "<option value=\"28\" selected>28</option>";
    }
    else
    {
        echo "<option value=\"28\">28</option>";
    }

    if ($time[1] == "29")
    {
        echo "<option value=\"29\" selected>29</option>";
    }
    else
    {
        echo "<option value=\"29\">29</option>";
    }

    if ($time[1] == "30")
    {
        echo "<option value=\"30\" selected>30</option>";
    }
    else
    {
        echo "<option value=\"30\">30</option>";
    }

    if ($time[1] == "31")
    {
        echo "<option value=\"31\" selected>31</option>";
    }
    else
    {
        echo "<option value=\"31\">31</option>";
    }

    if ($time[1] == "32")
    {
        echo "<option value=\"32\" selected>32</option>";
    }
    else
    {
        echo "<option value=\"32\">32</option>";
    }

    if ($time[1] == "33")
    {
        echo "<option value=\"33\" selected>33</option>";
    }
    else
    {
        echo "<option value=\"33\">33</option>";
    }

    if ($time[1] == "34")
    {
        echo "<option value=\"34\" selected>34</option>";
    }
    else
    {
        echo "<option value=\"34\">34</option>";
    }

    if ($time[1] == "35")
    {
        echo "<option value=\"35\" selected>35</option>";
    }
    else
    {
        echo "<option value=\"35\">35</option>";
    }

    if ($time[1] == "36")
    {
        echo "<option value=\"36\" selected>36</option>";
    }
    else
    {
        echo "<option value=\"36\">36</option>";
    }

    if ($time[1] == "37")
    {
        echo "<option value=\"37\" selected>37</option>";
    }
    else
    {
        echo "<option value=\"37\">37</option>";
    }

    if ($time[1] == "38")
    {
        echo "<option value=\"38\" selected>38</option>";
    }
    else
    {
        echo "<option value=\"38\">38</option>";
    }

    if ($time[1] == "39")
    {
        echo "<option value=\"39\" selected>39</option>";
    }
    else
    {
        echo "<option value=\"39\">39</option>";
    }

    if ($time[1] == "40")
    {
        echo "<option value=\"40\" selected>40</option>";
    }
    else
    {
        echo "<option value=\"40\">40</option>";
    }

    if ($time[1] == "41")
    {
        echo "<option value=\"41\" selected>41</option>";
    }
    else
    {
        echo "<option value=\"41\">41</option>";
    }

    if ($time[1] == "42")
    {
        echo "<option value=\"42\" selected>42</option>";
    }
    else
    {
        echo "<option value=\"42\">42</option>";
    }

    if ($time[1] == "43")
    {
        echo "<option value=\"43\" selected>43</option>";
    }
    else
    {
        echo "<option value=\"43\">43</option>";
    }

    if ($time[1] == "44")
    {
        echo "<option value=\"44\" selected>44</option>";
    }
    else
    {
        echo "<option value=\"44\">44</option>";
    }

    if ($time[1] == "45")
    {
        echo "<option value=\"45\" selected>45</option>";
    }
    else
    {
        echo "<option value=\"45\">45</option>";
    }

    if ($time[1] == "46")
    {
        echo "<option value=\"46\" selected>46</option>";
    }
    else
    {
        echo "<option value=\"46\">46</option>";
    }

    if ($time[1] == "47")
    {
        echo "<option value=\"47\" selected>47</option>";
    }
    else
    {
        echo "<option value=\"47\">47</option>";
    }

    if ($time[1] == "48")
    {
        echo "<option value=\"48\" selected>48</option>";
    }
    else
    {
        echo "<option value=\"48\">48</option>";
    }

    if ($time[1] == "49")
    {
        echo "<option value=\"49\" selected>49</option>";
    }
    else
    {
        echo "<option value=\"49\">49</option>";
    }

    if ($time[1] == "50")
    {
        echo "<option value=\"50\" selected>50</option>";
    }
    else
    {
        echo "<option value=\"50\">50</option>";
    }

    if ($time[1] == "51")
    {
        echo "<option value=\"51\" selected>51</option>";
    }
    else
    {
        echo "<option value=\"51\">51</option>";
    }

    if ($time[1] == "52")
    {
        echo "<option value=\"52\" selected>52</option>";
    }
    else
    {
        echo "<option value=\"52\">52</option>";
    }

    if ($time[1] == "53")
    {
        echo "<option value=\"53\" selected>53</option>";
    }
    else
    {
        echo "<option value=\"53\">53</option>";
    }

    if ($time[1] == "54")
    {
        echo "<option value=\"54\" selected>54</option>";
    }
    else
    {
        echo "<option value=\"54\">54</option>";
    }

    if ($time[1] == "55")
    {
        echo "<option value=\"55\" selected>55</option>";
    }
    else
    {
        echo "<option value=\"55\">55</option>";
    }

    if ($time[1] == "56")
    {
        echo "<option value=\"56\" selected>56</option>";
    }
    else
    {
        echo "<option value=\"56\">56</option>";
    }

    if ($time[1] == "57")
    {
        echo "<option value=\"57\" selected>57</option>";
    }
    else
    {
        echo "<option value=\"57\">57</option>";
    }

    if ($time[1] == "58")
    {
        echo "<option value=\"58\" selected>58</option>";
    }
    else
    {
        echo "<option value=\"58\">58</option>";
    }

    if ($time[1] == "59")
    {
        echo "<option value=\"59\" selected>59</option>";
    }
    else
    {
        echo "<option value=\"59\">59</option>";
    }

    echo "										</select>



							



							</td>



						</tr>



						<tr>



							<td>Description:</td>



							<td><input type=\"text\" name=\"description\" maxlength=\"255\" value=\"$description\"></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\"> <input type=\"hidden\" name=\"reportid\" value=\"$reportid\">



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ReportEdited($reportid, $loggedinuserid)
{

    require ('connection.php');

    $qry = "select * from report where id = $reportid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $reportday = $_REQUEST["reportday"];

    $reportmonth = $_REQUEST["reportmonth"];

    $reportyear = $_REQUEST["reportyear"];

    $reporthours = $_REQUEST["hours"];

    $reportminutes = $_REQUEST["minutes"];

    $reportdate = $reportyear . "-" . $reportmonth . "-" . $reportday . " " . $reporthours .
        ":" . $reportminutes . ":00";

    $description = $_REQUEST["description"];

    $claimid = $_REQUEST["claimid"];

    if (strlen($reportdate) == 0)
    {

        $qry = "update report set `reportdate` = NOW(), `description` = '$description', `userid` = $loggedinuserid where `id` = $reportid";

    }

    else
    {

        $qry = "update report set `reportdate` = '$reportdate', `description` = '$description', `userid` = $loggedinuserid where `id` = $reportid";

    }

    $qryresults = mysql_query($qry, $db);

    echo "<p>The report has been saved successfully.</p>";

    EditClaim($claimid, 4);

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ConfirmDeleteReport($reportid, $key)
{

    require ('connection.php');

    //include('functions.php');

    $qry = "select * from report where id = $reportid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $reportdate = $row["reportdate"];

    $description = $row["description"];

    $claimid = $row["claimid"];

    //$key = get_rand_id(32);

    $qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deletereport')";

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>Are you sure you want to delete the report of <strong>$reportdate</strong> with description <strong>$description</strong>?<br> <a href=\"loggedinaction.php?action=deletereport&amp;reportid=$reportid&amp;claimid=$claimid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function DeleteReport($reportid, $claimid, $key)
{

    require ('connection.php');

    $qry = "select * from `key` where `action` = 'deletereport' and `key` = '$key'";

    $qryresults = mysql_query($qry, $db);

    $keyrow = mysql_fetch_array($qryresults);

    $keyid = $keyrow["id"];

    $count = mysql_num_rows($qryresults);

    if ($count == 1)
    {

        $qrydelete = "delete from `key` where `id` = $keyid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from report where `id` = $reportid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        echo "<p>The report have been deleted successfully.</p>";

        EditClaim($claimid, 4);

    }

    else
    {

        echo "<p>It wont work if you enter the url just like that to delete a report... </p>";

        EditClaim($claimid, 4);

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function SaveStep($claimid, $step, $ccloggedin, $ccid)
{
    require ('connection.php');

    if ($step == 1)
    {

        $clientname = $_REQUEST["clientname"];
        $clientno = $_REQUEST["clientno"];
        $claimno = $_REQUEST["claimno"];
        $clientcontactno1 = $_REQUEST["clientcontactno1"];
        $clientcontactno2 = $_REQUEST["clientcontactno2"];
		$clientemail	= $_REQUEST["clientemail"];

        $panelbeaterid = $_REQUEST["panelbeater"];
        $vehiclemakemodel = $_REQUEST["vehiclemakemodel"];
        $vehicleyear = $_REQUEST["vehicleyear"];
        $vehicleregistrationno = $_REQUEST["vehicleregistrationno"];
        $vehicletype = $_REQUEST["vehicletype"];
		$vehiclevin = $_REQUEST["vehiclevin"];

        $administratorid = $_REQUEST["administrator"];

        $quoteno = $_REQUEST["quoteno"];
        $insurerid = $_REQUEST["insurerid"];

		$brokerid = $_REQUEST["brokerid"];

        $claimsclerkid = $_REQUEST["claimsclerk"];

        if ($ccloggedin == "yes")
        {
            $claimsclerkid = $ccid;
        }

        $authamount = $_REQUEST["authamount"] * 1;
        $excess = $_REQUEST["excess"] * 1 ;

		$excess_description = $_REQUEST["excess_description"];

        $betterment = $_REQUEST["betterment"] * 1;
        $quoteamount = $_REQUEST["quoteamount"] * 1;

        $assessorid = $_REQUEST["assessor"];

        $clientno = $_REQUEST["clientno"];

		$vehiclemakeid = $_REQUEST['vehiclemake'];

		$receivedDate = $_REQUEST["receivedyear"] . '-' . $_REQUEST["receivedmonth"] . '-' . $_REQUEST["receivedday"];

		$assessor_area_id = $_POST['area'];

		// update dates table

		mysql_query(" UPDATE `dates` SET received='$receivedDate' WHERE claimid='$claimid' ");

        $qryupdate = " UPDATE `claim` 
						SET `clientname` = '$clientname',
					   `claimno` = '$claimno',
					   `clientcontactno` = '$clientcontactno1',
					   `clientcontactno2` = '$clientcontactno2',
					   `clientemail` = '$clientemail',
					   `panelbeaterid` = '$panelbeaterid',
					   `makemodel` = '$vehiclemakemodel',
					   `vehiclemakeid` = '$vehiclemakeid',
					   `vehicleyear` = '$vehicleyear',
					   `vehicleregistrationno` = '$vehicleregistrationno',
					   `vehicletype` = '$vehicletype',
					   `vehiclevin` = '$vehiclevin',
					   `administratorid` = '$administratorid',
					   `quoteno` = '$quoteno',
					   `insurerid` = '$insurerid',
					   `brokerid` = '$brokerid',
					   `clientno` = '$clientno',
					   `claimsclerkid` = '$claimsclerkid',
					   `authamount` = '$authamount',
					   `excess` = '$excess',
					   `excess_description` = '" . mysql_real_escape_string($excess_description) . "',
					   `betterment` = '$betterment',
					   `quoteamount` = '$quoteamount',
					   `assessor_area_id` = '$assessor_area_id',
					   `assessorid` = '$assessorid'
					   WHERE `id` = '$claimid' ";


        $qryupdateresults = mysql_query($qryupdate, $db);

        $pbname = addslashes($_REQUEST["pbname"]);
        $pbowner = addslashes($_REQUEST["pbowner"]);
		$pbownercel = addslashes($_REQUEST["pbownercel"]);
		
        $pbcostingclerk = addslashes($_REQUEST["pbcostingclerk"]);
		$pbcostingclerkcel = addslashes($_REQUEST["pbcostingclerkcel"]);
		
        $pbcontactperson = addslashes($_REQUEST["pbcontactperson"]);

		$pbworkshopmanager = addslashes($_REQUEST["pbworkshopmanager"]);
		$pbworkshopmanagercel = addslashes($_REQUEST["pbworkshopmanagercel"]);
		
		$pbestimator = addslashes($_REQUEST["pbestimator"]);
		$pbestimatorcel = addslashes($_REQUEST["pbestimatorcel"]);
		
		$pbpartsmanager = addslashes($_REQUEST["pbpartsmanager"]);
		$pbpartsmanagercel = addslashes($_REQUEST["pbpartsmanagercel"]);
		
		$pbdms = addslashes($_REQUEST["pbdms"]);
		$pbmember = addslashes($_REQUEST["pbmember"]);
		$pbfactoring = addslashes($_REQUEST["pbfactoring"]);
		$pbsize = addslashes($_REQUEST["pbsize"]);


        $pbcontactnumber = addslashes($_REQUEST["pbcontactnumber"]);
		$pbcontactnumber2 = addslashes($_REQUEST["pbcontactnumber2"]);

        $pbfaxno = addslashes($_REQUEST["pbfaxno"]);

        $pbemail = addslashes($_REQUEST["pbemail"]);
        $pbadr1 = addslashes($_REQUEST["pbadr1"]);

        $pbadr2 = addslashes($_REQUEST["pbadr2"]);

        $pbadr3 = addslashes($_REQUEST["pbadr3"]);

        $pbadr4 = addslashes($_REQUEST["pbadr4"]);

		$notes = addslashes($_REQUEST["notes"]);
		
		$pbowneremail = addslashes($_REQUEST["pbowneremail"]);
		$pbownercel = addslashes($_REQUEST["pbownercel"]);
		
		$pbcostingclerkemail = addslashes($_REQUEST["pbcostingclerkemail"]);
		$pbcostingclerkecel = addslashes($_REQUEST["pbcostingclerkcel"]);
		
		$pbworkshopmanageremail = addslashes($_REQUEST["pbworkshopmanageremail"]);
		$pbworkshopmanagercel = addslashes($_REQUEST["pbworkshopmanagercel"]);

		$pbestimator = addslashes($_REQUEST["pbestimator"]);
		$pbestimatorcel = addslashes($_REQUEST["pbestimatorcel"]);
		$pbestimatoremail = addslashes($_REQUEST["pbestimatoremail"]);
		
		$pbpartsmanager = addslashes($_REQUEST["pbpartsmanager"]);
		$pbpartsmanagercel = addslashes($_REQUEST["pbpartsmanagercel"]);
		$pbpartsmanageremail = addslashes($_REQUEST["pbpartsmanageremail"]);
		
		$pbdms = addslashes($_REQUEST["pbdms"]);
		$pbmember = addslashes($_REQUEST["pbmember"]);
		$pbfactoring = addslashes($_REQUEST["pbfactoring"]);
		$pbsize = addslashes($_REQUEST["pbsize"]);
		
		$latitude = addslashes($_REQUEST["latitude"]);
		$longitude = addslashes($_REQUEST["longitude"]);


        $qryupdatepb = "update panelbeaters set `name` = '$pbname',



													  `owner` = '$pbowner',



													  `costingclerk` = '$pbcostingclerk',
													  `costingclerkcel` = '$pbcostingclerkcel',



													  `contactperson` = '$pbcontactperson',

													  `workshopmanager` = '$pbworkshopmanager',
													  `workshopmanagercel` = '$pbworkshopmanagercel',
													  
													  `estimator` = '$pbestimator',
													  `estimatorcel` = '$pbestimatorcel',
													  
													  `partsmanager` = '$pbpartsmanager',
													  `partsmanagercel` = '$pbpartsmanagercel',
													  
													  `dms` = '$pbdms',
													  `member` = '$pbmember',
													  
													  
													

													  `adr1` = '$pbadr1',

													  `adr2` = '$pbadr2',

													  `adr3` = '$pbadr3',

													  `adr4` = '$pbadr4',

													  `notes` = '$notes',


													  `contactno` = '$pbcontactnumber',

													  `contactno2` = '$pbcontactnumber2',

													  `faxno`= '$pbfaxno',
													  
													  `owneremail`= '$pbowneremail',
													  `ownercel`= '$pbownercel',
													  
													  `costingclerkemail`= '$pbcostingclerkemail',
													  `costingclerkcel`= '$pbcostingclerkcel',
													  
													  `workshopmanageremail`= '$pbworkshopmanageremail',
													  `workshopmanagercel`= '$pbworkshopmanagercel',


													  `estimator`= '$pbestimator',
													  `estimatorcel`= '$pbestimatorcel',
													  `estimatoremail`= '$pbestimatoremail',
													  
													  `partsmanager`= '$pbpartsmanager',
													  `partsmanagercel`= '$pbpartsmanagercel',
													  `partsmanageremail`= '$pbpartsmanageremail',
													  
													   `dms`= '$pbdms',
													   `member`= '$pbmember',
													   `factoring` = '$pbfactoring',
													   `size` = '$pbsize',
													   
													  `latitude`= '$latitude',
													  `longitude`= '$longitude',

													  `email` = '$pbemail' where `id` = $panelbeaterid";

        $qryupdatepbresults = mysql_query($qryupdatepb, $db);

    }

    else if ($step == 3)
    {

        $qrycheck = "select count(claimid) as counted from dates where claimid = $claimid";

        $qrycheckresults = mysql_query($qrycheck, $db);

        $therow = mysql_fetch_array($qrycheckresults);

        $count = $therow["counted"];

        $received = $_REQUEST["receivedyear"] . "-" . $_REQUEST["receivedmonth"] . "-" .
            $_REQUEST["receivedday"];

        $loss = $_REQUEST["lossyear"] . "-" . $_REQUEST["lossmonth"] . "-" . $_REQUEST["lossday"];

        $assappointed = $_REQUEST["assappointedyear"] . "-" . $_REQUEST["assappointedmonth"] .
            "-" . $_REQUEST["assappointedday"];

        $assessment = $_REQUEST["assessmentyear"] . "-" . $_REQUEST["assessmentmonth"] .
            "-" . $_REQUEST["assessmentday"];

        $assessmentreport = $_REQUEST["assessmentreportyear"] . "-" . $_REQUEST["assessmentreportmonth"] .
            "-" . $_REQUEST["assessmentreportday"];

        $assessmentinvtoinsurer = $_REQUEST["assessmentinvtoinsureryear"] . "-" . $_REQUEST["assessmentinvtoinsurermonth"] .
            "-" . $_REQUEST["assessmentinvtoinsurerday"];

        $auth = $_REQUEST["authyear"] . "-" . $_REQUEST["authmonth"] . "-" . $_REQUEST["authday"];

        $wp = $_REQUEST["wpyear"] . "-" . $_REQUEST["wpmonth"] . "-" . $_REQUEST["wpday"];

        $docreq = $_REQUEST["docreqyear"] . "-" . $_REQUEST["docreqmonth"] . "-" . $_REQUEST["docreqday"];

        $workinprogressinsp = $_REQUEST["workinprogressinspyear"] . "-" . $_REQUEST["workinprogressinspmonth"] .
            "-" . $_REQUEST["workinprogressinspday"];

        $dod = $_REQUEST["dodyear"] . "-" . $_REQUEST["dodmonth"] . "-" . $_REQUEST["dodday"];

        $finalcosting = $_REQUEST["finalcostingyear"] . "-" . $_REQUEST["finalcostingmonth"] .
            "-" . $_REQUEST["finalcostingday"];

        $acirepsentinsurer = $_REQUEST["acirepsentinsureryear"] . "-" . $_REQUEST["acirepsentinsurermonth"] .
            "-" . $_REQUEST["acirepsentinsurerday"];

        $invoicesent = $_REQUEST["invoicesentyear"] . "-" . $_REQUEST["invoicesentmonth"] .
            "-" . $_REQUEST["invoicesentday"];

        $assfeereceivedfrominsurer = $_REQUEST["assfeereceivedfrominsureryear"] . "-" .
            $_REQUEST["assfeereceivedfrominsurermonth"] . "-" . $_REQUEST["assfeereceivedfrominsurerday"];

        $acipaymentreceived = $_REQUEST["acipaymentreceivedyear"] . "-" . $_REQUEST["acipaymentreceivedmonth"] .
            "-" . $_REQUEST["acipaymentreceivedday"];

        /*"workinprogressinspday\"> -



        <input type=\"text\" style=\"width:20px;\" value=\"" . $workinprogressinsp[1] . "\" name=\"workinprogressinspmonth\"> -



        <input type=\"text\" style=\"width:35px;\" value=\"" . $workinprogressinsp[0] . "\" name=\"workinprogressinspyear\">*/

        if ($count == 0)
        {

            $qryinsert = "insert into dates (`claimid`, `received`, `loss`, `assappointed`, `assessment`, `assessmentreport`, 



												 `assessmentinvtoinsurer`, `auth`, `wp`, `docreq`, `workinprogressinsp`, `dod`, `finalcosting`,



												 `acirepsentinsurer`, `invoicesent`, `assfeereceivedfrominsurer`, `acipaymentreceived`)



										 values ($claimid, '$received', '$loss', '$assappointed', '$assessment', '$assessmentreport',



										         '$assessmentinvtoinsurer', '$auth', '$wp', '$docreq', '$workinprogressinsp', '$dod', '$finalcosting',



												 '$acirepsentinsurer', '$invoicesent', '$assfeereceivedfrominsurer', '$acipaymentreceived')";

            $qryinsertresults = mysql_query($qryinsert, $db);

            //echo $qryinsert;

        }

        else
        {

            $qryupdate = "update dates set `received` = '$received',



				                               `loss` = '$loss',



											   `assappointed` = '$assappointed',



											   `assessment` = '$assessment',



											   `assessmentreport` = '$assessmentreport',



											   `assessmentinvtoinsurer` = '$assessmentinvtoinsurer',



											   `auth` = '$auth', 



											   `wp` = '$wp',



											   `docreq` = '$docreq',



											   `workinprogressinsp` = '$workinprogressinsp',



											   `dod` = '$dod',



											   `finalcosting` = '$finalcosting',



											   `acirepsentinsurer` = '$acirepsentinsurer',



											   `invoicesent` = '$invoicesent',



											   `assfeereceivedfrominsurer` = '$assfeereceivedfrominsurer',



											   `acipaymentreceived` = '$acipaymentreceived' 



										 where `claimid` = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);

            //echo $qryupdate;

        }

    }

    else if ($step == 5)
    {

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        //$received = "2007-05-21";

        $received = explode("-", $received);

        $loss = $datesrow["loss"];

        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];

        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];

        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];

        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];

        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $auth = explode("-", $auth);

        $wp = $datesrow["wp"];

        $wp = explode("-", $wp);

        $docreq = $datesrow["docreq"];

        $docreq = explode("-", $docreq);

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $workinprogressinsp = explode("-", $workinprogressinsp);

        $dod = $datesrow["dod"];

        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $finalcosting = explode("-", $finalcosting);

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $acirepsentinsurer = explode("-", $acirepsentinsurer);

        $invoicesent = $datesrow["invoicesent"];

        $invoicesent = explode("-", $invoicesent);

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];

        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];

        $acipaymentreceived = explode("-", $acipaymentreceived);
		
		$calanderFields = array();

        if ($received[0] == "0000")
        {

            $received2 = $_REQUEST["receivedyear"] . "-" . $_REQUEST["receivedmonth"] . "-" .
                $_REQUEST["receivedday"];

        }

        else
        {

            $received2 = $datesrow["received"];

        }

        if ($loss[0] == "0000")
        {

            $loss2 = $_REQUEST["lossyear"] . "-" . $_REQUEST["lossmonth"] . "-" . $_REQUEST["lossday"];

        }

        else
        {

            $loss2 = $datesrow["loss"];

        }

        if ($assappointed[0] == "0000")
        {

            $assappointed2 = $_REQUEST["assappointedyear"] . "-" . $_REQUEST["assappointedmonth"] .
                "-" . $_REQUEST["assappointedday"];

        }

        else
        {

            $assappointed2 = $datesrow["assappointed"];

        }

        if ($assessment[0] == "0000")
        {

            $assessment2 = $_REQUEST["assessmentyear"] . "-" . $_REQUEST["assessmentmonth"] .
                "-" . $_REQUEST["assessmentday"];

        }

        else
        {

            $assessment2 = $datesrow["assessment"];

        }

        if ($assessmentreport[0] == "0000")
        {

            $assessmentreport2 = $_REQUEST["assessmentreportyear"] . "-" . $_REQUEST["assessmentreportmonth"] .
                "-" . $_REQUEST["assessmentreportday"];

        }

        else
        {

            $assessmentreport2 = $datesrow["assessmentreport"];

        }

        if ($assessmentinvtoinsurer[0] == "0000")
        {

            $assessmentinvtoinsurer2 = $_REQUEST["assessmentinvtoinsureryear"] . "-" . $_REQUEST["assessmentinvtoinsurermonth"] .
                "-" . $_REQUEST["assessmentinvtoinsurerday"];

        }

        else
        {

            $assessmentinvtoinsurer2 = $datesrow["assessmentinvtoinsurer"];

        }

        if ($auth[0] == "0000")
        {

            $auth2 = $_REQUEST["authyear"] . "-" . $_REQUEST["authmonth"] . "-" . $_REQUEST["authday"];

        }

        else
        {

            $auth2 = $datesrow["auth"];

        }

        if ($wp[0] == "0000")
        {

            $wp2 = $_REQUEST["wpyear"] . "-" . $_REQUEST["wpmonth"] . "-" . $_REQUEST["wpday"];

        }

        else
        {

            $wp2 = $datesrow["wp"];

        }

        if ($docreq[0] == "0000")
        {

            $docreq2 = $_REQUEST["docreqyear"] . "-" . $_REQUEST["docreqmonth"] . "-" . $_REQUEST["docreqday"];

        }

        else
        {

            $docreq2 = $datesrow["docreq"];

        }

        if ($workinprogressinsp[0] == "0000")
        {

            $workinprogressinsp2 = $_REQUEST["workinprogressinspyear"] . "-" . $_REQUEST["workinprogressinspmonth"] .
                "-" . $_REQUEST["workinprogressinspday"];

        }

        else
        {

            $workinprogressinsp2 = $datesrow["workinprogressinsp"];

        }

        if ($dod[0] == "0000")
        {

            $dod2 = $_REQUEST["dodyear"] . "-" . $_REQUEST["dodmonth"] . "-" . $_REQUEST["dodday"];

        }

        else
        {

            $dod2 = $datesrow["dod"];

        }

        if ($finalcosting[0] == "0000")
        {

            $finalcosting2 = $_REQUEST["finalcostingyear"] . "-" . $_REQUEST["finalcostingmonth"] .
                "-" . $_REQUEST["finalcostingday"];

        }

        else
        {

            $finalcosting2 = $datesrow["finalcosting"];

        }

        if ($acirepsentinsurer[0] == "0000")
        {

            $acirepsentinsurer2 = $_REQUEST["acirepsentinsureryear"] . "-" . $_REQUEST["acirepsentinsurermonth"] .
                "-" . $_REQUEST["acirepsentinsurerday"];

        }

        else
        {

            $acirepsentinsurer2 = $datesrow["acirepsentinsurer"];

        }

        if ($invoicesent[0] == "0000")
        {

            $invoicesent2 = $_REQUEST["invoicesentyear"] . "-" . $_REQUEST["invoicesentmonth"] .
                "-" . $_REQUEST["invoicesentday"];

        }

        else
        {

            $invoicesent2 = $datesrow["invoicesent"];

        }

        if ($assfeereceivedfrominsurer[0] == "0000")
        {

            $assfeereceivedfrominsurer2 = $_REQUEST["assfeereceivedfrominsureryear"] . "-" .
                $_REQUEST["assfeereceivedfrominsurermonth"] . "-" . $_REQUEST["assfeereceivedfrominsurerday"];

        }

        else
        {

            $assfeereceivedfrominsurer2 = $datesrow["assfeereceivedfrominsurer"];

        }

        if ($acipaymentreceived[0] == "0000")
        {

            $acipaymentreceived2 = $_REQUEST["acipaymentreceivedyear"] . "-" . $_REQUEST["acipaymentreceivedmonth"] .
                "-" . $_REQUEST["acipaymentreceivedday"];

        }

        else
        {

            $acipaymentreceived2 = $datesrow["acipaymentreceived"];

        }

        if ($count == 0)
        {

            $qryupdate = "update dates set `received` = '$received2',



				                               `loss` = '$loss2',



											   `assappointed` = '$assappointed2',



											   `assessment` = '$assessment2',



											   `assessmentreport` = '$assessmentreport2',



											   `assessmentinvtoinsurer` = '$assessmentinvtoinsurer2',



											   `auth` = '$auth2',



											   `wp` = '$wp2',



											   `docreq` = '$docreq2',



											   `workinprogressinsp` = '$workinprogressinsp2',



											   `dod` = '$dod2',



											   `finalcosting` = '$finalcosting2',



											   `acirepsentinsurer` = '$acirepsentinsurer2',



											   `invoicesent` = '$invoicesent2',



											   `assfeereceivedfrominsurer` = '$assfeereceivedfrominsurer2',



											   `acipaymentreceived` = '$acipaymentreceived2' 



										 where `claimid` = $claimid";

            $qryupdateresults = mysql_query($qryupdate, $db);

            //echo $qryupdate;

        }

    }

	if ( isset($_POST['next']) && $_POST['next']=='0' && $_POST['stepto']=='2') {
		$_SESSION['success_message'] = "Claim has been updated!";
		$redirectURL = "loggedinaction.php?action=editclaim&claimid=" . $claimid . "&stepto=" . $_POST['fromstep'];
	?>
		<script type="text/javascript">
			location.href= "<?php echo $redirectURL; ?>";
		</script>
	<?php
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ClaimEdited($claimid, $clientname, $clientno, $claimno, $clientcontactno1,
    $clientcontactno2, $clientemail, $panelbeaterid, $vehiclemakemodel, $vehicleregistrationno, $vehicleyear,
    $vehicletype, $administratorid, $quoteno, $insurerid, $claimsclerkid, $authamount,
    $excess, $betterment, $quoteamount, $assessorid, $pbname, $pbworkshopmanagercel, $pbestimator, $pbestimatorcel, 
	$pbcontactnumber, $pbdms, $pbmember, $pbfactoring, $pbsize, $pbcontactnumber2, $pbfaxno, $pbemail, $pbadr1, $pbadr2, $pbadr3,
    $pbadr4, $notes)
{

    require ('connection.php');

	$excess_description = $_POST['excess_description'];

    $qryupdate = "update claim set `clientname` = '$clientname',



		                               `clientno` = '$clientno',



									   `claimno` = '$claimno',



									   `clientcontactno` = '$clientcontactno1',



									   `clientcontactno2` = '$clientcontactno2',

									   `clientemail` = '$clientemail',



									   `panelbeaterid` = $panelbeaterid,



									   `makemodel` = '$vehiclemakemodel',



									   `vehicleregistrationno` = '$vehicleregistrationno',



									   `vehicleyear` = '$vehicleyear',



									   `vehicletype` = '$vehicletype',



									   `administratorid` = '$administratorid',



									   `quoteno` = '$quoteno',



									   `insurerid` = '$insurerid',



									   `claimsclerkid` = $claimclerkid,



									   `authamount` = '$authamount',



									   `excess` = '$excess',

									   `excess_description` = '$excess_description',



									   `betterment` = '$betterment',



									   `quoteamount` = '$quoteamount',



									   `assessorid` = '$assessorid' where `id` = $claimid;";

    //echo $qryupdate;

    $qryupdateresults = mysql_query($qryupdate, $db);

    $qryupdatepb = "update panelbeaters set `name` = '$pbname',



											  `owner` = '$pbowner',



											  `costingclerk` = '$pbcostingclerk',
											  `costingclerkcel` = '$pbcostingclerkcel',



											  `contactperson` = '$pbcontactperson',

											  `workshopmanager` = '$pbworkshopmanager',
											  `workshopmanagercel` = '$pbworkshopmanagercel',
											  
											  `estimator` = 'pb$pbestimator',
											  `estimatorcel` = '$pbestimatorcel',
											  
											  `partsmanager` = '$pbpartsmanager',
											  `partsmanagercel` = '$pbpartsmanagercel',
											  
											  `dms` = '$pbdms',
											  `member` = '$pbmember',
											  `factoring` = '$pbfactoring',
											  `size` = '$pbsize',



											  `adr1` = '$pbadr1',



											  `adr2` = '$pbadr2',



											  `adr3` = '$pbadr3',



											  `adr4` = '$pbadr4',

											  `notes` = '$notes',



											  `contactno` = '$pbcontactnumber',

											  `contactno2` = '$pbcontactnumber2',



											  `faxno`= '$pbfaxno',



											  `email` = '$pbemail' where `id` = $panelbeaterid";

    //echo "<br><br><br>" . $qryupdatepb;

    $qryupdatepbresults = mysql_query($qryupdatepb, $db);

    echo "<p><strong>The claim was edited successfully.</strong></p>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ConfirmDeleteClaim($claimid, $key)
{

    require ('connection.php');

    //include('functions.php');

    $qry = "select * from claim where id = $claimid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];

    $claimno = $row["claimno"];

    //$key = get_rand_id(32);

    $qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteclaim')";

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>Are you sure you want to delete the claim for <strong>$clientname</strong> with Claim No: <strong>$claimno</strong>? (Take note, all entered information will be deleted PERMANENTLY)<br> <a href=\"loggedinaction.php?action=deleteclaim&amp;claimid=$claimid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function DeleteClaim($claimid, $key)
{

    require ('connection.php');

    $qry = "select * from `key` where `action` = 'deleteclaim' and `key` = '$key'";

    $qryresults = mysql_query($qry, $db);

    $keyrow = mysql_fetch_array($qryresults);

    $keyid = $keyrow["id"];

    $count = mysql_num_rows($qryresults);

    if ($count == 1)
    {

        $qrydelete = "delete from `key` where `id` = $keyid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from items where `claimid` = $claimid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from report where `claimid` = $claimid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from claim where `id` = $claimid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from footer where `id` = $claimid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from dates where `claimid` = $claimid;";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        echo "<p>The claim has been deleted successfully. <a href=\"loggedinaction.php?action=claims&amp;from=1\">Go back to Claims</a></p>";

    }

    else
    {

        echo "<p>It wont work if you enter the url just like that to delete a claim... <a href=\"loggedinaction.php?action=claims&amp;from=1\">Go back to Claims</a></p>";

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function asClaims($from, $admin, $assid)
{

    require ('connection.php');

    //$from = $_REQUEST["from"];

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim where assessorid = $assid order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT * FROM claim where assessorid = $assid order by clientno LIMIT $frm , 30";

    }//end else

    $qrycountclaims = "select * from claim where assessorid = $assid";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims with you as the selected Assessor in the database.</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "<a href=\"asloggedinaction.php?action=claims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<a href=\"asloggedinaction.php?action=claims&amp;from=" .
                    $fromrecord . "\">Page $pagenumber</a> || ";

            }//end for loop

        }//end if

        $pageslinks = substr($pageslinks, 0, -4);

        echo "



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">



						  <tr>



							  <td><strong>Client Number</strong></td>



							  <td><strong>Client Name</strong></td>											



							  <td><strong>Claim Number</strong></td>";

        echo "



							  <td align=\"center\"><strong>Actions</strong></td>



						  </tr>";

        while ($row = mysql_fetch_array($qryclaims))
        {

            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>



						  <td>$clientno</td>



						  <td>$clientname</td>



						  <td>$claimno</td>";

            echo "



						  <td align=\"center\"><a href=\"asloggedinaction.php?action=editclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"View this Claim\" border=\"0\" title=\"View this Claim\"></td></tr>";

        }//end while loop

        echo "</table><br>$pageslinks<br>";

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function asEditClaim($id, $step, $assid)
{

    require ('connection.php');

    $claimid = $id;
    
    $qryclaimdetails = "select * from `claim` where `id` = $claimid";
    $qryclaimdetailsresults = mysql_query($qryclaimdetails, $db);

    $claimdetailsrow = mysql_fetch_array($qryclaimdetailsresults);

    $clientname = stripslashes($claimdetailsrow["clientname"]);
    $clientnumber2 = stripslashes($claimdetailsrow["clientno"]);
    $claimnumber = stripslashes($claimdetailsrow["claimno"]);
	$vehicleregistrationno = stripslashes($claimdetailsrow["vehicleregistrationno"]);

    //echo "WERQWERQ " . $step . " GFBGFDNYETRH";

    $fromstep = $_REQUEST["fromstep"];
    
    if ($fromstep == 3)
    {
		//save the dates that changed
		
		$dateofloss = $_REQUEST["lossyear"] . "-" . $_REQUEST["lossmonth"] . "-" . $_REQUEST["lossday"];
		$assessorappointed = $_REQUEST["assappointedyear"] . "-" . $_REQUEST["assappointedmonth"] . "-" . $_REQUEST["assappointedday"];
		$assessment = $_REQUEST["assessmentyear"] . "-" . $_REQUEST["assessmentmonth"] . "-" . $_REQUEST["assessmentday"];
		$assessmentreport = $_REQUEST["assessmentreportyear"] . "-" . $_REQUEST["assessmentreportmonth"] . "-" . $_REQUEST["assessmentreportday"];
		$assessmentinvtoinsurer = $_REQUEST["assessmentinvtoinsureryear"] . "-" . $_REQUEST["assessmentinvtoinsurermonth"] . "-" . $_REQUEST["assessmentinvtoinsurerday"];
		$dod = $_REQUEST["dodyear"] . "-" . $_REQUEST["dodmonth"] . "-" . $_REQUEST["dodday"];
		$assfeereceivedfrominsurer = $_REQUEST["assfeereceivedfrominsureryear"] . "-" . $_REQUEST["assfeereceivedfrominsurermonth"] . "-" . $_REQUEST["assfeereceivedfrominsurerday"];
		
		$qryupdate = "update dates set `loss` = '$dateofloss',
		                               `assappointed` = '$assessorappointed',
									   `assessment` = '$assessment',
									   `assessmentreport` = '$assessmentreport',
									   `assessmentinvtoinsurer` = '$assessmentinvtoinsurer',
									   `dod` = '$dod',
									   `assfeereceivedfrominsurer` = '$assfeereceivedfrominsurer' where `claimid` = $claimid";
		$qryupdateresults = mysql_query($qryupdate, $db);
	}

    if ($step == 1)
    {

        echo "
		
		<form><input type=\"button\" value=\"Claim Details\" disabled />			



						<input type=\"button\" value=\"Parts\" onClick=\"document.theform.next.value = 1; document.theform.stepto.value = 2;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Dates\" onClick=\"document.theform.stepto.value = 3;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Reports\" onClick=\"document.theform.stepto.value = 4;



																		 document.theform.submit();\" />
																		 
																		 
						<input type=\"button\" value=\"Attachments\" onClick=\"document.theform.stepto.value = 5;



																		 document.theform.submit();\" />


						<input type=\"button\" value=\"Quote\" onClick=\"document.theform.stepto.value = 7;



																		 document.theform.submit();\" />
											 



																		 </form> <p>Client NumberP: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";

        $qry = "select * from claim where id = $claimid";

        $qryresults = mysql_query($qry, $db) or die('error: ' . mysql_error());

        $row = mysql_fetch_array($qryresults);

        $clientname = stripslashes($row["clientname"]);

        $clientno = $row["clientno"];

        $claimno = stripslashes($row["claimno"]);

        $clientcontactno1 = stripslashes($row["clientcontactno"]);

        $clientcontactno2 = stripslashes($row["clientcontactno2"]);
		$clientemail = stripslashes($row["clientemail"]);

        $panelbeaterid = $row["panelbeaterid"];

        $vehiclemakemodel = stripslashes($row["makemodel"]);

        $vehicleyear = stripslashes($row["vehicleyear"]);

        $vehicleregistrationno = stripslashes($row["vehicleregistrationno"]);

        $vehicletype = stripslashes($row["vehicletype"]);

        $administratorid = $row["administratorid"];

        $quoteno = stripslashes($row["quoteno"]);

        $insurerid = $row["insurerid"];

        $claimsclerkid = $row["claimsclerkid"];

        $authamount = $row["authamount"];

        $quoteamount = $row["quoteamount"];

        $excess = $row["excess"];

		$excess_description = $row['excess_description'];

        $betterment = $row["betterment"];

        $assessorid = $row["assessorid"];

        echo "



					<form method=\"post\" action=\"asloggedinaction.php?action=editclaim\" name=\"theform\">



					<table>



					<tr>



						<td>	



							<table bgcolor=\"#E7E7FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"blue-bg\">
							<tr>
								<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Client:</h4>
								
									<div style=\"display:inline-block;\">
										Client NumberQ: <input type=\"text\" value=\"$clientno\" maxlength=\"50\" name=\"clientno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Name: <input type=\"text\" value=\"$clientname\" maxlength=\"50\" name=\"clientname\" />
									</div>

									<div style=\"display:inline-block;\">
										Claim Number: <input type=\"text\" value=\"$claimno\" maxlength=\"50\" name=\"claimno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No: <input type=\"text\" value=\"$clientcontactno1\" maxlength=\"50\" name=\"clientcontactno1\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No 2: <input type=\"text\" value=\"$clientcontactno2\" maxlength=\"50\" name=\"clientcontactno2\" />
									</div>

									<div style=\"display:inline-block;\">
										Email Address: <input type=\"text\" value=\"$clientemail\" maxlength=\"50\" name=\"clientemail\" />
									</div>

								</td>
							</tr>
						</table>



							<br />



							<table bgcolor=\"#D3D3FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>



								<tr>



									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;\">Panelbeater Details:</h4></td>



								</tr>";

        $qrygetpanelbeaterinfo = "select * from panelbeaters where `id` = $panelbeaterid";

        $qrygetpanelbeaterinforesults = mysql_query($qrygetpanelbeaterinfo, $db);

        $selectedpbrow = mysql_fetch_array($qrygetpanelbeaterinforesults);

        $pbname = stripslashes($selectedpbrow["name"]);

        $pbowner = stripslashes($selectedpbrow["owner"]);

        $pbcostingclerk = stripslashes($selectedpbrow["costingclerk"]);
		
		$pbcostingclerkcel = stripslashes($selectedpbrow["costingclerkcel"]);

        $pbcontactperson = stripslashes($selectedpbrow["contactperson"]);

		$pbworkshopmanager = stripslashes($selectedpbrow["workshopmanager"]);

        $pbcontactnumber = stripslashes($selectedpbrow["contactno"]);

		$pbcontactnumber2 = stripslashes($selectedpbrow["contactno2"]);

        $pbfaxno = stripslashes($selectedpbrow["faxno"]);

        $pbemail = stripslashes($selectedpbrow["email"]);

        $pbadr1 = stripslashes($selectedpbrow["adr1"]);

        $pbadr2 = stripslashes($selectedpbrow["adr2"]);

        $pbadr3 = stripslashes($selectedpbrow["adr3"]);

        $pbadr4 = stripslashes($selectedpbrow["adr4"]);

		$notes = stripslashes($selectedpbrow["notes"]);

        echo "



								<tr>



									<td>Panelbeater Name:</td>



									<td><input type=\"text\" value=\"$pbname\" maxlength=\"50\" name=\"pbname\" readonly /></td>



									<td>Owner/Manager:</td>



									<td><input type=\"text\" value=\"$pbowner\" maxlength=\"50\" name=\"pbowner\" readonly /></td>



									<td>Costing Clerk:</td>



									<td><input type=\"text\" value=\"$pbcostingclerk\" maxlength=\"50\" name=\"pbcostingclerk\" readonly /></td>



								</tr>



								<tr>
									
									<td>Workshop Manager:</td>

									<td><input type=\"text\" value=\"$pbworkshopmanager\" maxlength=\"50\" name=\"pbworkshopmanager\"  /></td>


									<td>Contact Person:</td>



									<td><input type=\"text\" value=\"$pbcontactperson\" maxlength=\"50\" name=\"pbcontactperson\" readonly  /></td>



									<td>Contact Number:</td>



									<td><input type=\"text\" value=\"$pbcontactnumber\" maxlength=\"50\" name=\"pbcontactnumber\" readonly  /></td>




								</tr>



								<tr>

									<td>Contact Number 2:</td>



									<td><input type=\"text\" value=\"$pbcontactnumber2\" maxlength=\"50\" name=\"pbcontactnumber2\" readonly  /></td>


									<td>Email Address:</td>



									<td ><input type=\"text\" value=\"$pbemail\" maxlength=\"255\" name=\"pbemail\" readonly  /></td>

									<td>Fax Number:</td>

									<td><input type=\"text\" value=\"$pbfaxno\" maxlength=\"50\" name=\"pbfaxno\" readonly  /></td>


								</tr>



								<tr>



									<td>Address:</td>



									<td><input type=\"text\" value=\"$pbadr1\" maxlength=\"50\" name=\"pbadr1\" readonly  /></td>

									<td rowspan=\"4\" valign='middle'>Notes:</td>
									<td colspan=\"3\" rowspan=\"4\">
										<textarea name=\"notes\" style='width:400px;height:85px;'></textarea>
									</td>



								</tr>



								<tr>



									<td>&nbsp;</td>



									<td><input type=\"text\" value=\"$pbadr2\" maxlength=\"50\" name=\"pbadr2\" readonly  /></td>



								</tr>



								<tr>



									<td>&nbsp;</td>



									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\" readonly  /></td>



								</tr>



								<tr>



									<td>&nbsp;</td>



									<td><input type=\"text\" value=\"$pbadr4\" maxlength=\"50\" name=\"pbadr4\" readonly  /></td>



								</tr>



							</table>



							<br />



							<table bgcolor=\"#BFBFFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>
									<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Vehicle:</h4>
									
										<div style=\"display:inline-block;\">
											Vehicle Type: $vehicletype
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Make/Model: 
											<input type=\"text\" value=\"$vehiclemakemodel\" maxlength=\"50\" name=\"vehiclemakemodel\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Year: 
											<input type=\"text\" value=\"$vehicleyear\" maxlength=\"10\" name=\"vehicleyear\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Registration&nbsp;No: 
											<input type=\"text\" value=\"$vehicleregistrationno\" maxlength=\"50\" name=\"vehicleregistrationno\" />
										</div>

									</td>

								</tr>


							</table>



							<br />



							<table bgcolor=\"#ABABFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>

									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Administrator:</h4>
										
										<select name=\"administrator\" onChange=\"ReloadThisPagePB(0);\">";

										$qrygetadministrators = "select * from administrators order by `name`";

										$qrygetadministratorsresults = mysql_query($qrygetadministrators, $db);

										while ($administratorrow = mysql_fetch_array($qrygetadministratorsresults))
										{

											$adminid = $administratorrow["id"];

											$adminname = $administratorrow["name"];

											if ($administratorid == $adminid)
											{

												echo "<option value=\"$adminid\" selected>$adminname</option>";

											}

											else
											{

												echo "<option value=\"$adminid\">$adminname</option>";

											}

										}

										$qrygetadministratorinfo = "select * from administrators where `id` = $administratorid";

										$qrygetadministratorinforesults = mysql_query($qrygetadministratorinfo, $db);

										$administratorinforow = mysql_fetch_array($qrygetadministratorinforesults);

										$admintelno = stripslashes($administratorinforow["telno"]);

										$adminfaxno = stripslashes($administratorinforow["faxno"]);

										$adminadr1 = stripslashes($administratorinforow["adr1"]);

										$adminadr2 = stripslashes($administratorinforow["adr2"]);

										$adminadr3 = stripslashes($administratorinforow["adr3"]);

										$adminadr4 = stripslashes($administratorinforow["adr4"]);

										$vatno = stripslashes($administratorinforow["vatno"]);

										echo "					</select>


										Insurance Company:

										<select name=\"insurerid\"><option value=\"0\">Select one</option>";

										$qryinsurers = "select * from `insurers` order by `name`";

										$qryinsurersresults = mysql_query($qryinsurers, $db);

										while ($insrow = mysql_fetch_array($qryinsurersresults))
										{

											$insid = $insrow["id"];

											$insurancecompname = stripslashes($insrow["name"]);

											if ($insid == $insurerid)
											{

												echo "<option value=\"$insid\" selected>$insurancecompname</option>";

											}

											else
											{

												echo "<option value=\"$insid\">$insurancecompname</option>";

											}

										}

										echo " </select>
									
									</td>

								</tr>

								<tr>
									<td colspan=\"5\">
										Tel: $admintelno, 
										Fax: $adminfaxno,
										P.O.Box: $adminadr1, $adminadr2, $adminadr3 ";
										
									if ( !empty($adminadr4) ) { echo $adminadr4; }
								echo "
									</td>
								</tr>
								
								<tr>
									<td colspan=\"5\">
										Claim Technician: 

										<select name=\"claimsclerk\" id=\"claimsclerk\">";

										$qryclaimsclerks = "select * from claimsclerks order by `name`";

										$qryclaimsclerksresults = mysql_query($qryclaimsclerks, $db);
										
										$defaultEmail = '';
										$counter = 0;
										while ($ccrow = mysql_fetch_array($qryclaimsclerksresults))
										{

											$ccid = $ccrow["id"];

											$ccname = stripslashes($ccrow["name"]);
											$ccemailid = stripslashes($ccrow["email"]);

											if ($counter==0) {
												$defaultEmail = $ccemailid;
											}

											if ($claimsclerkid == $ccid)
											{
												$defaultEmail = $ccemailid;

												echo "<option value=\"$ccid\" selected email=\"$ccemailid\" >$ccname</option>";

											}

											else
											{

												echo "<option value=\"$ccid\" email=\"$ccemailid\">$ccname</option>";

											}

											$counter++;

										}

										echo " </select>

										<a href=\"mailto:$defaultEmail?subject=$emailSubject\"  type=\"Claim\" claimId=\"$claimid\" class=\"send-email\" emailpart=\"subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" id=\"claimTechnicianEmailLink\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
										";
				
										global $admin;
										if ( $admin == 1 ) { echo " VAT&nbsp;Number: $vatno "; }

										echo "
									</td>
								</tr>
							</table>



							<br />



							<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\"  class='blue-bg'>
							<tr>
								<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Claim:</h4>										
									<div style=\"display:inline-block;\">
										Quote&nbsp;Number: <input type=\"text\" value=\"$quoteno\" maxlength=\"50\" name=\"quoteno\" />
									</div>
									<div style=\"display:inline-block;\">
										Authorised&nbsp;Amount: <input type=\"text\" value=\"$authamount\" maxlength=\"11\" name=\"authamount\" />
									</div>
									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess\" maxlength=\"11\" name=\"excess\" />
									</div>
									<div style=\"display:inline-block;\">
										Excess Description: <input type=\"text\" value=\"$excess_description\" style='width:300px;' name=\"excess_description\" />
									</div>
									<div style=\"display:inline-block;\">
										Betterment: <input type=\"text\" value=\"$betterment\" maxlength=\"11\" name=\"betterment\" />
									</div>
									<div style=\"display:inline-block;\">
										Quoted&nbsp;Amount: <input type=\"text\" value=\"$quoteamount\" maxlength=\"11\" name=\"quoteamount\" />
									</div>
									";
								
									$res = mysql_query("SELECT `received` FROM `dates` WHERE claimid='$claimid' ", $db);

									$daterow = mysql_fetch_array($res);

									$received = explode('-', $daterow['received']);

									echo "
										<div style=\"display:inline-block;\">
											Date Received:

											<input type=\"text\" style=\"width:25px;\" value=\"" . $received[2] . "\" name=\"receivedday\" readonly> -	<input type=\"text\" style=\"width:25px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> -  <input type=\"text\" style=\"width:40px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly> 
											<a href=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\"  title=\"cal1.showCalendar('anchor1'); return false;\" name=\"anchor1\" id=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></a>

											<script type='text/javascript'>	
												var cal1 = new CalendarPopup();
												cal1.setReturnFunction(\"setMultipleValues1\");

												function setMultipleValues1(y,m,d) {
													document.theform.receivedyear.value=y;
													document.theform.receivedmonth.value=LZ(m);
													document.theform.receivedday.value=LZ(d);
												}
											</script>

										</div>
								</td>
							</tr>
						</table>



						</td>



					</tr>



					</table>



					<br />

					<div class='no-show-in-print'>
					<p style=\"display:inline-block;\">Make the desired changes to the claim and click Next/Save</p>
					<input type=\"submit\" value=\"Next/Save >>\" /> <input type=\"hidden\" value=\"1\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					</div>



					</form>";

    }

    if ($step == 2)    //parts
    {

        //show the items for this claim:

        //echo "</tr><tr><td>";

        echo "<form method=\"POST\" action=\"asloggedinaction.php?action=editclaim\" name=\"topform\">



						<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																		 document.topform.submit();\" />



			  						



						<input type=\"button\" value=\"Parts\" disabled />



						<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																		 document.topform.submit();\" />



						<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;



																		 document.topform.submit();\" />
																		 
						<input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 5;



																		 document.topform.submit();\" />


						<input type=\"button\" value=\"Quote\" onClick=\"document.topform.stepto.value = 7;



																		 document.topform.submit();\" />

																		 



						<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"2\" /> 		 



																		 </form>";

        $qry = "SELECT * FROM items where claimid = $claimid";

        //echo $qry;

        $qrycount = mysql_query($qry, $db);

        $qryitems = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br><form action=\"loggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">

								<p>Client NumberR: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>

								There are no Items in the database. Click <input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" /> 



								<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" /> to add one.



								<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



						  </form>";

        }

        else
        {

            echo "
				
				<p>Client NumberS: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
				<p>



						<form name=\"theitems\" method=\"post\" action=\"loggedinaction.php?action=savetheitems\">



						  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">



							  <tr>



								  <td><strong>Qty</strong></td>



								  <td><strong>Description</strong></td>



								  <td><strong>Quoted</strong></td>



								  <td><strong>Cost</strong></td>



								  <td><strong>1.25</strong></td>



								  <td><strong>Adjustment</strong></td>



								  <td><strong>User</strong>



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

            while ($row = mysql_fetch_array($qryitems))
            {

                // give a name to the fields

                $itemid = $row["id"];

                $qty = $row["qty"];

                $desc = stripslashes($row["description"]);

                $quoted = $row["quoted"];

                $cost = $row["cost"];

                $onetwofive = $row["onetwofive"];

                $adjustment = $row["adjustment"];

                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";

                $qrygetusernameresults = mysql_query($qrygetusername, $db);

                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen

                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td align=\"center\">$qty</td>



							  <td style=\"width:250px;\">$desc</td>



							  <td align=\"right\">$quoted</td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"cost_" .
                    $itemid . "\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theitems.cost_" . $itemid .
                    ".value * 1.25))



																															   {



																																	document.theitems.onetwofive_" . $itemid .
                    ".value = (Math.round((document.theitems.cost_" . $itemid .
                    ".value * 1.25) * 100) / 100);  



																																	document.theitems.adjustment_" . $itemid .
                    ".value = (Math.round((document.theitems.onetwofive_" . $itemid . ".value - $quoted) * 100) / 100);



																															   }



																															   \"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"onetwofive_" .
                    $itemid . "\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theitems.onetwofive_" .
                    $itemid . ".value - $quoted))



																																	 {



																																		document.theitems.adjustment_" . $itemid .
                    ".value = document.theitems.onetwofive_" . $itemid . ".value - $quoted;



																																	 }



																																		\"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"adjustment_" .
                    $itemid . "\" value=\"$adjustment\"></td>



							  <td>$user</td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=edititem&amp;itemid=$itemid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Item\" border=\"0\" title=\"Edit this Item\"></td>



							  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteitem&amp;itemid=$itemid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Item\" border=\"0\" title=\"Delete this Item\"></td>



						  </tr>";

            }//end while loop

            $qrysum = "select sum(adjustment) as totaladjustment, sum(onetwofive) as totalonetwofive, sum(cost) as totalcost, sum(quoted) as totalquoted from items where claimid = $claimid";

            $qrysumresults = mysql_query($qrysum, $db);

            $totalrow = mysql_fetch_array($qrysumresults);

            $total = $totalrow["totaladjustment"];

            $onetwofive = $totalrow["totalonetwofive"];

            $quoted = $totalrow["totalquoted"];

            $cost = $totalrow["totalcost"];

            echo "	<tr>



							<td colspan=\"2\" align=\"right\">TOTALS:</td>										



							<td align=\"right\">$quoted</td>



							<td align=\"right\">$cost</td>



							<td align=\"right\">$onetwofive</td>



							<td align=\"right\">$total</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



							<td colspan=\"2\" align=\"right\">TOTALS INC VAT:</td>										



							<td align=\"right\">" . round($quoted * 1.14, 2) . "</td>



							<td align=\"right\">" . round($cost * 1.14, 2) . "</td>



							<td align=\"right\">" . round($onetwofive * 1.14, 2) . "</td>



							<td align=\"right\">" . round($total * 1.14, 2) . "</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



						  <td colspan=\"6\">&nbsp;<input type=\"submit\" value=\"Save Items\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\"></form></td>



						  <td colspan=\"3\" align=\"center\">



						  



						  <form action=\"loggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">



								<input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" />



								<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" />



								<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



						  </form>					  



						  </td>



					  </tr>



				</table>



				



					</p>";

        }

        echo "<br>



				<form>



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 1;



																	   document.topform.submit();\" >



					<input type=\"button\" value=\"Next >>\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" >



				</form>";

    }

    if ($step == 3)
    {

        echo "<form method=\"POST\" action=\"asloggedinaction.php?action=editclaim\">

					

					<input type=\"button\" value=\"Claim Details\" onClick=\"document.mainform.stepto.value = 1;



																	 document.mainform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.theform.next.value = 1; document.mainform.stepto.value = 2;



																	 document.mainform.submit();\" />



					<input type=\"button\" value=\"Dates\" disabled />



					<input type=\"button\" value=\"Reports\" onClick=\"document.mainform.stepto.value = 4;



																	 document.mainform.submit();\" />
																	 
					<input type=\"button\" value=\"Attachments\" onClick=\"document.mainform.stepto.value = 5;



																		 document.mainform.submit();\" />



																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">											 



																	 </form><p>Client NumberT: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        $loss = $datesrow["loss"];
        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];
        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];
        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];
        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];
        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $wp = $datesrow["wp"];

        $docreq = $datesrow["docreq"];

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $dod = $datesrow["dod"];
        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $invoicesent = $datesrow["invoicesent"];

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];
        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];
        
        echo "



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">

	var cal1 = new CalendarPopup();
	cal1.setReturnFunction(\"setMultipleValues1\");

	function setMultipleValues1(y,m,d) 
	{
		document.mainform.receivedyear.value=y;
		document.mainform.receivedmonth.value=LZ(m);
		document.mainform.receivedday.value=LZ(d);
	}

	var cal2 = new CalendarPopup();
	cal2.setReturnFunction(\"setMultipleValues2\");

	function setMultipleValues2(y,m,d) 
	{
		document.mainform.lossyear.value=y;
		document.mainform.lossmonth.value=LZ(m);
		document.mainform.lossday.value=LZ(d);
	}

	var cal3 = new CalendarPopup();
	cal3.setReturnFunction(\"setMultipleValues3\");

	function setMultipleValues3(y,m,d) 
	{
		document.mainform.assappointedyear.value=y;
		document.mainform.assappointedmonth.value=LZ(m);
		document.mainform.assappointedday.value=LZ(d);
	}

	var cal4 = new CalendarPopup();
	cal4.setReturnFunction(\"setMultipleValues4\");

	function setMultipleValues4(y,m,d) 
	{
		document.mainform.assessmentyear.value=y;
		document.mainform.assessmentmonth.value=LZ(m);
		document.mainform.assessmentday.value=LZ(d);
	}
	
	var cal5 = new CalendarPopup();
	cal5.setReturnFunction(\"setMultipleValues5\");

	function setMultipleValues5(y,m,d) 
	{
		document.mainform.assessmentreportyear.value=y;
		document.mainform.assessmentreportmonth.value=LZ(m);
		document.mainform.assessmentreportday.value=LZ(d);
	}
	
	var cal6 = new CalendarPopup();
	cal6.setReturnFunction(\"setMultipleValues6\");

	function setMultipleValues6(y,m,d) 
	{
		document.mainform.assessmentinvtoinsureryear.value=y;
		document.mainform.assessmentinvtoinsurermonth.value=LZ(m);
		document.mainform.assessmentinvtoinsurerday.value=LZ(d);
	}
	
	var cal7 = new CalendarPopup();
	cal7.setReturnFunction(\"setMultipleValues7\");

	function setMultipleValues7(y,m,d) 
	{
		document.mainform.authyear.value=y;
		document.mainform.authmonth.value=LZ(m);
		document.mainform.authday.value=LZ(d);
	}
	
	var cal8 = new CalendarPopup();
	cal8.setReturnFunction(\"setMultipleValues8\");

	function setMultipleValues8(y,m,d) 
	{
		document.mainform.wpyear.value=y;
		document.mainform.wpmonth.value=LZ(m);
		document.mainform.wpday.value=LZ(d);
	}
	
	var cal9 = new CalendarPopup();
	cal9.setReturnFunction(\"setMultipleValues9\");

	function setMultipleValues9(y,m,d) 
	{
		document.mainform.docreqyear.value=y;
		document.mainform.docreqmonth.value=LZ(m);
		document.mainform.docreqday.value=LZ(d);
	}
	
	var cal10 = new CalendarPopup();
	cal10.setReturnFunction(\"setMultipleValues10\");

	function setMultipleValues10(y,m,d) 
	{
		document.mainform.workinprogressinspyear.value=y;
		document.mainform.workinprogressinspmonth.value=LZ(m);
		document.mainform.workinprogressinspday.value=LZ(d);
	}
	
	var cal11 = new CalendarPopup();
	cal11.setReturnFunction(\"setMultipleValues11\");

	function setMultipleValues11(y,m,d) 
	{
		document.mainform.dodyear.value=y;
		document.mainform.dodmonth.value=LZ(m);
		document.mainform.dodday.value=LZ(d);
	}
	
	var cal12 = new CalendarPopup();
	cal12.setReturnFunction(\"setMultipleValues12\");

	function setMultipleValues12(y,m,d) 
	{
		document.mainform.finalcostingyear.value=y;
		document.mainform.finalcostingmonth.value=LZ(m);
		document.mainform.finalcostingday.value=LZ(d);
	}
	
	var cal13 = new CalendarPopup();
	cal13.setReturnFunction(\"setMultipleValues13\");

	function setMultipleValues13(y,m,d) 
	{
		document.mainform.acirepsentinsureryear.value=y;
		document.mainform.acirepsentinsurermonth.value=LZ(m);
		document.mainform.acirepsentinsurerday.value=LZ(d);
	}

	var cal14 = new CalendarPopup();
	cal14.setReturnFunction(\"setMultipleValues14\");

	function setMultipleValues14(y,m,d) 
	{
		document.mainform.invoicesentyear.value=y;
		document.mainform.invoicesentmonth.value=LZ(m);
		document.mainform.invoicesentday.value=LZ(d);
	}
	
	var cal15 = new CalendarPopup();
	cal15.setReturnFunction(\"setMultipleValues15\");

	function setMultipleValues15(y,m,d) 
	{
		document.mainform.assfeereceivedfrominsureryear.value=y;
		document.mainform.assfeereceivedfrominsurermonth.value=LZ(m);
		document.mainform.assfeereceivedfrominsurerday.value=LZ(d);
	}
	
	var cal16 = new CalendarPopup();
	cal16.setReturnFunction(\"setMultipleValues16\");

	function setMultipleValues16(y,m,d) 
	{
		document.mainform.acipaymentreceivedyear.value=y;
		document.mainform.acipaymentreceivedmonth.value=LZ(m);
		document.mainform.acipaymentreceivedday.value=LZ(d);
	}

</SCRIPT>		  



			  ";

        echo "<br /><form method=\"POST\" action=\"asloggedinaction.php?action=editclaim\" name=\"mainform\">



					<table>



						<tr>



							<td>Date received</td>



							<td><b>" . FormatDate($datesrow["received"], "M") . "</b></td> 



						</tr>



						<tr>



							<td>Date of loss</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $loss[2] . "\" name=\"lossday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $loss[1] . "\" name=\"lossmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $loss[0] . "\" name=\"lossyear\" readonly>



								<A HREF=\"#\" onClick=\"cal2.showCalendar('anchor2'); return false;\" 



								 	TITLE=\"cal2.showCalendar('anchor2'); return false;\" NAME=\"anchor2\" 



								 	ID=\"anchor2\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>



						<tr>



							<td>Assessor appointed</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[2] .
            "\" name=\"assappointedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[1] .
            "\" name=\"assappointedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assappointed[0] .
            "\" name=\"assappointedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal3.showCalendar('anchor3'); return false;\" 



								 	TITLE=\"cal3.showCalendar('anchor3'); return false;\" NAME=\"anchor3\" 



								 	ID=\"anchor3\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td> 



						</tr>



						<tr>



							<td>Date of assessment</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[2] .
            "\" name=\"assessmentday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[1] .
            "\" name=\"assessmentmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessment[0] .
            "\" name=\"assessmentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal4.showCalendar('anchor4'); return false;\" 



								 	TITLE=\"cal4.showCalendar('anchor4'); return false;\" NAME=\"anchor4\" 



								 	ID=\"anchor4\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Assessment report date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[2] .
            "\" name=\"assessmentreportday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[1] .
            "\" name=\"assessmentreportmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentreport[0] .
            "\" name=\"assessmentreportyear\" readonly>



								<A HREF=\"#\" onClick=\"cal5.showCalendar('anchor5'); return false;\" 



								 	TITLE=\"cal5.showCalendar('anchor5'); return false;\" NAME=\"anchor5\" 



								 	ID=\"anchor5\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Assessment invoice sent to insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[2] .
            "\" name=\"assessmentinvtoinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[1] .
            "\" name=\"assessmentinvtoinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentinvtoinsurer[0] .
            "\" name=\"assessmentinvtoinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal6.showCalendar('anchor6'); return false;\" 



								 	TITLE=\"cal6.showCalendar('anchor6'); return false;\" NAME=\"anchor6\" 



								 	ID=\"anchor6\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Authorise date</td>



							<td><b>" . FormatDate($datesrow["auth"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Withhold payment date</td>



							<td><b>" . FormatDate($datesrow["wp"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Document Request</td>



							<td><b>" . FormatDate($datesrow["docreq"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Work in progress inspection date</td>



							<td><b>" . FormatDate($datesrow["workinprogressinsp"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Expected date of delivery</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $dod[2] . "\" name=\"dodday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $dod[1] . "\" name=\"dodmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $dod[0] . "\" name=\"dodyear\" readonly>



								<A HREF=\"#\" onClick=\"cal11.showCalendar('anchor11'); return false;\" 



								 	TITLE=\"cal11.showCalendar('anchor11'); return false;\" NAME=\"anchor11\" 



								 	ID=\"anchor11\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Final costing</td>



							<td><b>" . FormatDate($datesrow["finalcosting"], "M") . "</b></td>



						</tr>



						<tr>



							<td>ACI report sent to insurer</td>



							<td><b>" . FormatDate($datesrow["acirepsentinsurer"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Date invoice sent</td>



							<td><b>" . FormatDate($datesrow["invoicesent"], "M") . "</b></td>



						</tr>



						<tr>



							<td>Assessment fee received from insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[2] .
            "\" name=\"assfeereceivedfrominsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[1] .
            "\" name=\"assfeereceivedfrominsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assfeereceivedfrominsurer[0] .
            "\" name=\"assfeereceivedfrominsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal15.showCalendar('anchor15'); return false;\" 



								 	TITLE=\"cal15.showCalendar('anchor15'); return false;\" NAME=\"anchor15\" 



								 	ID=\"anchor15\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>ACI payment received from insurer</td>



							<td><b>" . FormatDate($datesrow["acipaymentreceived"], "M") . "</b></td>



						</tr>



					</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.mainform.stepto.value = 2;



																	   document.mainform.submit();\" /> 



					<input type=\"button\" value=\"Next >>\" onClick=\"document.mainform.stepto.value = 4;



																	   document.mainform.submit();\" /> 



					<input type=\"reset\" value=\"Reset\" /> <input type=\"hidden\" value=\"3\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }

    if ($step == 4)
    {

        echo "<form method=\"POST\" action=\"asloggedinaction.php?action=editclaim\" name=\"topform\">



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																	 document.topform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Reports\" disabled  />



					<input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 5;



																		 document.topform.submit();\" />



																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">										<input type=\"hidden\" name=\"fromstep\" value=\"4\">		 



																	 </form><p>Client NumberU: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";

        $qry = "SELECT * FROM report where claimid = $claimid";

        $qrycount = mysql_query($qry, $db);

        $qryreports = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br /><p>There are no Reports in the database.</p>";

        }

        else
        {

            echo "<p>Select which report you want to view:<br><br>



							



								 <a href=\"reports.php?action=pbinvoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessor Invoice</a>



								 || <a href=\"reports.php?action=assessmentinstruction&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Instruction</a>



								 || <a href=\"reports.php?action=assessmentreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Report</a></p><br />

						Click <a href=\"asloggedinaction.php?action=newreport&amp;claimid=$claimid\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Report for this Claim\" title=\"Add new Report for this Claim\"></a> to add new report.

					  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">



							  <tr>



								  <td><strong>Report Date</strong></td>



								  <td><strong>Description</strong></td>



							  </tr>";

            while ($row = @mysql_fetch_array($qryreports))
            {

                // give a name to the fields

                $reportid = $row['id'];

                $reportdate = $row['reportdate'];

                $reportdesc = stripslashes($row['description']);

                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";

                $qrygetusernameresults = mysql_query($qrygetusername, $db);

                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen

                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td>" . FormatDate($reportdate, "M") . "</td>



							  <td>$reportdesc</td>



						  </tr>";

            }//end while loop

            //echo "</table> <br><a href=\"loggedinaction.php?action=newreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\"><img src=\"../images/admin/add.gif\" alt=\"Add new Report for this Claim\" border=\"0\" title=\"Add new Report for this Claim\"></a><br>";

        }
        
        

        echo "</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" /> 



					 <input type=\"hidden\" value=\"4\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }
    
    if ($step == 5)
    {
		echo "<form method=\"POST\" action=\"asloggedinaction.php?action=editclaim\" name=\"topform\">



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																	 document.topform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Attachments\" disabled />



																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">										<input type=\"hidden\" name=\"fromstep\" value=\"4\">		 



																	 </form><p>Client NumberV: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";
																	 
																	 
		$qryclaimfiles = "select f.*, u.username from `files` as f left join users as u on f.userid =u.id where f.`claimid` = $claimid order by f.`datetime`";

		$qryclaimfilesresults = mysql_query($qryclaimfiles, $db);
		
		if (mysql_num_rows($qryclaimfilesresults) == 0)
		{
			echo "<p>There are no files in the database for this claim.</p>
			
				<form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>
					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"assessor\">
				</form>
			
			";			
		}
		else
		{
			echo "<Br><form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>
					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"assessor\">
				</form><br>";
		
			echo "<table border=\"1\">
					<tr>
						<td><strong>Date and Time Uploaded:</strong></td>
						<td><strong>File:</strong></td>
						<td><strong>Description:</strong></td>
						<td><strong>File Size:</strong></td>
						<td><strong>User:</strong></td>
					</tr>";
				
			while ($filerow = mysql_fetch_array($qryclaimfilesresults))
			{
				$fileid = $filerow["id"];
				$filename = $filerow["filename"];
				$datetime = date('d/m/Y h:i A', strtotime($filerow["datetime"]));
				$desc = $filerow["description"];
				$fileSize = $filerow["filesize"];
				
				echo "<tr>
						<td>$datetime</td>
						<td><a href=\"claims/$claimid/$fileid-$filename\" target=\"_blank\" class=\"newWindow\">$filename</a></td>
						<td>$desc</td>
						<td>" . humanFileSize($fileSize) . "</td>
						<td>" . $filerow["username"] . "</td>
					  </tr>";
				
			}
			
			echo "</table>";
		}
																	 
	}

}

function pbClaims($claimid)
{

    require ('connection.php');

    $qry = "SELECT * FROM items where claimid = $claimid";

    //echo $qry;

    $qryitems = mysql_query($qry, $db);

    $qrycount = "select count(`id`) as `amount` from items where claimid = $claimid";

    $qrycountresults = mysql_query($qrycount, $db);

    //echo $qrycount;

    $rrooww = mysql_fetch_array($qrycountresults);

    $count = $rrooww["amount"];

    //echo "#$@!#$@!#$@! $count #$@%#$%^#$%^#$%^";

    if ($count == 0)
    {

        echo "<br><form action=\"pbloggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">



							There are no Items in the database. Click <input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" /> 



							<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" /> to add one.



							<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



					  </form>";

    }

    else
    {

        echo "<p>



					<form name=\"theitems\" method=\"post\" action=\"pbloggedinaction.php?action=savetheitems\">



					  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">



						  <tr>



							  <td><strong>Qty</strong></td>



							  <td><strong>Description</strong></td>



							  <td><strong>Quoted</strong></td>



							  <td><strong>Cost</strong></td>



							  <td><strong>1.25</strong></td>



							  <td><strong>Adjustment</strong></td>



							  <td><strong>User</strong>



							  <td align=\"center\"><strong>Actions</strong></td>



						  </tr>";

        while ($row = mysql_fetch_array($qryitems))
        {

            // give a name to the fields

            $itemid = $row["id"];

            $qty = $row["qty"];

            $desc = stripslashes($row["description"]);

            $quoted = $row["quoted"];

            $cost = $row["cost"];

            $onetwofive = $row["onetwofive"];

            $adjustment = $row["adjustment"];

            $userid = $row["userid"];

            $qrygetusername = "select * from users where `id` = $userid";

            $qrygetusernameresults = mysql_query($qrygetusername, $db);

            $usernamerow = mysql_fetch_array($qrygetusernameresults);

            $user = $usernamerow["username"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>



						  <td align=\"center\">$qty</td>



						  <td style=\"width:250px;\">$desc</td>



						  <td align=\"right\">$quoted</td>



						  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"cost_" .
                $itemid . "\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theitems.cost_" . $itemid .
                ".value * 1.25))



																														   {



																																document.theitems.onetwofive_" . $itemid .
                ".value = (Math.round((document.theitems.cost_" . $itemid .
                ".value * 1.25) * 100) / 100);  



																																document.theitems.adjustment_" . $itemid .
                ".value = (Math.round((document.theitems.onetwofive_" . $itemid . ".value - $quoted) * 100) / 100);



																														   }



																														   \"></td>



						  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"onetwofive_" .
                $itemid . "\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theitems.onetwofive_" .
                $itemid . ".value - $quoted))



																																 {



																																	document.theitems.adjustment_" . $itemid .
                ".value = document.theitems.onetwofive_" . $itemid . ".value - $quoted;



																																 }



																																	\"></td>



						  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"adjustment_" .
                $itemid . "\" value=\"$adjustment\"></td>



						  <td>$user</td>



						  <td align=\"center\"><a href=\"pbloggedinaction.php?action=edititem&amp;itemid=$itemid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Item\" border=\"0\" title=\"Edit this Item\"></td>



						  



					  </tr>";

        }//end while loop

        $qrysum = "select sum(adjustment) as totaladjustment, sum(onetwofive) as totalonetwofive, sum(cost) as totalcost, sum(quoted) as totalquoted from items where claimid = $claimid";

        $qrysumresults = mysql_query($qrysum, $db);

        $totalrow = mysql_fetch_array($qrysumresults);

        $total = $totalrow["totaladjustment"];

        $onetwofive = $totalrow["totalonetwofive"];

        $quoted = $totalrow["totalquoted"];

        $cost = $totalrow["totalcost"];

        echo "	<tr>



						<td colspan=\"2\" align=\"right\">TOTALS:</td>										



						<td align=\"right\">$quoted</td>



						<td align=\"right\">$cost</td>



						<td align=\"right\">$onetwofive</td>



						<td align=\"right\">$total</td>



						<td colspan=\"2\">&nbsp;</td>



					</tr>



					<tr>



						<td colspan=\"2\" align=\"right\">TOTALS INC VAT:</td>										



						<td align=\"right\">" . round($quoted * 1.14, 2) . "</td>



						<td align=\"right\">" . round($cost * 1.14, 2) . "</td>



						<td align=\"right\">" . round($onetwofive * 1.14, 2) . "</td>



						<td align=\"right\">" . round($total * 1.14, 2) . "</td>



						<td colspan=\"2\">&nbsp;</td>



					</tr>



					<tr>



					  <td colspan=\"6\">&nbsp;<input type=\"submit\" value=\"Save Items\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\"></form></td>



					  <td colspan=\"2\" align=\"center\">



					  



					  <form action=\"pbloggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">



							<input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" />



							<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" />



							<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



					  </form>					  



					  </td>



				  </tr>



			</table>



			



				</p>";

    }

}

function pbNewItem($claimid)
{

    require ('connection.php');

    $qry = "select * from claim where id = $claimid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $clientname = $row["clientname"];

    $claimno = $row["claimno"];

    $count = $_REQUEST["qty"];

    echo "<form method=\"post\" action=\"pbloggedinaction.php?action=addnewitem\" name=\"theform\">



				  <p>Enter the new Item for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table border=\"1\" cellspacing=\"1\">



						<tr>



							<td>Qty</td>



							<td>Description</td>



							<td>Quoted</td>



							<td>Cost</td>



							<td>1.25</td>



							<td>Adjustment</td>



						</tr>";

    for ($i = 1; $i <= $count; $i++)
    {

        echo "



					<tr>



						<td><input type=\"text\" name=\"qty" . $i . "\" maxlength=\"11\" style=\"width:75px\" value=\"1\"></td>



							<td><input type=\"text\" name=\"description" . $i . "\" size=\"100\" maxlength=\"255\"></td>



							<td><input type=\"text\" name=\"quoted" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.onetwofive" .
            $i . ".value - document.theform.quoted" . $i . ".value))



																																 {



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"cost" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.cost" .
            $i . ".value * 1.25))



																															   {



																																	document.theform.onetwofive" . $i .
            ".value = document.theform.cost" . $i . ".value * 1.25;  



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value; 



																															   }											



																																	\"></td>



							<td><input type=\"text\" name=\"onetwofive" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\" onKeyUp=\"if (!isNaN(document.theform.onetwofive" .
            $i . ".value - document.theform.quoted" . $i . ".value))



																																 {



																																	document.theform.adjustment" . $i .
            ".value = document.theform.onetwofive" . $i . ".value - document.theform.quoted" .
            $i . ".value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"adjustment" . $i . "\" maxlength=\"11\" value=\"0\" style=\"width:75px\"></td></tr>	



			";

    }

    echo "



						



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" value=\"$claimid\" name=\"claimid\"> <input type=\"hidden\" value=\"$count\" name=\"hoeveelheid\" />



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function pbAddNewItem($claimid, $qty, $description, $quoted, $cost, $onetwofive,
    $adjustment, $loggedinuserid, $count)
{

    require ('connection.php');

    //echo "asdf " . $claimid . " asdf";

    for ($i = 1; $i <= $count; $i++)
    {

        //echo $description[$i] . " asdf <br>";

        $qty2 = $qty[$i];

        $description2 = $description[$i];

        $quoted2 = $quoted[$i];

        $cost2 = $cost[$i];

        $onetwofive2 = $onetwofive[$i];

        $adjustment2 = $adjustment[$i];

        if (($description2 == "") && ($quoted2 == 0) && ($cost2 == 0))
        {

        }

        else
        {

            $qryinsert = "insert into items (`id`, `claimid`, `qty`, `description`, `quoted`, `cost`, `onetwofive`, `adjustment`)



										 values ('', $claimid, $qty2, '$description2', $quoted2, $cost2, $onetwofive2, $adjustment2)";

            $qryinsertresults = mysql_query($qryinsert, $db);

        }

        //echo $qryinsert . " <br>";

    }

    /**



    * $qryinsert = "insert into items (`id`, `claimid`, `qty`, `description`, `quoted`, `cost`, `onetwofive`, `adjustment`, `userid`) 



    * 								values ('', $claimid, $qty, '$description', $quoted, $cost, $onetwofive, $adjustment, $loggedinuserid)";	



    * 		$qryinsertresults = mysql_query($qryinsert, $db);



    */

    echo "<p>The Item/s have been saved successfully.</p>";

    pbClaims($claimid);

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function pbEditItem($itemid, $pbid)
{

    require ('connection.php');

    $qry = "select * from items where id = $itemid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $qty = $row["qty"];

    $desc = stripslashes($row["description"]);

    $quoted = $row["quoted"];

    $cost = $row["cost"];

    $onetwofive = $row["onetwofive"];

    $adjustment = $row["adjustment"];

    $claimid = $row["claimid"];

    $qryclaim = "select * from claim where id = $claimid";

    $qryclaimresults = mysql_query($qryclaim, $db);

    $claimrow = mysql_fetch_array($qryclaimresults);

    $clientname = $claimrow["clientname"];

    $claimno = $claimrow["claimno"];
    
    

    echo "<form method=\"post\" action=\"pbloggedinaction.php?action=itemedited\" name=\"theform\">



				  <p>Make the desired changes to the Item for Claim No: <strong>$claimno</strong> for the Client <strong>$clientname</strong>:</p>



					<table border=\"1\" cellspacing=\"1\">



						<tr>



							<td>Qty</td>



							<td>Description</td>



							<td>Quoted</td>



							<td>Cost</td>



							<td>1.25</td>



							<td>Adjustment</td>



						</tr>



						<tr>



							<td><input type=\"text\" name=\"qty\" maxlength=\"11\" style=\"width:75px\" value=\"$qty\"></td>



							<td><input type=\"text\" name=\"description\" maxlength=\"255\" value=\"$desc\" readonly></td>



							<td><input type=\"text\" name=\"quoted\" maxlength=\"11\" style=\"width:75px\" readonly value=\"$quoted\" onKeyUp=\"if (!isNaN(document.theform.onetwofive.value - document.theform.quoted.value))



																																 {



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"cost\" maxlength=\"11\" style=\"width:75px\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theform.cost.value * 1.25))



																															   {



																																	document.theform.onetwofive.value = document.theform.cost.value * 1.25;  



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value; 



																															   }											



																																	\"></td>



							<td><input type=\"text\" name=\"onetwofive\" maxlength=\"11\" style=\"width:75px\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theform.onetwofive.value - document.theform.quoted.value))



																																 {



																																	document.theform.adjustment.value = document.theform.onetwofive.value - document.theform.quoted.value;



																																 }



																																	\"></td>



							<td><input type=\"text\" name=\"adjustment\" maxlength=\"11\" style=\"width:75px\" value=\"$adjustment\"></td>



						</tr>



					</table>



<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"itemid\" value=\"$itemid\"> <input type=\"hidden\" name=\"pbid\" value=\"$pbid\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



			  </form>";

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function pbItemEdited($itemid, $claimid, $qty, $description, $quoted, $cost, $onetwofive,
    $adjustment, $loggedinuserid)
{

    require ('connection.php');

    if (strlen($description) == 0)
    {

        echo "<p>You must enter a description for the item. <a href=\"javascript:history.go(-1)\">Go back</a></p>";

    }

    else
    {

        $qry = "update items set `qty` = $qty,



									 `description` = '$description',



									 `quoted` = $quoted,



									 `cost` = $cost,



									 `onetwofive` = $onetwofive,



									 `adjustment` = $adjustment where `id` = $itemid;



										";

        //echo $qry;

        $qryresults = mysql_query($qry, $db);

        echo "<p>The item was edited successfully. </p>";

        pbClaims($claimid, 2);

    }

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ClaimSummary()
{

    require ('connection.php');

    //firstly, get all the claims:

    $qry = "select claim.id, claim.claimno, claim.clientname, dates.received



  from claim



  join dates



    on claim.id = dates.claimid



order by dates.received";

    $qryresults = mysql_query($qry, $db);

    $table = "



						<table border=\"1\">



							<tr>



								<td colspan=\"4\">The claims ordered by date received (oldest first)</td>



							</tr>



							<tr>



								<td>Claim Number</td>



								<td>Client Name</td>



								<td>Outstanding</td>



								<td>Actions</td>



							</tr>";

    while ($row = mysql_fetch_array($qryresults))
    {

        $claimid = $row["id"];

        $claimno = $row["claimno"];

        $clientname = $row["clientname"];

        $received = $row["received"];

        $table .= "	<tr>



							<td valign=\"top\">$claimno</td>



							<td valign=\"top\">$clientname</td>



							<td align=\"right\">";

        $qrygetdates = "select * from `dates` where `claimid` = $claimid";

        $qrygetdatesresults = mysql_query($qrygetdates, $db);

        $daterow = mysql_fetch_array($qrygetdatesresults);

        $received = $daterow["received"];

        if ($received == "0000-00-00")
        {

            $dates = "Date Received: <strong><font color=\"red\">$received</font></strong><br />";

        }

        else
        {

            $dates = "Date Received: <strong>$received</strong><br />";

        }

        $loss = $daterow["loss"];

        if ($loss == "0000-00-00")
        {

            $dates .= "Date of Loss: <strong><font color=\"red\">$loss</font></strong><br />";

        }

        else
        {

            $dates .= "Date of Loss: <strong>$loss</strong><br />";

        }

        $assappointed = $daterow["assappointed"];

        if ($assappointed == "0000-00-00")
        {

            $dates .= "Assessor Appointed: <strong><font color=\"red\">$assappointed</font></strong><br />";

        }

        else
        {

            $dates .= "Assessor Appointed: <strong>$assappointed</strong><br />";

        }

        $assessment = $daterow["assessment"];

        if ($assessment == "0000-00-00")
        {

            $dates .= "Date of Assessment: <strong><font color=\"red\">$assessment</font></strong><br />";

        }

        else
        {

            $dates .= "Date of Assessment: <strong>$assessment</strong><br />";

        }

        $assessmentreport = $daterow["assessmentreport"];

        if ($assessment == "0000-00-00")
        {

            $dates .= "Assessment Report Date: <strong><font color=\"red\">$assessmentreport</font></strong><br />";

        }

        else
        {

            $dates .= "Assessment Report Date: <strong>$assessmentreport</strong><br />";

        }

        $assessmentinvtoinsurer = $daterow["assessmentinvtoinsurer"];

        if ($assessmentinvtoinsurer == "0000-00-00")
        {

            $dates .= "Assessment Invoice sent to Insurer: <strong><font color=\"red\">$assessmentinvtoinsurer</font></strong><br />";

        }

        else
        {

            $dates .= "Assessment Invoice sent to Insurer: <strong>$assessmentinvtoinsurer</strong><br />";

        }

        $authdate = $daterow["auth"];

        if ($authdate == "0000-00-00")
        {

            $dates .= "Authorize Date: <strong><font color=\"red\">$authdate</font></strong><br />";

        }

        else
        {

            $dates .= "Authorize Date: <strong>$authdate</strong><br />";

        }

        $wp = $daterow["wp"];

        if ($wp == "0000-00-00")
        {

            $dates .= "WP Date: <strong><font color=\"red\">$wp</font></strong><br />";

        }

        else
        {

            $dates .= "WP Date: <strong>$wp</strong><br />";

        }

        $docreq = $daterow["docreq"];

        if ($docreq == "0000-00-00")
        {

            $dates .= "Document Request Sent Date: <strong><font color=\"red\">$docreq</font></strong><br />";

        }

        else
        {

            $dates .= "Document Request Sent Date: <strong>$docreq</strong><br />";

        }

        $workinprogressinsp = $daterow["workinprogressinsp"];

        if ($workinprogressinsp == "0000-00-00")
        {

            $dates .= "Work in Progress Inspection Date: <strong><font color=\"red\">$workinprogressinsp</font></strong><br />";

        }

        else
        {

            $dates .= "Work in Progress Inspection Date: <strong>$workinprogressinsp</strong><br />";

        }

        $dod = $daterow["dod"];

        if ($dod == "0000-00-00")
        {

            $dates .= "Expected Date of Delivery: <strong><font color=\"red\">$dod</font></strong><br />";

        }

        else
        {

            $dates .= "Expected Date of Delivery: <strong>$dod</strong><br />";

        }

        $finalcosting = $daterow["finalcosting"];

        if ($finalcosting == "0000-00-00")
        {

            $dates .= "Final Costing Date: <strong><font color=\"red\">$finalcosting</font></strong><br />";

        }

        else
        {

            $dates .= "Final Costing Date: <strong>$finalcosting</strong><br />";

        }

        $acirepsentinsurer = $daterow["acirepsentinsurer"];

        if ($acirepsentinsurer == "0000-00-00")
        {

            $dates .= "ACI Report Sent to Insurer Date: <strong><font color=\"red\">$acirepsentinsurer</font></strong><br />";

        }

        else
        {

            $dates .= "ACI Report Sent to Insurer Date: <strong>$acirepsentinsurer</strong><br />";

        }

        $invoicesent = $daterow["invoicesent"];

        if ($invoicesent == "0000-00-00")
        {

            $dates .= "Invoice Sent Date: <strong><font color=\"red\">$invoicesent</font></strong><br />";

        }

        else
        {

            $dates .= "Invoice Sent Date: <strong>$invoicesent</strong><br />";

        }

        $assfeereceivedfrominsurer = $daterow["assfeereceivedfrominsurer"];

        if ($assfeereceivedfrominsurer == "0000-00-00")
        {

            $dates .= "Assessment Fee Received from Insurer: <strong><font color=\"red\">$assfeereceivedfrominsurer</font></strong><br />";

        }

        else
        {

            $dates .= "Assessment Fee Received from Insurer: <strong>$assfeereceivedfrominsurer</strong><br />";

        }

        $acipaymentreceived = $daterow["acipaymentreceived"];

        if ($acipaymentreceived == "0000-00-00")
        {

            $dates .= "Payment Received from Insurer: <strong><font color=\"red\">$acipaymentreceived</font></strong><br />";

        }

        else
        {

            $dates .= "Payment Received from Insurer: <strong>$acipaymentreceived</strong><br />";

        }

        $table .= "$dates



						</td><td align=\"center\"><a href=\"loggedinaction.php?action=editclaim&amp;claimid=$claimid&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td></tr>";

    }

    $table .= "</table>";

    echo $table;

}

function ccClaims($from, $admin, $ccid)
{

    require ('connection.php');

    //$from = $_REQUEST["from"];

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim where claimsclerkid = $ccid order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT * FROM claim where claimsclerkid = $ccid order by clientno LIMIT $frm , 30";

    }//end else

    $qrycountclaims = "select * from claim where claimsclerkid = $ccid";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims in the database. Click <a href=\"ccloggedinaction.php?action=ccnewclaim\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Claim\" title=\"Add new Claim\"></a> to add one.</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "<a href=\"ccloggedinaction.php?action=ccclaims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<a href=\"ccloggedinaction.php?action=ccclaims&amp;from=" .
                    $fromrecord . "\">Page $pagenumber</a> || ";

            }//end for loop

        }//end if

        $pageslinks = substr($pageslinks, 0, -4);

        echo "



			



				<form action=\"ccloggedinaction.php?action=ccsearchclaims\" method=\"post\" name=\"searchform\">



						<strong>Search for a claim:</strong><br>



						Client NumberW: <input type=\"text\" name=\"clientno\"> 



						Client Name: <input type=\"text\" name=\"clientname\">



						Claim Number: <input type=\"text\" name=\"claimno\"> <input type=\"submit\" value=\"Search\">



						<input type=\"hidden\" name=\"from\" value=\"1\">



					<br><br>



					



					</form>



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">



						  <tr>



							  <td><strong>Client Number</strong></td>



							  <td><strong>Client Name</strong></td>											



							  <td><strong>Claim Number</strong></td>";

        echo "



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

        while ($row = mysql_fetch_array($qryclaims))
        {

            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>



						  <td>$clientno</td>



						  <td>$clientname</td>



						  <td>$claimno</td>";

            echo "



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=cceditclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

        }//end while loop

        echo "<tr>



					  <td colspan=\"3\">&nbsp;</td>";

        echo "



						  <td colspan=\"2\" align=\"center\"><a href=\"ccloggedinaction.php?action=ccnewclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

    }

}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ccSearchClaims($claimno, $clientno, $clientname, $from, $admin, $ccid)
{

    require ('connection.php');

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM claim where clientno like '%" . $clientno . "%' 



										and clientname like '%" . $clientname . "%' 



										and claimno like '%" . $claimno . "%'



										and claimsclerkid = $ccid 



										order by clientno LIMIT 0 , 30";

    }//end if

    else
    {

        if ($from < 2)
        {

            $frm = $from - 1;

        }

        else
        {

            $frm = $from;

        }

        $qry = "SELECT * FROM claim where clientno like '%" . $clientno . "%' 



										and clientname like '%" . $clientname . "%' 



										and claimno like '%" . $claimno . "%'



										and claimsclerkid = $ccid 



										order by clientno LIMIT $frm , 30";

    }//end else

    $qrycountclaims = "SELECT * FROM claim where clientno like '%" . $clientno .
        "%' 



										and clientname like '%" . $clientname . "%' 



										and claimno like '%" . $claimno . "%' 



										and claimsclerkid = $ccid



										order by clientno";

    $qrycount = mysql_query($qrycountclaims, $db);

    $qryclaims = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Claims in the database with these search criteria. <a href=\"javascript:history.go(-1);\">Go Back to Claims</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "



			



						   <form style=\"display:inline\" action=\"ccloggedinaction.php?action=ccsearchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page 1\"> 



																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">



																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">



																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">



																									<input type=\"hidden\" name=\"from\" value=\"1\"></form>&nbsp;";

        //<a href=\"loggedinaction.php?action=claims&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<form style=\"display:inline\" action=\"ccloggedinaction.php?action=ccsearchclaims\" method=\"post\"> <input type=\"submit\" value=\"Page $pagenumber\"> 



																									<input type=\"hidden\" name=\"clientno\" value=\"$clientno\">



																									<input type=\"hidden\" name=\"clientname\" value=\"$clientname\">



																									<input type=\"hidden\" name=\"claimno\" value=\"$claimno\">



																									<input type=\"hidden\" name=\"from\" value=\"$fromrecord\"></form>&nbsp;";

            }//end for loop

        }//end if

        //$pageslinks = substr($pageslinks, 0, -4);

        echo "Search results for Client NumberX: <strong>$clientno</strong>, Client Name: <strong>$clientname</strong> and Claim Number: <strong>$claimno</strong><br><br>	<form action=\"ccloggedinaction.php?action=ccsearchclaims\" method=\"post\" name=\"searchform\">



						<strong>Search for a claim:</strong><br>



						Client NumberY: <input type=\"text\" name=\"clientno\"> 



						Client Name: <input type=\"text\" name=\"clientname\">



						Claim Number: <input type=\"text\" name=\"claimno\"> <input type=\"submit\" value=\"Search\">



					<br><br>



					



					</form>



				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">



						  <tr>



							  <td><strong>Client Number</strong></td>



							  <td><strong>Client Name</strong></td>



							  <td><strong>Claim Number</strong></td>";

        echo "



								  <td align=\"center\" colspan=\"2\"><strong>Actions</strong></td>



							  </tr>";

        while ($row = mysql_fetch_array($qryclaims))
        {

            // give a name to the fields

            $claim_id = $row['id'];

            $clientname = $row['clientname'];

            $claimno = $row["claimno"];

            $clientno = $row["clientno"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>



						  <td>$clientno</td>



						  <td>$clientname</td>



						  <td>$claimno</td>";

            echo "



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=cceditclaim&amp;claimid=$claim_id&amp;stepto=1\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claim\" border=\"0\" title=\"Edit this Claim\"></td>											  



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=reports&amp;claimid=$claim_id\"><img src=\"../images/admin/reports.gif\" alt=\"Go to the Reports for this Claim\" border=\"0\" title=\"Go to the reports for this Claim\"></td>



						  </tr>";

        }//end while loop

        echo "<tr>



					  <td colspan=\"3\">&nbsp;</td>";

        echo "		  



						  <td align=\"center\" colspan=\"2\"><a href=\"ccloggedinaction.php?action=ccnewclaim\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claim\" border=\"0\" title=\"Add new Claim\"></a></td>



					  </tr>



				</table><br>$pageslinks<br>



					";

    }

}

function ccNewClaim($pbid)
{

    require ('connection.php');

    $reloaded = $_REQUEST["reload"];

    if ($reloaded == 1)
    {

        $clientname = $_REQUEST["clientname"];

        $clientno = $_REQUEST["clientno"];

        $claimno = $_REQUEST["claimno"];

        $clientcontactno1 = $_REQUEST["clientcontactno1"];

        $clientcontactno2 = $_REQUEST["clientcontactno2"];

		$clientemail	= $_REQUEST["clientemail"];

        $panelbeaterid = $_REQUEST["pbid"];

        $vehiclemakemodel = $_REQUEST["vehiclemakemodel"];

        $vehicleyear = $_REQUEST["vehicleyear"];

        $vehicleregistrationno = $_REQUEST["vehicleregistrationno"];

        $vehicletype = $_REQUEST["vehicletype"];

        $administratorid = $_REQUEST["adminid"];

        $quoteno = $_REQUEST["quoteno"];

        $insurerid = $_REQUEST["insurerid"];

        $claimsclerkid = $_REQUEST["claimsclerk"];

        $authamount = $_REQUEST["authamount"];

        $excess = $_REQUEST["excess"];

		$excess_description = $_REQUEST["excess_description"];

        $betterment = $_REQUEST["betterment"];

        $quoteamount = $_REQUEST["quoteamount"];

        $assessorid = $_REQUEST["assid"];

        $area = $_REQUEST["area"];

        if ($area == 0)
        {

            $assessorid = 0;

        }

    }

    else
    {

        $clientname = "";

        $clientno = "";

        $claimno = "";

        $qrygetclaimno = "select * from claimnumber";

        $qrygetclaimnoresults = mysql_query($qrygetclaimno, $db);

        $claimnorow = mysql_fetch_array($qrygetclaimnoresults);

        $clientno = $claimnorow["clientno"];

        //echo "sadfsdaf $claimno afsdfsdawerq";

        $clientcontactno1 = "";

        $clientcontactno2 = "";

		$clientemail = "";

        $panelbeaterid = "";

        $vehiclemakemodel = "";

        $vehicleyear = "";

        $vehicleregistrationno = "";

        $vehicletype = "";

        $administratorid = "";

        $quoteno = "";

        $insurerid = 0;

        $claimsclerkid = "";

        $authamount = "";

        $quoteamount = "";

        $excess = "";

		$excess_description = "";

        $betterment = "";

        $assessorid = "";

        $area = 0;

    }

    echo "<script type=\"text/javascript\">



		



		function ReloadThisPage()



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;


			



			document.hiddenform.pbid.value = pbid;	



			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;



			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;



			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;

			document.hiddenform.excess_description.value = document.theform.excess_description.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 0;



			document.hiddenform.area.value = document.theform.area.value;



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



		function ReloadThisPagePB(area)



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;			



			document.hiddenform.pbid.value = pbid;	



			document.hiddenform.pbname.value = document.theform.pbname.value;	



			document.hiddenform.pbowner.value = document.theform.pbowner.value;
			document.hiddenform.pbworkshopmanageremail.value = document.theform.pbworkshopmanageremail.value;
			document.hiddenform.pbcostingclerkemail.value = document.theform.pbcostingclerkemail.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;
			document.hiddenform.pbowneremail.value = document.theform.pbowneremail.value;
			document.hiddenform.pbownercel.value = document.theform.pbownercel.value;



			document.hiddenform.pbcostingclerk.value = document.theform.pbcostingclerk.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;



			document.hiddenform.pbcontactnumber.value = document.theform.pbcontactnumber.value;

			document.hiddenform.pbcontactnumber2.value = document.theform.pbcontactnumber2.value;



			document.hiddenform.pbcontactperson.value = document.theform.pbcontactperson.value;



			document.hiddenform.pbfaxno.value = document.theform.pbfaxno.value;



			document.hiddenform.pbemail.value = document.theform.pbemail.value;



			document.hiddenform.pbadr1.value = document.theform.pbadr1.value;



			document.hiddenform.pbadr2.value = document.theform.pbadr2.value;



			document.hiddenform.pbadr3.value = document.theform.pbadr3.value;



			document.hiddenform.pbadr4.value = document.theform.pbadr4.value;

			document.hiddenform.notes.value = document.theform.notes.value;

			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;



			document.hiddenform.claimsclerk.value = document.theform.claimsclerk.value;



			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;

			document.hiddenform.excess_description.value = document.theform.excess_description.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 1;	



			



			if (area == 1)



			{



				document.hiddenform.assid.value = 0;



			}



			document.hiddenform.area.value = document.theform.area.value;		



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



	</script>";

    echo "



				<form method=\"post\" action=\"ccloggedinaction.php?action=ccaddnewclaim\" name=\"theform\">

				<table>



				<tr>



					<td>	



						<table bgcolor=\"#E7E7FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\" blue-bg\">
							<tr>
								<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Client:</h4>
								
									<div style=\"display:inline-block;\">
										Client NumberZ: <input type=\"text\" value=\"$clientno\" maxlength=\"50\" name=\"clientno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Name: <input type=\"text\" value=\"$clientname\" maxlength=\"50\" name=\"clientname\" />
									</div>

									<div style=\"display:inline-block;\">
										Claim Number: <input type=\"text\" value=\"$claimno\" maxlength=\"50\" name=\"claimno\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No: <input type=\"text\" value=\"$clientcontactno1\" maxlength=\"50\" name=\"clientcontactno1\" />
									</div>

									<div style=\"display:inline-block;\">
										Client Contact No 2: <input type=\"text\" value=\"$clientcontactno2\" maxlength=\"50\" name=\"clientcontactno2\" />
									</div>
									
									<div style=\"display:inline-block;\">
										Email Address: <input type=\"text\" value=\"$clientemail\" maxlength=\"50\" name=\"clientemail\" />
									</div>
								</td>
							</tr>
						</table>



						<br />



						<table bgcolor=\"#D3D3FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>

									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Panelbeater:</h4>
										
										<div style=\"display:inline-block;\">
											Panelbeater: 

											<select name=\"panelbeater\" onChange=\"ReloadThisPage();\">";

											$qrygetpanelbeaters = "select * from panelbeaters order by `name`";
											$qrygetpanelbeatersresults = mysql_query($qrygetpanelbeaters, $db);

											while ($row = mysql_fetch_array($qrygetpanelbeatersresults))
											{
												$pbid = $row["id"];
												$pbname = stripslashes($row["name"]);
												if ($pbid == $panelbeaterid) {
													echo "<option value=\"$pbid\" selected>$pbname</option>";
												}
												else {
													echo "<option value=\"$pbid\">$pbname</option>";
												}
											}

											$qrygetpanelbeaterinfo = "select * from panelbeaters where `id` = $panelbeaterid";
											$qrygetpanelbeaterinforesults = mysql_query($qrygetpanelbeaterinfo, $db);
											$selectedpbrow = mysql_fetch_array($qrygetpanelbeaterinforesults);
											$dothepb = $_REQUEST["dothepb"];
											//echo "asdfasdf $dothepb ASDFSADF";
											$pbname = $selectedpbrow["name"];
											$pbowner = $selectedpbrow["owner"];
											$pbcostingclerk = $selectedpbrow["costingclerk"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbcontactperson = $selectedpbrow["contactperson"];
											$pbworkshopmanager = $selectedpbrow["workshopmanager"];
											$pbcontactnumber = $selectedpbrow["contactno"];
											$pbcontactnumber2 = $selectedpbrow["contactno2"];
											$pbfaxno = $selectedpbrow["faxno"];
											$pbemail = $selectedpbrow["email"];
											$pbadr1 = $selectedpbrow["adr1"];
											$pbadr2 = $selectedpbrow["adr2"];
											$pbadr3 = $selectedpbrow["adr3"];
											$pbadr4 = $selectedpbrow["adr4"];
											$notes = $selectedpbrow["notes"];
											$pbowneremail = $selectedpbrow["owneremail"];
											$pbownercel = $selectedpbrow["ownercel"];
											$pbcostingclerkemail = $selectedpbrow["costingclerkemail"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbworkshopmanageremail = $selectedpbrow["workshopmanageremail"];

											$pbestimator = $selectedpbrow["estimator"];
											$pbestimatoremail = $selectedpbrow["estimatoremail"];
											$pbpartsmanager = $selectedpbrow["partsmanager"];
											$pbpartsmanagercel = $selectedpbrow["partsmanagercel"];
											$pbpartsmanageremail = $selectedpbrow["partsmanageremail"];
											$latitude = $selectedpbrow["latitude"];
											$longitude = $selectedpbrow["longitude"];

											if ($dothepb == 1)
											{
												$pbname = $_REQUEST["pbname"];
												$pbowner = $_REQUEST["pbowner"];
												$pbcostingclerk = $_REQUEST["pbcostingclerk"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbcontactperson = $_REQUEST["pbcontactperson"];
												$pbworkshopmanager = $_REQUEST["pbworkshopmanager"];
												$pbcontactnumber = $_REQUEST["pbcontactnumber"];
												$pbcontactnumber2 = $_REQUEST["pbcontactnumber2"];
												$pbfaxno = $_REQUEST["pbfaxno"];
												$pbemail = $_REQUEST["pbemail"];
												$pbadr1 = $_REQUEST["pbadr1"];
												$pbadr2 = $_REQUEST["pbadr2"];
												$pbadr3 = $_REQUEST["pbadr3"];
												$pbadr4 = $_REQUEST["pbadr4"];
												$notes = $_REQUEST["notes"];
												$pbowneremail = $_REQUEST["pbowneremail"];
												$pbownercel = $_REQUEST["pbownercel"];
												$pbcostingclerkemail = $_REQUEST["pbcostingclerkemail"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbworkshopmanageremail = $_REQUEST["pbworkshopmanageremail"];

												$pbestimator = $_REQUEST["pbestimator"];
												$pbestimatoremail = $_REQUEST["pbestimatoremail"];
												$pbpartsmanager = $_REQUEST["pbpartsmanager"];
												$pbpartsmanagercel = $_REQUEST["pbpartsmanagercel"];
												$pbpartsmanageemail = $_REQUEST["pbpartsmanageemail"];
												$latitude = $_REQUEST["latitude"];
												$longitude = $_REQUEST["longitude"];
	
											}
											
											$emailSubject = ucwords("$clientnumber2, $clientname, $claimnumber, $vehicleregistrationno, $vehiclemakemodel");

											echo "					</select>

										</div>

										<div style=\"display:inline-block;\">
											Panelbeater: 
											<input type=\"text\" value=\"$pbname\" maxlength=\"50\" name=\"pbname\" style='width:300px;' />
										</div>
									
									</td>

								</tr>

								<tr>
									<td colspan=\"6\">
										<div style=\"display:inline-block;width:24%;\">
											Tel: <input type=\"text\" value=\"$pbcontactnumber\" maxlength=\"50\" name=\"pbcontactnumber\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Tel 2: <input type=\"text\" value=\"$pbcontactnumber2\" maxlength=\"50\" name=\"pbcontactnumber2\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Fax: <input type=\"text\" value=\"$pbfaxno\" maxlength=\"50\" name=\"pbfaxno\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Email: <input type=\"text\" value=\"$pbemail\" maxlength=\"255\" name=\"pbemail\" class='textinput-lg' />
											<a href=\"mailto:$pbemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
										</div>
									</td>
								</tr>

								<tr>
									<td>Owner/Manager:</td>
									<td><input type=\"text\" value=\"$pbowner\" maxlength=\"50\" name=\"pbowner\"  /></td>
									
									<td>Man Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbownercel\" maxlength=\"50\" name=\"pbownercel\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbowneremail\" maxlength=\"50\" name=\"pbowneremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbowneremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
									</td>

									<td>Contact: </td>
									<td><input type=\"text\" value=\"$pbcontactperson\" maxlength=\"50\" name=\"pbcontactperson\"  /></td>
								</tr>

								<tr>
									<td>Costing Clerk:</td>
									<td><input type=\"text\" value=\"$pbcostingclerk\" maxlength=\"50\" name=\"pbcostingclerk\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbcostingclerkcel\" maxlength=\"50\" name=\"pbcostingclerkcel\"  /></td>

									<td>Email: </td>
									<td><input type=\"text\" value=\"$pbcostingclerkemail\" maxlength=\"50\" name=\"pbcostingclerkemail\" class='textinput-lg'/>
										<a href=\"mailto:$pbcostingclerkemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Address: </td>
									<td><input type=\"text\" value=\"$pbadr1\" maxlength=\"50\" name=\"pbadr1\"  /></td>
								</tr>

								<tr>
									<td>Workshop Manager:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanager\" maxlength=\"50\" name=\"pbworkshopmanager\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbworkshopmanageremail\" maxlength=\"50\" name=\"pbworkshopmanageremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbworkshopmanageremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
									</td>

									<td>&nbsp;</td>
									<td><input type=\"text\" value=\"$pbadr2\" maxlength=\"50\" name=\"pbadr2\"  /></td>
								</tr>

								<tr>
									<td>Estimator: </td>
									<td><input type=\"text\" value=\"$pbestimator\" maxlength=\"50\" name=\"pbestimator\"  /></td>
									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbestimatoremail\" maxlength=\"50\" name=\"pbestimatoremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbestimatoremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
									</td>
									<td>Province: </td>
									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\"  /></td>
								</tr>
								
								<tr>
									<td rowspan=\"3\">Notes:</td>
									<td rowspan=\"3\" colspan=\"3\">
										<textarea name=\"notes\" style='width:400px;height:85px;'>$notes</textarea>
									</td>
									
									<td>Area Code: </td>
									<td><input type=\"text\" value=\"$pbadr4\" maxlength=\"50\" name=\"pbadr4\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Latitude: </td>
									<td><input type=\"text\" value=\"$latitude\" maxlength=\"50\" name=\"latitude\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Longitude: </td>
									<td><input type=\"text\" value=\"$longitude\" maxlength=\"50\" name=\"longitude\"  /></td>
								</tr>


							</table>



						<br />



						<table bgcolor=\"#BFBFFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>
									<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Vehicle:</h4>
									
										<div style=\"display:inline-block;\">
											Vehicle Type: 
											<select name=\"vehicletype\">";

											$vehicleTypesList = getVehicleTypesList();

											foreach ($vehicleTypesList as $vType) {
												$isSelected = ($vehicletype == $vType) ? 'selected="selected"' : '';
												echo '<option value="'.$vType.'" '.$isSelected.'>'.$vType.'</option>';
											}

											echo "					</select>
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Make/Model: 
											<input type=\"text\" value=\"$vehiclemakemodel\" maxlength=\"50\" name=\"vehiclemakemodel\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Year: 
											<input type=\"text\" value=\"$vehicleyear\" maxlength=\"10\" name=\"vehicleyear\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Registration&nbsp;No: 
											<input type=\"text\" value=\"$vehicleregistrationno\" maxlength=\"50\" name=\"vehicleregistrationno\" />
										</div>

									</td>

								</tr>


							</table>



						<br />



						<table bgcolor=\"#ABABFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

							<tr>

								<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Administrator:</h4>
									
									<select name=\"administrator\" onChange=\"ReloadThisPagePB(0);\">";

									$qrygetadministrators = "select * from administrators order by `name`";

									$qrygetadministratorsresults = mysql_query($qrygetadministrators, $db);

									while ($administratorrow = mysql_fetch_array($qrygetadministratorsresults))
									{

										$adminid = $administratorrow["id"];

										$adminname = $administratorrow["name"];

										if ($administratorid == $adminid)
										{

											echo "<option value=\"$adminid\" selected>$adminname</option>";

										}

										else
										{

											echo "<option value=\"$adminid\">$adminname</option>";

										}

									}

									$qrygetadministratorinfo = "select * from administrators where `id` = $administratorid";

									$qrygetadministratorinforesults = mysql_query($qrygetadministratorinfo, $db);

									$administratorinforow = mysql_fetch_array($qrygetadministratorinforesults);

									$admintelno = stripslashes($administratorinforow["telno"]);

									$adminfaxno = stripslashes($administratorinforow["faxno"]);

									$adminadr1 = stripslashes($administratorinforow["adr1"]);

									$adminadr2 = stripslashes($administratorinforow["adr2"]);

									$adminadr3 = stripslashes($administratorinforow["adr3"]);

									$adminadr4 = stripslashes($administratorinforow["adr4"]);

									$vatno = stripslashes($administratorinforow["vatno"]);

									echo "					</select>


									Insurance Company:

									<select name=\"insurerid\"><option value=\"0\">Select one</option>";

									$qryinsurers = "select * from `insurers` order by `name`";

									$qryinsurersresults = mysql_query($qryinsurers, $db);

									while ($insrow = mysql_fetch_array($qryinsurersresults))
									{

										$insid = $insrow["id"];

										$insurancecompname = stripslashes($insrow["name"]);

										if ($insid == $insurerid)
										{

											echo "<option value=\"$insid\" selected>$insurancecompname</option>";

										}

										else
										{

											echo "<option value=\"$insid\">$insurancecompname</option>";

										}

									}

									echo " </select>
								
								</td>

							</tr>

							<tr>
								<td colspan=\"5\">
									Tel: $admintelno, 
									Fax: $adminfaxno,
									P.O.Box: $adminadr1, $adminadr2, $adminadr3 ";
									
								if ( !empty($adminadr4) ) { echo $adminadr4; }
							echo "
								</td>
							</tr>
							
							<tr>
								<td colspan=\"5\">
									Claim Technician: 

									<select name=\"claimsclerk\" id=\"claimsclerk\">";

									$qryclaimsclerks = "select * from claimsclerks order by `name`";

									$qryclaimsclerksresults = mysql_query($qryclaimsclerks, $db);
									
									$defaultEmail = '';
									$counter = 0;
									while ($ccrow = mysql_fetch_array($qryclaimsclerksresults))
									{

										$ccid = $ccrow["id"];

										$ccname = stripslashes($ccrow["name"]);
										$ccemailid = stripslashes($ccrow["email"]);

										if ($counter==0) {
											$defaultEmail = $ccemailid;
										}

										if ($claimsclerkid == $ccid)
										{
											$defaultEmail = $ccemailid;

											echo "<option value=\"$ccid\" selected email=\"$ccemailid\" >$ccname</option>";

										}

										else
										{

											echo "<option value=\"$ccid\" email=\"$ccemailid\">$ccname</option>";

										}

										$counter++;

									}

									echo " </select>

									<a href=\"mailto:$defaultEmail?subject=$emailSubject\"  type=\"Claim\" claimId=\"$claimid\" class=\"send-email\" emailpart=\"subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" id=\"claimTechnicianEmailLink\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>

									";
				
									global $admin;
									if ( $admin == 1 ) { echo " VAT&nbsp;Number: $vatno "; }

									echo "

								</td>
							</tr>
							
						</table>



						<br />



						<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>
							<tr>
								<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Claim:</h4>										
									<div style=\"display:inline-block;\">
										Quote&nbsp;Number: <input type=\"text\" value=\"$quoteno\" maxlength=\"50\" name=\"quoteno\" />
									</div>
									<div style=\"display:inline-block;\">
										Authorised&nbsp;Amount: <input type=\"text\" value=\"$authamount\" maxlength=\"11\" name=\"authamount\" />
									</div>
									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess\" maxlength=\"11\" name=\"excess\" />
									</div>

									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess_description\" style='width:300px;' name=\"excess_description\" />
									</div>

									<div style=\"display:inline-block;\">
										Betterment: <input type=\"text\" value=\"$betterment\" maxlength=\"11\" name=\"betterment\" />
									</div>
									<div style=\"display:inline-block;\">
										Quoted&nbsp;Amount: <input type=\"text\" value=\"$quoteamount\" maxlength=\"11\" name=\"quoteamount\" />
									</div>
									";
								
									$res = mysql_query("SELECT `received` FROM `dates` WHERE claimid='$claimid' ", $db);

									$daterow = mysql_fetch_array($res);

									$received = explode('-', $daterow['received']);

									echo "
										<div style=\"display:inline-block;\">
											Date Received:

											<input type=\"text\" style=\"width:40px;\" value=\"" . $received[2] . "\" name=\"receivedday\" readonly> -	<input type=\"text\" style=\"width:40px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> -  <input type=\"text\" style=\"width:60px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly> 
											<a href=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\"  title=\"cal1.showCalendar('anchor1'); return false;\" name=\"anchor1\" id=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></a>

											<script type='text/javascript'>	
												var cal1 = new CalendarPopup();
												cal1.setReturnFunction(\"setMultipleValues1\");

												function setMultipleValues1(y,m,d) {
													document.theform.receivedyear.value=y;
													document.theform.receivedmonth.value=LZ(m);
													document.theform.receivedday.value=LZ(d);
												}
											</script>

										</div>
								</td>
							</tr>
						</table>



						<br />



						<table bgcolor=\"#8383FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=' blue-bg'>



								<tr>



									<td colspan=\"5\"><h4 style=\"margin-top:0px;display:inline-block;margin-bottom:0;\">Assessor Details:</h4>
										
										Select&nbsp;Assessor&nbsp;Area:

										<select name=\"area\" onChange=\"ReloadThisPagePB(1);\">
										<option value=\"0\">Select Assessor Area</option>";

										$qryareas = "select * from areas order by areaname";

										$qryareasresults = mysql_query($qryareas, $db);

										while ($arearow = mysql_fetch_array($qryareasresults))
										{

											$areaid = $arearow["id"];

											$areaname = $arearow["areaname"];

											if ($areaid == $area)
											{

												echo "<option value=\"$areaid\" selected>$areaname</option>";

											}

											else
											{

												echo "<option value=\"$areaid\">$areaname</option>";

											}

										}

									echo "</select>";


									echo " Select&nbsp;Assessor:


									<select name=\"assessor\" onChange=\"ReloadThisPagePB(0);\"><option value=\"0\">Select Assessor</option>";

										$qryassessors = "select * from assessors order by `company`";

										$qryassessorsresults = mysql_query($qryassessors, $db);

										while ($assrow = mysql_fetch_array($qryassessorsresults))
										{

											$assid = $assrow["id"];

											$assname = $assrow["name"];
											$asscompanyoption = $assrow["company"] . ' (' . $assname . ')';

											//check to see if this assessor is in the selected Area

											if ($area != 0)
											{

												$qrycheckarea = "select count(assessorid) as counted from assessor_area where assessorid = $assid and areaid = $area";

												$qrycheckarearesults = mysql_query($qrycheckarea, $db);

												$checkarearow = mysql_fetch_array($qrycheckarearesults);

												$count = $checkarearow["counted"];

												if ($count == 1)
												{

													if ($assessorid == $assid)
													{

														echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

													}

													else
													{

														echo "<option value=\"$assid\">$asscompanyoption</option>";

													}

												}

											}

											else
											{

												if ($assessorid == $assid)
												{

													echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

												}

												else
												{

													echo "<option value=\"$assid\">$asscompanyoption</option>";

												}

											}

										}

										$qrygetassessorinfo = "select * from assessors where `id` = $assessorid";

										$qrygetassessorinforesults = mysql_query($qrygetassessorinfo, $db);

										$assessorrow = mysql_fetch_array($qrygetassessorinforesults);

										$assname = stripslashes($assessorrow["name"]);

										$asscompany = stripslashes($assessorrow["company"]);

										$asstelno = stripslashes($assessorrow["telno"]);

										$assfaxno = stripslashes($assessorrow["faxno"]);

										$asscellno = stripslashes($assessorrow["cellno"]);

										$assemail = stripslashes($assessorrow["email"]);

										$asscomments = stripslashes($assessorrow["comments"]);

									echo " </select>";
									
									echo "</td>									



								</tr>";

								

								if ( !empty($assname) ) {

									$ass_string = ucwords(trim($asscompany)) . ', ' . ucwords(trim($assname)) . ', Tel:' . $asstelno . ', Fax:' . $assfaxno . ', Cel/Ext:' . $asscellno . ', Email:' . $assemail;

									echo "<tr>
										<td colspan=\"5\"> $ass_string 
										
										<div style=\"display: inline; \"><a href=\"mailto:$assemail?subject=$emailSubject\" type=\"Assessors\" claimId=\"$claimid\" class=\"send-email\" alt=\"Send Email\" title=\"Send Email\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a></div>

										</td>
									</tr>";
								
								}



								echo "<tr>

									<td>Comments:</td>

									<td colspan='4'>
										<textarea name=\"asscomments\" style='width:660px;height:40px;' >$asscomments</textarea>
									</td>

								</tr>



							</table>



					</td>



				</tr>



				</table>



				<br />



				<input type=\"submit\" value=\"Save Claim\" /> <input type=\"reset\" value=\"Reset\" /> 



				</form>";

    echo "<form action=\"ccloggedinaction.php?action=ccnewclaim\" method=\"POST\" name=\"hiddenform\">



<input type=\"hidden\" name=\"clientname\">



<input type=\"hidden\" name=\"clientno\">



<input type=\"hidden\" name=\"claimno\">



<input type=\"hidden\" name=\"clientcontactno1\">



<input type=\"hidden\" name=\"clientcontactno2\">

<input type=\"hidden\" name=\"clientemail\">







<input type=\"hidden\" name=\"pbid\">



<input type=\"hidden\" name=\"pbname\">



<input type=\"hidden\" name=\"pbowner\">
<input type=\"hidden\" name=\"pbworkshopmanageremail\">
<input type=\"hidden\" name=\"pbcostingclerkemail\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">
<input type=\"hidden\" name=\"pbowneremail\">
<input type=\"hidden\" name=\"pbownercel\">

<input type=\"hidden\" name=\"pbestimator\">
<input type=\"hidden\" name=\"pbestimatorcel\">
<input type=\"hidden\" name=\"pbdms\">
<input type=\"hidden\" name=\"pbmember\">
<input type=\"hidden\" name=\"pbfactoring\">
<input type=\"hidden\" name=\"pbsize\">

<input type=\"hidden\" name=\"pbcostingclerk\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">



<input type=\"hidden\" name=\"pbcontactperson\">



<input type=\"hidden\" name=\"pbcontactnumber\">

<input type=\"hidden\" name=\"pbcontactnumber2\">



<input type=\"hidden\" name=\"pbfaxno\">



<input type=\"hidden\" name=\"pbemail\">



<input type=\"hidden\" name=\"pbadr1\">



<input type=\"hidden\" name=\"pbadr2\">



<input type=\"hidden\" name=\"pbadr3\">



<input type=\"hidden\" name=\"pbadr4\">

<input type=\"hidden\" name=\"notes\">






<input type=\"hidden\" name=\"vehiclemakemodel\">



<input type=\"hidden\" name=\"vehicleyear\">



<input type=\"hidden\" name=\"vehicleregistrationno\">


<input type=\"hidden\" name=\"vehiclemake\" >
<input type=\"hidden\" name=\"vehicletype\">
<input type=\"hidden\" name=\"vehiclevin\">







<input type=\"hidden\" name=\"adminid\">







<input type=\"hidden\" name=\"quoteno\">



<input type=\"hidden\" name=\"insurerid\">



<input type=\"hidden\" name=\"claimsclerk\">



<input type=\"hidden\" name=\"authamount\">



<input type=\"hidden\" name=\"excess\">

<input type=\"hidden\" name=\"excess_description\">



<input type=\"hidden\" name=\"betterment\">



<input type=\"hidden\" name=\"quoteamount\">







<input type=\"hidden\" name=\"assid\">



<input type=\"hidden\" name=\"reload\">



<input type=\"hidden\" name=\"dothepb\">



<input type=\"hidden\" name=\"area\" />







		</form>";

}

function ccEditClaim($id, $step, $ccid)
{

    require ('connection.php');

    $claimid = $id;

    $qryclaimdetails = "select * from `claim` where `id` = $claimid";
    $qryclaimdetailsresults = mysql_query($qryclaimdetails, $db);

    $claimdetailsrow = mysql_fetch_array($qryclaimdetailsresults);

    $clientname = stripslashes($claimdetailsrow["clientname"]);
    $clientnumber2 = stripslashes($claimdetailsrow["clientno"]);
    $claimnumber = stripslashes($claimdetailsrow["claimno"]);
	$vehicleregistrationno = stripslashes($claimdetailsrow["vehicleregistrationno"]);

    $fromstep = $_REQUEST["fromstep"];

    if ($fromstep == 1)
    {

        SaveStep($claimid, 1, "yes", $ccid);

    }

    if ($fromstep == 3)
    {

        SaveStep($claimid, 3, "no", 0);

    }

    if ($fromstep == 5)
    {

        SaveStep($claimid, 5, "no", 0);

    }

    if ($step == 1)
    {

        echo "<form class='no-show-in-print'><input type=\"button\" value=\"Claim Details\" disabled />			



						<input type=\"button\" value=\"Parts\" onClick=\"document.theform.stepto.value = 2;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Dates\" onClick=\"document.theform.stepto.value = 3;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Reports\" onClick=\"document.theform.stepto.value = 4;



																		 document.theform.submit();\" />



						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.theform.stepto.value = 5;



																		 document.theform.submit();\" />	

																		 										 

				        <input type=\"button\" value=\"Attachments\" onClick=\"document.theform.stepto.value = 6;



																		 document.theform.submit();\" />
																		 
						<input type=\"button\" value=\"Quote\" onClick=\"document.theform.stepto.value = 7;



																		 document.theform.submit();\" />
												 

																		 </form>";

        $qry = "select * from claim where id = $claimid";

        $qryresults = mysql_query($qry, $db) or die('error: ' . mysql_error());

        $row = mysql_fetch_array($qryresults);

        $reloaded = $_REQUEST["reload"];

        if ($reloaded == 1)
        {

            $clientname = $_REQUEST["clientname"];

            $clientno = $_REQUEST["clientno"];

            $claimno = $_REQUEST["claimno"];

            $clientcontactno1 = $_REQUEST["clientcontactno1"];

            $clientcontactno2 = $_REQUEST["clientcontactno2"];

			$clientemail = $_REQUEST["clientemail"];

            $panelbeaterid = $_REQUEST["pbid"];

            $vehiclemakemodel = $_REQUEST["vehiclemakemodel"];

            $vehicleyear = $_REQUEST["vehicleyear"];

            $vehicleregistrationno = $_REQUEST["vehicleregistrationno"];

            $vehicletype = $_REQUEST["vehicletype"];

            $administratorid = $_REQUEST["adminid"];

            $quoteno = $_REQUEST["quoteno"];

            $insurerid = $_REQUEST["insurerid"];

            $claimsclerkid = $_REQUEST["claimsclerk"];

            $authamount = $_REQUEST["authamount"];

            $excess = $_REQUEST["excess"];

			$excess_description = $_REQUEST["excess_description"];

            $betterment = $_REQUEST["betterment"];

            $quoteamount = $_REQUEST["quoteamount"];

            $assessorid = $_REQUEST["assid"];

            $area = $_REQUEST["area"];

            if ($area == 0)
            {

                $assessorid = 0;

            }

        }

        else
        {

            $clientname = stripslashes($row["clientname"]);

            $clientno = $row["clientno"];

            $claimno = stripslashes($row["claimno"]);

            $clientcontactno1 = stripslashes($row["clientcontactno"]);

            $clientcontactno2 = stripslashes($row["clientcontactno2"]);

			$clientemail = stripslashes($row["clientemail"]);

            $panelbeaterid = $row["panelbeaterid"];

            $vehiclemakemodel = stripslashes($row["makemodel"]);

            $vehicleyear = stripslashes($row["vehicleyear"]);

            $vehicleregistrationno = stripslashes($row["vehicleregistrationno"]);

            $vehicletype = stripslashes($row["vehicletype"]);

            $administratorid = $row["administratorid"];

            $quoteno = stripslashes($row["quoteno"]);

            $insurerid = $row["insurerid"];

            $claimsclerkid = $row["claimsclerkid"];

            $authamount = $row["authamount"];

            $quoteamount = $row["quoteamount"];

            $excess = $row["excess"];

			$excess_description = $row["excess_description"];

            $betterment = $row["betterment"];

            $assessorid = $row["assessorid"];

            $area = 0;

        }

        echo "<script type=\"text/javascript\">



		



		function ReloadThisPage()



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;



			



			document.hiddenform.pbid.value = pbid;	



			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;



			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;

			document.hiddenform.excess_description.value = document.theform.excess_description.value;



			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 0;



			document.hiddenform.area.value = document.theform.area.value;



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



		function ReloadThisPagePB(area)



		{



			var pbid = document.theform.panelbeater.value;



			



			var adminid = document.theform.administrator.value;



			var assid = document.theform.assessor.value;



			



			document.hiddenform.clientno.value = document.theform.clientno.value;



			document.hiddenform.clientname.value = document.theform.clientname.value;



			document.hiddenform.claimno.value = document.theform.claimno.value;



			document.hiddenform.clientcontactno1.value = document.theform.clientcontactno1.value;



			document.hiddenform.clientcontactno2.value = document.theform.clientcontactno2.value;

			document.hiddenform.clientemail.value = document.theform.clientemail.value;


			



			document.hiddenform.pbid.value = pbid;	



			document.hiddenform.pbname.value = document.theform.pbname.value;	



			document.hiddenform.pbowner.value = document.theform.pbowner.value;
			document.hiddenform.pbworkshopmanageremail.value = document.theform.pbworkshopmanageremail.value;
			document.hiddenform.pbcostingclerkemail.value = document.theform.pbcostingclerkemail.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;
			document.hiddenform.pbowneremail.value = document.theform.pbowneremail.value;
			document.hiddenform.pbownercel.value = document.theform.pbownercel.value;



			document.hiddenform.pbcostingclerk.value = document.theform.pbcostingclerk.value;
			document.hiddenform.pbcostingclerkcel.value = document.theform.pbcostingclerkcel.value;



			document.hiddenform.pbcontactnumber.value = document.theform.pbcontactnumber.value;

			document.hiddenform.pbcontactnumber2.value = document.theform.pbcontactnumber2.value;



			document.hiddenform.pbcontactperson.value = document.theform.pbcontactperson.value;



			document.hiddenform.pbfaxno.value = document.theform.pbfaxno.value;



			document.hiddenform.pbemail.value = document.theform.pbemail.value;



			document.hiddenform.pbadr1.value = document.theform.pbadr1.value;



			document.hiddenform.pbadr2.value = document.theform.pbadr2.value;



			document.hiddenform.pbadr3.value = document.theform.pbadr3.value;



			document.hiddenform.pbadr4.value = document.theform.pbadr4.value;

			document.hiddenform.notes.value = document.theform.notes.value;



			



			document.hiddenform.vehiclemakemodel.value = document.theform.vehiclemakemodel.value;



			document.hiddenform.vehicleyear.value = document.theform.vehicleyear.value;



			document.hiddenform.vehicleregistrationno.value = document.theform.vehicleregistrationno.value;


			document.hiddenform.vehiclemake.value = document.theform.vehiclemake.value;
			document.hiddenform.vehicletype.value = document.theform.vehicletype.value;		
			document.hiddenform.vehiclevin.value = document.theform.vehiclevin.value;



			



			document.hiddenform.adminid.value = adminid;		



			



			document.hiddenform.quoteno.value = document.theform.quoteno.value;



			document.hiddenform.insurerid.value = document.theform.insurerid.value;







			document.hiddenform.authamount.value = document.theform.authamount.value;



			document.hiddenform.excess.value = document.theform.excess.value;

			document.hiddenform.excess_description.value = document.theform.excess_description.value;




			document.hiddenform.betterment.value = document.theform.betterment.value;



			document.hiddenform.quoteamount.value = document.theform.quoteamount.value;



			



			document.hiddenform.assid.value = assid;



					



			document.hiddenform.reload.value = 1;



			document.hiddenform.dothepb.value = 1;	



			



			if (area == 1)



			{



				document.hiddenform.assid.value = 0;



			}



			document.hiddenform.area.value = document.theform.area.value;		



			



			//alert(id);



			



			document.hiddenform.submit();



	



		}



		



	</script>";

        echo "



					<form method=\"post\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"theform\">

						<p>Client NumberAA: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>			


					<table>



					<tr>



						<td>	



							<table bgcolor=\"#E7E7FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=\"blue-bg\">
								<tr>
									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Client:</h4>
									
										<div style=\"display:inline-block;\">
											Client NumberBB: <input type=\"text\" value=\"$clientno\" maxlength=\"50\" name=\"clientno\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Name: <input type=\"text\" value=\"$clientname\" maxlength=\"50\" name=\"clientname\" />
										</div>

										<div style=\"display:inline-block;\">
											Claim Number: <input type=\"text\" value=\"$claimno\" maxlength=\"50\" name=\"claimno\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Contact No: <input type=\"text\" value=\"$clientcontactno1\" maxlength=\"50\" name=\"clientcontactno1\" />
										</div>

										<div style=\"display:inline-block;\">
											Client Contact No 2: <input type=\"text\" value=\"$clientcontactno2\" maxlength=\"50\" name=\"clientcontactno2\" />
										</div>

										<div style=\"display:inline-block;\">
											Email Address: <input type=\"text\" value=\"$clientemail\" maxlength=\"50\" name=\"clientemail\" />
										</div>
									</td>
								</tr>
							</table>



							<br />



							<table bgcolor=\"#D3D3FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>

									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Panelbeater:</h4>
										
										<div style=\"display:inline-block;\">
											Panelbeater: 

											<select name=\"panelbeater\" onChange=\"ReloadThisPage();\">";

											$qrygetpanelbeaters = "select * from panelbeaters order by `name`";
											$qrygetpanelbeatersresults = mysql_query($qrygetpanelbeaters, $db);

											while ($row = mysql_fetch_array($qrygetpanelbeatersresults))
											{
												$pbid = $row["id"];
												$pbname = stripslashes($row["name"]);
												if ($pbid == $panelbeaterid) {
													echo "<option value=\"$pbid\" selected>$pbname</option>";
												}
												else {
													echo "<option value=\"$pbid\">$pbname</option>";
												}
											}

											$qrygetpanelbeaterinfo = "select * from panelbeaters where `id` = $panelbeaterid";
											$qrygetpanelbeaterinforesults = mysql_query($qrygetpanelbeaterinfo, $db);
											$selectedpbrow = mysql_fetch_array($qrygetpanelbeaterinforesults);
											$dothepb = $_REQUEST["dothepb"];
											//echo "asdfasdf $dothepb ASDFSADF";
											$pbname = $selectedpbrow["name"];
											$pbowner = $selectedpbrow["owner"];
											$pbcostingclerk = $selectedpbrow["costingclerk"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbcontactperson = $selectedpbrow["contactperson"];
											$pbworkshopmanager = $selectedpbrow["workshopmanager"];
											$pbcontactnumber = $selectedpbrow["contactno"];
											$pbcontactnumber2 = $selectedpbrow["contactno2"];
											$pbfaxno = $selectedpbrow["faxno"];
											$pbemail = $selectedpbrow["email"];
											$pbadr1 = $selectedpbrow["adr1"];
											$pbadr2 = $selectedpbrow["adr2"];
											$pbadr3 = $selectedpbrow["adr3"];
											$pbadr4 = $selectedpbrow["adr4"];
											$notes = $selectedpbrow["notes"];
											$pbowneremail = $selectedpbrow["owneremail"];
											$pbownercel = $selectedpbrow["ownercel"];
											$pbcostingclerkemail = $selectedpbrow["costingclerkemail"];
											$pbcostingclerkcel = $selectedpbrow["costingclerkcel"];
											$pbworkshopmanageremail = $selectedpbrow["workshopmanageremail"];

											$pbestimator = $selectedpbrow["estimator"];
											$pbestimatoremail = $selectedpbrow["estimatoremail"];
											$pbpartsmanager = $selectedpbrow["partsmanager"];
											$pbpartsmanagercel = $selectedpbrow["partsmanagercel"];
											$pbpartsmanageremail = $selectedpbrow["partsmanageremail"];
											$latitude = $selectedpbrow["latitude"];
											$longitude = $selectedpbrow["longitude"];

											if ($dothepb == 1)
											{
												$pbname = $_REQUEST["pbname"];
												$pbowner = $_REQUEST["pbowner"];
												$pbcostingclerk = $_REQUEST["pbcostingclerk"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbcontactperson = $_REQUEST["pbcontactperson"];
												$pbworkshopmanager = $_REQUEST["pbworkshopmanager"];
												$pbcontactnumber = $_REQUEST["pbcontactnumber"];
												$pbcontactnumber2 = $_REQUEST["pbcontactnumber2"];
												$pbfaxno = $_REQUEST["pbfaxno"];
												$pbemail = $_REQUEST["pbemail"];
												$pbadr1 = $_REQUEST["pbadr1"];
												$pbadr2 = $_REQUEST["pbadr2"];
												$pbadr3 = $_REQUEST["pbadr3"];
												$pbadr4 = $_REQUEST["pbadr4"];
												$notes = $_REQUEST["notes"];
												$pbowneremail = $_REQUEST["pbowneremail"];
												$pbownercel = $_REQUEST["pbownercel"];
												$pbcostingclerkemail = $_REQUEST["pbcostingclerkemail"];
												$pbcostingclerkcel = $_REQUEST["pbcostingclerkcel"];
												$pbworkshopmanageremail = $_REQUEST["pbworkshopmanageremail"];

												$pbestimator = $_REQUEST["pbestimator"];
												$pbestimatoremail = $_REQUEST["pbestimatoremail"];
												$pbpartsmanager = $_REQUEST["pbpartsmanager"];
												$pbpartsmanagercel = $_REQUEST["pbpartsmanagercel"];
												$pbpartsmanageremail = $_REQUEST["pbpartsmanageremail"];
												$latitude = $_REQUEST["latitude"];
												$longitude = $_REQUEST["longitude"];
	
											}
											
											$emailSubject = ucwords("$clientnumber2, $clientname, $claimnumber, $vehicleregistrationno, $vehiclemakemodel");

											echo "					</select>

										</div>

										<div style=\"display:inline-block;\">
											Panelbeater: 
											<input type=\"text\" value=\"$pbname\" maxlength=\"50\" name=\"pbname\" style='width:300px;' />
										</div>
									
									</td>

								</tr>

								<tr>
									<td colspan=\"6\">
										<div style=\"display:inline-block;width:24%;\">
											Tel: <input type=\"text\" value=\"$pbcontactnumber\" maxlength=\"50\" name=\"pbcontactnumber\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Tel 2: <input type=\"text\" value=\"$pbcontactnumber2\" maxlength=\"50\" name=\"pbcontactnumber2\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Fax: <input type=\"text\" value=\"$pbfaxno\" maxlength=\"50\" name=\"pbfaxno\"  />
										</div>

										<div style=\"display:inline-block;width:24%;\">
											Email: <input type=\"text\" value=\"$pbemail\" maxlength=\"255\" name=\"pbemail\" class='textinput-lg' />
											<a href=\"mailto:$pbemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
										</div>
									</td>
								</tr>

								<tr>
									<td>Owner/Manager:</td>
									<td><input type=\"text\" value=\"$pbowner\" maxlength=\"50\" name=\"pbowner\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbownercel\" maxlength=\"50\" name=\"pbownercel\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbowneremail\" maxlength=\"50\" name=\"pbowneremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbowneremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Contact: </td>
									<td><input type=\"text\" value=\"$pbcontactperson\" maxlength=\"50\" name=\"pbcontactperson\"  /></td>
								</tr>

								<tr>
									<td>Costing Clerk:</td>
									<td><input type=\"text\" value=\"$pbcostingclerk\" maxlength=\"50\" name=\"pbcostingclerk\"  /></td>
									
									<td>Cel/Ext:</td>
									<td><input type=\"text\" value=\"$pbcostingclerkcel\" maxlength=\"50\" name=\"pbcostingclerkcel\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbcostingclerkemail\" maxlength=\"50\" name=\"pbcostingclerkemail\" class='textinput-lg'/>
										<a href=\"mailto:$pbcostingclerkemail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:15px;vertical-align:middle;\" /></a>
									</td>

									<td>Address: </td>
									<td><input type=\"text\" value=\"$pbadr1\" maxlength=\"50\" name=\"pbadr1\"  /></td>
								</tr>

								<tr>
									<td>Workshop Manager:</td>
									<td><input type=\"text\" value=\"$pbworkshopmanager\" maxlength=\"50\" name=\"pbworkshopmanager\"  /></td>

									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbworkshopmanageremail\" maxlength=\"50\" name=\"pbworkshopmanageremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbworkshopmanageremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
									</td>

									<td>&nbsp;</td>
									<td><input type=\"text\" value=\"$pbadr2\" maxlength=\"50\" name=\"pbadr2\"  /></td>
								</tr>

								<tr>
									<td>Estimator: </td>
									<td><input type=\"text\" value=\"$pbestimator\" maxlength=\"50\" name=\"pbestimator\"  /></td>
									<td>Email Address: </td>
									<td><input type=\"text\" value=\"$pbestimatoremail\" maxlength=\"50\" name=\"pbestimatoremail\" class='textinput-lg'/>
										<a href=\"mailto:$pbestimatoremail?subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" class=\"send-email\" type=\"Panelbeaters\" claimId=\"$claimid\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
									</td>
									<td>Province: </td>
									<td><input type=\"text\" value=\"$pbadr3\" maxlength=\"50\" name=\"pbadr3\"  /></td>
								</tr>
								
								<tr>
									<td rowspan=\"3\">Notes:</td>
									<td rowspan=\"3\" colspan=\"3\">
										<textarea name=\"notes\" style='width:400px;height:85px;'>$notes</textarea>
									</td>
									
									<td>Area Code: </td>
									<td><input type=\"text\" value=\"$pbadr4\" maxlength=\"50\" name=\"pbadr4\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Latitude: </td>
									<td><input type=\"text\" value=\"$latitude\" maxlength=\"50\" name=\"latitude\"  /></td>
								</tr>

								<tr>
									<td style='padding-left:0;'>Longitude: </td>
									<td><input type=\"text\" value=\"$longitude\" maxlength=\"50\" name=\"longitude\"  /></td>
								</tr>

							</table>



							<br />



							<table bgcolor=\"#BFBFFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>
									<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Vehicle:</h4>
									
										<div style=\"display:inline-block;\">
											Vehicle Type: 
											<select name=\"vehicletype\">";

											$vehicleTypesList = getVehicleTypesList();

											foreach ($vehicleTypesList as $vType) {
												$isSelected = ($vehicletype == $vType) ? 'selected="selected"' : '';
												echo '<option value="'.$vType.'" '.$isSelected.'>'.$vType.'</option>';
											}
											echo "					</select>
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Make/Model: 
											<input type=\"text\" value=\"$vehiclemakemodel\" maxlength=\"50\" name=\"vehiclemakemodel\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Year: 
											<input type=\"text\" value=\"$vehicleyear\" maxlength=\"10\" name=\"vehicleyear\" />
										</div>

										<div style=\"display:inline-block;\">
											Vehicle&nbsp;Registration&nbsp;No: 
											<input type=\"text\" value=\"$vehicleregistrationno\" maxlength=\"50\" name=\"vehicleregistrationno\" />
										</div>

									</td>

								</tr>


							</table>



							<br />



							<table bgcolor=\"#ABABFF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>

								<tr>

									<td colspan=\"6\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Administrator:</h4>
										
										<select name=\"administrator\" onChange=\"ReloadThisPagePB(0);\">";

										$qrygetadministrators = "select * from administrators order by `name`";

										$qrygetadministratorsresults = mysql_query($qrygetadministrators, $db);

										while ($administratorrow = mysql_fetch_array($qrygetadministratorsresults))
										{

											$adminid = $administratorrow["id"];

											$adminname = $administratorrow["name"];

											if ($administratorid == $adminid)
											{

												echo "<option value=\"$adminid\" selected>$adminname</option>";

											}

											else
											{

												echo "<option value=\"$adminid\">$adminname</option>";

											}

										}

										$qrygetadministratorinfo = "select * from administrators where `id` = $administratorid";

										$qrygetadministratorinforesults = mysql_query($qrygetadministratorinfo, $db);

										$administratorinforow = mysql_fetch_array($qrygetadministratorinforesults);

										$admintelno = stripslashes($administratorinforow["telno"]);

										$adminfaxno = stripslashes($administratorinforow["faxno"]);

										$adminadr1 = stripslashes($administratorinforow["adr1"]);

										$adminadr2 = stripslashes($administratorinforow["adr2"]);

										$adminadr3 = stripslashes($administratorinforow["adr3"]);

										$adminadr4 = stripslashes($administratorinforow["adr4"]);

										$vatno = stripslashes($administratorinforow["vatno"]);

										echo "					</select>


										Insurance Company:

										<select name=\"insurerid\"><option value=\"0\">Select one</option>";

										$qryinsurers = "select * from `insurers` order by `name`";

										$qryinsurersresults = mysql_query($qryinsurers, $db);

										while ($insrow = mysql_fetch_array($qryinsurersresults))
										{

											$insid = $insrow["id"];

											$insurancecompname = stripslashes($insrow["name"]);

											if ($insid == $insurerid)
											{

												echo "<option value=\"$insid\" selected>$insurancecompname</option>";

											}

											else
											{

												echo "<option value=\"$insid\">$insurancecompname</option>";

											}

										}

										echo " </select>
									
									</td>

								</tr>

								<tr>
									<td colspan=\"5\">
										Tel: $admintelno, 
										Fax: $adminfaxno,
										P.O.Box: $adminadr1, $adminadr2, $adminadr3 ";
										
									if ( !empty($adminadr4) ) { echo $adminadr4; }
								echo "
									</td>
								</tr>
								
								<tr>
									<td colspan=\"5\">
										Claim Technician: 

										<select name=\"claimsclerk\" id=\"claimsclerk\">";

										$qryclaimsclerks = "select * from claimsclerks order by `name`";

										$qryclaimsclerksresults = mysql_query($qryclaimsclerks, $db);
										
										$defaultEmail = '';
										$counter = 0;
										while ($ccrow = mysql_fetch_array($qryclaimsclerksresults))
										{

											$ccid = $ccrow["id"];

											$ccname = stripslashes($ccrow["name"]);
											$ccemailid = stripslashes($ccrow["email"]);

											if ($counter==0) {
												$defaultEmail = $ccemailid;
											}

											if ($claimsclerkid == $ccid)
											{
												$defaultEmail = $ccemailid;

												echo "<option value=\"$ccid\" selected email=\"$ccemailid\" >$ccname</option>";

											}

											else
											{

												echo "<option value=\"$ccid\" email=\"$ccemailid\">$ccname</option>";

											}

											$counter++;

										}

										echo " </select>

										<a href=\"mailto:$defaultEmail?subject=$emailSubject\"  type=\"Claim\" claimId=\"$claimid\" class=\"send-email\" emailpart=\"subject=$emailSubject\" alt=\"Send Email\" title=\"Send Email\" id=\"claimTechnicianEmailLink\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a>
										";
				
										global $admin;
										if ( $admin == 1 ) { echo " VAT&nbsp;Number: $vatno "; }

										echo "
									</td>
								</tr>

							</table>



							<br />



							<table bgcolor=\"#9797FF\" style=\"border:1px solid #000000;\" width=\"100%\" class='blue-bg'>
							<tr>
								<td colspan=\"5\"><h4 style=\"margin-top:0px;margin-bottom:0;display:inline-block;\">Claim:</h4>										
									<div style=\"display:inline-block;\">
										Quote&nbsp;Number: <input type=\"text\" value=\"$quoteno\" maxlength=\"50\" name=\"quoteno\" />
									</div>
									<div style=\"display:inline-block;\">
										Authorised&nbsp;Amount: <input type=\"text\" value=\"$authamount\" maxlength=\"11\" name=\"authamount\" />
									</div>
									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess\" maxlength=\"11\" name=\"excess\" />
									</div>

									<div style=\"display:inline-block;\">
										Excess: <input type=\"text\" value=\"$excess_description\" style='width:300px;' name=\"excess_description\" />
									</div>
									<div style=\"display:inline-block;\">
										Betterment: <input type=\"text\" value=\"$betterment\" maxlength=\"11\" name=\"betterment\" />
									</div>
									<div style=\"display:inline-block;\">
										Quoted&nbsp;Amount: <input type=\"text\" value=\"$quoteamount\" maxlength=\"11\" name=\"quoteamount\" />
									</div>
									";
								
									$res = mysql_query("SELECT `received` FROM `dates` WHERE claimid='$claimid' ", $db);

									$daterow = mysql_fetch_array($res);

									$received = explode('-', $daterow['received']);

									echo "
										<div style=\"display:inline-block;\">
											Date Received:

											<input type=\"text\" style=\"width:20px;\" value=\"" . $received[2] . "\" name=\"receivedday\" readonly> -	<input type=\"text\" style=\"width:20px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> -  <input type=\"text\" style=\"width:35px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly> 
											<a href=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\"  title=\"cal1.showCalendar('anchor1'); return false;\" name=\"anchor1\" id=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></a>

											<script type='text/javascript'>	
												var cal1 = new CalendarPopup();
												cal1.setReturnFunction(\"setMultipleValues1\");

												function setMultipleValues1(y,m,d) {
													document.theform.receivedyear.value=y;
													document.theform.receivedmonth.value=LZ(m);
													document.theform.receivedday.value=LZ(d);
												}
											</script>

										</div>
								</td>
							</tr>
						</table>



							<br />



							<table bgcolor=\"#8383FF\" style=\"border:1px solid #000000;\" width=\"100%\" class=' blue-bg'>



								<tr>



									<td colspan=\"5\"><h4 style=\"margin-top:0px;display:inline-block;margin-bottom:0;\">Assessor Details:</h4>
										
										Select&nbsp;Assessor&nbsp;Area:

										<select name=\"area\" onChange=\"ReloadThisPagePB(1);\">
										<option value=\"0\">Select Assessor Area</option>";

										$qryareas = "select * from areas order by areaname";

										$qryareasresults = mysql_query($qryareas, $db);

										while ($arearow = mysql_fetch_array($qryareasresults))
										{

											$areaid = $arearow["id"];

											$areaname = $arearow["areaname"];

											if ($areaid == $area)
											{

												echo "<option value=\"$areaid\" selected>$areaname</option>";

											}

											else
											{

												echo "<option value=\"$areaid\">$areaname</option>";

											}

										}

									echo "</select>";


									echo " Select&nbsp;Assessor:


									<select name=\"assessor\" onChange=\"ReloadThisPagePB(0);\"><option value=\"0\">Select Assessor</option>";

										$qryassessors = "select * from assessors order by `company`";

										$qryassessorsresults = mysql_query($qryassessors, $db);

										while ($assrow = mysql_fetch_array($qryassessorsresults))
										{

											$assid = $assrow["id"];

											$assname = $assrow["name"];
											$asscompanyoption = $assrow["company"] . ' (' . $assname . ')';

											//check to see if this assessor is in the selected Area

											if ($area != 0)
											{

												$qrycheckarea = "select count(assessorid) as counted from assessor_area where assessorid = $assid and areaid = $area";

												$qrycheckarearesults = mysql_query($qrycheckarea, $db);

												$checkarearow = mysql_fetch_array($qrycheckarearesults);

												$count = $checkarearow["counted"];

												if ($count == 1)
												{

													if ($assessorid == $assid)
													{

														echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

													}

													else
													{

														echo "<option value=\"$assid\">$asscompanyoption</option>";

													}

												}

											}

											else
											{

												if ($assessorid == $assid)
												{

													echo "<option value=\"$assid\" selected>$asscompanyoption</option>";

												}

												else
												{

													echo "<option value=\"$assid\">$asscompanyoption</option>";

												}

											}

										}

										$qrygetassessorinfo = "select * from assessors where `id` = $assessorid";

										$qrygetassessorinforesults = mysql_query($qrygetassessorinfo, $db);

										$assessorrow = mysql_fetch_array($qrygetassessorinforesults);

										$assname = stripslashes($assessorrow["name"]);

										$asscompany = stripslashes($assessorrow["company"]);

										$asstelno = stripslashes($assessorrow["telno"]);

										$assfaxno = stripslashes($assessorrow["faxno"]);

										$asscellno = stripslashes($assessorrow["cellno"]);

										$assemail = stripslashes($assessorrow["email"]);

										$asscomments = stripslashes($assessorrow["comments"]);

									echo " </select>";
									
									echo "</td>									



								</tr>";

								

								if ( !empty($assname) ) {

									$ass_string = ucwords(trim($asscompany)) . ', ' . ucwords(trim($assname)) . ', Tel:' . $asstelno . ', Fax:' . $assfaxno . ', Cel/Ext:' . $asscellno . ', Email:' . $assemail;

									echo "<tr>
										<td colspan=\"5\"> $ass_string 
										
										<div style=\"display: inline; \"><a href=\"mailto:$assemail?subject=$emailSubject\" type=\"Assessors\" claimId=\"$claimid\" class=\"send-email\" alt=\"Send Email\" title=\"Send Email\" ><img src=\"../images/email-send.png\" style=\"height:20px;vertical-align:middle;\" /></a></div>

										</td>
									</tr>";
								
								}



								echo "<tr>

									<td>Comments:</td>

									<td colspan='4'>
										<textarea name=\"asscomments\" style='width:660px;height:40px;' >$asscomments</textarea>
									</td>

								</tr>



							</table>



						</td>



					</tr>



					</table>



					<br />
					
					<div class='no-show-in-print'>
					<p style=\"display:inline-block;\">Make the desired changes to the claim and click Next/Save</p>

					<input type=\"submit\" value=\"Next/Save >>\" /> <input type=\"hidden\" value=\"1\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					</div>



					</form>";

        echo "<form action=\"ccloggedinaction.php?action=cceditclaim\" method=\"POST\" name=\"hiddenform\">



<input type=\"hidden\" name=\"clientname\">



<input type=\"hidden\" name=\"clientno\">



<input type=\"hidden\" name=\"claimno\">



<input type=\"hidden\" name=\"clientcontactno1\">



<input type=\"hidden\" name=\"clientcontactno2\">

<input type=\"hidden\" name=\"clientemail\">







<input type=\"hidden\" name=\"pbid\">



<input type=\"hidden\" name=\"pbname\">



<input type=\"hidden\" name=\"pbowner\">
<input type=\"hidden\" name=\"pbworkshopmanageremail\">
<input type=\"hidden\" name=\"pbcostingclerkemail\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">
<input type=\"hidden\" name=\"pbowneremail\">
<input type=\"hidden\" name=\"pbownercel\">


<input type=\"hidden\" name=\"pbestimator\">
<input type=\"hidden\" name=\"pbestimatorcel\">
<input type=\"hidden\" name=\"pbdms\">
<input type=\"hidden\" name=\"pbmember\">
<input type=\"hidden\" name=\"pbfactoring\">
<input type=\"hidden\" name=\"pbsize\">


<input type=\"hidden\" name=\"pbcostingclerk\">
<input type=\"hidden\" name=\"pbcostingclerkcel\">



<input type=\"hidden\" name=\"pbcontactperson\">



<input type=\"hidden\" name=\"pbcontactnumber\">

<input type=\"hidden\" name=\"pbcontactnumber2\">



<input type=\"hidden\" name=\"pbfaxno\">



<input type=\"hidden\" name=\"pbemail\">



<input type=\"hidden\" name=\"pbadr1\">



<input type=\"hidden\" name=\"pbadr2\">



<input type=\"hidden\" name=\"pbadr3\">



<input type=\"hidden\" name=\"pbadr4\">


<input type=\"hidden\" name=\"notes\">







<input type=\"hidden\" name=\"vehiclemakemodel\">



<input type=\"hidden\" name=\"vehicleyear\">



<input type=\"hidden\" name=\"vehicleregistrationno\">


<input type=\"hidden\" name=\"vehiclemake\">
<input type=\"hidden\" name=\"vehicletype\">
<input type=\"hidden\" name=\"vehiclevin\">







<input type=\"hidden\" name=\"adminid\">







<input type=\"hidden\" name=\"quoteno\">



<input type=\"hidden\" name=\"insurerid\">



<input type=\"hidden\" name=\"authamount\">



<input type=\"hidden\" name=\"excess\">

<input type=\"hidden\" name=\"excess_description\">




<input type=\"hidden\" name=\"betterment\">



<input type=\"hidden\" name=\"quoteamount\">







<input type=\"hidden\" name=\"assid\">



<input type=\"hidden\" name=\"reload\">



<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



<input type=\"hidden\" name=\"stepto\" value=\"1\" />



<input type=\"hidden\" name=\"dothepb\" />



<input type=\"hidden\" name=\"area\" />











			</form>";

    }

    if ($step == 2)
    {

        //show the items for this claim:

        //echo "</tr><tr><td>";

        echo "<form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"topform\" class='no-show-in-print'>



						<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																		 document.topform.submit();\" />



			  						



						<input type=\"button\" value=\"Parts\" disabled />



						<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																		 document.topform.submit();\" />



						<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;



																		 document.topform.submit();\" />



						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;



																		 document.topform.submit();\" />

																		 

						<input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 6;



																		 document.topform.submit();\" />

																		 
						<input type=\"button\" value=\"Quote\" onClick=\"document.topform.stepto.value = 7;



																		 document.topform.submit();\" />


						<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"2\" /> 		 



																		 </form>";

        $qry = "SELECT * FROM items where claimid = $claimid";

        //echo $qry;

        $qrycount = mysql_query($qry, $db);

        $qryitems = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br><form action=\"ccloggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">

								<p>Client NumberCC: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>

								There are no Items in the database. Click <input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" /> 



								<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" /> to add one.



								<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



						  </form>";

        }

        else
        {

            echo "	<p>Client NumberDD: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
			
			<p>



						<form name=\"theitems\" method=\"post\" action=\"ccloggedinaction.php?action=savetheitems\">



						  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">



							  <tr>



								  <td><strong>Qty</strong></td>



								  <td><strong>Description</strong></td>



								  <td><strong>Quoted</strong></td>



								  <td><strong>Cost</strong></td>



								  <td><strong>1.25</strong></td>



								  <td><strong>Adjustment</strong></td>



								  <td><strong>User</strong>



								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>



							  </tr>";

            while ($row = mysql_fetch_array($qryitems))
            {

                // give a name to the fields

                $itemid = $row["id"];

                $qty = $row["qty"];

                $desc = stripslashes($row["description"]);

                $quoted = $row["quoted"];

                $cost = $row["cost"];

                $onetwofive = $row["onetwofive"];

                $adjustment = $row["adjustment"];

                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";

                $qrygetusernameresults = mysql_query($qrygetusername, $db);

                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen

                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td align=\"center\">$qty</td>



							  <td style=\"width:250px;\">$desc</td>



							  <td align=\"right\">$quoted</td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"cost_" .
                    $itemid . "\" value=\"$cost\" onKeyUp=\"if (!isNaN(document.theitems.cost_" . $itemid .
                    ".value * 1.25))



																															   {



																																	document.theitems.onetwofive_" . $itemid .
                    ".value = (Math.round((document.theitems.cost_" . $itemid .
                    ".value * 1.25) * 100) / 100);  



																																	document.theitems.adjustment_" . $itemid .
                    ".value = (Math.round((document.theitems.onetwofive_" . $itemid . ".value - $quoted) * 100) / 100);



																															   }



																															   \"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"onetwofive_" .
                    $itemid . "\" value=\"$onetwofive\" onKeyUp=\"if (!isNaN(document.theitems.onetwofive_" .
                    $itemid . ".value - $quoted))



																																	 {



																																		document.theitems.adjustment_" . $itemid .
                    ".value = document.theitems.onetwofive_" . $itemid . ".value - $quoted;



																																	 }



																																		\"></td>



							  <td><input style=\"width:98%;text-align:right;\" type=\"text\" name=\"adjustment_" .
                    $itemid . "\" value=\"$adjustment\"></td>



							  <td>$user</td>



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=edititem&amp;itemid=$itemid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Item\" border=\"0\" title=\"Edit this Item\"></td>



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=confirmdeleteitem&amp;itemid=$itemid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Item\" border=\"0\" title=\"Delete this Item\"></td>



						  </tr>";

            }//end while loop

            $qrysum = "select sum(adjustment) as totaladjustment, sum(onetwofive) as totalonetwofive, sum(cost) as totalcost, sum(quoted) as totalquoted from items where claimid = $claimid";

            $qrysumresults = mysql_query($qrysum, $db);

            $totalrow = mysql_fetch_array($qrysumresults);

            $total = $totalrow["totaladjustment"];

            $onetwofive = $totalrow["totalonetwofive"];

            $quoted = $totalrow["totalquoted"];

            $cost = $totalrow["totalcost"];

            echo "	<tr>



							<td colspan=\"2\" align=\"right\">TOTALS:</td>										



							<td align=\"right\">$quoted</td>



							<td align=\"right\">$cost</td>



							<td align=\"right\">$onetwofive</td>



							<td align=\"right\">$total</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



							<td colspan=\"2\" align=\"right\">TOTALS INC VAT:</td>										



							<td align=\"right\">" . round($quoted * 1.14, 2) . "</td>



							<td align=\"right\">" . round($cost * 1.14, 2) . "</td>



							<td align=\"right\">" . round($onetwofive * 1.14, 2) . "</td>



							<td align=\"right\">" . round($total * 1.14, 2) . "</td>



							<td colspan=\"3\">&nbsp;</td>



						</tr>



						<tr>



						  <td colspan=\"6\">&nbsp;<input type=\"submit\" value=\"Save Items\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\"></form></td>



						  <td colspan=\"3\" align=\"center\">



						  



						  <form action=\"ccloggedinaction.php?action=newitem\" method=\"POST\" name=\"addnewitemform\">



								<input type=\"image\" src=\"../images/admin/add.gif\" width=\"20\" height=\"16\" title=\"Add new Item for this Claim\" />



								<input type=\"text\" value=\"1\" size=\"1\" maxlength=\"2\" name=\"qty\" />



								<input type=\"hidden\" value=\"$claimid\" name=\"claimid\" />



						  </form>					  



						  </td>



					  </tr>



				</table>



				



					</p>";

        }

        echo "<br>



				<form>



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 1;



																	   document.topform.submit();\" >



					<input type=\"button\" value=\"Next >>\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" >



				</form>";

    }

    if ($step == 3)
    {

        echo "<form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" class='no-show-in-print'>



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.mainform.stepto.value = 1;



																	 document.mainform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.mainform.stepto.value = 2;



																	 document.mainform.submit();\" />



					<input type=\"button\" value=\"Dates\" disabled />



					<input type=\"button\" value=\"Reports\" onClick=\"document.mainform.stepto.value = 4;



																	 document.mainform.submit();\" />



					<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.mainform.stepto.value = 5;



																	 document.mainform.submit();\" />

																	 

					<input type=\"button\" value=\"Attachments\" onClick=\"document.mainform.stepto.value = 6;



																	 document.mainform.submit();\" />

					<input type=\"button\" value=\"Quote\" onClick=\"document.mainform.stepto.value = 7;



																	 document.mainform.submit();\" />												 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">											 



																	 </form>";

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        //$received = "2007-05-21";

        $received = explode("-", $received);

        $loss = $datesrow["loss"];

        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];

        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];

        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];

        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];

        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $auth = explode("-", $auth);

        $wp = $datesrow["wp"];

        $wp = explode("-", $wp);

        $docreq = $datesrow["docreq"];

        $docreq = explode("-", $docreq);

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $workinprogressinsp = explode("-", $workinprogressinsp);

        $dod = $datesrow["dod"];

        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $finalcosting = explode("-", $finalcosting);

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $acirepsentinsurer = explode("-", $acirepsentinsurer);

        $invoicesent = $datesrow["invoicesent"];

        $invoicesent = explode("-", $invoicesent);

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];

        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];

        $acipaymentreceived = explode("-", $acipaymentreceived);

        echo "



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">







	var cal1 = new CalendarPopup();



	cal1.setReturnFunction(\"setMultipleValues1\");







	function setMultipleValues1(y,m,d) 



	{



		document.mainform.receivedyear.value=y;



		document.mainform.receivedmonth.value=LZ(m);



		document.mainform.receivedday.value=LZ(d);



	}



	



	var cal2 = new CalendarPopup();



	cal2.setReturnFunction(\"setMultipleValues2\");







	function setMultipleValues2(y,m,d) 



	{



		document.mainform.lossyear.value=y;



		document.mainform.lossmonth.value=LZ(m);



		document.mainform.lossday.value=LZ(d);



	}



	



	var cal3 = new CalendarPopup();



	cal3.setReturnFunction(\"setMultipleValues3\");







	function setMultipleValues3(y,m,d) 



	{



		document.mainform.assappointedyear.value=y;



		document.mainform.assappointedmonth.value=LZ(m);



		document.mainform.assappointedday.value=LZ(d);



	}



	



	var cal4 = new CalendarPopup();



	cal4.setReturnFunction(\"setMultipleValues4\");







	function setMultipleValues4(y,m,d) 



	{



		document.mainform.assessmentyear.value=y;



		document.mainform.assessmentmonth.value=LZ(m);



		document.mainform.assessmentday.value=LZ(d);



	}



	



	var cal5 = new CalendarPopup();



	cal5.setReturnFunction(\"setMultipleValues5\");







	function setMultipleValues5(y,m,d) 



	{



		document.mainform.assessmentreportyear.value=y;



		document.mainform.assessmentreportmonth.value=LZ(m);



		document.mainform.assessmentreportday.value=LZ(d);



	}



	



	var cal6 = new CalendarPopup();



	cal6.setReturnFunction(\"setMultipleValues6\");







	function setMultipleValues6(y,m,d) 



	{



		document.mainform.assessmentinvtoinsureryear.value=y;



		document.mainform.assessmentinvtoinsurermonth.value=LZ(m);



		document.mainform.assessmentinvtoinsurerday.value=LZ(d);



	}



	



	var cal7 = new CalendarPopup();



	cal7.setReturnFunction(\"setMultipleValues7\");







	function setMultipleValues7(y,m,d) 



	{



		document.mainform.authyear.value=y;



		document.mainform.authmonth.value=LZ(m);



		document.mainform.authday.value=LZ(d);



	}



	



	var cal8 = new CalendarPopup();



	cal8.setReturnFunction(\"setMultipleValues8\");







	function setMultipleValues8(y,m,d) 



	{



		document.mainform.wpyear.value=y;



		document.mainform.wpmonth.value=LZ(m);



		document.mainform.wpday.value=LZ(d);



	}



	



	var cal9 = new CalendarPopup();



	cal9.setReturnFunction(\"setMultipleValues9\");







	function setMultipleValues9(y,m,d) 



	{



		document.mainform.docreqyear.value=y;



		document.mainform.docreqmonth.value=LZ(m);



		document.mainform.docreqday.value=LZ(d);



	}



	



	var cal10 = new CalendarPopup();



	cal10.setReturnFunction(\"setMultipleValues10\");







	function setMultipleValues10(y,m,d) 



	{



		document.mainform.workinprogressinspyear.value=y;



		document.mainform.workinprogressinspmonth.value=LZ(m);



		document.mainform.workinprogressinspday.value=LZ(d);



	}



	



	var cal11 = new CalendarPopup();



	cal11.setReturnFunction(\"setMultipleValues11\");







	function setMultipleValues11(y,m,d) 



	{



		document.mainform.dodyear.value=y;



		document.mainform.dodmonth.value=LZ(m);



		document.mainform.dodday.value=LZ(d);



	}



	



	var cal12 = new CalendarPopup();



	cal12.setReturnFunction(\"setMultipleValues12\");







	function setMultipleValues12(y,m,d) 



	{



		document.mainform.finalcostingyear.value=y;



		document.mainform.finalcostingmonth.value=LZ(m);



		document.mainform.finalcostingday.value=LZ(d);



	}



	



	var cal13 = new CalendarPopup();



	cal13.setReturnFunction(\"setMultipleValues13\");







	function setMultipleValues13(y,m,d) 



	{



		document.mainform.acirepsentinsureryear.value=y;



		document.mainform.acirepsentinsurermonth.value=LZ(m);



		document.mainform.acirepsentinsurerday.value=LZ(d);



	}



	



	var cal14 = new CalendarPopup();



	cal14.setReturnFunction(\"setMultipleValues14\");







	function setMultipleValues14(y,m,d) 



	{



		document.mainform.invoicesentyear.value=y;



		document.mainform.invoicesentmonth.value=LZ(m);



		document.mainform.invoicesentday.value=LZ(d);



	}



	



	var cal15 = new CalendarPopup();



	cal15.setReturnFunction(\"setMultipleValues15\");







	function setMultipleValues15(y,m,d) 



	{



		document.mainform.assfeereceivedfrominsureryear.value=y;



		document.mainform.assfeereceivedfrominsurermonth.value=LZ(m);



		document.mainform.assfeereceivedfrominsurerday.value=LZ(d);



	}



	



	var cal16 = new CalendarPopup();



	cal16.setReturnFunction(\"setMultipleValues16\");







	function setMultipleValues16(y,m,d) 



	{



		document.mainform.acipaymentreceivedyear.value=y;



		document.mainform.acipaymentreceivedmonth.value=LZ(m);



		document.mainform.acipaymentreceivedday.value=LZ(d);



	}







</SCRIPT>		  



			  ";

        echo "<p>Client NumberEE: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
		
		<br /><form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"mainform\">



					<table>



						<tr>



							<td>Date received</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $received[2] .
            "\" name=\"receivedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly>



								 <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" 



								 	TITLE=\"cal1.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" 



								 	ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>



						<tr>



							<td>Date of loss</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $loss[2] . "\" name=\"lossday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $loss[1] . "\" name=\"lossmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $loss[0] . "\" name=\"lossyear\" readonly>



								<A HREF=\"#\" onClick=\"cal2.showCalendar('anchor2'); return false;\" 



								 	TITLE=\"cal2.showCalendar('anchor2'); return false;\" NAME=\"anchor2\" 



								 	ID=\"anchor2\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>



						<tr>



							<td>Assessor appointed</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[2] .
            "\" name=\"assappointedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[1] .
            "\" name=\"assappointedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assappointed[0] .
            "\" name=\"assappointedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal3.showCalendar('anchor3'); return false;\" 



								 	TITLE=\"cal3.showCalendar('anchor3'); return false;\" NAME=\"anchor3\" 



								 	ID=\"anchor3\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td> 



						</tr>



						<tr>



							<td>Date of assessment</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[2] .
            "\" name=\"assessmentday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[1] .
            "\" name=\"assessmentmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessment[0] .
            "\" name=\"assessmentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal4.showCalendar('anchor4'); return false;\" 



								 	TITLE=\"cal4.showCalendar('anchor4'); return false;\" NAME=\"anchor4\" 



								 	ID=\"anchor4\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Assessment report date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[2] .
            "\" name=\"assessmentreportday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[1] .
            "\" name=\"assessmentreportmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentreport[0] .
            "\" name=\"assessmentreportyear\" readonly>



								<A HREF=\"#\" onClick=\"cal5.showCalendar('anchor5'); return false;\" 



								 	TITLE=\"cal5.showCalendar('anchor5'); return false;\" NAME=\"anchor5\" 



								 	ID=\"anchor5\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Assessment Invoice sent to Insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[2] .
            "\" name=\"assessmentinvtoinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[1] .
            "\" name=\"assessmentinvtoinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentinvtoinsurer[0] .
            "\" name=\"assessmentinvtoinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal6.showCalendar('anchor6'); return false;\" 



								 	TITLE=\"cal6.showCalendar('anchor6'); return false;\" NAME=\"anchor6\" 



								 	ID=\"anchor6\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Authorise date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $auth[2] . "\" name=\"authday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $auth[1] . "\" name=\"authmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $auth[0] . "\" name=\"authyear\" readonly>



								<A HREF=\"#\" onClick=\"cal7.showCalendar('anchor7'); return false;\" 



								 	TITLE=\"cal7.showCalendar('anchor7'); return false;\" NAME=\"anchor7\" 



								 	ID=\"anchor7\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Withhold payment date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $wp[2] . "\" name=\"wpday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $wp[1] . "\" name=\"wpmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $wp[0] . "\" name=\"wpyear\" readonly>



								<A HREF=\"#\" onClick=\"cal8.showCalendar('anchor8'); return false;\" 



								 	TITLE=\"cal8.showCalendar('anchor8'); return false;\" NAME=\"anchor8\" 



								 	ID=\"anchor8\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Document Request</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $docreq[2] . "\" name=\"docreqday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $docreq[1] . "\" name=\"docreqmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $docreq[0] . "\" name=\"docreqyear\" readonly>



								<A HREF=\"#\" onClick=\"cal9.showCalendar('anchor9'); return false;\" 



								 	TITLE=\"cal9.showCalendar('anchor9'); return false;\" NAME=\"anchor9\" 



								 	ID=\"anchor9\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>



						<tr>



							<td>Work in progress inspection date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $workinprogressinsp[2] .
            "\" name=\"workinprogressinspday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $workinprogressinsp[1] .
            "\" name=\"workinprogressinspmonth\" readonly> -



								<input type=\"text\" style=\"width:35px;\" value=\"" . $workinprogressinsp[0] .
            "\" name=\"workinprogressinspyear\" readonly>



								<A HREF=\"#\" onClick=\"cal10.showCalendar('anchor10'); return false;\" 



								 	TITLE=\"cal10.showCalendar('anchor10'); return false;\" NAME=\"anchor10\" 



								 	ID=\"anchor10\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Expected date of delivery</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $dod[2] . "\" name=\"dodday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $dod[1] . "\" name=\"dodmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $dod[0] . "\" name=\"dodyear\" readonly>



								<A HREF=\"#\" onClick=\"cal11.showCalendar('anchor11'); return false;\" 



								 	TITLE=\"cal11.showCalendar('anchor11'); return false;\" NAME=\"anchor11\" 



								 	ID=\"anchor11\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Final costing</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $finalcosting[2] .
            "\" name=\"finalcostingday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $finalcosting[1] .
            "\" name=\"finalcostingmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $finalcosting[0] .
            "\" name=\"finalcostingyear\" readonly>



								<A HREF=\"#\" onClick=\"cal12.showCalendar('anchor12'); return false;\" 



								 	TITLE=\"cal12.showCalendar('anchor12'); return false;\" NAME=\"anchor12\" 



								 	ID=\"anchor12\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>ACI report sent to insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $acirepsentinsurer[2] .
            "\" name=\"acirepsentinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $acirepsentinsurer[1] .
            "\" name=\"acirepsentinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $acirepsentinsurer[0] .
            "\" name=\"acirepsentinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal13.showCalendar('anchor13'); return false;\" 



								 	TITLE=\"cal13.showCalendar('anchor13'); return false;\" NAME=\"anchor13\" 



								 	ID=\"anchor13\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Date invoice sent</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $invoicesent[2] .
            "\" name=\"invoicesentday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $invoicesent[1] .
            "\" name=\"invoicesentmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $invoicesent[0] .
            "\" name=\"invoicesentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal14.showCalendar('anchor14'); return false;\" 



								 	TITLE=\"cal14.showCalendar('anchor14'); return false;\" NAME=\"anchor14\" 



								 	ID=\"anchor14\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>Assessment fee received from insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[2] .
            "\" name=\"assfeereceivedfrominsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[1] .
            "\" name=\"assfeereceivedfrominsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assfeereceivedfrominsurer[0] .
            "\" name=\"assfeereceivedfrominsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal15.showCalendar('anchor15'); return false;\" 



								 	TITLE=\"cal15.showCalendar('anchor15'); return false;\" NAME=\"anchor15\" 



								 	ID=\"anchor15\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



						<tr>



							<td>ACI payment received from insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $acipaymentreceived[2] .
            "\" name=\"acipaymentreceivedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $acipaymentreceived[1] .
            "\" name=\"acipaymentreceivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $acipaymentreceived[0] .
            "\" name=\"acipaymentreceivedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal16.showCalendar('anchor16'); return false;\" 



								 	TITLE=\"cal16.showCalendar('anchor16'); return false;\" NAME=\"anchor16\" 



								 	ID=\"anchor16\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>



					</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.mainform.stepto.value = 2;



																	   document.mainform.submit();\" /> 



					<input type=\"button\" value=\"Next >>\" onClick=\"document.mainform.stepto.value = 4;



																	   document.mainform.submit();\" /> 



					<input type=\"reset\" value=\"Reset\" /> <input type=\"hidden\" value=\"3\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }

    if ($step == 4)
    {

        echo "<form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"topform\" class='no-show-in-print'>



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;



																	 document.topform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Reports\" disabled  />



					<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;



																	 document.topform.submit();\" />

																	 

					<input type=\"button\" value=\"Attachments\" onClick=\"document.topform.stepto.value = 6;



																	 document.topform.submit();\" />



					<input type=\"button\" value=\"Quote\" onClick=\"document.topform.stepto.value = 7;



																	 document.topform.submit();\" />

																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">										<input type=\"hidden\" name=\"fromstep\" value=\"4\">		 



																	 </form>";

        echo "<p>Client NumberFF: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
		
			<p>Select which reports you want to view:<br><br>



								<a href=\"reports.php?action=pbfax&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Fax</a> 
								
								 || <a href=\"reports.php?action=pbdocrequest&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Document Request</a>

									
								|| <a href=\"reports.php?action=pbpartsrequest&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Panelbeater Parts Request</a>


								 || <a href=\"reports.php?action=auditreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Audit Report</a> 



								 || <a href=\"reports.php?action=invoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Invoice</a>



								 || <a href=\"reports.php?action=pbinvoice&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessor Invoice</a>



								 || <a href=\"reports.php?action=assessmentinstruction&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Instruction</a>



								 || <a href=\"reports.php?action=assessmentreport&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Assessment Report</a>



								 || <a href=\"reports.php?action=authorization&amp;claimid=$claimid\" target=\"_blank\" class=\"newWindow\">Authorization for Repairs</a></p>";

        $qry = "SELECT * FROM report where claimid = $claimid";

        $qrycount = mysql_query($qry, $db);

        $qryreports = mysql_query($qry, $db);

        $count = mysql_num_rows($qrycount);

        if ($count == 0)
        {

            echo "<br /><p>There are no Reports in the database. Click <a href=\"ccloggedinaction.php?action=newreport&amp;claimid=$claimid\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Report for this Claim\" title=\"Add new Report for this Claim\"></a> to add one.</p>";

        }

        else
        {

            echo "<br />



					  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\" width=\"770\">
							  <tr>
								  <td><strong>Report Date and Time</strong></td>
								  <td><strong>Description</strong></td>
								  <td><strong>User</strong></td>
								  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
							  </tr>";

            while ($row = @mysql_fetch_array($qryreports))
            {
                // give a name to the fields

                $reportid = $row['id'];
                $reportdate = $row['reportdate'];

                $year = substr($reportdate, 0, 4);
                $month = substr($reportdate, 5, 2);
                $day = substr($reportdate, 8, 2);

                $hour = substr($reportdate, 11, 2);
                $minute = substr($reportdate, 14, 2);

                $ourtime = mktime($hour, $minute, 0, $month, $day, $year);

                $reportdate = date("j M Y H:i", $ourtime);

                $reportdesc = $row['description'];
                $userid = $row["userid"];

                $qrygetusername = "select * from users where `id` = $userid";
                $qrygetusernameresults = mysql_query($qrygetusername, $db);
                $usernamerow = mysql_fetch_array($qrygetusernameresults);

                $user = $usernamerow["username"];

                //echo the results onscreen
                //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

                echo "<tr>



							  <td>$reportdate</td>



							  <td>$reportdesc</td>



							  <td>$user</td>



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=editreport&amp;reportid=$reportid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Report\" border=\"0\" title=\"Edit this Report\"></td>



							  <td align=\"center\"><a href=\"ccloggedinaction.php?action=confirmdeletereport&amp;reportid=$reportid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Report\" border=\"0\" title=\"Delete this Report\"></td>



						  </tr>";

            }//end while loop

            echo "<tr>



						  <td colspan=\"3\">&nbsp;</td>



						  <td colspan=\"2\" align=\"center\"><a href=\"ccloggedinaction.php?action=newreport&amp;claimid=$claimid\"><img src=\"../images/admin/add.gif\" alt=\"Add new Report for this Claim\" border=\"0\" title=\"Add new Report for this Claim\"></a></td>



					  </tr>



				</table>



					";

        }

        echo "<br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.topform.stepto.value = 3;



																	   document.topform.submit();\" /> 



					<input type=\"button\" value=\"Next >>\" onClick=\"document.topform.stepto.value = 5;



																	   document.topform.submit();\" /> 



					 <input type=\"hidden\" value=\"4\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }

    if ($step == 5)
    {

        echo "<form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" class='no-show-in-print'>



					<input type=\"button\" value=\"Claim Details\" onClick=\"document.datesform.stepto.value = 1;



																	 document.datesform.submit();\" />



		  						



					<input type=\"button\" value=\"Parts\" onClick=\"document.datesform.stepto.value = 2;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Dates\" onClick=\"document.datesform.stepto.value = 3;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Reports\" onClick=\"document.datesform.stepto.value = 4;



																	 document.datesform.submit();\" />



					<input type=\"button\" value=\"Outstanding Reports\" disabled />

					

					<input type=\"button\" value=\"Attachments\" onClick=\"document.datesform.stepto.value = 6;



																	 document.datesform.submit();\" />
																	 
					<input type=\"button\" value=\"Quote\" onClick=\"document.datesform.stepto.value = 7;



																	 document.datesform.submit();\" />												 



																	 



					<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">											 



																	 </form>";

        echo "<p>Client NumberGG: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>
		
		<h3>The following actions is outstanding:</h3>";

        $qrydates = "select * from dates where claimid = $claimid";

        $qrydatesresults = mysql_query($qrydates, $db);

        $datesrow = mysql_fetch_array($qrydatesresults);

        $received = $datesrow["received"];

        //$received = "2007-05-21";

        $received = explode("-", $received);

        $loss = $datesrow["loss"];

        $loss = explode("-", $loss);

        $assappointed = $datesrow["assappointed"];

        $assappointed = explode("-", $assappointed);

        $assessment = $datesrow["assessment"];

        $assessment = explode("-", $assessment);

        $assessmentreport = $datesrow["assessmentreport"];

        $assessmentreport = explode("-", $assessmentreport);

        $assessmentinvtoinsurer = $datesrow["assessmentinvtoinsurer"];

        $assessmentinvtoinsurer = explode("-", $assessmentinvtoinsurer);

        $auth = $datesrow["auth"];

        $auth = explode("-", $auth);

        $wp = $datesrow["wp"];

        $wp = explode("-", $wp);

        $docreq = $datesrow["docreq"];

        $docreq = explode("-", $docreq);

        $workinprogressinsp = $datesrow["workinprogressinsp"];

        $workinprogressinsp = explode("-", $workinprogressinsp);

        $dod = $datesrow["dod"];

        $dod = explode("-", $dod);

        $finalcosting = $datesrow["finalcosting"];

        $finalcosting = explode("-", $finalcosting);

        $acirepsentinsurer = $datesrow["acirepsentinsurer"];

        $acirepsentinsurer = explode("-", $acirepsentinsurer);

        $invoicesent = $datesrow["invoicesent"];

        $invoicesent = explode("-", $invoicesent);

        $assfeereceivedfrominsurer = $datesrow["assfeereceivedfrominsurer"];

        $assfeereceivedfrominsurer = explode("-", $assfeereceivedfrominsurer);

        $acipaymentreceived = $datesrow["acipaymentreceived"];

        $acipaymentreceived = explode("-", $acipaymentreceived);

        echo "



<SCRIPT LANGUAGE=\"JavaScript\" ID=\"js1\">







	var cal1 = new CalendarPopup();



	cal1.setReturnFunction(\"setMultipleValues1\");







	function setMultipleValues1(y,m,d) 



	{



		document.datesform.receivedyear.value=y;



		document.datesform.receivedmonth.value=LZ(m);



		document.datesform.receivedday.value=LZ(d);



	}



	



	var cal2 = new CalendarPopup();



	cal2.setReturnFunction(\"setMultipleValues2\");







	function setMultipleValues2(y,m,d) 



	{



		document.datesform.lossyear.value=y;



		document.datesform.lossmonth.value=LZ(m);



		document.datesform.lossday.value=LZ(d);



	}



	



	var cal3 = new CalendarPopup();



	cal3.setReturnFunction(\"setMultipleValues3\");







	function setMultipleValues3(y,m,d) 



	{



		document.datesform.assappointedyear.value=y;



		document.datesform.assappointedmonth.value=LZ(m);



		document.datesform.assappointedday.value=LZ(d);



	}



	



	var cal4 = new CalendarPopup();



	cal4.setReturnFunction(\"setMultipleValues4\");







	function setMultipleValues4(y,m,d) 



	{



		document.datesform.assessmentyear.value=y;



		document.datesform.assessmentmonth.value=LZ(m);



		document.datesform.assessmentday.value=LZ(d);



	}



	



	var cal5 = new CalendarPopup();



	cal5.setReturnFunction(\"setMultipleValues5\");







	function setMultipleValues5(y,m,d) 



	{



		document.datesform.assessmentreportyear.value=y;



		document.datesform.assessmentreportmonth.value=LZ(m);



		document.datesform.assessmentreportday.value=LZ(d);



	}



	



	var cal6 = new CalendarPopup();



	cal6.setReturnFunction(\"setMultipleValues6\");







	function setMultipleValues6(y,m,d) 



	{



		document.datesform.assessmentinvtoinsureryear.value=y;



		document.datesform.assessmentinvtoinsurermonth.value=LZ(m);



		document.datesform.assessmentinvtoinsurerday.value=LZ(d);



	}



	



	var cal7 = new CalendarPopup();



	cal7.setReturnFunction(\"setMultipleValues7\");







	function setMultipleValues7(y,m,d) 



	{



		document.datesform.authyear.value=y;



		document.datesform.authmonth.value=LZ(m);



		document.datesform.authday.value=LZ(d);



	}



	



	var cal8 = new CalendarPopup();



	cal8.setReturnFunction(\"setMultipleValues8\");







	function setMultipleValues8(y,m,d) 



	{



		document.datesform.wpyear.value=y;



		document.datesform.wpmonth.value=LZ(m);



		document.datesform.wpday.value=LZ(d);



	}



	



	var cal9 = new CalendarPopup();



	cal9.setReturnFunction(\"setMultipleValues9\");







	function setMultipleValues9(y,m,d) 



	{



		document.datesform.docreqyear.value=y;



		document.datesform.docreqmonth.value=LZ(m);



		document.datesform.docreqday.value=LZ(d);



	}



	



	var cal10 = new CalendarPopup();



	cal10.setReturnFunction(\"setMultipleValues10\");







	function setMultipleValues10(y,m,d) 



	{



		document.datesform.workinprogressinspyear.value=y;



		document.datesform.workinprogressinspmonth.value=LZ(m);



		document.datesform.workinprogressinspday.value=LZ(d);



	}



	



	var cal11 = new CalendarPopup();



	cal11.setReturnFunction(\"setMultipleValues11\");







	function setMultipleValues11(y,m,d) 



	{



		document.datesform.dodyear.value=y;



		document.datesform.dodmonth.value=LZ(m);



		document.datesform.dodday.value=LZ(d);



	}



	



	var cal12 = new CalendarPopup();



	cal12.setReturnFunction(\"setMultipleValues12\");







	function setMultipleValues12(y,m,d) 



	{



		document.datesform.finalcostingyear.value=y;



		document.datesform.finalcostingmonth.value=LZ(m);



		document.datesform.finalcostingday.value=LZ(d);



	}



	



	var cal13 = new CalendarPopup();



	cal13.setReturnFunction(\"setMultipleValues13\");







	function setMultipleValues13(y,m,d) 



	{



		document.datesform.acirepsentinsureryear.value=y;



		document.datesform.acirepsentinsurermonth.value=LZ(m);



		document.datesform.acirepsentinsurerday.value=LZ(d);



	}



	



	var cal14 = new CalendarPopup();



	cal14.setReturnFunction(\"setMultipleValues14\");







	function setMultipleValues14(y,m,d) 



	{



		document.datesform.invoicesentyear.value=y;



		document.datesform.invoicesentmonth.value=LZ(m);



		document.datesform.invoicesentday.value=LZ(d);



	}



	



	var cal15 = new CalendarPopup();



	cal15.setReturnFunction(\"setMultipleValues15\");







	function setMultipleValues15(y,m,d) 



	{



		document.datesform.assfeereceivedfrominsureryear.value=y;



		document.datesform.assfeereceivedfrominsurermonth.value=LZ(m);



		document.datesform.assfeereceivedfrominsurerday.value=LZ(d);



	}



	



	var cal16 = new CalendarPopup();



	cal16.setReturnFunction(\"setMultipleValues16\");







	function setMultipleValues16(y,m,d) 



	{



		document.datesform.acipaymentreceivedyear.value=y;



		document.datesform.acipaymentreceivedmonth.value=LZ(m);



		document.datesform.acipaymentreceivedday.value=LZ(d);



	}







</SCRIPT>		  



			  ";

        echo "<br /><form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"datesform\">



					<table>";

        $icount = 0;

        if ($received[0] == "0000")
        {
			$calanderFields[] = array('receivedday', 'receivedmonth', 'receivedyea');

            echo "	<tr>



							<td>Date received</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $received[2] .
                "\" name=\"receivedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $received[1] . "\" name=\"receivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $received[0] . "\" name=\"receivedyear\" readonly>



								 <A HREF=\"#\" onClick=\"cal1.showCalendar('anchor1'); return false;\" 



								 	TITLE=\"cal1.showCalendar('anchor1'); return false;\" NAME=\"anchor1\" 



								 	ID=\"anchor1\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>";

            $icount++;

        }

        if ($loss[0] == "0000")
        {
			$calanderFields[] = array('lossday', 'lossmonth', 'lossyear');

            echo "	<tr>



							<td>Date of loss</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $loss[2] . "\" name=\"lossday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $loss[1] . "\" name=\"lossmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $loss[0] . "\" name=\"lossyear\" readonly>



								<A HREF=\"#\" onClick=\"cal2.showCalendar('anchor2'); return false;\" 



								 	TITLE=\"cal2.showCalendar('anchor2'); return false;\" NAME=\"anchor2\" 



								 	ID=\"anchor2\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td> 



						</tr>";

            $icount++;

        }

        if ($assappointed[0] == "0000")
        {
			$calanderFields[] = array('assappointedday', 'assappointedmonth', 'assappointedyear');

            echo "	<tr>



							<td>Assessor appointed</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[2] .
                "\" name=\"assappointedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assappointed[1] .
                "\" name=\"assappointedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assappointed[0] .
                "\" name=\"assappointedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal3.showCalendar('anchor3'); return false;\" 



								 	TITLE=\"cal3.showCalendar('anchor3'); return false;\" NAME=\"anchor3\" 



								 	ID=\"anchor3\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td> 



						</tr>";

            $icount++;

        }

        if ($assessment[0] == "0000")
        {
			$calanderFields[] = array('assessmentday', 'assessmentmonth', 'assessmentyear');

            echo "	<tr>



							<td>Date of assessment</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[2] .
                "\" name=\"assessmentday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessment[1] .
                "\" name=\"assessmentmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessment[0] .
                "\" name=\"assessmentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal4.showCalendar('anchor4'); return false;\" 



								 	TITLE=\"cal4.showCalendar('anchor4'); return false;\" NAME=\"anchor4\" 



								 	ID=\"anchor4\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($assessmentreport[0] == "0000")
        {
			$calanderFields[] = array('assessmentreportday', 'assessmentreportmonth', 'assessmentreportyear');

            echo "	<tr>



							<td>Assessment report date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[2] .
                "\" name=\"assessmentreportday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentreport[1] .
                "\" name=\"assessmentreportmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentreport[0] .
                "\" name=\"assessmentreportyear\" readonly>



								<A HREF=\"#\" onClick=\"cal5.showCalendar('anchor5'); return false;\" 



								 	TITLE=\"cal5.showCalendar('anchor5'); return false;\" NAME=\"anchor5\" 



								 	ID=\"anchor5\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($assessmentinvtoinsurer == "0000")
        {

            echo "	<tr>



							<td>Assessment invoice sent to insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[2] .
                "\" name=\"assessmentinvtoinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assessmentinvtoinsurer[1] .
                "\" name=\"assessmentinvtoinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assessmentinvtoinsurer[0] .
                "\" name=\"assessmentinvtoinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal6.showCalendar('anchor6'); return false;\" 



								 	TITLE=\"cal6.showCalendar('anchor6'); return false;\" NAME=\"anchor6\" 



								 	ID=\"anchor6\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($auth[0] == "0000")
        {
			$calanderFields[] = array('authday', 'authmonth', 'authyear');

            echo "	<tr>



							<td>Authorise date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $auth[2] . "\" name=\"authday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $auth[1] . "\" name=\"authmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $auth[0] . "\" name=\"authyear\" readonly>



								<A HREF=\"#\" onClick=\"cal7.showCalendar('anchor7'); return false;\" 



								 	TITLE=\"cal7.showCalendar('anchor7'); return false;\" NAME=\"anchor7\" 



								 	ID=\"anchor7\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($wp[0] == "0000")
        {
			$calanderFields[] = array('wpday', 'wpmonth', 'wpyear');

            echo "	<tr>



							<td>Withhold payment date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $wp[2] . "\" name=\"wpday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $wp[1] . "\" name=\"wpmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $wp[0] . "\" name=\"wpyear\" readonly>



								<A HREF=\"#\" onClick=\"cal8.showCalendar('anchor8'); return false;\" 



								 	TITLE=\"cal8.showCalendar('anchor8'); return false;\" NAME=\"anchor8\" 



								 	ID=\"anchor8\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($docreq[0] == "0000")
        {
			$calanderFields[] = array('docreqday', 'docreqmonth', 'docreqyear');

            echo " 	<tr>



							<td>Document Request</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $docreq[2] . "\" name=\"docreqday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $docreq[1] . "\" name=\"docreqmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $docreq[0] . "\" name=\"docreqyear\" readonly>



								<A HREF=\"#\" onClick=\"cal9.showCalendar('anchor9'); return false;\" 



								 	TITLE=\"cal9.showCalendar('anchor9'); return false;\" NAME=\"anchor9\" 



								 	ID=\"anchor9\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>



							</td>



						</tr>";

            $icount++;

        }

        if ($workinprogressinsp[0] == "0000")
        {
			$calanderFields[] = array('workinprogressinspday', 'workinprogressinspmonth', 'workinprogressinspyear');

            echo "	<tr>



							<td>Work in progress inspection date</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $workinprogressinsp[2] .
                "\" name=\"workinprogressinspday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $workinprogressinsp[1] .
                "\" name=\"workinprogressinspmonth\" readonly> -



								<input type=\"text\" style=\"width:35px;\" value=\"" . $workinprogressinsp[0] .
                "\" name=\"workinprogressinspyear\" readonly>



								<A HREF=\"#\" onClick=\"cal10.showCalendar('anchor10'); return false;\" 



								 	TITLE=\"cal10.showCalendar('anchor10'); return false;\" NAME=\"anchor10\" 



								 	ID=\"anchor10\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($dod[0] == "0000")
        {
			$calanderFields[] = array('dodday', 'dodmonth', 'dodyear');

            echo "	<tr>



							<td>Expected date of delivery</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $dod[2] . "\" name=\"dodday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $dod[1] . "\" name=\"dodmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $dod[0] . "\" name=\"dodyear\" readonly>



								<A HREF=\"#\" onClick=\"cal11.showCalendar('anchor11'); return false;\" 



								 	TITLE=\"cal11.showCalendar('anchor11'); return false;\" NAME=\"anchor11\" 



								 	ID=\"anchor11\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($finalcosting[0] == "0000")
        {
			$calanderFields[] = array('finalcostingday', 'finalcostingmonth', 'finalcostingyear');

            echo " 	<tr>



							<td>Final costing</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $finalcosting[2] .
                "\" name=\"finalcostingday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $finalcosting[1] .
                "\" name=\"finalcostingmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $finalcosting[0] .
                "\" name=\"finalcostingyear\" readonly>



								<A HREF=\"#\" onClick=\"cal12.showCalendar('anchor12'); return false;\" 



								 	TITLE=\"cal12.showCalendar('anchor12'); return false;\" NAME=\"anchor12\" 



								 	ID=\"anchor12\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($acirepsentinsurer[0] == "0000")
        {
			$calanderFields[] = array('acirepsentinsurerday', 'acirepsentinsurermonth', 'acirepsentinsureryear');

            echo "	<tr>



							<td>ACI report sent to insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $acirepsentinsurer[2] .
                "\" name=\"acirepsentinsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $acirepsentinsurer[1] .
                "\" name=\"acirepsentinsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $acirepsentinsurer[0] .
                "\" name=\"acirepsentinsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal13.showCalendar('anchor13'); return false;\" 



								 	TITLE=\"cal13.showCalendar('anchor13'); return false;\" NAME=\"anchor13\" 



								 	ID=\"anchor13\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($invoicesent[0] == "0000")
        {
			$calanderFields[] = array('invoicesentday', 'invoicesentmonth', 'invoicesentyear');

            echo "	<tr>



							<td>Date invoice sent</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $invoicesent[2] .
                "\" name=\"invoicesentday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $invoicesent[1] .
                "\" name=\"invoicesentmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $invoicesent[0] .
                "\" name=\"invoicesentyear\" readonly>



								<A HREF=\"#\" onClick=\"cal14.showCalendar('anchor14'); return false;\" 



								 	TITLE=\"cal14.showCalendar('anchor14'); return false;\" NAME=\"anchor14\" 



								 	ID=\"anchor14\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($assfeereceivedfrominsurer[0] == "0000")
        {
			$calanderFields[] = array('assfeereceivedfrominsurerday', 'assfeereceivedfrominsurermonth', 'assfeereceivedfrominsureryear');

            echo "	<tr>



							<td>Assessment fee received from insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[2] .
                "\" name=\"assfeereceivedfrominsurerday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $assfeereceivedfrominsurer[1] .
                "\" name=\"assfeereceivedfrominsurermonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $assfeereceivedfrominsurer[0] .
                "\" name=\"assfeereceivedfrominsureryear\" readonly>



								<A HREF=\"#\" onClick=\"cal15.showCalendar('anchor15'); return false;\" 



								 	TITLE=\"cal15.showCalendar('anchor15'); return false;\" NAME=\"anchor15\" 



								 	ID=\"anchor15\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($acipaymentreceived[0] == "0000")
        {
			$calanderFields[] = array('acipaymentreceivedday', 'acipaymentreceivedmonth', 'acipaymentreceivedyear');

            echo " 	<tr>



							<td>ACI payment received from insurer</td>



							<td><input type=\"text\" style=\"width:20px;\" value=\"" . $acipaymentreceived[2] .
                "\" name=\"acipaymentreceivedday\" readonly> - 



								<input type=\"text\" style=\"width:20px;\" value=\"" . $acipaymentreceived[1] .
                "\" name=\"acipaymentreceivedmonth\" readonly> - 



								<input type=\"text\" style=\"width:35px;\" value=\"" . $acipaymentreceived[0] .
                "\" name=\"acipaymentreceivedyear\" readonly>



								<A HREF=\"#\" onClick=\"cal16.showCalendar('anchor16'); return false;\" 



								 	TITLE=\"cal16.showCalendar('anchor16'); return false;\" NAME=\"anchor16\" 



								 	ID=\"anchor16\"><img src=\"../images/admin/calendar.gif\" border=\"0\"></A>	



							</td>



						</tr>";

            $icount++;

        }

        if ($icount == 0)
        {

            echo "<h5>There are now outstanding dates</h5>";

        }

        echo "



					</table><br />



					<input type=\"button\" value=\"<< Back\" onClick=\"document.datesform.stepto.value = 4;



																	   document.datesform.submit();\" /> 



					<input type=\"reset\" value=\"Reset\" /> <input type=\"hidden\" value=\"5\" name=\"fromstep\" /> <input type=\"hidden\" name=\"stepto\" value=\"4\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">



				  </form>";

    }
    
    if ($step == 6)
    {
		echo "<form method=\"POST\" action=\"ccloggedinaction.php?action=cceditclaim\" name=\"topform\" class='no-show-in-print'>
						<input type=\"button\" value=\"Claim Details\" onClick=\"document.topform.stepto.value = 1;

																		 document.topform.submit();\" />

						<input type=\"button\" value=\"Parts\" onClick=\"document.topform.stepto.value = 2;

																		 document.topform.submit();\" /> 
						<input type=\"button\" value=\"Dates\" onClick=\"document.topform.stepto.value = 3;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Reports\" onClick=\"document.topform.stepto.value = 4;
																		 document.topform.submit();\" />
						<input type=\"button\" value=\"Outstanding Reports\" onClick=\"document.topform.stepto.value = 5;
																		 document.topform.submit();\" />
																 
						<input type=\"button\" value=\"Attachments\" disabled />

																		 



						<input type=\"hidden\" name=\"stepto\" value=\"2\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"2\" /> 		 



																		 </form><p>Client NumberHH: <strong>$clientnumber2</strong>; Client Name: <strong>$clientname</strong>; Claim Number: <strong>$claimnumber</strong>; Vehicle Registration Number: <strong>$vehicleregistrationno</strong></p>";
																		 
		$qryclaimfiles = "select f.*, u.username from `files` as f left join users as u on f.userid =u.id where f.`claimid` = $claimid order by f.`datetime`";
		$qryclaimfilesresults = mysql_query($qryclaimfiles, $db);
		
		if (mysql_num_rows($qryclaimfilesresults) == 0)
		{
			echo "<p>There are no files in the database for this claim.</p>
			
				<form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>
					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"claimsclerk\">
				</form>
			
			";			
		}
		else
		{
			echo "<Br><form name=\"fileuploadform\" method=\"post\" action=\"uploadfile.php\" enctype=\"multipart/form-data\">
				
					<table>
						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr class=\"attachment-row\">
							<td>
								<table width=\"100%\">
									<tr>
										<td>Upload new file:</td>
										<td><input type=\"file\" name=\"fileupload[]\" /></td>
									</tr>
									<tr>
										<td>File Description:</td>
										<td><input type=\"text\"  size=\"116\" maxlength=\"255\" name=\"desc[]\" /></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width=\"100%\">
									<tr>
										<td colspan=\"2\" align=\"right\">
											<input value=\"Upload File\" type=\"submit\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>
					<input type=\"hidden\" name=\"claimid\" value=\"$claimid\">
					<input type=\"hidden\" name=\"usertype\" value=\"claimsclerk\">
				</form><br>";
			
		
			echo "<table border=\"1\">
					<tr>
						<td><strong>Date and Time Uploaded:</strong></td>
						<td><strong>File:</strong></td>
						<td><strong>Description:</strong></td>
						<td><strong>File Size:</strong></td>
						<td><strong>User:</strong></td>
					</tr>";
				
			while ($filerow = mysql_fetch_array($qryclaimfilesresults))
			{
				$fileid = $filerow["id"];
				$filename = $filerow["filename"];
				$datetime = date('d/m/Y h:i A', strtotime($filerow["datetime"]));
				$desc = $filerow["description"];
				$fileSize = $filerow["filesize"];
				
				echo "<tr>
						<td>$datetime</td>
						<td><a href=\"claims/$claimid/$fileid-$filename\" target=\"_blank\" class=\"newWindow\">$filename</a></td>
						<td>$desc</td>
						<td>" . humanFileSize($fileSize) . "</td>
						<td>" . $filerow["username"] . "</td>
					  </tr>";
				
			}
			
			echo "</table>";
		}
	}
	
	print_r($calanderFields);
	echo '
		<script type="text/javascript">
		$(document).ready(function() {
			$(\'#completeAllToday\').click(function() {
		
				var dateObj = new Date();
				var month = dateObj.getUTCMonth() + 1; //months from 1-12
				var day = dateObj.getUTCDate();
				var year = dateObj.getUTCFullYear();
	';

	foreach ($calanderFields as $cField) {
		echo	'$(\'input[name="'.$cField[0].'"]\').val(day);
				$(\'input[name="'.$cField[1].'"]\').val(month);
				$(\'input[name="'.$cField[2].'"]\').val(year);';
	}
	
	echo 'return false;
			});	
		});
	</script>
	';

}

function ccAddNewClaim($clientname, $clientno, $claimno, $clientcontactno1, $clientcontactno2, $clientemail,
    $panelbeaterid, $vehiclemakemodel, $vehicleregistrationno, $vehicleyear, $vehicletype,
    $administratorid, $quoteno, $insurerid, $claimsclerkid, $authamount, $excess, $betterment,
    $quoteamount, $assessorid, $pbname, $pbowner, $pbcostingclerk, $pbcostingclerkcel, $pbcontactperson, $pbworkshopmanager,
    $pbcontactnumber, $pbcontactnumber2, $pbfaxno, $pbemail, $pbadr1, $pbadr2, $pbadr3, $pbadr4, $notes)
{

    require ('connection.php');

    if ($panelbeaterid == 0)
    {

        $qryinsert = "insert into panelbeaters (`id`, `name`, `owner`, `costingclerk`, `costingclerkcel`, `contactperson`, `adr1`, `adr2`, `adr3`, `adr4`, 



			                                        `contactno`, `workshopmanager`, `contactno2`, `faxno`, `email`, `notes`)



									 	    values ('', '$pbname', '$pbowner', '$pbcostingclerk', '$pbcostingclerkcel', '$pbcontactperson', '$pbworkshopmanager', '$pbadr1', '$pbadr2',



											        '$pbadr3', '$pbadr4', '$pbcontactnumber', '$pbcontactnumber2', '$pbfaxno', '$pbemail', '$notes')";

        $qryinsertresults = mysql_query($qryinsert, $db);

        $qrygetnewid = "select max(`id`) as newid from panelbeaters";

        $qrygetnewidresults = mysql_query($qrygetnewid, $db);

        $newidrow = mysql_fetch_array($qrygetnewidresults);

        $panelbeaterid = $newidrow["newid"];

    }

    else
    {

        $qryupdate = "update panelbeaters set `name` = '$pbname',



											  `owner` = '$pbowner',



											  `costingclerk` = '$pbcostingclerk',
											  `costingclerkcel` = '$pbcostingclerkcel',



											  `contactperson` = '$pbcontactperson',

											  `workshopmanager` = '$pbworkshopmanager',



											  `adr1` = '$pbadr1',



											  `adr2` = '$pbadr2',



											  `adr3` = '$pbadr3',



											  `adr4` = '$pbadr4',

											  `notes` = '$notes',



											  `contactno` = '$pbcontactnumber',

											  `contactno2` = '$pbcontactnumber2',



											  `faxno`= '$pbfaxno',



											  `email` = '$pbemail' where `id` = $panelbeaterid";

        $qryupdateresults = mysql_query($qryupdate, $db);

    }

    $qrygetclaimno = "select * from claimnumber";

    $qrygetclaimnoresults = mysql_query($qrygetclaimno, $db);

    $claimnorow = mysql_fetch_array($qrygetclaimnoresults);

    $dbclientno = $claimnorow["clientno"];

	$excess_description = $_POST['excess_description'];

    if ($clientno == $dbclientno)
    {

        $dbclientno++;

        $upd = "update claimnumber set clientno = $dbclientno";

        $updres = mysql_query($upd, $db);

    }

    $qryinsert = "insert into claim (`clientname`, `clientno`, `claimno`, `clientcontactno`, `clientcontactno2`, `clientemail`, `panelbeaterid`, `makemodel`,



		                                 `vehicleregistrationno`, `vehicleyear`, `vehicletype`, `administratorid`, `quoteno`, `insurerid`, `claimsclerkid`,



										 `authamount`, `excess`, `excess_description`, `betterment`, `quoteamount`, `assessorid`)



							     values ('$clientname', '$clientno', '$claimno', '$clientcontactno1', '$clientcontactno2', '$clientemail', $panelbeaterid, '$vehiclemakemodel',



								         '$vehicleregistrationno', '$vehicleyear', '$vehicletype', $administratorid, '$quoteno', '$insurerid', $claimsclerkid,



										 $authamount, $excess, '$excess_description', $betterment, $quoteamount, $assessorid)";

    $qryinsertresults = mysql_query($qryinsert, $db);

    $qrygetmax = "select max(id) as newid from claim";

    $qrygetmaxresults = mysql_query($qrygetmax, $db);

    $newidrow = mysql_fetch_array($qrygetmaxresults);

    $newid = $newidrow["newid"];

    $qryinsertdates = "INSERT INTO `dates` ( `claimid` , `received` , `loss` , `assappointed` , `assessment` , `assessmentreport` , 



		                   `assessmentinvtoinsurer` , `auth` , `wp` , `docreq` , `workinprogressinsp` , `dod` , `finalcosting` , 



						   `acirepsentinsurer` , `invoicesent` , `assfeereceivedfrominsurer` , `acipaymentreceived` )



					VALUES ($newid, CURDATE(), '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 



							'0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 



							'0000-00-00', '0000-00-00')";

    $qryinsertdatesresults = mysql_query($qryinsertdates, $db);

    //echo $qryinsert;

	$cookie = $_COOKIE['ccloggedincookie'];
	
	$loggedincc = explode("-", $cookie);



	$ccid = $loggedincc[0];

	$password = $loggedincc[1];

    //ccClaims(1, 0, $ccid);
    
    echo "<p>Claim added successfully. <a href=\"ccloggedinaction.php?action=ccclaims&amp;from=1>Go back to claims</p>";

}

?>

<style type="text/css">
	.attachment-row td table{border:1px solid #ccc;padding:10px;}
</style>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {

		$('#claimsclerk').change(function(){ 
			var email = $(this).find('option:selected').attr('email');

			$('#claimTechnicianEmailLink').attr('href', 'mailto:'+email+'?'+$('#claimTechnicianEmailLink').attr('emailpart') );
		});

		$('.send-email').click(function() {

			var thisAddr = $(this).attr('href');

			var claimId = $(this).attr('claimId');

			var type = $(this).attr('type');

			var email = thisAddr.substring( thisAddr.indexOf(":")+1, thisAddr.indexOf("?") );
			
			var params = {"action":"save-email-report", "claimid":claimId, "to_address":email, "type":type};

			$.post('ajax.php?action=save-email-report', params, function(data) {
				window.location.href=thisAddr
			});


			return false;

		});

	});
		
									

</script>
