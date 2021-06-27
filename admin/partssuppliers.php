<?php

	function PartSuppliers($request_data){

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
					<input type=\"hidden\" name=\"action\" value=\"partssuppliers\" />
					<input type=\"hidden\" name=\"from\" value=\"1\" />

					<strong>Search for partsuppliers:</strong><br>

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

			$qry = "SELECT * FROM partssuppliers $subQry LIMIT 0 , 30";

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

			

			$qry = "SELECT * FROM partssuppliers $subQry LIMIT $frm , 30";

		}	//end else

		

		$qrycountpartssuppliers = "select * from partssuppliers $subQry ";

		$qrycount = mysql_query($qrycountpartssuppliers, $db);

		

		$qrypartssuppliers = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		

		if ($count == 0)

		{

			echo "<p>There are no Part Suppliers in the database. Click <a href=\"loggedinaction.php?action=newpartssupplier\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Part Supplier\" title=\"Add new Part Supplier\"></a> to add one.</p>";

		}

		else

		{

			

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=partssuppliers&amp;from=1&amp;name=$name&amp;email=$email\">Page 1</a> || ";

			

			//echo "pages that will be needed is $count today";

			

			if ($pagesneeded > 1)	//build next page links here

			{

				for ($i = 1; $i < $pagesneeded; $i++)

				{

					//echo "i is $i<br>";

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=partssuppliers&amp;from=" . $fromrecord . "&amp;name=$name&amp;email=$email \">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			

			$pageslinks = substr($pageslinks, 0, -4);

			

			echo "<div>

				<p style='margin-bottom:20px;visibility:hidden;' id='top-actions'><a href='#' id='btn-send-to-all' class='btn btn-primary'><i class='glyphicon glyphicon-envelope'></i> Send email to selected parts suppliers </a></p>

				  <table class=\"table table-striped\">

						  <tr>
							  <td><input type='checkbox' id='checkAll' title=\"Select all\" /></td>

							  <td><strong>Name</strong></td>

							  <td><strong>Contact Name</strong></td>

							  <td><strong>Telephone</strong></td>

							  <td><strong>Fax</strong></td>

							  <td><strong>Cell</strong></td>

							  <td><strong>Email</strong></td>

							  <td><strong>Comments</strong></td>

							  <td><strong>Area/s</strong></td>
							  <td><strong>Vehicle Makes</strong></td>

							  <td><strong>Bank Details</strong></td>

							  <td><strong>Logo</strong></td>

							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>

						  </tr>";

						  

			while ($row = mysql_fetch_array($qrypartssuppliers)) 

			{

				// give a name to the fields

				$id = $row['id'];

				$name = stripslashes($row['name']);

				$contactname = stripslashes($row['contactname']);

				$telno = stripslashes($row['telno']);

				$faxno = stripslashes($row['faxno']);

				$cellno = stripslashes($row['cellno']);

				$email = $row['email'];

				$comments = stripslashes($row['comments']);

				$bankdetails = stripslashes($row["bankdetails"]);

				$address = stripslashes($row["adr1"]) . ", " . stripslashes($row["adr2"]) . ", " . stripslashes($row["adr3"]) . ", " . stripslashes($row["4"]);


				echo "<tr>
						  <td><input type=\"checkbox\" class=\"row-checkbox\" name=\"partsupplier-checked[]\" value=\"$id\" /></td>

						  <td valign=\"top\">$name</td>

						  <td valign=\"top\">$contactname</td>

						  <td valign=\"top\">$telno</td>

						  <td valign=\"top\">$faxno</td>

						  <td valign=\"top\">$cellno</td>

						  <td valign=\"top\">$email</td>

						  <td valign=\"top\">$comments</td>

						  <td valign=\"top\">";

						  $qryareas = "SELECT areaname FROM partsupplier_area 

								join areas

								  on partsupplier_area.areaid = areas.id

								join partssuppliers

								on partssuppliers.id = partsupplier_area.partssupplierid

								and partssuppliers.id = $id";

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
						  
						  
						  echo "

						  <td valign=\"top\">";

				

				$qryvehiclemakes = "SELECT vehiclemake FROM `partssupplier_vehiclemake` 

								join vehiclemake

								  on partssupplier_vehiclemake.vehiclemakeid = vehiclemake.id

								join partssuppliers

								on partssuppliers.id = partssupplier_vehiclemake.partssupplierid

								and partssuppliers.id = $id";

				$qryvehiclemakesresults = mysql_query($qryvehiclemakes, $db);

				

				$vehiclemakes = "";

				

				while ($vehiclemakerow = mysql_fetch_array($qryvehiclemakesresults))				

				{					

					$vehiclemake = $vehiclemakerow["vehiclemake"];					

					$vehiclemakes .= $vehiclemake . "; ";					

				}

				

				if (strlen($vehiclemakes) > 2)

				{

					$vehiclemakes = substr($vehiclemakes, 0, -2);

				}

				

				echo "$vehiclemakes</td>

						<td valign=\"top\">$bankdetails</td>

				";


				if (file_exists("../images/partsuppliers/$id.jpg"))

				{

					echo "<td valign=\"top\"><img src=\"../images/partsuppliers/$id.jpg\" style='width:80px;height:80px;'></td>";

				}

				else

				{

					echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

				}

				echo "

						  <td align=\"center\"><a href=\"loggedinaction.php?action=editpartssupplier&amp;partssupplierid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Parts Supplier\" border=\"0\" title=\"Edit this Parts Supplier\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletepartssupplier&amp;partssupplierid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Parts Supplier\" border=\"0\" title=\"Delete this Parts Supplier\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=send-profile-link-to-partsuppliers&amp;id=$id\" onClick=\"return confirm('Are you sure, you want to send profile updation link to this Parts Supplier?');\"><img src=\"../images/email-send.png\" style=\"height:16px;\" alt=\"Send Profile Update link to thisParts Supplier\" border=\"0\" title=\"Send Profile Update link to this Parts Supplier\"></td>

					  </tr>";

				

			}	//end while loop			

			

			echo "<tr>

					  <td colspan=\"11\">&nbsp;</td>

					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newpartssupplier\"><img src=\"../images/admin/add.gif\" alt=\"Add new Parts Supplier\" border=\"0\" title=\"Add new Parts Supplier\"></a></td>

				  </tr>

			</table><br>$pageslinks<br>



			<!-- Modal -->
		  <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
			<div class=\"modal-dialog\">
			
			  <!-- Modal content-->
			  <div class=\"modal-content\">
				<div class=\"modal-header\">
				  <h4 class=\"modal-title\">Sending Emails to Panelbeaters</h4>
				</div>
				<div class=\"modal-body\">
				  <p>Please wait while the system is sending emails to the Panelbeaters. This may take upto a couple of minutes.</p>
				</div>
			  </div>
			  
			</div>
		  </div>



			<script type='text/javascript'>
				$(document).ready(function() {
					$('#checkAll').change(function() {
						if ( $(this).is(':checked') === true )  {
							$('.row-checkbox').prop('checked', true);
							$(this).attr('title', 'Unselect all');
						} else {
							$('.row-checkbox').prop('checked', false);
							$(this).attr('title', 'Select all');
						}
						
						if ($('.row-checkbox:checked').length > 0) {
							$('#top-actions').css('visibility', 'visible');
						}
						else {
							$('#top-actions').css('visibility', 'hidden');
						}

					});
				});

				$('.row-checkbox').change(function() {
					
					if ($('.row-checkbox:checked').length > 0) {
						$('#top-actions').css('visibility', 'visible');
					}
					else {
						$('#top-actions').css('visibility', 'hidden');
					}

				});

				$('#btn-send-to-all').click(function(event){
					event.preventDefault();

					var agree = confirm('Are you sure, you want links to all the selected parts suppliers?');

					if (!agree) {
						return false;
					}

					var psIds = [];
					$('.row-checkbox:checked').each(function(){
						psIds.push($(this).val());
					});

					$('#myModal').modal('show');

					var url = 'loggedinaction.php?action=send-profile-link-to-partsuppliers&id=' + psIds;
					
					location.href=url;

					return false;
				});

				
			</script>



				";

		}

	}

	

	function NewPartsSupplier()

	{

		require('connection.php');

		

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewpartsupplier\" enctype=\"multipart/form-data\" name=\"theform\">

				  <p>Enter the new Part Supplier details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Part Supplier's Name:</td>

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
								Select <input type='checkbox' name='used' value='1' /> Used &nbsp;&nbsp;
								<input type='checkbox' name='alternate' value='1' /> Alternate &nbsp;&nbsp;
								<input type='checkbox' name='new' value='1' /> New
							</td>

						</tr>
						
						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\"></td>

							<td>&nbsp;</td>
							<td>&nbsp;</td>

						</tr>

						<tr>

							<td valign=\"top\">Select Areas for this Part Supplier:
								<script type='text/javascript'>
									$(document).ready(function() {
										$('.select-all-suppliers').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"supplier_areas[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"supplier_areas[]\"]').prop('checked', false);
											}
										});

										$('.select-all-makes').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"vehiclemakes_list[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"vehiclemakes_list[]\"]').prop('checked', false);
											}
										});
									});
								</script>
							
							</td>

							<td><input type='checkbox' class='select-all-suppliers' /> Select All <br />";

									$qryareas = "select * from areas order by `areaname`";

									$qryareasresults = mysql_query($qryareas, $db);

									$theareas = "";

									

									while ($arearow = mysql_fetch_array($qryareasresults))

									{

										$areaname = stripslashes($arearow["areaname"]);

										$id = $arearow["id"];

										$theareas .= "<input type=\"checkbox\" name=\"supplier_areas[]\" value=\"$id\"> $areaname <br />";

									}

									$theareas = substr($theareas, 0, -6);

									echo "$theareas</td>

									<td > Select Makes for Parts Supplier: </td><td><input type='checkbox' class='select-all-makes' /> Select All <br />";

									$makesQryResult = mysql_query('SELECT * FROM vehiclemake ORDER BY vehiclemake ASC');

									while($row = mysql_fetch_array($makesQryResult)) {
										echo '<label style="font-weight:normal;"><input type="checkbox" name="vehiclemakes_list[]" value="'.$row["id"].'" /> &nbsp; ' . $row["vehiclemake"] . '</label><br />';
									}

									echo "</td>

						</tr>

						<tr>

							<td>Logo:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	

	function AddNewPartSupplier($name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password)

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

		$used = isset($_REQUEST['used']) ? 1 : 0;
		$alternate = isset($_REQUEST['alternate']) ? 1 : 0;
		$new = isset($_REQUEST['new']) ? 1 : 0;

		$vehiclemakes_list = $_REQUEST['vehiclemakes_list'];
		
		$vatno = $_REQUEST["vatno"];

		$qryinsert = "insert into partssuppliers (`name`, `contactname`, `telno`, `faxno`, `cellno`, `email`, `adr1`, `adr2`, `adr3`, `adr4`,  `comments`, `password`, `bankdetails`, `vatno`, `email2`, `latitude`, `longitude`, `used`, `alternate`, `new`)

								values ('$name', '$contactname', '$telno', '$faxno', '$cellno', '$email', '$adr1', '$adr2', '$adr3', '$adr4',  '$comments', '$password', '$bankdetails', '$vatno', '$email2', '$latitude', '$longitude', '$used', '$alternate', '$new')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		//get the new id

		$newid = mysql_insert_id();
		
		$supplier_areas = $_REQUEST['supplier_areas'];

		//print '<pre>'; print_r($supplier_areas);die;

		if ( !empty($supplier_areas) ) {
			foreach ($supplier_areas as $areaId) {
				mysql_query("insert into partsupplier_area (partssupplierid, areaid) values ($newid, $areaId)", $db);
			}
		}
		

		if ( !empty($vehiclemakes_list) ) {

			foreach ($vehiclemakes_list as $vehicleMakeId) {
				mysql_query("insert into partssupplier_vehiclemake (partssupplierid, vehiclemakeid) values ($newid, $vehicleMakeId)", $db);
			}

		}

		

		if (file_exists("../images/partsuppliers/$newid.jpg"))

		{

			unlink("../images/partsuppliers/$newid.jpg");

		}



		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/partsuppliers/$newid.jpg");

		

		echo "<p>The Part Supplier has been saved successfully.</p>";

		

		PartSuppliers(1);

		

	}

	

	function EditPartSupplier($partssupplierid)

	{

		require('connection.php');

						

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


		echo "<form method=\"post\" action=\"loggedinaction.php?action=partssupplieredited\" enctype=\"multipart/form-data\" name=\"theform\">

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

							<td valign=\"top\">Select Areas for this Part Supplier:
							<script type='text/javascript'>
									$(document).ready(function() {
										$('.select-all-suppliers').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"supplier_areas[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"supplier_areas[]\"]').prop('checked', false);
											}
										});

										$('.select-all-makes').on('click', function() {
											if ($(this).is(':checked')) {
												$('input[name=\"vehiclemakes_list[]\"]').prop('checked', true);
											}
											else {
												$('input[name=\"vehiclemakes_list[]\"]').prop('checked', false);
											}
										});
									});
								</script>
							</td>

							<td><input type='checkbox' class='select-all-suppliers' /> Select All <br />";

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

									<td > Select Makes for Parts Supplier: </td><td><input type='checkbox' class='select-all-makes' /> Select All <br />";

									$makesQryResult = mysql_query('SELECT * FROM vehiclemake ORDER BY vehiclemake ASC');

									while($row = mysql_fetch_array($makesQryResult)) {

										$isChecked = ( in_array($row["id"], $savedMakes) ) ? 'checked="checked"' : '';


										echo '<label style="font-weight:normal;"><input type="checkbox" name="vehiclemakes_list[]" value="'.$row["id"].'" '.$isChecked.' /> &nbsp; ' . $row["vehiclemake"] . '</label><br />';
									}

									echo "</td>

				</tr>

				<tr>

							<td>Logo:</td><td colspan=\"3\">";

		

		if (file_exists("../images/partsuppliers/$partssupplierid.jpg"))

		{

			echo "<img src=\"../images/partsuppliers/$partssupplierid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

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

<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"partsupplierid\" value=\"$partssupplierid\">

			  </form>";

	}

	

	function PartSupplierEdited($partsupplierid, $name, $contactname, $telno, $faxno, $cellno, $email, $comments, $password)

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

		$used = (int) $_REQUEST["used"];

		$alternate = (int) $_REQUEST["alternate"];

		$new = (int) $_REQUEST["new"];

		$vehiclemakes_list = $_REQUEST['vehiclemakes_list'];
		
		$vatno = $_REQUEST["vatno"];

		$qryupdate = "update partssuppliers set `name` = '$name',

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
										   
										   `vatno` = '$vatno',
										   `used` = '$used',
										   `alternate` = '$alternate',
										   `new` = '$new'
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

			if (file_exists("../images/partsuppliers/$partsupplierid.jpg"))

			{

				unlink("../images/partsuppliers/$partsupplierid.jpg");

			}

	

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/partsuppliers/$partsupplierid.jpg");

		}

		

		echo "<p>The Part Supplier has been edited successfully.</p>";

		PartSuppliers(1);

	}

	

	function ConfirmDeletePartSupplier($partssupplierid, $key)

	{

		require('connection.php');


		$qry = "select * from partssuppliers where `id` = $partssupplierid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$name = $row["name"];

		

		$qryinsert = "insert into `key` (`key`, `action`) values ('$key', 'deletepartsupplier')";

		$qryinsertresults = mysql_query($qryinsert, $db);


		echo "<p>Are you sure you want to delete the Part Supplier <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deletepartsupplier&amp;partsupplierid=$partssupplierid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						

		

	}

	

	function DeletePartSupplier($partsupplierid, $key)

	{

		require('connection.php');

								

		$qry = "select * from `key` where `action` = 'deletepartsupplier' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		

		$keyrow = mysql_fetch_array($qryresults);

		

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		

		if ($count == 1)

		{

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydelete = "delete from partssuppliers where `id` = $partsupplierid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydeleteareas = "delete from partsupplier_area where partssupplierid = $partsupplierid";

			$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

			$qrydeletemakes = "delete from partssupplier_vehiclemake where partssupplierid = $partsupplierid";

			$qrydeletemakesresults = mysql_query($qrydeletemakes, $db);

			

			if (file_exists("../images/partsuppliers/$partsupplierid.jpg"))

			{

				unlink("../images/partsuppliers/$partsupplierid.jpg");

			}

			

			echo "<p>The Part Supplier has been deleted successfully.</p>";

			

			PartSuppliers(1);

		}

		else

		{

			echo "<p>It wont work if you enter the url just like that to delete a partsupplier...</p>";

			

			PartSuppliers(1);

		}

	}

	function SendProfileLinkToPartSupplier($id) {
		
		require('connection.php');

		$partsuppliersids = explode(',', $id);

		require_once "../vendor/autoload.php";

		$mail = new PHPMailer;

		foreach ($partsuppliersids as $psid) {
		
			$guid = generate_guid();

			$sql = " UPDATE partssuppliers SET profile_access_token = '" . $guid . "' WHERE id='" . $psid . "' AND ( profile_access_token='' OR profile_access_token IS NULL ) ";

			mysql_query($sql);

			$guidSQL = " SELECT `profile_access_token` FROM partssuppliers WHERE id='".$psid."' ";
			$guidResult =mysql_query($guidSQL);
			$guidInfo = mysql_fetch_object($guidResult);
			$guid = $guidInfo->profile_access_token;

			$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/update-partsupplier-profile.php?psid=' . $psid . '&token=' . $guid;

			$pbQry = " SELECT name, email FROM partssuppliers WHERE id='" . $psid . "' ";

			$res = mysql_query($pbQry);

			$psInfo = mysql_fetch_object($res);

			$mail->setFrom('info@panelshop.co.za', 'Panelshop.co.za');
			$mail->addAddress($psInfo->email, $psInfo->name);     // Add a recipient
			$mail->addReplyTo('info@panelshop.co.za', 'Panelshop.co.za');
			$mail->addBCC('info@panelshop.co.za');

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Please update your Part Supplier Profile for Insurance Purposes';

			$body = file_get_contents('../templates/partsupplier-update-profile-notification.html');

			$body = str_replace(['{{PARTSUPPLIER_NAME}}', '{{PROFILE_UPDATE_LINK}}'], [$psInfo->name, $profileLink], $body);
			
			$mail->Body    = $body;
			$mail->AltBody = strip_tags($body);

			$mail->send();

			$mail->clearAddresses();
			$mail->clearAttachments();
			
		}
		
		$_SESSION['success_message'] = 'Sent profile update link to Part Suppliers.';

		?>
			<script type="text/javascript">
				location.href = 'loggedinaction.php?action=partssuppliers&from=1';
			</script>

		<?php
		
	}

?>