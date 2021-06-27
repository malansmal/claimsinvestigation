<?php

require('admin/connection.php');

//print '<pre>'; print_r($_POST);die;


if ( !empty($_POST)  && !empty($_POST["email"]) ) {

	$name			= addslashes($_POST['name']);
	$contactname	= addslashes($_POST['contactname']);
	$telno			= addslashes($_POST['telno']);
	$faxno			= addslashes($_POST['faxno']);
	$cellno			= addslashes($_POST['cellno']);
	$email			= addslashes($_POST['email']);

	$bankdetails = addslashes($_REQUEST["bankdetails"]);

	$email2 = addslashes($_REQUEST["email2"]);

	$adr1 = addslashes($_REQUEST["adr1"]);

	$adr2 = addslashes($_REQUEST["adr2"]);

	$adr3 = addslashes($_REQUEST["adr3"]);

	$adr4 = addslashes($_REQUEST["adr4"]);

	$latitude = addslashes($_REQUEST["latitude"]);

	$longitude = addslashes($_REQUEST["longitude"]);

	$used = isset($_REQUEST['used']) ? 1 : 0;
	$alternate = isset($_REQUEST['alternate']) ? 1 : 0;
	$new = isset($_REQUEST['new']) ? 1 : 0;

	$vehiclemakes_list = $_REQUEST['vehiclemakes_list'];
	
	$vatno = $_REQUEST["vatno"];

	$qryinsert =	" INSERT INTO partssuppliers 
					  (`name`, `contactname`, `telno`, `faxno`, `cellno`, `email`, `adr1`, `adr2`, `adr3`, `adr4`, `bankdetails`, `vatno`, `email2`, `latitude`, `longitude`, `used`, `alternate`, `new`)
					  VALUES 
					  ('$name', '$contactname', '$telno', '$faxno', '$cellno', '$email', '$adr1', '$adr2', '$adr3', '$adr4', '$bankdetails', '$vatno', '$email2', '$latitude', '$longitude', '$used', '$alternate', '$new') ";

	$qryinsertresults = mysql_query($qryinsert, $db);

	//get the new id

	$newid = mysql_insert_id();
	
	$supplier_areas = $_REQUEST['supplier_areas'];

	if ( !empty($supplier_areas) ) {
		foreach ($supplier_areas as $areaId) {
			mysql_query("INSERT INTO partsupplier_area (partssupplierid, areaid) VALUES ($newid, $areaId)", $db);
		}
	}
	
	if ( !empty($vehiclemakes_list) ) {

		foreach ($vehiclemakes_list as $vehicleMakeId) {
			mysql_query("INSERT INTO partssupplier_vehiclemake (partssupplierid, vehiclemakeid) VALUES ($newid, $vehicleMakeId)", $db);
		}

	}

	if (file_exists("images/partsuppliers/$newid.jpg")) {

		unlink("images/partsuppliers/$newid.jpg");

	}

	move_uploaded_file($_FILES['uploadfile'] ['tmp_name'], "images/partsuppliers/$newid.jpg");


	// Send a notification email to the admin regarding the sigining up of the panelbeater.

	require_once "vendor/autoload.php";

	$mail = new PHPMailer;

	$mail->setFrom('info@panelshop.co.za');
	$mail->addAddress('info@panelshop.co.za');		// Add a recipient

	$mail->isHTML(true);							// Set email format to HTML

	$mail->Subject = 'A new parts supplier is added.';

	$body = file_get_contents('templates/new-partsupplier-notification-to-admin.html');

	$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/loggedinaction.php?action=editpartssupplier&partssupplierid=' . $newid;

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

	$body = str_replace(['{{PARTSUPPLIER_NAME}}', '{{PROFILE_UPDATE_LINK}}'], [$name, $profileLink, $details], $body);

	$mail->Body    = $body;
	$mail->AltBody = strip_tags($body);

	$mail->send();

	ob_clean();
	header('Location:add-partsupplier.php?success=true');

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

<form method="post" action="add-partsupplier.php" enctype="multipart/form-data" name="theform">

	<p style="font-weight:bold;text-transform:uppercase;">Enter the new Part Supplier details and click Save</p>
	<table class="table table-striped">
		<tr>
			<td>Part Supplier's Name:</td>
			<td><input type="text" name="name" maxlength="50" required="true"></td>
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
			<td><input type="email" name="email" maxlength="60"></td>
			<td style="padding-left:50px;">Email Address 2 : </td>
			<td><input type="text" name="email2" maxlength="60"></td>
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
			<td style="padding-left:50px;">Latitude</td>
			<td><input type="text" name="latitude" maxlength="15"><br /><span class="more-info">(Please enter as per google format: -26.394479)</span></td>
		</tr>

		<tr>
			<td>
				<span class="pull-right">Code:</span>
				<div class="clear clearfix"></div>
			</td>
			<td><input type="text" name="adr4" maxlength="50"></td>
			<td style="padding-left:50px;">Longitude</td>
			<td><input type="text" name="longitude" maxlength="15"><br /><span class="more-info"> (Please enter as per google format: 30.37676257)</span></td>
		</tr>

		<tr>
			<td>Comments:</td>
			<td><input type="text" name="comments"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td>Password:</td>
			<td><input type="text" name="password" maxlength="20"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td>Bank Details:</td>
			<td><input type="text" name="bankdetails" maxlength="255"></td>
			<td colspan="2" style="padding-left:50px;">
				Select <input type='checkbox' name='used' value='1' /> Used &nbsp;&nbsp;
				<input type='checkbox' name='alternate' value='1' /> Alternate &nbsp;&nbsp;
				<input type='checkbox' name='new' value='1' /> New
			</td>
		</tr>

		<tr>
			<td>VAT Number:</td>
			<td><input type="text" name="vatno" maxlength="10"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

		<tr>
			<td valign="top">Select Areas for this Part Supplier:</td>
			<td valign="top">

				<script type='text/javascript'>
					$(document).ready(function() {
						$('.select-all-makes').on('click', function() {
							if ($(this).is(':checked')) {
								$('input[name="vehiclemakes_list[]"]').prop('checked', true);
							}
							else {
								$('input[name="vehiclemakes_list[]"]').prop('checked', false);
							}
						});

						$('.select-all-areas').on('click', function() {
							if ($(this).is(':checked')) {
								$('input[name="supplier_areas[]"]').prop('checked', true);
							}
							else {
								$('input[name="supplier_areas[]"]').prop('checked', false);
							}
						});
					});
				</script>

				<input type='checkbox' class='select-all-areas' /> Select All <br />

				<?php
					$qryareas = "select * from areas order by `areaname`";
					$qryareasresults = mysql_query($qryareas, $db);
					$theareas = "";				

					while ($arearow = mysql_fetch_array($qryareasresults))
					{
						$areaname = stripslashes($arearow["areaname"]);
						$id = $arearow["id"];
						$theareas .= '<input type="checkbox" name="supplier_areas[]" value="' . $id . '"> ' . $areaname . ' <br />';
					}

					$theareas = substr($theareas, 0, -6);

					echo $theareas;	
				?>		
			</td>
			<td valign="top" style="padding-left:50px;"> Select Makes for Parts Supplier: </td>			
			<td valign="top">
				<input type='checkbox' class='select-all-makes' /> Select All <br />
				<?php
					$makesQryResult = mysql_query('SELECT * FROM vehiclemake ORDER BY vehiclemake ASC');

					while($row = mysql_fetch_array($makesQryResult)) {
						echo '<label style="font-weight:normal;"><input type="checkbox" name="vehiclemakes_list[]" value="'.$row["id"].'" /> &nbsp; ' . $row["vehiclemake"] . '</label><br />';
					}				
				?>
			</td>
		</tr>
		<tr>
			<td>Logo:</td>
			<td colspan="3"><input type="file" name="uploadfile"> (note: images MUST be 100 x 100 pixels)</td>
		</tr>
	</table>
	<br><input type="submit" value="Save"> <input type="reset" value="Clear">
</form>