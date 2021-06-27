<?php

require('admin/connection.php');

$adminsListing = [];

$qryadmin = "select id, name from `administrators` ORDER BY name ASC";
$qryadminresults = mysql_query($qryadmin, $db);

while($adminrow = mysql_fetch_array($qryadminresults)) {
	$adminsListing[] = $adminrow;
}

$manufacturesListing = [];

$qryvm = "select id, vehiclemake from `vehiclemake` ORDER BY vehiclemake ASC";
$qryvmresults = mysql_query($qryvm, $db);

while($vmrow = mysql_fetch_array($qryvmresults)) {
	$manufacturesListing[] = $vmrow;
}

if ( isset($_POST["pbemail"]) && strlen(trim($_POST["pbemail"]))>0 ) {

	$name = addslashes($_POST['pbname']);

	$owner = addslashes($_POST['owner']);

	$costingclerk = addslashes($_POST['costingclerk']);

	$contactperson = addslashes($_POST['pbcontactperson']);
	
	$owneremail = addslashes($_POST['owneremail']);
	$costingclerkemail = addslashes($_POST['costingclerkemail']);

	$workshopmanager = addslashes($_POST['workshopmanager']);
	$workshopmanageremail = addslashes($_POST['workshopmanageremail']);

	$estimator = addslashes($_POST['estimator']);
	$pbestimatoremail = addslashes($_POST['pbestimatoremail']);

	$password = addslashes(trim($_POST['pbpasstext']));

	$adr1 = addslashes($_POST["pbadr1"]);

	$adr2 = addslashes($_POST["pbadr2"]);

	$adr3 = addslashes($_POST["pbadr3"]);

	$adr4 = addslashes($_POST["pbadr4"]);


	$contactno = addslashes($_POST["pbcontactno"]);

	$faxno = addslashes($_POST["pbfaxno"]);

	$email = addslashes($_POST["pbemail"]);

	$latitude = addslashes($_POST["latitude"]);

	$longitude = addslashes($_POST["longitude"]);

	$qryinsert = " INSERT INTO panelbeaters ( `name`, `owner`, `costingclerk`, `contactperson`, `adr1`, `adr2`, `adr3`, `adr4`, `contactno`, `faxno`, `email`, `latitude`, `longitude`, `owneremail`, `costingclerkemail`, `workshopmanager`, `workshopmanageremail`, `estimator`, `pbestimatoremail`)
	VALUES ( '$name', '$owner', '$costingclerk', '$contactperson', '$adr1', '$adr2', '$adr3', '$adr4', '$contactno', '$faxno', '$email', '$latitude', '$longitude', '$owneremail', '$costingclerkemail', '$workshopmanager', '$workshopmanageremail', '$estimator', '$pbestimatoremail' )";

	$qryinsertresults = mysql_query($qryinsert, $db);

	$pbid = mysql_insert_id();


	if (!empty($password)) {
		mysql_query("UPDATE panelbeaters SET password='".md5($password)."' WHERE id='".$pbid."'", $db);
	}


	//echo $qryinsert;

	$administrators = $_POST['administrators'];
	$vehiclemakes	= $_POST['vehiclemakes'];

	if (!empty($administrators)) {
		foreach ($administrators as $admin_id) {
			mysql_query(" INSERT INTO panelbeaters_administrators (panelbeater_id, administrator_id) VALUES ('".$pbid."', '".$admin_id."') ");
		}
	}
	
	if ( !empty($vehiclemakes) ) {
		// vehicle makes
		foreach ($vehiclemakes as $vm_id) {
			mysql_query(" INSERT INTO panelbeaters_vehiclemakes (panelbeater_id, vehiclemake_id) VALUES ('".$pbid."', '".$vm_id."') ");
		}
	}


	// panelbeater areas

	$panelbeater_areas = $_REQUEST['panelbeater_areas'];

	if ( !empty($panelbeater_areas) ) {
		foreach ($panelbeater_areas as $areaId) {
			mysql_query("insert into panelbeater_area (panelbeaterid, areaid) values ($pbid, $areaId)", $db);
		}
	}

	if (!file_exists("images/panelbeaters")) {
		mkdir("images/panelbeaters", 0777);
	}

	if (file_exists("images/panelbeaters/$pbid.jpg"))
	{
		unlink("images/panelbeaters/$pbid.jpg");
	}

	move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "images/panelbeaters/$pbid.jpg");

	// Send a notification email to the admin regarding the sigining up of the panelbeater.

	require_once "vendor/autoload.php";

	$mail = new PHPMailer;

	$mail->setFrom('info@panelshop.co.za');
	$mail->addAddress('info@panelshop.co.za');		// Add a recipient

	$mail->isHTML(true);							// Set email format to HTML

	$mail->Subject = 'A new panelbeater is added.';

	$body = file_get_contents('templates/new-panelbeater-notification-to-admin.html');

	$details = '
			<table >
				<tr>
					<td>Name</td>
					<td><strong>' . $name . '</strong></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><strong>' . $email . '</strong></td>
				</tr>
				<tr>
					<td>Contact Person</td>
					<td><strong>' . $contactperson . '</strong></td>
				</tr>
				<tr>
					<td>Contact Number</td>
					<td><strong>' . $contactno . '</strong></td>
				</tr>
			</table>
	';

	$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/loggedinaction.php?action=editpanelbeater&panelbeaterid=' . $pbid;

	$body = str_replace(['{{PANELBEATER_NAME}}', '{{PROFILE_UPDATE_LINK}}', '{{PROFILE_DETAILS}}'], [$name, $profileLink, $details], $body);

	$mail->Body    = $body;
	$mail->AltBody = strip_tags($body);

	$mail->send();

	ob_clean();
	header('Location:add-panelbeater.php?success=true');

}
else if ( isset($_GET['success']) ) {
	echo "Thank you. Your information has been saved. ";die;
}

?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

<style type="text/css">
	input[type="text"] {width: 100%;max-width: 300px;padding: 4px 5px;box-shadow: none;border: 1px solid #cdcdcd;}
	.more-info {color:#6d6161;font-weight:bold;}
</style>

<form method="post" name="theform" enctype="multipart/form-data">
	<p style="font-weight:bold;text-transform:uppercase;">Enter the new Panel Beater details and click Save</p>
	<table class="table table-striped" cellspacing="10">
		<tr>
			<td>Name:</td>
			<td><input type="text" name="pbname" maxlength="50" required="true"></td>
		</tr>
		<tr>
			<td>Contact Person</td>
			<td><input type="text" name="pbcontactperson" maxlength="50"></td>
		</tr>
		<tr>
			<td>Address : </td>
			<td><input type="text" name="pbadr1" maxlength="50"> <span class="more-info"> (street address)</span></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" name="pbadr2" maxlength="50"> <span class="more-info"> (only enter suburb)</span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="text" name="pbadr3" maxlength="50"> <span class="more-info"> (only enter province)</span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="text" name="pbadr4" maxlength="50"> <span class="more-info"> (only enter area code)</span></td>
		</tr>
		<tr>
			<td>Contact Number:</td>
			<td><input type="text" name="pbcontactno" maxlength="50"></td>
		</tr>
		<tr>
			<td>Fax Number:</td>
			<td><input type="text" name="pbfaxno" maxlength="50"></td>
		</tr>	
		<tr>
			<td>Email Address:</td>
			<td><input type="email" name="pbemail" maxlength="50" required="true"></td>
		</tr>

		<tr>
			<td>Choose a Password:</td>
			<!-- <td><input type="text" name="pbpasstext" required="true" pattern=".{6,}" title="6 characters minimum" maxlength="30"></td> -->
			<td><input type="text" name="pbpasstext" maxlength="30"></td>
		</tr>

		<tr>
			<td>Owner/Manager Name:</td>
			<td><input type="text" name="owner" maxlength="50"></td>
		</tr>
		<tr>
			<td>Owner/Manager Email:</td>
			<td><input type="text" name="owneremail" maxlength="50"></td>
		</tr>

		<tr>
			<td>Costing Clerk:</td>
			<td><input type="text" name="costingclerk" maxlength="50"></td>
		</tr>

		<tr>
			<td>Costing Clerk Email:</td>
			<td><input type="text" name="costingclerkemail" maxlength="50"></td>
		</tr>

		<tr>
			<td>Workshop Manager:</td>
			<td><input type="text" name="workshopmanager" maxlength="50"></td>
		</tr>
		<tr>
			<td>Workshop Manager Email:</td>
			<td><input type="text" name="workshopmanageremail" maxlength="50"></td>
		</tr>

		<tr>
			<td>Estimator:</td>
			<td><input type="text" name="estimator" maxlength="50"></td>
		</tr>
		<tr>
			<td>Estimator Email:</td>
			<td><input type="text" name="pbestimatoremail" maxlength="50"></td>
		</tr>
		<tr>
			<td>Latitude : </td>
			<td><input type="text" name="latitude" maxlength="50" > 
				<br /><span class="more-info">(Please enter as per google format: -26.394479)</span>
			</td>
		</tr>
		<tr>
			<td>Longitude: </td>
			<td><input type="text" name="longitude" maxlength="50" > <br /><span class="more-info"> (Please enter as per google format: 30.37676257)</span></td>
		</tr>

		<tr>
			<td>Logo:</td>
			<td><input type="file" name="uploadfile"> (note: images MUST be 100 x 100 pixels)</td>
		</tr>

		<tr>
			<td valign="top">
				
				<script type='text/javascript'>
					$(document).ready(function() {
						
						$('.select-all-admins').on('click', function() {
							if ($(this).is(':checked')) {
								$('input[name="administrators[]"]').prop('checked', true);
							}
							else {
								$('input[name="administrators[]"]').prop('checked', false);
							}
						});
						
						$('.select-all-makes').on('click', function() {
							if ($(this).is(':checked')) {
								$('input[name="vehiclemakes[]"]').prop('checked', true);
							}
							else {
								$('input[name="vehiclemakes[]"]').prop('checked', false);
							}
						});

						$('.select-all-areas').on('click', function() {
							if ($(this).is(':checked')) {
								$('input[name="panelbeater_areas[]"]').prop('checked', true);
							}
							else {
								$('input[name="panelbeater_areas[]"]').prop('checked', false);
							}
						});
					});
				</script>


				<p><strong>Please select Adminstrator Accredited with: </strong></p>
				<label><input type='checkbox' class='select-all-admins' /> Select All </label><br />
				<?php
					foreach ($adminsListing as $adminUser) {
						echo '<label><input type="checkbox" name="administrators[]" value="'.$adminUser["id"].'"> ' . $adminUser['name'] . '</label>';
						echo "<br />";
					}
				?>

			</td>
			<td valign="top">
				<p><strong>Select Areas </strong></p>
				<label><input type='checkbox' class='select-all-areas' /> Select All </label> <br />
				<?php

					$qryareas = "select * from areas order by `areaname`";

					$qryareasresults = mysql_query($qryareas, $db);

					$theareas = "";

					while ($arearow = mysql_fetch_array($qryareasresults))
					{

						$areaname = stripslashes($arearow["areaname"]);

						$id = $arearow["id"];

						$theareas .= '<label><input type="checkbox" name="panelbeater_areas[]" value="'.$id.'"> '. $areaname . ' </label> <br />';

					}

					$theareas = substr($theareas, 0, -6);

					echo $theareas;
				?>
			</td>

			<td valign="top">
				<p><strong>Select Manufacturers Accredited with:</strong></p>
				<label><input type='checkbox' class='select-all-makes' /> Select All</label> <br />
				<?php
				foreach ($manufacturesListing as $vm) {
					echo '<label><input type="checkbox" name="vehiclemakes[]" value="'.$vm["id"].'"> ' . $vm['vehiclemake'] . '</label>';
					echo "<br />";
				}
				?>
			</td>
		</tr>

	</table>
	<br><input type="submit" value="Save">
	<input type="reset" value="Clear">
  </form>