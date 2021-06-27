<?php

	function EditPanelbeater($pbid) {

		global $db;

		$qry = "select * from panelbeaters where id = $pbid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		
		$name = stripslashes($row["name"]);
		$contactperson = stripslashes($row["contactperson"]);
		$adr1 = stripslashes($row["adr1"]);
		$adr2 = stripslashes($row["adr2"]);
		$adr3 = stripslashes($row["adr3"]);
		$adr4 = stripslashes($row["adr4"]);
		$contactno = stripslashes($row["contactno"]);
		$faxno = stripslashes($row["faxno"]);
		$email = stripslashes($row["email"]);
		$latitude = stripslashes($row["latitude"]);
		$longitude = stripslashes($row["longitude"]);
		$accesstoken = $row['profile_access_token'];


		$owner = stripslashes($row["owner"]);
		$owneremail  = stripslashes($row["owneremail"]);
		
		$costingclerk = stripslashes($row["costingclerk"]);
		$costingclerkemail = stripslashes($row["costingclerkemail"]);

		$workshopmanager = stripslashes($row["workshopmanager"]);
		$workshopmanageremail = stripslashes($row["workshopmanageremail"]);

		$estimator = stripslashes($row["estimator"]);
		$pbestimatoremail = stripslashes($row["pbestimatoremail"]);



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

		$pbAdmins = [];
		$pbAdminsQry = " SELECT administrator_id FROM panelbeaters_administrators WHERE panelbeater_id='".$pbid."' ";
		$adminsQryResult = mysql_query($pbAdminsQry, $db);

		while($pbadminrow = mysql_fetch_array($adminsQryResult)) {
			$pbAdmins[] = $pbadminrow['administrator_id'];
		}

		$pbVehicleMakes = [];
		$pbVehicleMakesQry = " SELECT vehiclemake_id FROM panelbeaters_vehiclemakes WHERE panelbeater_id='".$pbid."' ";
		$vehicleMakesQryResult = mysql_query($pbVehicleMakesQry, $db);

		while($pbvehiclemakerow = mysql_fetch_array($vehicleMakesQryResult)) {
			$pbVehicleMakes[] = $pbvehiclemakerow['vehiclemake_id'];
		}

		$pbareasRes = mysql_query(" SELECT * FROM panelbeater_area WHERE panelbeaterid='".$pbid."'");

		$pbAreas = [];

		while($pbAreaRow = mysql_fetch_array($pbareasRes)) {
			$pbAreas[] = $pbAreaRow['areaid'];
		}

		echo "<form method=\"post\" action=\"update-pb-profile.php?action=panelbeateredited&pbid=$pbid&token=$accesstoken\" name=\"theform\" enctype=\"multipart/form-data\">
				  <p>Enter the new Panel Beater details and click Save</p>
					<table class=\"table table-striped\">
						<tr>
							<td>Name:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbname\" maxlength=\"50\" value=\"$name\"></td>
						</tr>
						
						<tr>
							<td>Contact Person:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbcontactperson\" maxlength=\"50\" value=\"$contactperson\"></td>
						</tr>
						<tr>
							<td>Address:</td>
							<td class='text-right'>STREET ADDRESS</td>
							<td><input type=\"text\" name=\"pbadr1\" maxlength=\"50\" value=\"$adr1\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='text-right'>ONLY ENTER SUBURB</td>
							<td><input type=\"text\" name=\"pbadr2\" maxlength=\"50\" value=\"$adr2\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='text-right'>ONLY ENTER PROVINCE</td>
							<td><input type=\"text\" name=\"pbadr3\" maxlength=\"50\" value=\"$adr3\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class='text-right'>ONLY ENTER AREA CODE</td>
							<td><input type=\"text\" name=\"pbadr4\" maxlength=\"50\" value=\"$adr4\"></td>
						</tr>
						<tr>
							<td>Contact Number:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbcontactno\" maxlength=\"50\" value=\"$contactno\"></td>
						</tr>
						<tr>
							<td>Fax Number:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbfaxno\" maxlength=\"50\" value=\"$faxno\"></td>
						</tr>	
						<tr>
							<td>Email Address:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbemail\" maxlength=\"50\" value=\"$email\"></td>
						</tr>

						<tr>
							<td>Owner/Manager Name:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"owner\" maxlength=\"50\" value=\"$owner\"></td>
						</tr>
						<tr>
							<td>Owner/Manager Email:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"owneremail\" maxlength=\"50\" value=\"$owneremail\"></td>
						</tr>

						<tr>
							<td>Costing Clerk:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"costingclerk\" maxlength=\"50\" value=\"$costingclerk\"></td>
						</tr>
						<tr>
							<td>Costing Clerk Email:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"costingclerkemail\" maxlength=\"50\" value=\"$costingclerkemail\"></td>
						</tr>

						<tr>
							<td>Workshop Manager:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"workshopmanager\" maxlength=\"50\" value=\"$workshopmanager\"></td>
						</tr>
						<tr>
							<td>Workshop Manager Email:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"workshopmanageremail\" maxlength=\"50\" value=\"$workshopmanageremail\"></td>
						</tr>

						<tr>
							<td>Estimator:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"estimator\" maxlength=\"50\" value=\"$estimator\"></td>
						</tr>
						<tr>
							<td>Estimator Email:</td>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbestimatoremail\" maxlength=\"50\" value=\"$pbestimatoremail\"></td>
						</tr>

						<tr>
							<td>Latitude:</td>
							<td class='text-right'>(Please enter as per google format: -26.394479)</td>
							<td><input type=\"text\" name=\"latitude\" maxlength=\"50\" value=\"$latitude\"></td>
						</tr>
						<tr>
							<td>Longitude:</td>
							<td class='text-right'>(Please enter as per google format: 30.37676257)</td>
							<td><input type=\"text\" name=\"longitude\" maxlength=\"50\" value=\"$longitude\"></td>
						</tr> ";
						
						if (file_exists("images/panelbeaters/$pbid.jpg")) {

							echo "<tr><td>Logo: </td><td><img src=\"images/panelbeaters/$pbid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br> <br>

							<input type=\"file\" name=\"uploadfile\"></td></tr>";

						}
						else {

							echo "<tr><td>Logo: </td><td><input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)</td></tr>";

						}


						echo " <tr>
							<td>
								<script type='text/javascript'>
									$(document).ready(function() {
										$('.select-all-makes').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"vehiclemakes[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"vehiclemakes[]\"]').prop('checked', false);
											}
										});

										$('.select-all-pbareas').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"panelbeater_areas[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"panelbeater_areas[]\"]').prop('checked', false);
											}
										});
										$('.select-all-admins').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"administrators[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"administrators[]\"]').prop('checked', false);
											}
										});
									});
								</script>

								<p style='background:#F1F1F1;padding:7px;'><strong>Please select Adminstrator/Insurer accredited with: </strong></p> <label><input type='checkbox' class='select-all-admins' /> Select All</label> <br /> ";
								
								foreach ($adminsListing as $adminUser) {
									$checked = (in_array($adminUser['id'], $pbAdmins)) ? 'checked="checked"' : '';
									echo '<label><input type="checkbox" name="administrators[]" value="'.$adminUser["id"].'" '.$checked.'> ' . $adminUser['name'] . '</label>';
									echo "<br />";
								
								}

						echo "	</td>
								<td>
									<p style='background:#F1F1F1;padding:7px;'><strong>Select Areas </strong></p>
									<label><input type='checkbox' class='select-all-pbareas' /> Select All</label> <br />
									";

									$qryareas = "select * from areas order by `areaname`";

									$qryareasresults = mysql_query($qryareas, $db);

									$theareas = "";

									while ($arearow = mysql_fetch_array($qryareasresults))
									{

										$areaname = stripslashes($arearow["areaname"]);

										$id = $arearow["id"];

										$isChecked = (in_array($id, $pbAreas)) ? 'checked="checked"' : '';

										$theareas .= "<label><input type=\"checkbox\" name=\"panelbeater_areas[]\" value=\"$id\" ".$isChecked."> $areaname </label> <br />";

									}

									$theareas = substr($theareas, 0, -6);

									echo $theareas;								


						echo"	</td>
							<td>
								<p style='background:#F1F1F1;padding:7px;'><strong>Select manufacturers accredited with: </strong></p> 
								<label><input type='checkbox' class='select-all-makes' /> Select All</label> <br />
								";
								
								foreach ($manufacturesListing as $vm) {
									$checked = (in_array($vm['id'], $pbVehicleMakes)) ? 'checked="checked"' : '';
									echo '<label><input type="checkbox" name="vehiclemakes[]" value="'.$vm["id"].'" '.$checked.'> ' . $vm['vehiclemake'] . '</label>';
									echo "<br />";
								}

						echo"	</td>
						</tr>




					</table>
					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"panelbeaterid\" value=\"$pbid\">
			  </form>";
	}
	
	function PanelbeaterEdited($pbid, $name, $owner, $costingclerk, $contactperson, $adr1, $adr2, $adr3, $adr4, $contactno, $faxno, $email,$latitude='',$longitude='') {

		global $db;


		$owneremail				= $_POST['owneremail'];
		$costingclerkemail		= $_POST['costingclerkemail'];
		$workshopmanager		= $_POST['workshopmanager'];
		$workshopmanageremail	= $_POST['workshopmanageremail'];
		$estimator				= $_POST['estimator'];
		$pbestimatoremail		= $_POST['pbestimatoremail'];
		$costingclerk			= $_POST['costingclerk'];

		$qryupdate = "update panelbeaters set `name` = '".mysql_real_escape_string($name)."',
											  `owner` = '".mysql_real_escape_string($owner)."',
											  `costingclerk` = '".mysql_real_escape_string($costingclerk)."',
											  `contactperson` = '".mysql_real_escape_string($contactperson)."',
											  `adr1` = '".mysql_real_escape_string($adr1)."',
											  `adr2` = '".mysql_real_escape_string($adr2)."',
											  `adr3` = '".mysql_real_escape_string($adr3)."',
											  `adr4` = '".mysql_real_escape_string($adr4)."',
											  `contactno` = '".mysql_real_escape_string($contactno)."',
											  `faxno`= '".mysql_real_escape_string($faxno)."',
											  `latitude`='".mysql_real_escape_string($latitude)."',
											  `longitude`='".mysql_real_escape_string($longitude)."',
											  `email` = '".mysql_real_escape_string($email)."',

											  `owneremail` = '".mysql_real_escape_string($owneremail)."',
											  `costingclerkemail` = '".mysql_real_escape_string($costingclerkemail)."',
											  `workshopmanager` = '".mysql_real_escape_string($workshopmanager)."',
											  `workshopmanageremail` = '".mysql_real_escape_string($workshopmanageremail)."',
											  `estimator` = '".mysql_real_escape_string($estimator)."',
											  `pbestimatoremail` = '".mysql_real_escape_string($pbestimatoremail)."'

											  WHERE `id` = $pbid";

		$qryupdateresults = mysql_query($qryupdate, $db);

		mysql_query(" DELETE FROM panelbeaters_administrators WHERE panelbeater_id='".$pbid."' ", $db);

		mysql_query(" DELETE FROM panelbeaters_vehiclemakes WHERE panelbeater_id='".$pbid."' ", $db);

		mysql_query(" DELETE FROM panelbeater_area WHERE panelbeaterid='".$pbid."' ", $db);

		$administrators = $_POST['administrators'];
		$vehiclemakes	= $_POST['vehiclemakes'];

		foreach ($administrators as $admin_id) {
			mysql_query(" INSERT INTO panelbeaters_administrators (panelbeater_id, administrator_id) VALUES ('".$pbid."', '".$admin_id."') ");
		}

		foreach ($vehiclemakes as $vm_id) {
			mysql_query(" INSERT INTO panelbeaters_vehiclemakes (panelbeater_id, vehiclemake_id) VALUES ('".$pbid."', '".$vm_id."') ");
		}

		$panelbeater_areas = $_REQUEST['panelbeater_areas'];

		if ( !empty($panelbeater_areas) ) {
			foreach ($panelbeater_areas as $areaId) {
				mysql_query("insert into panelbeater_area (panelbeaterid, areaid) values ($pbid, $areaId)", $db);
			}
		}


		if ($_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {

			if (!file_exists("images/panelbeaters")) {
				mkdir("images/panelbeaters", 0777);
			}

			if (file_exists("images/panelbeaters/$pbid.jpg"))
			{
				unlink("images/panelbeaters/$pbid.jpg");
			}

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "images/panelbeaters/$pbid.jpg");
		
		}


		echo '<p style="padding:10px 20px;background:#d7ffce;">Thank you. Your information has been saved.</p>';

	}

?>