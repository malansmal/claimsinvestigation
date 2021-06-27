<?php
	

	function Adverts($from) {

		require('connection.php');
		
		if ($from == "") {
			$from = 1;
		}
		
		// show first 30
		if ($from < 1) {

			$qry = "SELECT * FROM adverts LIMIT 0 , 30";

		}	//end if
		else {

			if ($from < 2) {
				$frm = $from - 1;
			}
			else {
				$frm = $from;
			}

			$qry = "SELECT * FROM adverts LIMIT $frm , 30";

		}	//end else

		$qrycountadverts = "select * from adverts";

		$qrycount = mysql_query($qrycountadverts, $db);

		$qryadverts = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		if ($count == 0) {

			echo "<p>There are no Adverts in the database. Click <a href=\"loggedinaction.php?action=newadvert\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add an Advert\" title=\"Add an Advert\"></a> to add one.</p>";

		} else {

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=adverts&amp;from=1\">Page 1</a> || ";

			if ($pagesneeded > 1) {
				//build next page links here 

				for ($i = 1; $i < $pagesneeded; $i++) {

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=adverts&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			$pageslinks = substr($pageslinks, 0, -4);

			echo "<div>

					<table class=\"table table-striped\" >
						<tr>
							<td><strong>Title</strong></td>
							<td><strong>Link</strong></td>
							<td><strong>Image</strong></td>
							<td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						</tr>";

			while ($row = mysql_fetch_array($qryadverts)) {

				// give a name to the fields

				$id = $row['id'];

				$advertname = stripslashes($row['advertname']);

				$link = $row['link'];
			?>
				<tr>
					<td valign="top"><?php echo $advertname; ?></td>

					<td valign="top"><?php echo $link ?></td>
					<?php
							if (file_exists("../images/adverts/$id.jpg")) {

								echo "<td valign=\"top\"><img src=\"../images/adverts/$id.jpg\" style='width:80px;height:80px;'></td>";

							}
							else {

								echo "<td valign=\"top\"><img src=\"../images/administrators/nologo.jpg\"></td>";

							}	
					?>

					<td align="center"><a href="loggedinaction.php?action=editadvert&amp;advertid=<?php echo $id ?>"><img src="../images/admin/edit.gif" alt="Edit this Advert" border="0" title="Edit this Advert"></td>
			
					<td align="center"><a href="loggedinaction.php?action=confirmdeleteadvert&amp;advertid=<?php echo $id ?>"><img src="../images/admin/delete.gif" alt="Delete this Advert" border="0" title="Delete this Advert"></td>

				</tr>
			<?php
			}	//end while loop

			echo "	<tr>
						<td colspan=\"3\">&nbsp;</td>
						<td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newadvert\"><img src=\"../images/admin/add.gif\" alt=\"Add an Advert\" border=\"0\" title=\"Add an Advert\"></a></td>
					</tr>

				</table>
				<br />$pageslinks <br />

				";
		}

	}

	function NewAdvert() {

		require('connection.php');

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewadvert\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Advert details and click Save</p>

					<table border=\"0\" cellspacing=\"1\">

						<tr>

							<td>Title:</td>

							<td><input type=\"text\" name=\"advertname\" maxlength=\"255\"></td>

						</tr>

						<tr>

							<td>Link:</td>

							<td><input type=\"text\" name=\"link\" maxlength=\"255\"></td>

						</tr>
						<tr>

							<td>Image:</td>

							<td colspan=\"3\"><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	function AddNewAdvert($advertname, $link) {

		require('connection.php');

		$qryinsert = " INSERT INTO adverts (`advertname`, `link`)
					VALUES ('$advertname', '$link')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		$newid = mysql_insert_id();
		
		if (!file_exists("../images/adverts")) {
			mkdir("../images/adverts", 0777);
		}

		if (file_exists("../images/adverts/$newid.jpg"))
		{
			unlink("../images/adverts/$newid.jpg");
		}

		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/adverts/$newid.jpg");

		echo "<p>The Advert has been saved successfully.</p>";		

		Adverts(1);

	}

	function EditAdvert($advertid) {

		require('connection.php');

		$qry = "select * from adverts where id = $advertid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$advertname = stripslashes($row["advertname"]);

		$link = stripslashes($row['link']);

								

		echo "<form method=\"post\" action=\"loggedinaction.php?action=advertedited\" name=\"theform\" enctype=\"multipart/form-data\">

				  <p>Enter the new Advert details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Title:</td>

							<td><input type=\"text\" name=\"advertname\" maxlength=\"255\" value=\"$advertname\"></td>

						</tr>

						<tr>

							<td>Link:</td>

							<td><input type=\"text\" name=\"link\" maxlength=\"255\" value=\"$link\"></td>

						</tr> ";

							if (file_exists("../images/adverts/$advertid.jpg"))

							{

								echo "<tr><td colspan='2'><img src=\"../images/adverts/$advertid.jpg\" width='150'><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

								<input type=\"file\" name=\"uploadfile\"></td></tr>";

							}	

							else

							{

								echo "<tr><td colspan='2'><input type=\"file\" name=\"uploadfile\"> <input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> (note: images MUST be 100 x 100 pixels)</td></tr>";

							}


					echo "</table>

					<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"advertid\" value=\"$advertid\">

			  </form>";

	}


	function AdvertEdited($advertid, $advertname, $link) {

		require('connection.php');

		$qryupdate = "UPDATE adverts SET `advertname` = '$advertname',
					   `link` = '$link' WHERE `id` = $advertid";

		$qryupdateresults = mysql_query($qryupdate, $db);
		
		if ($_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
		
			if (!file_exists("../images/adverts")) {
				mkdir("../images/adverts", 0777);
			}

			if (file_exists("../images/adverts/$advertid.jpg"))
			{
				unlink("../images/adverts/$advertid.jpg");
			}

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/adverts/$advertid.jpg");

		}



		echo "<p>The Advert has been edited successfully.</p>";

		Adverts(1);

	}

	function ConfirmDeleteAdvert($advertid, $key) {

		require('connection.php');

		$qry = "select * from adverts where `id` = $advertid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		$advertname = $row["advertname"];

		$qryinsert = "insert into `key` (`key`, `action`) values ( '$key', 'deleteadvert')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		echo "<p>Are you sure you want to delete the Advert <strong>$advertname</strong>?<br> <a href=\"loggedinaction.php?action=deleteadvert&amp;advertid=$advertid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";	

	}

	function DeleteAdvert($advertid, $key) {

		require('connection.php');

		$qry = "select * from `key` where `action` = 'deleteadvert' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		$keyrow = mysql_fetch_array($qryresults);

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		if ($count == 1) {

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			$qrydelete = "delete from adverts where `id` = $advertid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			echo "<p>The Advert has been deleted successfully.</p>";

			Adverts(1);

		} else {

			echo "<p>It wont work if you enter the url just like that to delete a advert...</p>";

			Adverts(1);

		}

	}