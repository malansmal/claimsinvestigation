<?php

	function EditTowingOperator($towingoperatorid) {

		global $db;

		$qry = "select * from towingoperators where id = $towingoperatorid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);

		$name = stripslashes($row["name"]);
		$contactname = stripslashes($row['contactname']);
		$telno = stripslashes($row['telno']);
		$faxno = stripslashes($row['faxno']);
		$cellno = stripslashes($row['cellno']);
		$email = $row['email'];
		$email2 = $row['email2'];
		$comments = stripslashes($row['comments']);
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

		$vehicle_registration_no = stripslashes($row['vehicle_registration_no']);		
		
		$carsChecked = ($row['cars'] == '1') ? 'checked="checked"' : '';
		$hcvChecked = ($row['hcv'] == '1') ? 'checked="checked"' : '';
		$rollBackChecked = ($row['roll_back'] == '1') ? 'checked="checked"' : '';

		$savedAreas = [];
		$savedAreasQry = "SELECT areaid FROM `towingoperator_area` WHERE towingoperatorid='".$towingoperatorid."' ";

		$savedAreasResult = mysql_query($savedAreasQry);

		while($row = mysql_fetch_array($savedAreasResult)) {
			$savedAreas[] = $row['areaid'];
		}

		echo "<form method=\"post\" action=\"update-towingoperator-profile.php?action=towingoperatoredited&towingoperatorid=$towingoperatorid&token=$accesstoken\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Towing Operator details and click Save</p>
					<table class=\"table table-striped\">
						<tr>
							<td>Towing Operator's Name:</td>
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
								<span class=\"pull-right\">STREET ADDRESS</span>
								<div class=\"clear clearfix\"></div>
							</td>
							<td><input type=\"text\" name=\"adr1\" maxlength=\"50\" value=\"$adr1\"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>
								<span class=\"pull-right\">ONLY ENTER SUBURB</span>
								<div class=\"clear clearfix\"></div>
							</td>
							<td><input type=\"text\" name=\"adr2\" maxlength=\"50\" value=\"$adr2\"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>
								<span class=\"pull-right\">ONLY ENTER PROVINCE</span>
								<div class=\"clear clearfix\"></div>
							</td>
							<td><input type=\"text\" name=\"adr3\" maxlength=\"50\" value=\"$adr3\"></td>
							<td>Latitude <br />(Please enter as per google format: -26.394479)</td>
							<td><input type=\"text\" name=\"latitude\" maxlength=\"15\" value=\"$latitude\"></td>
						</tr>
						<tr>
							<td>
								<span class=\"pull-right\">ONLY ENTER AREA CODE</span>
								<div class=\"clear clearfix\"></div>
							</td>
							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\" value=\"$adr4\"></td>
							<td>Longitude <br /> (Please enter as per google format: 30.37676257)</td>
							<td><input type=\"text\" name=\"longitude\" maxlength=\"15\" value=\"$longitude\"></td>
						</tr>
						<tr>
							<td>Comments:</td>
							<td><input type=\"text\" name=\"comments\" value=\"$comments\"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
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
								Select <input type='checkbox' name='cars' value='1' ".$carsChecked." /> Cars &nbsp;&nbsp;
								<input type='checkbox' name='hcv' value='1' ".$hcvChecked." /> HCV &nbsp;&nbsp;
								<input type='checkbox' name='roll_back' value='1' ".$rollBackChecked." /> Roll Back
							</td>

						</tr>
						
						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\" value=\"$vatno\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Vehicle Registration Number:</td>

							<td><input type=\"text\" name=\"vehicle_registration_no\" maxlength=\"20\" value=\"$vehicle_registration_no\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td valign=\"top\">Select Areas for this Towing Operator:</td>

							<td>";

							$qryareas = "select * from areas order by `areaname`";

									$qryareasresults = mysql_query($qryareas, $db);

									$theareas = "";
									

									while ($arearow = mysql_fetch_array($qryareasresults))

									{

										$areaname = stripslashes($arearow["areaname"]);

										$id = $arearow["id"];

										$isChecked = ( in_array($id, $savedAreas) ) ? 'checked="checked"' : '';

										$theareas .= "<input type=\"checkbox\" name=\"towingoperator_areas[]\" value=\"$id\" ".$isChecked."> $areaname <br />";

									}

									$theareas = substr($theareas, 0, -6);

									echo "$theareas</td>

									<td colspan='2'>&nbsp;</td>

				</tr>

				<tr>

					<td>Logo:</td><td colspan=\"3\">";

						if (file_exists("images/towingoperatorss/$towingoperatorid.jpg")) {

							echo "<img src=\"images/towingoperatorss/$towingoperatorid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>
							<input type=\"file\" name=\"uploadfile\">";
						}
						else {
							echo "<input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)";
						}

				echo "</td>

				</tr>

				<tr>

					<td>Driver Photo:</td><td colspan=\"3\">";

						if (file_exists("images/photodriver/$towingoperatorid.jpg")) {

							echo "<img src=\"images/photodriver/$towingoperatorid.jpg\" width='150'> <br />
							<input type=\"file\" name=\"photodriver\">";
						}
						else {
							echo "<input type=\"file\" name=\"photodriver\"> (note: images MUST be 100 x 100 pixels)";
						}

				echo "</td>
				</tr>

				<tr>
					<td>Towing Truck Photo:</td><td colspan=\"3\">";
						if (file_exists("images/photo_towing_truck/$towingoperatorid.jpg")) {
							echo "<img src=\"images/photo_towing_truck/$towingoperatorid.jpg\" width='150'> <br />
							<input type=\"file\" name=\"photo_towing_truck\" />";
						}
						else {
							echo "<input type=\"file\" name=\"photo_towing_truck\" /> (note: images MUST be 100 x 100 pixels)";
						}

					echo "</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"towingoperatorsid\" value=\"$towingoperatorid\">

			  </form>";
	}
	
	function TowingOperatorEdited($towingoperatorsid, $name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password = '') {

		global $db;


		$bankdetails = addslashes($_REQUEST["bankdetails"]);

		$email2 = addslashes($_REQUEST["email2"]);

		$adr1 = addslashes($_REQUEST["adr1"]);

		$adr2 = addslashes($_REQUEST["adr2"]);

		$adr3 = addslashes($_REQUEST["adr3"]);

		$adr4 = addslashes($_REQUEST["adr4"]);

		$latitude = addslashes($_REQUEST["latitude"]);

		$longitude = addslashes($_REQUEST["longitude"]);

		$cars = addslashes($_REQUEST["cars"]);

		$hcv = addslashes($_REQUEST["hcv"]);

		$roll_back = addslashes($_REQUEST["roll_back"]);

		
		$vatno = $_REQUEST["vatno"];

		$vehicle_registration_no = $_REQUEST['vehicle_registration_no'];

		

		$qryupdate = "update towingoperators set `name` = '$name',

										   `contactname` = '$contactname', 

										   `telno` = '$telno',

										   `faxno` = '$faxno',

										   `cellno` = '$cellno',

										   `email` = '$email',
										   `email2` = '$email2',

										   `adr1` = '$adr1',

										   `adr2` = '$adr2',

										   `adr3` = '$adr3',

										   `adr4` = '$adr4',

										   `latitude` = '$latitude',

										   `longitude` = '$longitude',

										   `comments` = '$comments',

										   `password` = '$password',

										   `bankdetails` = '$bankdetails',

										   `vehicle_registration_no` = '$vehicle_registration_no',
										   
										   `vatno` = '$vatno' where `id` = $towingoperatorsid";

		$qryupdateresults = mysql_query($qryupdate, $db);

		

		$qrydeleteareas = "delete from towingoperator_area where towingoperatorid = $towingoperatorsid";

		$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

		$qrydeletemakes = "delete from towingoperator_vehiclemake where towingoperatorid = $towingoperatorsid";

		$qrydeletemakesresults = mysql_query($qrydeletemakes, $db);



		$towingoperator_areas = $_REQUEST['towingoperator_areas'];

		if ( !empty($towingoperator_areas) ) {
			foreach ($towingoperator_areas as $areaId) {
				mysql_query("insert into towingoperator_area (towingoperatorid, areaid) values ($towingoperatorsid, $areaId)", $db);
			}
		}

		$i = $_REQUEST["uploadnewfile"];

		if ($_FILES["uploadfile"]["error"] === UPLOAD_ERR_OK) 	{
			if (!file_exists("images/towingoperatorss")) {
				mkdir("images/towingoperatorss", 0777);
			}

			if (file_exists("images/towingoperatorss/$towingoperatorsid.jpg"))
			{
				unlink("images/towingoperatorss/$towingoperatorsid.jpg");
			}	

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "images/towingoperatorss/$towingoperatorsid.jpg");

		}



		// Photo driver

		if ($_FILES["photodriver"]["error"] === UPLOAD_ERR_OK) 	{

			if (!file_exists("images/photodriver")) {
				mkdir("images/photodriver", 0777);
			}
			
			if (file_exists("images/photodriver/$towingoperatorsid.jpg")) {
				unlink("images/photodriver/$towingoperatorsid.jpg");
			}

			move_uploaded_file ($_FILES['photodriver'] ['tmp_name'], "images/photodriver/$towingoperatorsid.jpg");

		}

		// Photo towing truck

		if ($_FILES["photo_towing_truck"]["error"] === UPLOAD_ERR_OK) 	{
			if (!file_exists("images/photo_towing_truck")) {
				mkdir("images/photo_towing_truck", 0777);
			}
			
			if (file_exists("images/photo_towing_truck/$towingoperatorsid.jpg")) {
				unlink("images/photo_towing_truck/$towingoperatorsid.jpg");
			}

			move_uploaded_file ($_FILES['photo_towing_truck'] ['tmp_name'], "images/photo_towing_truck/$towingoperatorsid.jpg");
		}



		echo '<p style="padding:10px 20px;background:#d7ffce;">Thank you. Your information has been saved.</p>';

	}

?>