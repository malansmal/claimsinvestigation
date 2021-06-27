<?php

	//echo "test";

	function Panelbeaters($from, $pbnamekeywords='', $pbownerkeywords='')
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
		//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM panelbeaters WHERE 1 ";
			
			if ( !empty($pbnamekeywords) ) {
				$qry .= " and `name` like '%" . $pbnamekeywords . "%' ";
			}
			if ( !empty($pbownerkeywords) ) {
				$qry .= "and `owner` like '%" . $pbownerkeywords . "%' ";
			}
			$qry .= "order by `name` LIMIT 0 , 30";
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

			$qry = "SELECT * FROM panelbeaters WHERE 1 ";
			
			if ( !empty($pbnamekeywords) ) {
				$qry .= " and `name` like '%" . $pbnamekeywords . "%' ";
			}
			if ( !empty($pbownerkeywords) ) {
				$qry .= "and `owner` like '%" . $pbownerkeywords . "%' ";
			}
			$qry .= "order by `name` LIMIT $frm, 30";

		}	//end else
		
		$qrycountpanelbeaters = "select * from panelbeaters WHERE 1 ";

		if ( !empty($pbnamekeywords) ) {
			$qrycountpanelbeaters .= " and `name` like '%" . $pbnamekeywords . "%' ";
		}
		if ( !empty($pbownerkeywords) ) {
			$qrycountpanelbeaters .= " and `owner` like '%" . $pbownerkeywords . "%' ";
		}

		$qrycount = mysql_query($qrycountpanelbeaters, $db);
		
		$qrypanelbeaters = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Panel Beaters in the database. Click <a href=\"loggedinaction.php?action=newpanelbeater\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Panel Beater\" title=\"Add new Panel Beater\"></a> to add one.</p>";
		}
		else
		{
			
			// finding panel beaters

			$qryallpanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

			$respanelbeaters = mysql_query($qryallpanelbeaters, $db);

			$panelbeatersArray = [];
			
			while($pbrow = mysql_fetch_array($respanelbeaters)) {
				$panelbeatersArray[] = '"' . $pbrow['name'] . '"';
			}

			$panelbeatersArray = implode(',', $panelbeatersArray);

			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=panelbeaters&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=panelbeaters&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
				}	//end for loop
			}	//end if
			
			$pageslinks = substr($pageslinks, 0, -4);
			
			echo "	
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

			<form action=\"loggedinaction.php?action=searchpanelbeaters\" method=\"post\" name=\"searchform\">
						<strong>Search for a Panelbeater:</strong><br>
						Panel Beater Name: <input type=\"text\" name=\"pbname\" id=\"panelbeatername\" value='".$pbnamekeywords."' /> 
						Owner: <input type=\"text\" name=\"pbowner\"  value='".$pbownerkeywords."' > 
						<input type=\"submit\" value=\"Search\">
						<input type=\"hidden\" name=\"from\" value=\"1\">
					<br><br>

					</form>

					<p style='margin-bottom:20px;visibility:hidden;' id='top-actions'><a href='#' id='btn-send-to-all' class='btn btn-primary'><i class='glyphicon glyphicon-envelope'></i> Send email to selected panelbeaters </a></p>
					<table class=\"table table-striped\">
						<tr>
							<td><input type='checkbox' id='checkAll' title=\"Select all\" /></td>
							<td><strong>Panel Beater</strong></td>
							<td><strong>Owner</strong></td>
							<td><strong>Costing Clerk</strong></td>
							<td><strong>Contact Person</strong></td>
							<td><strong>Address</strong></td>
							<td><strong>Contact No</strong></td>
							<td><strong>Fax No</strong></td>
							<td><strong>Email Address</strong></td>
							<td colspan=\"3\" align=\"center\"><strong>Actions</strong></td>
						</tr>";

			while ($row = mysql_fetch_array($qrypanelbeaters)) 
			{
				// give a name to the fields
				$pbid = $row['id'];
				$name = stripslashes($row['name']);
				$owner = stripslashes($row["owner"]);
				$costingclerk = stripslashes($row["costingclerk"]);
				$contactperson = stripslashes($row["contactperson"]);
				$adr1 = stripslashes($row["adr1"]);
				$adr2 = stripslashes($row["adr2"]);
				$adr3 = stripslashes($row["adr3"]);
				$adr4 = stripslashes($row["adr4"]);
				$contactno = stripslashes($row["contactno"]);
				$faxno = stripslashes($row["faxno"]);
				$email = stripslashes($row["email"]);
				
				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td><input type=\"checkbox\" class=\"row-checkbox\" name=\"panelbeater-checked[]\" value=\"$pbid\" /></td>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$owner</td>
						  <td valign=\"top\">$costingclerk</td>
						  <td valign=\"top\">$contactperson</td>
						  <td valign=\"top\">$adr1 <br> $adr2 <br> $adr3 <br> $adr4</td>
						  <td valign=\"top\">$contactno</td>
						  <td valign=\"top\">$faxno</td>
						  <td valign=\"top\">$email</td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editpanelbeater&amp;panelbeaterid=$pbid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Panel Beater\" border=\"0\" title=\"Edit this Panel Beater\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletepanelbeater&amp;panelbeaterid=$pbid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Panel Beater\" border=\"0\" title=\"Delete this Panel Beater\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=send-profile-link-to-panelbeater&amp;panelbeaterid=$pbid\" onClick=\"return confirm('Are you sure, you want to send profile updation link to this Panelbeater?');\"><img src=\"../images/email-send.png\" style=\"height:16px;\" alt=\"Send Profile Update link to this Panel Beater\" border=\"0\" title=\"Send Profile Update link to this Panel Beater\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"10\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newpanelbeater\"><img src=\"../images/admin/add.gif\" alt=\"Add new Panel Beater\" border=\"0\" title=\"Add new Panel Beater\"></a></td>
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

					var agree = confirm('Are you sure, you want links to all the selected panelbeaters?');

					if (!agree) {
						return false;
					}

					var pbIds = [];
					$('.row-checkbox:checked').each(function(){
						pbIds.push($(this).val());
					});

					$('#myModal').modal('show');

					var url = 'loggedinaction.php?action=send-profile-link-to-panelbeater&panelbeaterid=' + pbIds;
					
					location.href=url;

					return false;
				});

				
			</script>
				";
		}
	}

	function SearchPanelbeaters($pbname, $pbowner, $from)
	{
		require('connection.php');
				
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM panelbeaters 
										 where `name` like '%" . $pbname . "%' 
										   and `owner` like '%" . $pbowner . "%' 
										   order by `name` LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM panelbeaters 
										 where `name` like '%" . $pbname . "%' 
										   and `owner` like '%" . $pbowner . "%' 
										   order by `name` LIMIT $frm , 30";
		}	//end else
		
		$qrycountpanelbeaters = "SELECT * FROM panelbeaters 
										 where `name` like '%" . $pbname . "%' 
										   and `owner` like '%" . $pbowner . "%'";
		$qrycount = mysql_query($qrycountpanelbeaters, $db);
		
		$qrypanelbeaters = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Panel Beaters in the database with these search criteria. <a href=\"javascript.history.go(-1);\">Go Back to Panelbeaters</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<table>
								<tr><td>
						   <form style=\"display:inline\" action=\"loggedinaction.php?action=searchpanelbeaters\" method=\"post\"> <input type=\"submit\" value=\"Page 1\"> 
																									<input type=\"hidden\" name=\"pbname\" value=\"$pbname\">
																									<input type=\"hidden\" name=\"pbowner\" value=\"$pbowner\">
																									<input type=\"hidden\" name=\"from\" value=\"1\"></form>&nbsp;";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<form style=\"display:inline\" action=\"loggedinaction.php?action=searchpanelbeaters\" method=\"post\"> <input type=\"submit\" value=\"Page $pagenumber\"> 
																									<input type=\"hidden\" name=\"pbname\" value=\"$pbname\">
																									<input type=\"hidden\" name=\"pbowner\" value=\"$pbowner\">
																									<input type=\"hidden\" name=\"from\" value=\"$fromrecord\"></form>&nbsp;";
				}	//end for loop
			}	//end if
			
			
			// finding panel beaters

			$qryallpanelbeaters = "select `name` from panelbeaters WHERE ( TRIM(name) != '' AND name IS NOT NULL) ORDER BY name ASC";

			$respanelbeaters = mysql_query($qryallpanelbeaters, $db);

			$panelbeatersArray = [];
			
			while($pbrow = mysql_fetch_array($respanelbeaters)) {
				$panelbeatersArray[] = '"' . $pbrow['name'] . '"';
			}

			$panelbeatersArray = implode(',', $panelbeatersArray);

			
			echo "Search results for Panelbeater Name: <strong>$pbname</strong> and owner:: <strong>$pbowner</strong><br><br>	
			
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
			
			<form action=\"loggedinaction.php?action=searchpanelbeaters\" method=\"post\" name=\"searchform\">
						<strong>Search for a Panelbeater:</strong><br>
						Panel Beater Namer: <input type=\"text\" name=\"pbname\" id=\"panelbeatername\" value=\"$pbname\"> 
						Owner: <input type=\"text\" name=\"pbowner\"> <input type=\"submit\" value=\"Search\">
						<input type=\"hidden\" name=\"from\" value=\"1\">
					<br><br>
					
					</form>
				  <table cellpadding=\"2\" cellspacing=\"0\" border=\"1\">
						  <tr>
							  <td><strong>Panel Beater</strong></td>
							  <td><strong>Owner</strong></td>
							  <td><strong>Costing Clerk</strong></td>
							  <td><strong>Contact Person</strong></td>
							  <td><strong>Address</strong></td>
							  <td><strong>Contact No</strong></td>
							  <td><strong>Fax No</strong></td>
							  <td><strong>Email Address</strong></td>
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qrypanelbeaters)) 
			{
				// give a name to the fields
				$pbid = $row['id'];
				$name = stripslashes($row['name']);
				$owner = stripslashes($row["owner"]);
				$costingclerk = stripslashes($row["costingclerk"]);
				$contactperson = stripslashes($row["contactperson"]);
				$adr1 = stripslashes($row["adr1"]);
				$adr2 = stripslashes($row["adr2"]);
				$adr3 = stripslashes($row["adr3"]);
				$adr4 = stripslashes($row["adr4"]);
				$contactno = stripslashes($row["contactno"]);
				$faxno = stripslashes($row["faxno"]);
				$email = stripslashes($row["email"]);
				
				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$owner</td>
						  <td valign=\"top\">$costingclerk</td>
						  <td valign=\"top\">$contactperson</td>
						  <td valign=\"top\">$adr1 <br> $adr2 <br> $adr3 <br> $adr4</td>
						  <td valign=\"top\">$contactno</td>
						  <td valign=\"top\">$faxno</td>
						  <td valign=\"top\">$email</td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editpanelbeater&amp;panelbeaterid=$pbid\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Panel Beater\" border=\"0\" title=\"Edit this Panel Beater\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletepanelbeater&amp;panelbeaterid=$pbid\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Panel Beater\" border=\"0\" title=\"Delete this Panel Beater\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"8\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newpanelbeater\"><img src=\"../images/admin/add.gif\" alt=\"Add new Panel Beater\" border=\"0\" title=\"Add new Panel Beater\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}	
	}
	
	function NewPanelbeater()
	{
		require('connection.php');


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
		
		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewpanelbeater\" name=\"theform\" enctype=\"multipart/form-data\">
								  <p>Enter the new Panel Beater details and click Save</p>
								   	<table class=\"table table-striped\">
								  		<tr>
											<td>Name:</td>
											<td><input type=\"text\" name=\"pbname\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Owner:</td>
											<td><input type=\"text\" name=\"pbowner\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Costing Clerk:</td>
											<td><input type=\"text\" name=\"pbcostingclerk\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Contact Person</td>
											<td><input type=\"text\" name=\"pbcontactperson\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Address:</td>
											<td><input type=\"text\" name=\"pbadr1\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td><input type=\"text\" name=\"pbadr2\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td><input type=\"text\" name=\"pbadr3\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td><input type=\"text\" name=\"pbadr4\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Contact Number:</td>
											<td><input type=\"text\" name=\"pbcontactno\" maxlength=\"50\"></td>
										</tr>
										<tr>
											<td>Fax Number:</td>
											<td><input type=\"text\" name=\"pbfaxno\" maxlength=\"50\"></td>
										</tr>	
										<tr>
											<td>Email Address:</td>
											<td><input type=\"text\" name=\"pbemail\" maxlength=\"255\"></td>
										</tr>
										<tr>
											<td>Latitude:</td>
											<td><input type=\"text\" name=\"latitude\" maxlength=\"50\" ></td>
										</tr>
										<tr>
											<td>Longitude:</td>
											<td><input type=\"text\" name=\"longitude\" maxlength=\"50\" ></td>
										</tr>

										<tr>
											<td>Logo:</td>
											<td><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>
										</tr>

										<tr>
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


												<p><strong>Please select Adminstrator Accredited with: </strong></p>
												<label><input type='checkbox' class='select-all-admins' /> Select All</label> <br />
												";
												
												foreach ($adminsListing as $adminUser) {
													echo '<label><input type="checkbox" name="administrators[]" value="'.$adminUser["id"].'"> ' . $adminUser['name'] . '</label>';
													echo "<br />";
												
												}

										echo "	</td>
										<td>
												<p ><strong>Select Areas </strong></p>
												
												<label><input type='checkbox' class='select-all-pbareas' /> Select All</label> <br />
												";

												$qryareas = "select * from areas order by `areaname`";

												$qryareasresults = mysql_query($qryareas, $db);

												$theareas = "";

												while ($arearow = mysql_fetch_array($qryareasresults))

												{

													$areaname = stripslashes($arearow["areaname"]);

													$id = $arearow["id"];

													$theareas .= "<label><input type=\"checkbox\" name=\"panelbeater_areas[]\" value=\"$id\"> $areaname </label> <br />";

												}

												$theareas = substr($theareas, 0, -6);

												echo $theareas;								


									echo  " </td>

											<td>
												<p><strong>Select Makes for Parts Supplier: </strong></p>
												<label><input type='checkbox' class='select-all-makes' /> Select All</label> <br />
												";
												
												foreach ($manufacturesListing as $vm) {
													echo '<label><input type="checkbox" name="vehiclemakes[]" value="'.$vm["id"].'"> ' . $vm['vehiclemake'] . '</label>';
													echo "<br />";
												}

										echo"	</td>
										</tr>


									</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
							  </form>";
	}
	
	function AddNewPanelbeater($name, $owner, $costingclerk, $contactperson, $adr1, $adr2, $adr3, $adr4, $contactno, $faxno, $email, $latitude='', $longitude='')
	{
		require('connection.php');		

		
		$qryinsert = "insert into panelbeaters ( `name`, `owner`, `costingclerk`, `contactperson`, `adr1`, `adr2`, `adr3`, `adr4`, `contactno`, `faxno`, `email`, `latitude`, `longitude`)
										values ( '$name', '$owner', '$costingclerk', '$contactperson', '$adr1', '$adr2', '$adr3', '$adr4', '$contactno', '$faxno', '$email', '$latitude', '$longitude')";
		$qryinsertresults = mysql_query($qryinsert, $db);

		$pbid = mysql_insert_id();

		
		//echo $qryinsert;

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

		if (!file_exists("../images/panelbeaters")) {
			mkdir("../images/panelbeaters", 0777);
		}

		if (file_exists("../images/panelbeaters/$pbid.jpg"))
		{
			unlink("../images/panelbeaters/$pbid.jpg");
		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/panelbeaters/$pbid.jpg");

		
		echo "<p>The Panel Beater has been saved successfully.</p>";
		
		PanelBeaters(1);
		
	}
	
	function EditPanelbeater($pbid)
	{
		require('connection.php');
								
		$qry = "select * from panelbeaters where id = $pbid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);

		$name = stripslashes($row["name"]);
		$owner = stripslashes($row["owner"]);
		$costingclerk = stripslashes($row["costingclerk"]);
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



		echo "<form method=\"post\" action=\"loggedinaction.php?action=panelbeateredited\" name=\"theform\" enctype=\"multipart/form-data\">
				  <p>Enter the new Panel Beater details and click Save</p>
					<table class=\"table table-striped\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"pbname\" maxlength=\"50\" value=\"$name\"></td>
						</tr>
						<tr>
							<td>Owner:</td>
							<td><input type=\"text\" name=\"pbowner\" maxlength=\"50\" value=\"$owner\"></td>
						</tr>
						<tr>
							<td>Costing Clerk:</td>
							<td><input type=\"text\" name=\"pbcostingclerk\" maxlength=\"50\" value=\"$costingclerk\"></td>
						</tr>
						<tr>
							<td>Contact Person:</td>
							<td><input type=\"text\" name=\"pbcontactperson\" maxlength=\"50\" value=\"$contactperson\"></td>
						</tr>
						<tr>
							<td>Address:</td>
							<td><input type=\"text\" name=\"pbadr1\" maxlength=\"50\" value=\"$adr1\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbadr2\" maxlength=\"50\" value=\"$adr2\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbadr3\" maxlength=\"50\" value=\"$adr3\"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type=\"text\" name=\"pbadr4\" maxlength=\"50\" value=\"$adr4\"></td>
						</tr>
						<tr>
							<td>Contact Number:</td>
							<td><input type=\"text\" name=\"pbcontactno\" maxlength=\"50\" value=\"$contactno\"></td>
						</tr>
						<tr>
							<td>Fax Number:</td>
							<td><input type=\"text\" name=\"pbfaxno\" maxlength=\"50\" value=\"$faxno\"></td>
						</tr>	
						<tr>
							<td>Email Address:</td>
							<td><input type=\"text\" name=\"pbemail\" maxlength=\"50\" value=\"$email\"></td>
						</tr>
						<tr>
							<td>Latitude:</td>
							<td><input type=\"text\" name=\"latitude\" maxlength=\"50\" value=\"$latitude\"></td>
						</tr>
						<tr>
							<td>Longitude:</td>
							<td><input type=\"text\" name=\"longitude\" maxlength=\"50\" value=\"$longitude\"></td>
						</tr> ";

						if (file_exists("../images/panelbeaters/$pbid.jpg"))

						{

							echo "<tr><td>Logo: </td><td><img src=\"../images/panelbeaters/$pbid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br> <br>

							<input type=\"file\" name=\"uploadfile\"></td></tr>";

						}	

						else

						{

							echo "<tr><td>Logo: </td><td><input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)</td></tr>";

						}

						echo " <tr>
							<td>
								<p><strong>Please select Adminstrator Accredited with: </strong></p> 
								<label><input type='checkbox' class='select-all-admins' /> Select All</label> <br />
								";
								
								foreach ($adminsListing as $adminUser) {
									$checked = (in_array($adminUser['id'], $pbAdmins)) ? 'checked="checked"' : '';
									echo '<label><input type="checkbox" name="administrators[]" value="'.$adminUser["id"].'" '.$checked.'> ' . $adminUser['name'] . '</label>';
									echo "<br />";
								
								}

						echo "	</td>
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

									<p ><strong>Select Areas </strong></p>
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


						echo  " </td>

							<td>
								<p><strong>Select Makes for Parts Supplier: </strong></p>
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
	
	function PanelbeaterEdited($pbid, $name, $owner, $costingclerk, $contactperson, $adr1, $adr2, $adr3, $adr4, $contactno, $faxno, $email,$latitude='',$longitude='')
	{
		require('connection.php');
				
		$qryupdate = "update panelbeaters set `name` = '$name',
											  `owner` = '$owner',
											  `costingclerk` = '$costingclerk',
											  `contactperson` = '$contactperson',
											  `adr1` = '$adr1',
											  `adr2` = '$adr2',
											  `adr3` = '$adr3',
											  `adr4` = '$adr4',
											  `contactno` = '$contactno',
											  `faxno`= '$faxno',
											  `latitude`='$latitude',
											  `longitude`='$longitude',
											  `email` = '$email' where `id` = $pbid";
		$qryupdateresults = mysql_query($qryupdate, $db);
		
		echo "<p>The Panel Beater has been edited successfully.</p>";

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

			if (!file_exists("../images/panelbeaters")) {
				mkdir("../images/panelbeaters", 0777);
			}

			if (file_exists("../images/panelbeaters/$pbid.jpg"))
			{
				unlink("../images/panelbeaters/$pbid.jpg");
			}

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/panelbeaters/$pbid.jpg");
		
		}
		
		Panelbeaters(1);
		
	}
	
	function ConfirmDeletePanelbeater($pbid, $key)
	{
		require('connection.php');
		///////////include('functions.php');
						
		$qry = "select * from panelbeaters where `id` = $pbid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = $row["name"];
		
		//////////$key = get_rand_id(32);
		
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values (null, '$key', 'deletepanelbeater')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the Panel Beater <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deletepanelbeater&amp;panelbeaterid=$pbid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						
		
	}

	function DeletePanelbeater($pbid, $key)
	{
		require('connection.php');

		$qry = "select * from `key` where `action` = 'deletepanelbeater' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						

		$keyrow = mysql_fetch_array($qryresults);

		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from panelbeaters where `id` = $pbid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The Panel Beater has been deleted successfully.</p>";
			Panelbeaters(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a panel beater...</p>";
			Panelbeaters(1);
		}
	}

	function SendProfileLinkToPanelbeater($pbid) {
		
		require('connection.php');

		$panelbeaterids = explode(',', $pbid);

		require_once "../vendor/autoload.php";

		$mail = new PHPMailer;

		foreach ($panelbeaterids as $pbid) {
		
			$guid = generate_guid();

			$sql = " UPDATE panelbeaters SET profile_access_token = '" . $guid . "' WHERE id='" . $pbid . "' AND ( profile_access_token='' OR profile_access_token IS NULL ) ";

			mysql_query($sql);

			$guidSQL = " SELECT `profile_access_token` FROM panelbeaters WHERE id='".$pbid."' ";
			$guidResult = mysql_query($guidSQL);
			$guidInfo = mysql_fetch_object($guidResult);
			$guid = $guidInfo->profile_access_token;


			$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/update-pb-profile.php?pbid=' . $pbid . '&token=' . $guid;

			$pbQry = " SELECT name, email FROM panelbeaters WHERE id='" . $pbid . "' ";

			$res = mysql_query($pbQry);

			$pbInfo = mysql_fetch_object($res);

			$mail->setFrom('info@panelshop.co.za', 'Panelshop.co.za');
			$mail->addAddress($pbInfo->email, $pbInfo->name);     // Add a recipient
			$mail->addReplyTo('info@panelshop.co.za', 'Panelshop.co.za');
			$mail->addBCC('info@panelshop.co.za');

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Please update your Panelbeater Profile for Insurance Purposes';

			$body = file_get_contents('../templates/panelbeater-update-profile-notification.html');

			$body = str_replace(['{{PANELBEATER_NAME}}', '{{PROFILE_UPDATE_LINK}}'], [$pbInfo->name, $profileLink], $body);
			
			$mail->Body    = $body;
			$mail->AltBody = strip_tags($body);

			$mail->send();

			$mail->clearAddresses();
			$mail->clearAttachments();
			
		}
		
		$_SESSION['success_message'] = 'Sent profile update link to Panelbeaters.';

		?>
			<script type="text/javascript">
				location.href = 'loggedinaction.php?action=panelbeaters&from=1';
			</script>

		<?php
		
	}

?>
