<?php

require('admin/connection.php');

if ( !empty($_POST)  && !empty($_POST["email"]) ) {

	$name			= addslashes($_POST['name']);
	$contactname	= addslashes($_POST['contactname']);
	$telno			= addslashes($_POST['telno']);
	$faxno			= addslashes($_POST['faxno']);
	$cellno			= addslashes($_POST['cellno']);
	$email			= addslashes($_POST['email']);

	$bankdetails	= addslashes($_REQUEST["bankdetails"]);

	$email2 = addslashes($_REQUEST["email2"]);

	$adr1 = addslashes($_REQUEST["adr1"]);

	$adr2 = addslashes($_REQUEST["adr2"]);

	$adr3 = addslashes($_REQUEST["adr3"]);

	$adr4 = addslashes($_REQUEST["adr4"]);

	$latitude = addslashes($_REQUEST["latitude"]);

	$longitude = addslashes($_REQUEST["longitude"]);

	$cars = isset($_REQUEST['cars']) ? 1 : 0;
	$hcv = isset($_REQUEST['hcv']) ? 1 : 0;
	$roll_back = isset($_REQUEST['roll_back']) ? 1 : 0;
	
	$vatno = $_REQUEST["vatno"];

	$password = addslashes(trim($_POST['pbpasstext']));

	$vehicle_registration_no = $_POST['vehicle_registration_no'];

	$qryinsert = "	INSERT INTO towingoperators 
					( `name`, `contactname`, `telno`, `faxno`, `cellno`, `email`, `adr1`, `adr2`, `adr3`, `adr4`, `bankdetails`, `vatno`, `email2`, `latitude`, `longitude`, `cars`, `hcv`, `roll_back`, `vehicle_registration_no`)
					VALUES 
					('$name', '$contactname', '$telno', '$faxno', '$cellno', '$email', '$adr1', '$adr2', '$adr3', '$adr4', '$bankdetails', '$vatno', '$email2', '$latitude', '$longitude', '$cars', '$hcv', '$roll_back', '$vehicle_registration_no') ";

	$qryinsertresults = mysql_query($qryinsert, $db);

	//get the new id

	$newid = mysql_insert_id();

	if (!empty($password)) {
		mysql_query("UPDATE towingoperators SET password='".md5($password)."' WHERE id='".$newid."'", $db);
	}
	
	$towingoperator_areas = $_REQUEST['towingoperator_areas'];

	if ( !empty($towingoperator_areas) ) {
		foreach ($towingoperator_areas as $areaId) {
			mysql_query("INSERT INTO towingoperator_area (towingoperatorid, areaid) VALUES ($newid, $areaId)", $db);
		}
	}
	
	// Photo towing operator
	if (!file_exists("images/towingoperatorss")) {
		mkdir("images/towingoperatorss", 0777);
	}
	
	if (file_exists("images/towingoperatorss/$newid.jpg")) {
		unlink("images/towingoperatorss/$newid.jpg");
	}

	move_uploaded_file($_FILES['uploadfile'] ['tmp_name'], "images/towingoperatorss/$newid.jpg");
	

	// Photo driver

	if (!file_exists("images/photodriver")) {
		mkdir("images/photodriver", 0777);
	}
	
	if (file_exists("images/photodriver/$newid.jpg")) {
		unlink("images/photodriver/$newid.jpg");
	}

	move_uploaded_file ($_FILES['photodriver'] ['tmp_name'], "../images/photodriver/$newid.jpg");

	// Photo towing truck
	if (!file_exists("../images/photo_towing_truck")) {
		mkdir("../images/photo_towing_truck", 0777);
	}
	
	if (file_exists("../images/photo_towing_truck/$newid.jpg")) {
		unlink("../images/photo_towing_truck/$newid.jpg");
	}

	move_uploaded_file ($_FILES['photo_towing_truck'] ['tmp_name'], "../images/photo_towing_truck/$newid.jpg");


	// Send a notification email to the admin regarding the sigining up of the tow operator.

	require_once "vendor/autoload.php";

	$mail = new PHPMailer;

	$mail->setFrom('info@panelshop.co.za');
	$mail->addAddress('info@panelshop.co.za');		// Add a recipient

	$mail->isHTML(true);							// Set email format to HTML

	$mail->Subject = 'A new towing operator is added.';

	$body = file_get_contents('templates/new-towingoperator-notification-to-admin.html');

	$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/loggedinaction.php?action=edittowingoperator&towingoperatorid=' . $pbid;

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
					<td>Contact Name</td>
					<td><strong>' . $contactname . '</strong></td>
				</tr>
				<tr>
					<td>Cell Number</td>
					<td><strong>' . $cellno . '</strong></td>
				</tr>
			</table>
	';


	$body = str_replace(['{{TOWOPERATOR_NAME}}', '{{PROFILE_UPDATE_LINK}}'], [$name, $profileLink, $details], $body);

	$mail->Body    = $body;
	$mail->AltBody = strip_tags($body);

	$mail->send();

	ob_clean();
	header('Location:add-towoperator.php?success=true');

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


	<form method="post" action="add-towoperator.php" enctype="multipart/form-data" name="theform">
		<p style="font-weight:bold;text-transform:uppercase;">Enter the new Towing Operator details and click Save</p>
		<table class="table table-striped" width="100%">
			<tr>
				<td>Towing Operator's Name:</td>
				<td><input type="text" name="name" maxlength="50"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>Contact Name:</td>
				<td><input type="text" name="contactname" maxlength="50"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>Telephone Number:</td>
				<td><input type="text" name="telno" maxlength="20"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>

			</tr>

			<tr>
				<td>Fax Number:</td>
				<td><input type="text" name="faxno" maxlength="20"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>Cell Number:</td>
				<td><input type="text" name="cellno" maxlength="20"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>Email Address:</td>
				<td><input type="text" name="email" maxlength="60"></td>
				<td>Email Address 2 : </td>
				<td><input type="text" name="email2" maxlength="60"></td>
			</tr>

			<tr>
				<td>Choose a Password:</td>
				<td><input type="text" name="pbpasstext" pattern=".{6,}" title="6 characters minimum" maxlength="30"></td>
			</tr>

			<tr>
				<td>							
					Address: 
					<span class="pull-right">Street Name:</span>
					<div class="clear clearfix"></div>
				</td>
				<td><input type="text" name="adr1" maxlength="50"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>
					<span class="pull-right">Suburb:</span>
					<div class="clear clearfix"></div>
				</td>							
				<td><input type="text" name="adr2" maxlength="50"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>
					<span class="pull-right">Province:</span>
					<div class="clear clearfix"></div>
				</td>
				<td><input type="text" name="adr3" maxlength="50"></td>
				<td>Latitude</td>
				<td><input type="text" name="latitude" maxlength="15">
				<br /><span class="more-info">(Please enter as per google format: -26.394479)</span>
				</td>
			</tr>

			<tr>
				<td>
					<span class="pull-right">Code:</span>
					<div class="clear clearfix"></div>
				</td>
				<td><input type="text" name="adr4" maxlength="50"></td>
				<td>Longitude</td>
				<td><input type="text" name="longitude" maxlength="15">
				 <br /><span class="more-info"> (Please enter as per google format: 30.37676257)</span></td>
			</tr>

			<tr>
				<td>Bank Details:</td>
				<td><input type="text" name="bankdetails" maxlength="255"></td>
				<td colspan="2">
					Select <input type='checkbox' name='cars' value='1' /> Cars &nbsp;&nbsp;
					<input type='checkbox' name='hcv' value='1' /> HCV &nbsp;&nbsp;
					<input type='checkbox' name='roll_back' value='1' /> Roll Back
				</td>
			</tr>
			
			<tr>
				<td>VAT Number:</td>
				<td><input type="text" name="vatno" maxlength="10"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>Vehicle Registration Number:</td>
				<td><input type="text" name="vehicle_registration_no" maxlength="20"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td valign="top">Select Areas for this Towing Operator:
				
					<script type='text/javascript'>
						$(document).ready(function() {
				
							$('.select-all-areas').on('click', function() {
								if ($(this).is(':checked')) {
									$('input[name="towingoperator_areas[]"]').prop('checked', true);
								}
								else {
									$('input[name="towingoperator_areas[]"]').prop('checked', false);
								}
							});
						});
					</script>
				</td>
				<td>
					<label><input type='checkbox' class='select-all-areas' /> Select All </label> <br />

					<?php
							$qryareas = "select * from areas order by `areaname`";

							$qryareasresults = mysql_query($qryareas, $db);

							$theareas = "";									

							while ($arearow = mysql_fetch_array($qryareasresults)) {
								$areaname = stripslashes($arearow["areaname"]);
								$id = $arearow["id"];
								$theareas .= '<input type="checkbox" name="towingoperator_areas[]" value="' . $id . '"> ' . $areaname . '<br />';
							}

							$theareas = substr($theareas, 0, -6);

							echo $theareas;
					?>
				</td>
				<td colspan='2'>&nbsp;</td>
			</tr>

			<tr>
				<td>Logo:</td>
				<td colspan="3"><input type="file" name="uploadfile" /> (note: images MUST be 100 x 100 pixels)</td>
			</tr>

			<tr>
				<td>Driver Photo:</td>
				<td colspan="3"><input type="file" name="photodriver" /> (note: images MUST be 100 x 100 pixels)</td>
			</tr>

			<tr>
				<td>Towing Truck Photo:</td>
				<td colspan="3"><input type="file" name="photo_towing_truck" /> (note: images MUST be 100 x 100 pixels)</td>
			</tr>

		</table>
		<br><input type="submit" value="Save"> <input type="reset" value="Clear">
	</form>