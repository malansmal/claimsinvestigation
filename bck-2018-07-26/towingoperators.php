<?php

	function TowingOperators($request_data){

		$from = $request_data['from'];

		require('connection.php');

		$name = isset($request_data['name']) ? trim($request_data['name']) : '';

		$email = isset($request_data['email']) ? trim($request_data['email']) : '';
		
		$subQry = '';

		if ( !empty($name)  ) {
			$subQry .= empty($subQry) ? " WHERE " : " AND ";
			$subQry .= " name like '%" . $name . "%' ";
		}

		if ( !empty($email)  ) {
			$subQry .= empty($subQry) ? " WHERE " : " AND ";
			$subQry .= " email like '" . $email . "%' ";
		}

		echo "<p><a href=\"\">Administrate Areas</a></p>";

		echo "
			<form action=\"loggedinaction.php\" method=\"get\" name=\"searchform\">
					<input type=\"hidden\" name=\"action\" value=\"towingoperators\" />
					<input type=\"hidden\" name=\"from\" value=\"1\" />

					<strong>Search for Towing Operators:</strong><br>

					Name: <input type=\"text\" name=\"name\" value='". $name. "' /> 

					Email: <input type=\"text\" name=\"email\" value='". $email. "' />

					<input type=\"submit\" value=\"Search\">


					<br><br>

			</form>
		";

		

		if ($from == "")

		{

			$from = 1;

		}

		

			//display first 30

		if ($from < 1)

		{

			$qry = "SELECT * FROM towingoperators $subQry LIMIT 0 , 30";

		}	//end if

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

			

			$qry = "SELECT * FROM towingoperators $subQry LIMIT $frm , 30";

		}	//end else

		

		$qrycounttowingoperators = "select * from towingoperators $subQry ";

		$qrycount = mysql_query($qrycounttowingoperators, $db);

		

		$qrytowingoperators = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		

		if ($count == 0)

		{

			echo "<p>There are no Towing Operators in the database. Click <a href=\"loggedinaction.php?action=newtowingoperator\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Towing Operator\" title=\"Add new Towing Operator\"></a> to add one.</p>";

		}

		else

		{

			

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=towingoperators&amp;from=1&amp;name=$name&amp;email=$email\">Page 1</a> || ";

			

			//echo "pages that will be needed is $count today";

			

			if ($pagesneeded > 1)	//build next page links here

			{

				for ($i = 1; $i < $pagesneeded; $i++)

				{

					//echo "i is $i<br>";

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=towingoperators&amp;from=" . $fromrecord . "&amp;name=$name&amp;email=$email \">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			

			$pageslinks = substr($pageslinks, 0, -4);

			

			echo "<div>

				  <table class=\"table table-striped\">

						  <tr>

							  <td><strong>Name</strong></td>

							  <td><strong>Contact Name</strong></td>

							  <td><strong>Telephone</strong></td>

							  <td><strong>Fax</strong></td>

							  <td><strong>Cell</strong></td>

							  <td><strong>Email</strong></td>

							  <td><strong>Area/s</strong></td>

							  <td><strong>Bank Details</strong></td>

							  <td><strong>Logo</strong></td>

							  <td colspan=\"3\" align=\"center\"><strong>Actions</strong></td>

						  </tr>";

						  

			while ($row = mysql_fetch_array($qrytowingoperators)) 

			{

				// give a name to the fields

				$id = $row['id'];

				$name = stripslashes($row['name']);

				$contactname = stripslashes($row['contactname']);

				$telno = stripslashes($row['telno']);

				$faxno = stripslashes($row['faxno']);

				$cellno = stripslashes($row['cellno']);

				$email = $row['email'];

				$bankdetails = stripslashes($row["bankdetails"]);

				$address = stripslashes($row["adr1"]) . ", " . stripslashes($row["adr2"]) . ", " . stripslashes($row["adr3"]) . ", " . stripslashes($row["4"]);


				echo "<tr>

						  <td valign=\"top\">$name</td>

						  <td valign=\"top\">$contactname</td>

						  <td valign=\"top\">$telno</td>

						  <td valign=\"top\">$faxno</td>

						  <td valign=\"top\">$cellno</td>

						  <td valign=\"top\">$email</td>

						  <td valign=\"top\">$comments</td>

						  <td valign=\"top\">";

						  $qryareas = "SELECT areaname FROM towingoperator_area 

								join areas

								  on towingoperator_area.areaid = areas.id

								join towingoperators

								on towingoperators.id = towingoperator_area.towingoperatorid

								and towingoperators.id = $id";

								$qryareasresults = mysql_query($qryareas, $db);								

								$areas = "";

								while ($arearow = mysql_fetch_array($qryareasresults))
								{

									$areaname = $arearow["areaname"];					

									$areas .= $areaname . "; ";					

								}

								if (strlen($areas) > 2)
								{

									$areas = substr($areas, 0, -2);

								}

								echo "$areas</td>";
						  
						  
						  echo "<td valign=\"top\">$bankdetails</td>

				";


				if (file_exists("../images/towingoperatorss/$id.jpg"))

				{

					echo "<td valign=\"top\"><img src=\"../images/towingoperatorss/$id.jpg\" style='width:80px;height:80px;'></td>";

				}

				else

				{

					echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

				}

				echo "

						  <td align=\"center\"><a href=\"loggedinaction.php?action=edittowingoperator&amp;towingoperatorid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Towing Operator\" border=\"0\" title=\"Edit this Towing Operator\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletetowingoperator&amp;towingoperatorid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Towing Operator\" border=\"0\" title=\"Delete this Towing Operator\"></td>

					  </tr>";

				

			}	//end while loop			

			

			echo "<tr>

					  <td colspan=\"11\">&nbsp;</td>

					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newtowingoperator\"><img src=\"../images/admin/add.gif\" alt=\"Add new Towing Operator\" border=\"0\" title=\"Add new Towing Operator\"></a></td>

				  </tr>

			</table><br>$pageslinks<br>

				";

		}

	}

	

	function NewTowingOperator()

	{

		require('connection.php');

		

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewtowingoperator\" enctype=\"multipart/form-data\" name=\"theform\">

				  <p>Enter the new Towing Operator details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Towing Operator's Name:</td>

							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Contact Name:</td>

							<td><input type=\"text\" name=\"contactname\" maxlength=\"50\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Telephone Number:</td>

							<td><input type=\"text\" name=\"telno\" maxlength=\"20\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Fax Number:</td>

							<td><input type=\"text\" name=\"faxno\" maxlength=\"20\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Cell Number:</td>

							<td><input type=\"text\" name=\"cellno\" maxlength=\"20\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Email Address:</td>

							<td><input type=\"text\" name=\"email\" maxlength=\"60\"></td>

							<td>Email Address 2 : </td>
							<td><input type=\"text\" name=\"email2\" maxlength=\"60\"></td>

						</tr>

						<tr>

							<td>
							
								Address: 
								<span class=\"pull-right\">Street Name:</span>
								<div class=\"clear clearfix\"></div>
							</td>

							<td><input type=\"text\" name=\"adr1\" maxlength=\"50\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<td>
								<span class=\"pull-right\">Suburb:</span>
								<div class=\"clear clearfix\"></div>
							</td>
							
							<td><input type=\"text\" name=\"adr2\" maxlength=\"50\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<td>
								<span class=\"pull-right\">Province:</span>
								<div class=\"clear clearfix\"></div>
							</td>

							<td><input type=\"text\" name=\"adr3\" maxlength=\"50\"></td>

							<td>Latitude</td>
							<td><input type=\"text\" name=\"latitude\" maxlength=\"15\"></td>

						</tr>

						<tr>
							<td>
								<span class=\"pull-right\">Code:</span>
								<div class=\"clear clearfix\"></div>
							</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\"></td>

							<td>Longitude</td>
							<td><input type=\"text\" name=\"longitude\" maxlength=\"15\"></td>

						</tr>

						<tr>

							<td>Comments:</td>

							<td><input type=\"text\" name=\"comments\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Password:</td>

							<td><input type=\"text\" name=\"password\" maxlength=\"20\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Bank Details:</td>

							<td><input type=\"text\" name=\"bankdetails\" maxlength=\"255\"></td>

							<td colspan=\"2\">
								Select <input type='checkbox' name='cars' value='1' /> Cars &nbsp;&nbsp;
								<input type='checkbox' name='hcv' value='1' /> HCV &nbsp;&nbsp;
								<input type='checkbox' name='roll_back' value='1' /> Roll Back
							</td>

						</tr>
						
						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td>Vehicle Registration Number:</td>

							<td><input type=\"text\" name=\"vehicle_registration_no\" maxlength=\"20\"></td>

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

										$theareas .= "<input type=\"checkbox\" name=\"towingoperator_areas[]\" value=\"$id\"> $areaname <br />";

									}

									$theareas = substr($theareas, 0, -6);

									echo "$theareas</td>

									<td colspan='2'>&nbsp;</td>

						</tr>

						<tr>

							<td>Logo:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

						<tr>

							<td>Driver Photo:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"photodriver\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

						<tr>

							<td>Towing Truck Photo:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"photo_towing_truck\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	

	function AddNewTowingOperator($name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password) {

		require('connection.php');

		$bankdetails = addslashes($_REQUEST["bankdetails"]);
		
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

		$vehicle_registration_no = $_POST['vehicle_registration_no'];

		$qryinsert = "insert into towingoperators ( `name`, `contactname`, `telno`, `faxno`, `cellno`, `email`, `adr1`, `adr2`, `adr3`, `adr4`,  `comments`, `password`, `bankdetails`, `vatno`, `email2`, `latitude`, `longitude`, `cars`, `hcv`, `roll_back`, `vehicle_registration_no`)

								values ( '$name', '$contactname', '$telno', '$faxno', '$cellno', '$email', '$adr1', '$adr2', '$adr3', '$adr4',  '$comments', '$password', '$bankdetails', '$vatno', '$email2', '$latitude', '$longitude', '$cars', '$hcv', '$roll_back', '$vehicle_registration_no')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		//get the new id

		$newid = mysql_insert_id();
		
		$towingoperator_areas = $_REQUEST['towingoperator_areas'];

		//print '<pre>'; print_r($towingoperator_areas);die;

		if ( !empty($towingoperator_areas) ) {
			foreach ($towingoperator_areas as $areaId) {
				mysql_query("insert into towingoperator_area (towingoperatorid, areaid) values ($newid, $areaId)", $db);
			}
		}
		
		// Photo towing operator
		if (!file_exists("../images/towingoperatorss")) {
			mkdir("../images/towingoperatorss", 0777);
		}
		
		if (file_exists("../images/towingoperatorss/$newid.jpg")) {
			unlink("../images/towingoperatorss/$newid.jpg");
		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/towingoperatorss/$newid.jpg");
		

		// Photo driver

		if (!file_exists("../images/photodriver")) {
			mkdir("../images/photodriver", 0777);
		}
		
		if (file_exists("../images/photodriver/$newid.jpg")) {
			unlink("../images/photodriver/$newid.jpg");
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

		

		echo "<p>The Towing Operator has been saved successfully.</p>";

		

		TowingOperators(1);

		

	}

	

	function EditTowingOperator($towingoperatorid)

	{

		require('connection.php');

						

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

		echo "<form method=\"post\" action=\"loggedinaction.php?action=towingoperatoredited\" enctype=\"multipart/form-data\" name=\"theform\">

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

							<td>Latitude</td>
							<td><input type=\"text\" name=\"latitude\" maxlength=\"15\" value=\"$latitude\"></td>

						</tr>

						<tr>

							<td>
								<span class=\"pull-right\">Code:</span>
								<div class=\"clear clearfix\"></div>
							</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\" value=\"$adr4\"></td>

							<td>Longitude</td>
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

						if (file_exists("../images/towingoperatorss/$towingoperatorid.jpg")) {

							echo "<img src=\"../images/towingoperatorss/$towingoperatorid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>
							<input type=\"file\" name=\"uploadfile\">";
						}
						else {
							echo "<input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)";
						}

				echo "</td>

				</tr>

				<tr>

					<td>Driver Photo:</td><td colspan=\"3\">";

						if (file_exists("../images/photodriver/$towingoperatorid.jpg")) {

							echo "<img src=\"../images/photodriver/$towingoperatorid.jpg\" width='150'> <br />
							<input type=\"file\" name=\"photodriver\">";
						}
						else {
							echo "<input type=\"file\" name=\"photodriver\"> (note: images MUST be 100 x 100 pixels)";
						}

				echo "</td>

				</tr>


				<tr>

					<td>Towing Truck Photo:</td><td colspan=\"3\">";

						if (file_exists("../images/photo_towing_truck/$towingoperatorid.jpg")) {

							echo "<img src=\"../images/photo_towing_truck/$towingoperatorid.jpg\" width='150'> <br />
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

	

	function TowingOperatorEdited($towingoperatorsid, $name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password)

	{

		require('connection.php');

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
			if (!file_exists("../images/towingoperatorss")) {
				mkdir("../images/towingoperatorss", 0777);
			}

			if (file_exists("../images/towingoperatorss/$towingoperatorsid.jpg"))
			{
				unlink("../images/towingoperatorss/$towingoperatorsid.jpg");
			}	

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/towingoperatorss/$towingoperatorsid.jpg");

		}



		// Photo driver

		if ($_FILES["photodriver"]["error"] === UPLOAD_ERR_OK) 	{

			if (!file_exists("../images/photodriver")) {
				mkdir("../images/photodriver", 0777);
			}
			
			if (file_exists("../images/photodriver/$towingoperatorsid.jpg")) {
				unlink("../images/photodriver/$towingoperatorsid.jpg");
			}

			move_uploaded_file ($_FILES['photodriver'] ['tmp_name'], "../images/photodriver/$towingoperatorsid.jpg");

		}

		// Photo towing truck

		if ($_FILES["photo_towing_truck"]["error"] === UPLOAD_ERR_OK) 	{
			if (!file_exists("../images/photo_towing_truck")) {
				mkdir("../images/photo_towing_truck", 0777);
			}
			
			if (file_exists("../images/photo_towing_truck/$towingoperatorsid.jpg")) {
				unlink("../images/photo_towing_truck/$towingoperatorsid.jpg");
			}

			move_uploaded_file ($_FILES['photo_towing_truck'] ['tmp_name'], "../images/photo_towing_truck/$towingoperatorsid.jpg");
		}
		

		echo "<p>The Towing Operator has been edited successfully.</p>";

		TowingOperators(1);

	}

	

	function ConfirmDeleteTowingOperator($towingoperatorid, $key)

	{

		require('connection.php');


		$qry = "select * from towingoperators where `id` = $towingoperatorid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$name = $row["name"];

		

		$qryinsert = "insert into `key` (`id`, `key`, `action`) values (null, '$key', 'deletetowingoperators')";

		$qryinsertresults = mysql_query($qryinsert, $db);


		echo "<p>Are you sure you want to delete the Towing Operator <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deletetowingoperators&amp;towingoperatorsid=$towingoperatorid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						

		

	}

	

	function DeleteTowingOperator($towingoperatorsid, $key)

	{

		require('connection.php');

		$qry = "select * from `key` where `action` = 'deletetowingoperators' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		$keyrow = mysql_fetch_array($qryresults);

		

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		

		if ($count == 1)

		{

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydelete = "delete from towingoperators where `id` = $towingoperatorsid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydeleteareas = "delete from towingoperator_area where towingoperatorid = $towingoperatorsid";

			$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

			$qrydeletemakes = "delete from towingoperator_vehiclemake where towingoperatorid = $towingoperatorsid";

			$qrydeletemakesresults = mysql_query($qrydeletemakes, $db);

			

			if (file_exists("../images/towingoperatorss/$towingoperatorsid.jpg"))

			{

				unlink("../images/towingoperatorss/$towingoperatorsid.jpg");

			}

			

			echo "<p>The Towing Operator has been deleted successfully.</p>";

			

			TowingOperators(1);

		}

		else

		{

			echo "<p>It wont work if you enter the url just like that to delete a towingoperators...</p>";

			

			TowingOperators(1);

		}

	}

?>