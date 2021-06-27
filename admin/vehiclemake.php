<?php

	function VehicleMake($from) {

		require('connection.php');
		
		if ($from == "") {
			$from = 1;
		}
		
		// show first 30
		if ($from < 1) {

			$qry = "SELECT * FROM vehiclemake LIMIT 0 , 30";

		}	//end if
		else {

			if ($from < 2) {
				$frm = $from - 1;
			}
			else {
				$frm = $from;
			}

			$qry = "SELECT * FROM vehiclemake LIMIT $frm , 30";

		}	//end else

		$qrycountvehiclemakes = "select * from vehiclemake";

		$qrycount = mysql_query($qrycountvehiclemakes, $db);

		$qryvehiclemakes = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		if ($count == 0) {

			echo "<p>There are no Vehicle Makes in the database. Click <a href=\"loggedinaction.php?action=newvehiclemake\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Vehicle Make\" title=\"Add new Vehicle Make\"></a> to add one.</p>";

		} else {

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=vehiclemake&amp;from=1\">Page 1</a> || ";

			if ($pagesneeded > 1) {
				//build next page links here 

				for ($i = 1; $i < $pagesneeded; $i++) {

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=vehiclemake&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			$pageslinks = substr($pageslinks, 0, -4);

			echo "<div>

					<table class=\"table table-striped\" >
						<tr>
							<td><strong>Vehicle Make</strong></td>
							<td><strong>Remarks</strong></td>
							<td><strong>Logo</strong></td>
							<td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						</tr>";

			while ($row = mysql_fetch_array($qryvehiclemakes)) {

				// give a name to the fields

				$id = $row['id'];

				$vehiclemake = stripslashes($row['vehiclemake']);

				$remarks = stripslashes($row['remarks']);
			?>
				<tr>
					<td valign="top"><?php echo $vehiclemake; ?></td>

					<td valign="top"><?php echo $remarks ?></td>
					<?php
							if (file_exists("../images/vehiclemakes/$id.jpg"))

								{

									echo "<td valign=\"top\"><img src=\"../images/vehiclemakes/$id.jpg\" style='width:80px;height:80px;'></td>";

								}

								else

								{

									echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

								}	
					?>

					<td align="center"><a href="loggedinaction.php?action=editvehiclemake&amp;vehiclemakeid=<?php echo $id ?>"><img src="../images/admin/edit.gif" alt="Edit this Vehicle Make" border="0" title="Edit this Vehicle Make"></td>
			
					<td align="center"><a href="loggedinaction.php?action=confirmdeletevehiclemake&amp;vehiclemakeid=<?php echo $id ?>"><img src="../images/admin/delete.gif" alt="Delete this Vehicle Make" border="0" title="Delete this Vehicle Make"></td>

				</tr>
			<?php
			}	//end while loop			

			echo "	<tr>
						<td colspan=\"3\">&nbsp;</td>
						<td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newvehiclemake\"><img src=\"../images/admin/add.gif\" alt=\"Add new Vehicle Make\" border=\"0\" title=\"Add new Vehicle Make\"></a></td>
					</tr>

				</table>
				<br />$pageslinks <br />

				";
		}

	}

	function NewVehicleMake() {

		require('connection.php');

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewvehiclemake\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Vehicle Make details and click Save</p>

					<table border=\"0\" cellspacing=\"1\">

						<tr>

							<td>Vehicle Make:</td>

							<td><input type=\"text\" name=\"vehiclemake\" maxlength=\"255\"></td>

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

	function AddNewVehicleMake($vehiclemake, $remarks) {

		require('connection.php');

		$qryinsert = " INSERT INTO vehiclemake (`vehiclemake`, `remarks`)
					VALUES ('$vehiclemake', '$remarks')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		$newid = mysql_insert_id();
		
		if (!file_exists("../images/vehiclemakes")) {
			mkdir("../images/vehiclemakes", 0777);
		}

		if (file_exists("../images/vehiclemakes/$newid.jpg"))
		{
			unlink("../images/vehiclemakes/$newid.jpg");
		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/vehiclemakes/$newid.jpg");


		echo "<p>The Vehicle Make has been saved successfully.</p>";		

		VehicleMake(1);

	}

	function EditVehicleMake($vehiclemakeid) {

		require('connection.php');

		$qry = "select * from vehiclemake where id = $vehiclemakeid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$vehiclemake = stripslashes($row["vehiclemake"]);

		$remarks = stripslashes($row['remarks']);

								

		echo "<form method=\"post\" action=\"loggedinaction.php?action=vehiclemakeedited\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Vehicle Make details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Vehicle Make:</td>

							<td><input type=\"text\" name=\"vehiclemake\" maxlength=\"255\" value=\"$vehiclemake\"></td>

						</tr>

						<tr>

							<td>Remarks:</td>

							<td><input type=\"text\" name=\"remarks\" maxlength=\"255\" value=\"$remarks\"></td>

						</tr> ";

							if (file_exists("../images/vehiclemakes/$vehiclemakeid.jpg"))

							{

								echo "<tr><td colspan='2'><img src=\"../images/vehiclemakes/$vehiclemakeid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

								<input type=\"file\" name=\"uploadfile\"></td></tr>";

							}	

							else

							{

								echo "<tr><td colspan='2'><input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)</td></tr>";

							}


					echo "</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"vehiclemakeid\" value=\"$vehiclemakeid\">

			  </form>";

	}


	function VehicleMakeEdited($vehiclemakeid, $vehiclemake, $remarks) {

		require('connection.php');

		$qryupdate = "UPDATE vehiclemake SET `vehiclemake` = '$vehiclemake',
					   `remarks` = '$remarks' WHERE `id` = $vehiclemakeid";

		$qryupdateresults = mysql_query($qryupdate, $db);

		if ($_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
			if (!file_exists("../images/vehiclemakes")) {
				mkdir("../images/vehiclemakes", 0777);
			}

			if (file_exists("../images/vehiclemakes/$vehiclemakeid.jpg"))
			{
				unlink("../images/vehiclemakes/$vehiclemakeid.jpg");
			}

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/vehiclemakes/$vehiclemakeid.jpg");
		}

		echo "<p>The Vehicle Make has been edited successfully.</p>";

		VehicleMake(1);

	}

	function ConfirmDeleteVehicleMake($vehiclemakeid, $key) {

		require('connection.php');

		$qry = "select * from vehiclemake where `id` = $vehiclemakeid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		$vehiclemake = $row["vehiclemake"];

		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deletevehiclemake')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		echo "<p>Are you sure you want to delete the Vehicle Make <strong>$areaname</strong>?<br> <a href=\"loggedinaction.php?action=deletevehiclemake&amp;vehiclemakeid=$vehiclemakeid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";	

	}

	function DeleteVehicleMake($vehiclemakeid, $key) {

		require('connection.php');

		$qry = "select * from `key` where `action` = 'deletevehiclemake' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		$keyrow = mysql_fetch_array($qryresults);

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		if ($count == 1) {

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			$qrydelete = "delete from vehiclemake where `id` = $vehiclemakeid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			echo "<p>The Vehicle Make has been deleted successfully.</p>";

			VehicleMake(1);

		} else {

			echo "<p>It wont work if you enter the url just like that to delete a vehicle make...</p>";

			VehicleMake(1);

		}

	}