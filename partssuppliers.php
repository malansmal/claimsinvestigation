<?php

function EditPartSupplier($partssupplierid) {

	global $db;

	$qry = "select * from partssuppliers where id = $partssupplierid";

	$qryresults = mysql_query($qry, $db);

	$row = mysql_fetch_array($qryresults);

	

	$name = stripslashes($row["name"]);

	$contactname = stripslashes($row['contactname']);

	$telno = stripslashes($row['telno']);

	$faxno = stripslashes($row['faxno']);

	$cellno = stripslashes($row['cellno']);

	$email = $row['email'];
	$email2 = $row['email2'];

	$password = stripslashes($row['password']);

	$bankdetails = stripslashes($row["bankdetails"]);
	
	$vatno = stripslashes($row["vatno"]);

	$adr1 = stripslashes($row["adr1"]);

	$adr2 = stripslashes($row["adr2"]);

	$adr3 = stripslashes($row["adr3"]);

	$adr4 = stripslashes($row["adr4"]);

	$latitude = stripslashes($row["latitude"]);

	$longitude = stripslashes($row["longitude"]);

	$accesstoken = $row['profile_access_token'];

	$usedChecked = ($row['used'] == '1') ? 'checked="checked"' : '';
	$alternateChecked = ($row['alternate'] == '1') ? 'checked="checked"' : '';
	$newChecked = ($row['new'] == '1') ? 'checked="checked"' : '';


	$savedAreas = [];
	$savedAreasQry = "SELECT areaid FROM `partsupplier_area` WHERE partssupplierid='".$partssupplierid."' ";

	$savedAreasResult = mysql_query($savedAreasQry);

	while($row = mysql_fetch_array($savedAreasResult)) {
		$savedAreas[] = $row['areaid'];
	}

	$savedMakes = [];
	$savedMakesQry = "SELECT vehiclemakeid FROM `partssupplier_vehiclemake` WHERE partssupplierid='".$partssupplierid."' ";

	$savedMakesResult = mysql_query($savedMakesQry);

	while($row = mysql_fetch_array($savedMakesResult)) {
		$savedMakes[] = $row['vehiclemakeid'];
	}


	echo "<form method=\"post\" action=\"update-partsupplier-profile.php?action=partssupplieredited&psid=$partssupplierid&token=$accesstoken\" enctype=\"multipart/form-data\" name=\"theform\">

			  <p>Enter the new Part Supplier details and click Save</p>

				<table class=\"table table-striped\">

					<tr>

						<td>Part Supplier's Name:</td>

						<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Contact Name:</td>

						<td><input type=\"text\" name=\"contactname\" maxlength=\"50\" value=\"$contactname\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Telephone Number:</td>

						<td><input type=\"text\" name=\"telno\" maxlength=\"20\" value=\"$telno\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Fax Number:</td>

						<td><input type=\"text\" name=\"faxno\" maxlength=\"20\" value=\"$faxno\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Cell Number:</td>

						<td><input type=\"text\" name=\"cellno\" maxlength=\"20\" value=\"$cellno\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Email Address:</td>

						<td><input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$email\"></td>

						<td>Email Address 2 : </td>
						<td><input type=\"text\" name=\"email2\" maxlength=\"60\" value=\"$email2\"></td>

					</tr>

					<tr>

						<td>
						
							Address: 
							<span class=\"pull-right\">Street Name:</span>
							<div class=\"clear clearfix\"></div>
						</td>

						<td><input type=\"text\" name=\"adr1\" maxlength=\"50\" value=\"$adr1\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>
							<span class=\"pull-right\">Suburb:</span>
							<div class=\"clear clearfix\"></div>
						</td>

						<td><input type=\"text\" name=\"adr2\" maxlength=\"50\" value=\"$adr2\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>
							<span class=\"pull-right\">Province:</span>
							<div class=\"clear clearfix\"></div>
						</td>

						<td><input type=\"text\" name=\"adr3\" maxlength=\"50\" value=\"$adr3\"></td>

						<td>Latitude <br /> (Please enter as per google format: -26.394479)</td>
						<td><input type=\"text\" name=\"latitude\" maxlength=\"15\" value=\"$latitude\"></td>

					</tr>

					<tr>

						<td>
							<span class=\"pull-right\">Code:</span>
							<div class=\"clear clearfix\"></div>
						</td>

						<td><input type=\"text\" name=\"adr4\" maxlength=\"50\" value=\"$adr4\"></td>

						<td>Longitude <br />(Please enter as per google format: 30.37676257)</td>
						<td><input type=\"text\" name=\"longitude\" maxlength=\"15\" value=\"$longitude\"></td>

					</tr>						

					<tr>

						<td>Password:</td>

						<td><input type=\"text\" name=\"password\" maxlength=\"20\" value=\"$password\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td>Bank Details:</td>

						<td><input type=\"text\" name=\"bankdetails\" maxlength=\"255\" value=\"$bankdetails\"></td>

						<td colspan=\"2\">
							Select <input type='checkbox' name='used' value='1' ".$usedChecked." /> Used &nbsp;&nbsp;
							<input type='checkbox' name='alternate' value='1' ".$alternateChecked." /> Alternate &nbsp;&nbsp;
							<input type='checkbox' name='new' value='1' ".$newChecked." /> New
						</td>

					</tr>
					
					<tr>

						<td>VAT Number:</td>

						<td><input type=\"text\" name=\"vatno\" maxlength=\"10\" value=\"$vatno\"></td>

						<td>&nbsp;</td>
						<td>&nbsp;</td>

					</tr>

					<tr>

						<td valign=\"top\">Select Areas for this Part Supplier:</td>

						<td>
							<script type='text/javascript'>
								$(document).ready(function() {
									$('.select-all-makes').on('click', function() {
										if ($(this).is(':checked')) {
											$('input[name=\"vehiclemakes_list[]\"]').prop('checked', true);
										}
										else {
											$('input[name=\"vehiclemakes_list[]\"]').prop('checked', false);
										}
									});

									$('.select-all-areas').on('click', function() {
										if ($(this).is(':checked')) {
											$('input[name=\"supplier_areas[]\"]').prop('checked', true);
										}
										else {
											$('input[name=\"supplier_areas[]\"]').prop('checked', false);
										}
									});
								});
							</script>

							<input type='checkbox' class='select-all-areas' /> Select All <br />

						
						";

						$qryareas = "select * from areas order by `areaname`";

								$qryareasresults = mysql_query($qryareas, $db);

								$theareas = "";

								

								while ($arearow = mysql_fetch_array($qryareasresults))

								{

									$areaname = stripslashes($arearow["areaname"]);

									$id = $arearow["id"];

									$isChecked = ( in_array($id, $savedAreas) ) ? 'checked="checked"' : '';

									$theareas .= "<input type=\"checkbox\" name=\"supplier_areas[]\" value=\"$id\" ".$isChecked."> $areaname <br />";

								}

								$theareas = substr($theareas, 0, -6);

								echo "$theareas</td>

								<td > Select Makes for Parts Supplier: </td><td>
								<input type='checkbox' class='select-all-makes' /> Select All <br />
								";

								$makesQryResult = mysql_query('SELECT * FROM vehiclemake ORDER BY vehiclemake ASC');

								while($row = mysql_fetch_array($makesQryResult)) {

									$isChecked = ( in_array($row["id"], $savedMakes) ) ? 'checked="checked"' : '';


									echo '<label style="font-weight:normal;"><input type="checkbox" name="vehiclemakes_list[]" value="'.$row["id"].'" '.$isChecked.' /> &nbsp; ' . $row["vehiclemake"] . '</label><br />';
								}

								echo "</td>

			</tr>

			<tr>

						<td>Logo:</td><td colspan=\"3\">";


						if (file_exists("images/partsuppliers/$partssupplierid.jpg")) {

							echo "<img src=\"images/partsuppliers/$partssupplierid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

							<input type=\"file\" name=\"uploadfile\">";

						}
						else {

							echo "<input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)";

						}

						echo "

						</td>

					</tr>										

				</table>

				<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"partsupplierid\" value=\"$partssupplierid\">

		  </form>";

}


function PartSupplierEdited($partsupplierid, $name, $contactname, $telno, $faxno, $cellno, $email, $password) {

	global $db;

	$bankdetails		= $_POST["bankdetails"];
	$email2				= $_POST["email2"];
	$adr1				= $_POST["adr1"];
	$adr2				= $_POST["adr2"];
	$adr3				= $_POST["adr3"];
	$adr4				= $_POST["adr4"];
	$latitude			= $_POST["latitude"];
	$longitude			= $_POST["longitude"];
	$used				= $_POST["used"];
	$alternate			= $_POST["alternate"];
	$new				= $_POST["new"];
	$vehiclemakes_list	= $_POST['vehiclemakes_list'];
	$vatno				= $_POST["vatno"];

	

	$qryupdate = "update partssuppliers set `name` = '".mysql_real_escape_string($name)."',

									   `contactname` = '".mysql_real_escape_string($contactname)."',

									   `telno` = '".mysql_real_escape_string($telno)."',

									   `faxno` = '".mysql_real_escape_string($faxno)."',

									   `cellno` = '".mysql_real_escape_string($cellno)."',

									   `email` = '".mysql_real_escape_string($email)."',
									   `email2` = '".mysql_real_escape_string($email2)."',

									   `adr1` = '".mysql_real_escape_string($adr1)."',

									   `adr2` = '".mysql_real_escape_string($adr2)."',

									   `adr3` = '".mysql_real_escape_string($adr3)."',

									   `adr4` = '".mysql_real_escape_string($adr4)."',

									   `latitude` = '".mysql_real_escape_string($latitude)."',

									   `longitude` = '".mysql_real_escape_string($longitude)."',

									    `password` = '".mysql_real_escape_string($password)."',

									   `bankdetails` = '".mysql_real_escape_string($name)."',
									   
									   `vatno` = '".mysql_real_escape_string($name)."'
									   where `id` = $partsupplierid";

	$qryupdateresults = mysql_query($qryupdate, $db);

	$qrydeleteareas = "delete from partsupplier_area where partssupplierid = $partsupplierid";

	$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

	$qrydeletemakes = "delete from partssupplier_vehiclemake where partssupplierid = $partsupplierid";

	$qrydeletemakesresults = mysql_query($qrydeletemakes, $db);

	$supplier_areas = $_REQUEST['supplier_areas'];

	if ( !empty($supplier_areas) ) {
		foreach ($supplier_areas as $areaId) {
			mysql_query("insert into partsupplier_area (partssupplierid, areaid) values ($partsupplierid, $areaId)", $db);
		}
	}
	

	if ( !empty($vehiclemakes_list) ) {

		foreach ($vehiclemakes_list as $vehicleMakeId) {
			mysql_query("insert into partssupplier_vehiclemake (partssupplierid, vehiclemakeid) values ($partsupplierid, $vehicleMakeId)", $db);
		}

	}
	

	$i = $_REQUEST["uploadnewfile"];

	if ($i == 1)
	{

		if (file_exists("images/partsuppliers/$partsupplierid.jpg"))

		{

			unlink("images/partsuppliers/$partsupplierid.jpg");

		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "images/partsuppliers/$partsupplierid.jpg");

	}

	echo "<p>The Part Supplier has been edited successfully.</p>";

}

?>