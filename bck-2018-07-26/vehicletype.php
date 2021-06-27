<?php

	function VehicleType($from) {

		require('connection.php');
		
		if ($from == "") {
			$from = 1;
		}
		
		// show first 30
		if ($from < 1) {

			$qry = "SELECT * FROM vehicletype LIMIT 0 , 30";

		}	//end if
		else {

			if ($from < 2) {
				$frm = $from - 1;
			}
			else {
				$frm = $from;
			}

			$qry = "SELECT * FROM vehicletype LIMIT $frm , 30";

		}	//end else

		$qrycountvehicletypes = "select * from vehicletype";

		$qrycount = mysql_query($qrycountvehicletypes, $db);

		$qryvehicletypes = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		if ($count == 0) {

			echo "<p>There are no Vehicle Types in the database. Click <a href=\"loggedinaction.php?action=newvehicletype\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Vehicle Type\" title=\"Add new Vehicle Type\"></a> to add one.</p>";

		} else {

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=vehicletype&amp;from=1\">Page 1</a> || ";

			if ($pagesneeded > 1) {
				//build next page links here 

				for ($i = 1; $i < $pagesneeded; $i++) {

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=vehicletype&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			$pageslinks = substr($pageslinks, 0, -4);

			echo "<div>

					<table class=\"table table-striped\" >
						<tr>
							<td><strong>Vehicle Type</strong></td>
							<td><strong>Remarks</strong></td>
							<td><strong>Logo</strong></td>
							<td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						</tr>";

			while ($row = mysql_fetch_array($qryvehicletypes)) {

				// give a name to the fields

				$id = $row['id'];

				$vehicletype = stripslashes($row['vehicletype']);

				$remarks = stripslashes($row['remarks']);
			?>
				<tr>
					<td valign="top"><?php echo $vehicletype; ?></td>

					<td valign="top"><?php echo $remarks ?></td>
					<?php
							if (file_exists("../images/vehicletypes/$id.jpg"))

								{

									echo "<td valign=\"top\"><img src=\"../images/vehicletypes/$id.jpg\" style='width:80px;height:80px;'></td>";

								}

								else

								{

									echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

								}	
					?>

					<td align="center"><a href="loggedinaction.php?action=editvehicletype&amp;vehicletypeid=<?php echo $id ?>"><img src="../images/admin/edit.gif" alt="Edit this Vehicle Type" border="0" title="Edit this Vehicle Type"></td>
			
					<td align="center"><a href="loggedinaction.php?action=confirmdeletevehicletype&amp;vehicletypeid=<?php echo $id ?>"><img src="../images/admin/delete.gif" alt="Delete this Vehicle Type" border="0" title="Delete this Vehicle Type"></td>

				</tr>
			<?php
			}	//end while loop

			echo "	<tr>
						<td colspan=\"3\">&nbsp;</td>
						<td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newvehicletype\"><img src=\"../images/admin/add.gif\" alt=\"Add new Vehicle Type\" border=\"0\" title=\"Add new Vehicle Type\"></a></td>
					</tr>

				</table>
				<br />$pageslinks <br />

				";
		}

	}

	function NewVehicleType() {

		require('connection.php');

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewvehicletype\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Vehicle Type details and click Save</p>

					<table border=\"0\" cellspacing=\"1\">

						<tr>

							<td>Vehicle Type:</td>

							<td><input type=\"text\" name=\"vehicletype\" maxlength=\"255\"></td>

						</tr>

						<tr>

							<td>Remarks:</td>

							<td><input type=\"text\" name=\"remarks\" maxlength=\"255\"></td>

						</tr>
						<tr>

							<td>Logo:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	function AddNewVehicleType($vehicletype, $remarks) {

		require('connection.php');

		$qryinsert = " INSERT INTO vehicletype (`vehicletype`, `remarks`)
					VALUES ('$vehicletype', '$remarks')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		$newid = mysql_insert_id();
		
		if (!file_exists("../images/vehicletypes")) {
			mkdir("../images/vehicletypes", 0777);
		}

		if (file_exists("../images/vehicletypes/$newid.jpg"))
		{
			unlink("../images/vehicletypes/$newid.jpg");
		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/vehicletypes/$newid.jpg");


		echo "<p>The Vehicle Type has been saved successfully.</p>";		

		VehicleType(1);

	}

	function EditVehicleType($vehicletypeid) {

		require('connection.php');

		$qry = "select * from vehicletype where id = $vehicletypeid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$vehicletype = stripslashes($row["vehicletype"]);

		$remarks = stripslashes($row['remarks']);

								

		echo "<form method=\"post\" action=\"loggedinaction.php?action=vehicletypeedited\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Vehicle Type details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Vehicle Type:</td>

							<td><input type=\"text\" name=\"vehicletype\" maxlength=\"255\" value=\"$vehicletype\"></td>

						</tr>

						<tr>

							<td>Remarks:</td>

							<td><input type=\"text\" name=\"remarks\" maxlength=\"255\" value=\"$remarks\"></td>

						</tr> ";

							if (file_exists("../images/vehicletypes/$vehicletypeid.jpg"))

							{

								echo "<tr><td colspan='2'><img src=\"../images/vehicletypes/$vehicletypeid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

								<input type=\"file\" name=\"uploadfile\"></td></tr>";

							}	

							else

							{

								echo "<tr><td colspan='2'><input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)</td></tr>";

							}


					echo "</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"vehicletypeid\" value=\"$vehicletypeid\">

			  </form>";

	}


	function VehicleTypeEdited($vehicletypeid, $vehicletype, $remarks) {

		require('connection.php');

		$qryupdate = "UPDATE vehicletype SET `vehicletype` = '$vehicletype',
					   `remarks` = '$remarks' WHERE `id` = $vehicletypeid";

		$qryupdateresults = mysql_query($qryupdate, $db);
		
		if ($_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
		
			if (!file_exists("../images/vehicletypes")) {
				mkdir("../images/vehicletypes", 0777);
			}

			if (file_exists("../images/vehicletypes/$vehicletypeid.jpg"))
			{
				unlink("../images/vehicletypes/$vehicletypeid.jpg");
			}

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/vehicletypes/$vehicletypeid.jpg");

		}



		echo "<p>The Vehicle Type has been edited successfully.</p>";

		VehicleType(1);

	}

	function ConfirmDeleteVehicleType($vehicletypeid, $key) {

		require('connection.php');

		$qry = "select * from vehicletype where `id` = $vehicletypeid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		$vehicletype = $row["vehicletype"];

		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deletevehicletype')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		echo "<p>Are you sure you want to delete the Vehicle Type <strong>$areaname</strong>?<br> <a href=\"loggedinaction.php?action=deletevehicletype&amp;vehicletypeid=$vehicletypeid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";	

	}

	function DeleteVehicleType($vehicletypeid, $key) {

		require('connection.php');

		$qry = "select * from `key` where `action` = 'deletevehicletype' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		$keyrow = mysql_fetch_array($qryresults);

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		if ($count == 1) {

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			$qrydelete = "delete from vehicletype where `id` = $vehicletypeid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			echo "<p>The Vehicle Type has been deleted successfully.</p>";

			VehicleType(1);

		} else {

			echo "<p>It wont work if you enter the url just like that to delete a vehicle type...</p>";

			VehicleType(1);

		}

	}