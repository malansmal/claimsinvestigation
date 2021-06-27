<?php

function Administrators($from)
{

    require ('connection.php');

    if ($from == "")
    {

        $from = 1;

    }

    //display first 30

    if ($from < 1)
    {

        $qry = "SELECT * FROM administrators LIMIT 0 , 30";

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

        $qry = "SELECT * FROM administrators LIMIT $frm , 30";

    }//end else

    $qrycountadministrators = "select * from administrators";

    $qrycount = mysql_query($qrycountadministrators, $db);

    $qryadministrators = mysql_query($qry, $db);

    $count = mysql_num_rows($qrycount);

    if ($count == 0)
    {

        echo "<p>There are no Administrators in the database. Click <a href=\"loggedinaction.php?action=newadministrator\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Administrator\" title=\"Add new Administrator\"></a> to add one.</p>";

    }

    else
    {

        $pagesneeded = $count / 30;

        $pagesneeded = ceil($pagesneeded);

        $pageslinks = "<a href=\"loggedinaction.php?action=administrators&amp;from=1\">Page 1</a> || ";

        //echo "pages that will be needed is $count today";

        if ($pagesneeded > 1) //build next page links here
        {

            for ($i = 1; $i < $pagesneeded; $i++)
            {

                //echo "i is $i<br>";

                $fromrecord = $i * 30;

                $pagenumber = $i + 1;

                $pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=administrators&amp;from=" .
                    $fromrecord . "\">Page $pagenumber</a> || ";

            }//end for loop

        }//end if

        $pageslinks = substr($pageslinks, 0, -4);

        echo "<div>

				  <table class=\"table table-striped\">

						  <tr>

							  <td><strong>Name</strong></td>

							  <td><strong>Telephone No</strong></td>

							  <td><strong>Fax No</strong></td>

							  <td><strong>Address</strong></td>

							  <td><strong>VAT Number</strong></td>

							  <td><strong>Email Address</strong></td>

							  <td><strong>Logo</strong></td>

							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>

						  </tr>";

        while ($row = mysql_fetch_array($qryadministrators))
        {

            // give a name to the fields

            $id = $row['id'];

            $name = $row['name'];

            $telno = $row["telno"];

            $faxno = $row["faxno"];

            $adr1 = $row["adr1"];

            $adr2 = $row["adr2"];

            $adr3 = $row["adr3"];

            $adr4 = $row["adr4"];

            $vatno = $row["vatno"];

            $email = $row["email"];

            //echo the results onscreen

            //echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

            echo "<tr>

						  <td valign=\"top\">$name</td>

						  <td valign=\"top\">$telno</td>

						  <td valign=\"top\">$faxno</td>

						  <td valign=\"top\">$adr1; $adr2; $adr3; $adr4</td>

						  <td valign=\"top\">$vatno</td>

						  <td valign=\"top\">$email</td>";

            if (file_exists("../images/administrators/$id.jpg"))
            {

                echo "<td valign=\"top\"><img src=\"../images/administrators/$id.jpg\"></td>";

            }

            else
            {

                echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

            }

            echo "		  

						  <td align=\"center\"><a href=\"loggedinaction.php?action=editadministrator&amp;administratorid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Administrator\" border=\"0\" title=\"Edit this Administrator\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteadministrator&amp;administratorid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Administrator\" border=\"0\" title=\"Delete this Administrator\"></td>

					  </tr>";

        }//end while loop

        echo "<tr>

					  <td colspan=\"7\">&nbsp;</td>

					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newadministrator\"><img src=\"../images/admin/add.gif\" alt=\"Add new Administrator\" border=\"0\" title=\"Add new Administrator\"></a></td>

				  </tr>

			</table><br>$pageslinks<br>

				";

    }

}

function NewAdministrator()
{

    require ('connection.php');

    echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewadministrator\" enctype=\"multipart/form-data\" name=\"theform\">

				  <p>Enter the new Administrator details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Name:</td>

							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Telephone Number:</td>

							<td><input type=\"text\" name=\"telno\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Fax Number:</td>

							<td><input type=\"text\" name=\"faxno\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Address:</td>

							<td><input type=\"text\" name=\"adr1\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr2\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr3\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Email Address:</td>

							<td><input type=\"text\" name=\"email\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Emergency Number 1:</td>

							<td><input type=\"text\" name=\"emergency_no_1\" maxlength=\"255\" value=\"$emergency_no_1\"></td>

						</tr>

						<tr>

							<td>Emergency Number 2:</td>

							<td><input type=\"text\" name=\"emergency_no_2\" maxlength=\"255\" value=\"$emergency_no_2\"></td>

						</tr>

						<tr>

							<td>Call center number 1:</td>

							<td><input type=\"text\" name=\"callcenter_no_1\" maxlength=\"255\" value=\"$callcenter_no_1\"></td>

						</tr>

						<tr>

							<td>Call center number 2:</td>

							<td><input type=\"text\" name=\"callcenter_no_2\" maxlength=\"255\" value=\"$callcenter_no_2\"></td>

						</tr>
						
						<tr>

							<td>Instruction:</td>

							<td><textarea cols=\"75\" rows=\"10\" name=\"instruction\"></textarea></td>

						</tr>
						
						<tr>

							<td>Authorization:</td>

							<td><textarea cols=\"75\" rows=\"10\" name=\"auth\"></textarea></td>

						</tr>

						<tr>
							<td colspan=\"2\">
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
									<tr>
										<td><strong>Accredited Panelbeaters</strong></td>
										<td><strong>Accredited Towing Operators</strong></td>
									</tr>
									<tr>
										<td>
											<div style='height:400px;overflow-y:scroll;padding:20px;'>
											<label><input type='checkbox' id='selectAllPB' /> Select All</label> ";
									
											$panelBeatersList = getPanelBeaters();

											foreach ($panelBeatersList as $pb) {
												echo '<div><label><input type="checkbox" class="panelbeaterslist" name="panelbeaters[]" value="'.$pb["id"].'" /> ' . $pb["name"] . '</label></div>';
											}
								
											echo "
											</div>
										</td>
										<td>
											<div style='height:400px;overflow-y:scroll;padding:20px;'>
											<label><input type='checkbox' id='selectAllTowingOperators' /> Select All</label> ";
									
											$towingOperatorsList = getTowingOperators();

											foreach ($towingOperatorsList as $towingoperator) {
												echo '<div><label><input type="checkbox" class="towingoperatorslist" name="towingoperators[]" value="'.$towingoperator["id"].'" /> ' . $towingoperator["name"] . '</label></div>';
											}
								
											echo "
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>

							<td>Logo:</td>

							<td><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
				
				<script type=\"text/javascript\">
					$(document).ready(function() {

						$('#selectAllPB').on('click', function() {
							if ( $(this).is(':checked') == true ) {
								$('.panelbeaterslist').prop('checked', true);
							}
							else {
								$('.panelbeaterslist').prop('checked', false);
							}
						});

						$('#selectAllTowingOperators').on('click', function() {
							if ( $(this).is(':checked') == true ) {
								$('.towingoperatorslist').prop('checked', true);
							}
							else {
								$('.towingoperatorslist').prop('checked', false);
							}
						});

					});
				</script>
				

			  </form>";

}

function AddNewAdministrator($name, $telno, $faxno, $adr1, $adr2, $adr3, $adr4,
    $vatno)
{

    require ('connection.php');

    $email = $_REQUEST["email"];
    $instruction = addslashes($_REQUEST["instruction"]);
    $auth = addslashes($_REQUEST["auth"]);

	$emergency_no_1 = addslashes($_POST['emergency_no_1']);
	$emergency_no_2 = addslashes($_POST['emergency_no_2']);
	$callcenter_no_1 = addslashes($_POST['callcenter_no_1']);
	$callcenter_no_2 = addslashes($_POST['callcenter_no_2']);

    $qryinsert = "insert into administrators (`id`, `name`, `telno`, `faxno`, `adr1`, `adr2`, `adr3`, `adr4`, `vatno`, `email`, `instruction`, `auth`, `emergency_no_1`, `emergency_no_2`, `callcenter_no_1`, `callcenter_no_2`)

										values (null, '$name', '$telno', '$faxno', '$adr1', '$adr2', '$adr3', '$adr4', '$vatno', '$email', '$instruction', '$auth', '$emergency_no_1', '$emergency_no_2', '$callcenter_no_1', '$callcenter_no_2')";

    $qryinsertresults = mysql_query($qryinsert, $db);

    $newid = GetNewID2('administrators', 'id', $db);

	
	$panelbeatersList = $_POST['panelbeaters'];
	$towingoperatorsList = $_POST['towingoperators'];

	foreach ($panelbeatersList as $pb) {
		$pbsql = " INSERT INTO panelbeaters_administrators (panelbeater_id, administrator_id) VALUES ('".$pb."', '".$newid."') ";
		mysql_query($pbsql, $db);
	}

	foreach ($towingoperatorsList as $to) {
		$tosql = " INSERT INTO towingoperators_administrators (towingoperator_id, administrator_id) VALUES ('".$to."', '".$newid."') ";
		mysql_query($tosql, $db);
	}

    if (file_exists("../images/administrators/$newid.jpg"))
    {

        unlink("../images/administrators/$newid.jpg");

    }

    move_uploaded_file($_FILES['uploadfile']['tmp_name'], "../images/administrators/$newid.jpg");

    echo "<p>The Administrator has been saved successfully.</p>";

    Administrators(1);

}

function EditAdministrator($adminid)
{

    require ('connection.php');

    $qry = "select * from administrators where id = $adminid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $name = stripslashes($row["name"]);

    $telno = stripslashes($row["telno"]);

    $faxno = stripslashes($row["faxno"]);

    $adr1 = stripslashes($row["adr1"]);

    $adr2 = stripslashes($row["adr2"]);

    $adr3 = stripslashes($row["adr3"]);

    $adr4 = stripslashes($row["adr4"]);

    $vatno = stripslashes($row["vatno"]);

    $email = $row["email"];

	$emergency_no_1 = stripslashes($row["emergency_no_1"]);

	$emergency_no_2 = stripslashes($row["emergency_no_2"]);

	$callcenter_no_1 = stripslashes($row["callcenter_no_1"]);

	$callcenter_no_2 = stripslashes($row["callcenter_no_2"]);

    $instruction = stripslashes($row["instruction"]);

    $auth = stripslashes($row["auth"]);
	

	$currentPBs = [];

	$pbsql = " SELECT panelbeater_id FROM panelbeaters_administrators WHERE administrator_id='".$adminid."' ";
	$pbqryresults = mysql_query($pbsql, $db);

	while($pbrow = mysql_fetch_assoc($pbqryresults)) {
		$currentPBs[] = $pbrow['panelbeater_id'];	
	}

	$currentTowingOperators = [];

	$tosql = " SELECT towingoperator_id FROM towingoperators_administrators WHERE administrator_id='".$adminid."' ";
	$toqryresults = mysql_query($tosql, $db);

	while($torow = mysql_fetch_assoc($toqryresults)) {
		$currentTowingOperators[] = $torow['towingoperator_id'];	
	}

    echo "<form method=\"post\" action=\"loggedinaction.php?action=administratoredited\" enctype=\"multipart/form-data\" name=\"theform\">
	<input type=\"hidden\" name=\"administratorid\" value=\"$adminid\">

				  <p>Enter the new Administrator details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Name:</td>

							<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>

						</tr>

						<tr>

							<td>Telephone Number:</td>

							<td><input type=\"text\" name=\"telno\" maxlength=\"50\" value=\"$telno\"></td>

						</tr>

						<tr>

							<td>Fax Number:</td>

							<td><input type=\"text\" name=\"faxno\" maxlength=\"50\" value=\"$faxno\"></td>

						</tr>

						<tr>

							<td>Address:</td>

							<td><input type=\"text\" name=\"adr1\" maxlength=\"255\" value=\"$adr1\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr2\" maxlength=\"255\" value=\"$adr2\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr3\" maxlength=\"255\" value=\"$adr3\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"255\" value=\"$adr4\"></td>

						</tr>

						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"20\" value=\"$vatno\"></td>

						</tr>

						<tr>

							<td>Email Address:</td>

							<td><input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$email\"></td>

						</tr>


						<tr>

							<td>Emergency Number 1:</td>

							<td><input type=\"text\" name=\"emergency_no_1\" maxlength=\"255\" value=\"$emergency_no_1\"></td>

						</tr>

						<tr>

							<td>Emergency Number 2:</td>

							<td><input type=\"text\" name=\"emergency_no_2\" maxlength=\"255\" value=\"$emergency_no_2\"></td>

						</tr>

						<tr>

							<td>Call center number 1:</td>

							<td><input type=\"text\" name=\"callcenter_no_1\" maxlength=\"255\" value=\"$callcenter_no_1\"></td>

						</tr>

						<tr>

							<td>Call center number 2:</td>

							<td><input type=\"text\" name=\"callcenter_no_2\" maxlength=\"255\" value=\"$callcenter_no_2\"></td>

						</tr>
						
						<tr>

							<td>Instruction:</td>

							<td><textarea cols=\"75\" rows=\"10\" name=\"instruction\">$instruction</textarea></td>

						</tr>
						
						<tr>

							<td>Authorization:</td>

							<td><textarea cols=\"75\" rows=\"10\" name=\"auth\">$auth</textarea></td>

						</tr>


						<tr>
							<td colspan=\"2\">
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
									<tr>
										<td><strong>Accredited Panelbeaters</strong></td>
										<td><strong>Accredited Towing Operators</strong></td>
									</tr>
									<tr>
										<td>
											<div style='height:400px;overflow-y:scroll;padding:20px;'>
											<label><input type='checkbox' id='selectAllPB' /> Select All</label> ";
									
											$panelBeatersList = getPanelBeaters();

											foreach ($panelBeatersList as $pb) {
												$isChecked = in_array($pb['id'], $currentPBs) ? 'checked="checked"' : '';
												echo '<div><label><input type="checkbox" class="panelbeaterslist" name="panelbeaters[]" value="'.$pb["id"].'" '.$isChecked.' /> ' . $pb["name"] . '</label></div>';
											}
								
											echo "
											</div>
										</td>
										<td>
											<div style='height:400px;overflow-y:scroll;padding:20px;'>
											<label><input type='checkbox' id='selectAllTowingOperators' /> Select All</label> ";
									
											$towingOperatorsList = getTowingOperators();

											foreach ($towingOperatorsList as $towingoperator) {
												$isChecked = in_array($towingoperator['id'], $currentTowingOperators) ? 'checked="checked"' : '';

												echo '<div><label><input type="checkbox" class="towingoperatorslist" name="towingoperators[]" value="'.$towingoperator["id"].'" '.$isChecked.' /> ' . $towingoperator["name"] . '</label></div>';
											}
								
											echo "
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>

							<td>Logo:</td><td>";

    if (file_exists("../images/administrators/$adminid.jpg"))
    {

        echo "<img src=\"../images/administrators/$adminid.jpg\"><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

			<input type=\"file\" name=\"uploadfile\">";

    }

    else
    {

        echo "<input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)";

    }

    echo "

							</td>

						</tr>

					</table>

				<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> 


				<script type=\"text/javascript\">
					$(document).ready(function() {

						$('#selectAllPB').on('click', function() {
							if ( $(this).is(':checked') == true ) {
								$('.panelbeaterslist').prop('checked', true);
							}
							else {
								$('.panelbeaterslist').prop('checked', false);
							}
						});

						$('#selectAllTowingOperators').on('click', function() {
							if ( $(this).is(':checked') == true ) {
								$('.towingoperatorslist').prop('checked', true);
							}
							else {
								$('.towingoperatorslist').prop('checked', false);
							}
						});

					});
				</script>

			  </form>";

}

function AdministratorEdited($adminid, $name, $telno, $faxno, $adr1, $adr2, $adr3,
    $adr4, $vatno)
{

    require ('connection.php');

    $email = $_REQUEST["email"];
    $instruction = addslashes($_REQUEST["instruction"]);
    $auth = addslashes($_REQUEST["auth"]);
	
	$emergency_no_1 = addslashes($_POST['emergency_no_1']);
	$emergency_no_2 = addslashes($_POST['emergency_no_2']);
	$callcenter_no_1 = addslashes($_POST['callcenter_no_1']);
	$callcenter_no_2 = addslashes($_POST['callcenter_no_2']);


    $qryupdate = "update administrators set `name` = '$name',

											  `telno` = '$telno',

											  `faxno` = '$faxno',

											  `adr1` = '$adr1',

											  `adr2` = '$adr2',

											  `adr3` = '$adr3',

											  `adr4` = '$adr4',

											  `vatno` = '$vatno', 

											  `email` = '$email',

											  `emergency_no_1` = '$emergency_no_1', 
											  `emergency_no_2` = '$emergency_no_2', 
											  `callcenter_no_1` = '$callcenter_no_1', 
											  `callcenter_no_2` = '$callcenter_no_2', 
											  
											  `instruction` = '$instruction',
											  
											  `auth` = '$auth' where `id` = $adminid";

    $qryupdateresults = mysql_query($qryupdate, $db);

	
	// delete panelbeaters admin
	mysql_query(" DELETE FROM panelbeaters_administrators WHERE administrator_id='".$adminid."' ", $db);

	// delete towingoperators admin
	mysql_query(" DELETE FROM towingoperators_administrators WHERE administrator_id='".$adminid."' ", $db);

	$panelbeatersList = $_POST['panelbeaters'];
	$towingoperatorsList = $_POST['towingoperators'];

	foreach ($panelbeatersList as $pb) {
		$pbsql = " INSERT INTO panelbeaters_administrators (panelbeater_id, administrator_id) VALUES ('".$pb."', '".$adminid."') ";
		mysql_query($pbsql, $db);
	}

	foreach ($towingoperatorsList as $to) {
		$tosql = " INSERT INTO towingoperators_administrators (towingoperator_id, administrator_id) VALUES ('".$to."', '".$adminid."') ";
		mysql_query($tosql, $db);
	}

    $i = $_REQUEST["uploadnewfile"];

    if ($i == 1)
    {

        if (file_exists("../images/administrators/$adminid.jpg"))
        {

            unlink("../images/administrators/$adminid.jpg");

        }

        move_uploaded_file($_FILES['uploadfile']['tmp_name'], "../images/administrators/$adminid.jpg");

    }

    echo "<p>The Administrator has been edited successfully.</p>";

    Administrators(1);

}

function ConfirmDeleteAdministrator($adminid, $key)
{

    require ('connection.php');

    //include('functions.php');

    $qry = "select * from administrators where `id` = $adminid";

    $qryresults = mysql_query($qry, $db);

    $row = mysql_fetch_array($qryresults);

    $name = $row["name"];

    //$key = get_rand_id(32);

    $qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteadministrator')";

    $qryinsertresults = mysql_query($qryinsert, $db);

    echo "<p>Are you sure you want to delete the Administrator <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deleteadministrator&amp;administratorid=$adminid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";

}

function DeleteAdministrator($adminid, $key)
{

    require ('connection.php');

    $qry = "select * from `key` where `action` = 'deleteadministrator' and `key` = '$key'";

    $qryresults = mysql_query($qry, $db);

    $keyrow = mysql_fetch_array($qryresults);

    $keyid = $keyrow["id"];

    $count = mysql_num_rows($qryresults);

    if ($count == 1)
    {

        $qrydelete = "delete from `key` where `id` = $keyid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        $qrydelete = "delete from administrators where `id` = $adminid";

        $qrydeleteresults = mysql_query($qrydelete, $db);

        if (file_exists("../images/administrators/$adminid.jpg"))
        {

            unlink("../images/administrators/$adminid.jpg");

        }

        echo "<p>The Administrator has been deleted successfully.</p>";

        Administrators(1);

    }

    else
    {

        echo "<p>It wont work if you enter the url just like that to delete a administrator...</p>";

        Administrators(1);

    }

}



?>