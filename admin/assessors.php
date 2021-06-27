<?php



//	echo "test";



	function Assessors($request_data){

		//echo '<pre>'; print_r($request_data);die;

		$from = $request_data['from'];

		require('connection.php');

		$name = isset($request_data['name']) ? trim($request_data['name']) : '';

		$company = isset($request_data['company']) ? trim($request_data['company']) : '';
		
		$subQry = '';

		if ( !empty($name)  ) {
			$subQry .= empty($subQry) ? " WHERE " : " AND ";
			$subQry .= " name like '%" . $name . "%' ";
		}

		if ( !empty($company)  ) {
			$subQry .= empty($subQry) ? " WHERE " : " AND ";
			$subQry .= " company like '%" . $company . "%' ";
		}

		echo "<p><a href=\"\">Administrate Areas</a></p>";

		echo "
			<form action=\"loggedinaction.php\" method=\"get\" name=\"searchform\">
					<input type=\"hidden\" name=\"action\" value=\"assessors\" />
					<input type=\"hidden\" name=\"from\" value=\"1\" />

					<strong>Search for assessors:</strong><br>

					Name: <input type=\"text\" name=\"name\" value='". $name. "' /> 

					Company: <input type=\"text\" name=\"company\" value='". $company. "' />

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

			$qry = "SELECT * FROM assessors $subQry LIMIT 0 , 30";

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

			

			$qry = "SELECT * FROM assessors $subQry LIMIT $frm , 30";

		}	//end else

		

		$qrycountassessors = "select * from assessors $subQry ";

		$qrycount = mysql_query($qrycountassessors, $db);

		

		$qryassessors = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		

		if ($count == 0)

		{

			echo "<p>There are no Assessors in the database. Click <a href=\"loggedinaction.php?action=newassessor\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Assessor\" title=\"Add new Assessor\"></a> to add one.</p>";

		}

		else

		{

			

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=assessors&amp;from=1&amp;name=$name&amp;company=$company\">Page 1</a> || ";

			

			//echo "pages that will be needed is $count today";

			

			if ($pagesneeded > 1)	//build next page links here

			{

				for ($i = 1; $i < $pagesneeded; $i++)

				{

					//echo "i is $i<br>";

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=assessors&amp;from=" . $fromrecord . "&amp;name=$name&amp;company=$company \">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			

			$pageslinks = substr($pageslinks, 0, -4);

			

			echo "<div>

				  <table class=\"table table-striped\">

						  <tr>

							  <td><strong>Name</strong></td>

							  <td><strong>Company</strong></td>

							  <td><strong>Telephone</strong></td>

							  <td><strong>Fax</strong></td>

							  <td><strong>Cell</strong></td>

							  <td><strong>Email</strong></td>

							  <td><strong>Address</strong></td>

							  <td><strong>Comments</strong></td>

							  <td><strong>Area/s</strong></td>

							  <td><strong>Bank Details</strong></td>

							  <td><strong>Logo</strong></td>

							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>

						  </tr>";

						  

			while ($row = mysql_fetch_array($qryassessors)) 

			{

				// give a name to the fields

				$id = $row['id'];

				$name = stripslashes($row['name']);

				$company = stripslashes($row['company']);

				$telno = stripslashes($row['telno']);

				$faxno = stripslashes($row['faxno']);

				$cellno = stripslashes($row['cellno']);

				$email = $row['email'];

				$comments = stripslashes($row['comments']);

				$bankdetails = stripslashes($row["bankdetails"]);

				$address = stripslashes($row["adr1"]) . ", " . stripslashes($row["adr2"]) . ", " . stripslashes($row["adr3"]) . ", " . stripslashes($row["4"]);



				//echo the results onscreen

				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

				

				echo "<tr>

						  <td valign=\"top\">$name</td>

						  <td valign=\"top\">$company</td>

						  <td valign=\"top\">$telno</td>

						  <td valign=\"top\">$faxno</td>

						  <td valign=\"top\">$cellno</td>

						  <td valign=\"top\">$email</td>

						  <td valign=\"top\">$address</td>

						  <td valign=\"top\">$comments</td>

						  <td valign=\"top\">";

				

				$qryareas = "SELECT areaname FROM `assessor_area` 

								join areas

								  on assessor_area.areaid = areas.id

								join assessors

								on assessors.id = assessor_area.assessorid

								and assessors.id = $id";

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

				

				echo "$areas</td>

						<td valign=\"top\">$bankdetails</td>

				";

				

				

				

				if (file_exists("../images/assessors/$id.jpg"))

				{

					echo "<td valign=\"top\"><img src=\"../images/assessors/$id.jpg\"></td>";

				}

				else

				{

					echo "<td valign=\"top\"><img src=\"../images/assessors/nologo.jpg\"></td>";

				}	

						  

				echo "

						  <td align=\"center\"><a href=\"loggedinaction.php?action=editassessor&amp;assessorid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Assessor\" border=\"0\" title=\"Edit this Assessor\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteassessor&amp;assessorid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Assessor\" border=\"0\" title=\"Delete this Assessor\"></td>

					  </tr>";

				

			}	//end while loop			

			

			echo "<tr>

					  <td colspan=\"11\">&nbsp;</td>

					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newassessor\"><img src=\"../images/admin/add.gif\" alt=\"Add new Assessor\" border=\"0\" title=\"Add new Assessor\"></a></td>

				  </tr>

			</table><br>$pageslinks<br>

				";

		}

	}

	

	function NewAssessor()

	{

		require('connection.php');

		

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewassessor\" enctype=\"multipart/form-data\" name=\"theform\">

				  <p>Enter the new Assessor details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Name:</td>

							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Company:</td>

							<td><input type=\"text\" name=\"company\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Telephone Number:</td>

							<td><input type=\"text\" name=\"telno\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Fax Number:</td>

							<td><input type=\"text\" name=\"faxno\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Cell Number:</td>

							<td><input type=\"text\" name=\"cellno\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Email Address:</td>

							<td><input type=\"text\" name=\"email\" maxlength=\"255\"></td>

						</tr>

						<tr>

							<td>Address:</td>

							<td><input type=\"text\" name=\"adr1\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr2\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr3\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\"></td>

						</tr>

						<tr>

							<td>Comments:</td>

							<td><input type=\"text\" name=\"comments\"></td>

						</tr>

						<tr>

							<td>Password:</td>

							<td><input type=\"text\" name=\"password\" maxlength=\"20\"></td>

						</tr>

						<tr>

							<td>Bank Details:</td>

							<td><input type=\"text\" name=\"bankdetails\" maxlength=\"255\"></td>

						</tr>
						
						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\"></td>

						</tr>

						<tr>

							<td valign=\"top\">Select Areas for this Assessor:</td>

							<td>";

			

		$qryareas = "select * from areas order by `areaname`";

		$qryareasresults = mysql_query($qryareas, $db);

		$theareas = "";

		

		while ($arearow = mysql_fetch_array($qryareasresults))

		{

			$areaname = stripslashes($arearow["areaname"]);

			$id = $arearow["id"];

			

			$theareas .= "<input type=\"checkbox\" name=\"area-$id\"> $areaname <br />";

		}

		

		$theareas = substr($theareas, 0, -6);

							

		echo "$theareas</td>

						</tr>

						<tr>

							<td>Logo:</td>

							<td><input type=\"file\" name=\"uploadfile\"> (note: images MUST be 100 x 100 pixels)</td>

						</tr>

					</table>

<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	

	function AddNewAssessor($name, $company, $telno, $faxno, $cellno, $email, $comments, $password)

	{

		require('connection.php');

		

		$bankdetails = addslashes($_REQUEST["bankdetails"]);

		

		$adr1 = addslashes($_REQUEST["adr1"]);

		$adr2 = addslashes($_REQUEST["adr2"]);

		$adr3 = addslashes($_REQUEST["adr3"]);

		$adr4 = addslashes($_REQUEST["adr4"]);
		
		$vatno = $_REQUEST["vatno"];

		

		$qryinsert = "insert into assessors (`id`, `name`, `company`, `telno`, `faxno`, `cellno`, `email`, `adr1`, `adr2`, `adr3`, `adr4`,  `comments`, `password`, `bankdetails`, `vatno`)

								values ('', '$name', '$company', '$telno', '$faxno', '$cellno', '$email', '$adr1', '$adr2', '$adr3', '$adr4',  '$comments', '$password', '$bankdetails', '$vatno')";

		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo $qryinsert;

		

		//get the new id

		

		$newid = GetNewID2("assessors", "id", $db);

		

		$qryareas = "select * from areas";

		$qryareasresults = mysql_query($qryareas, $db);

		

		while ($arearow = mysql_fetch_array($qryareasresults))

		{

			$areaid = $arearow["id"];

			

			$thearea = $_REQUEST["area-$areaid"];

			

			if ($thearea == "on")

			{

				$qryinsertarea = "insert into assessor_area (`assessorid`, `areaid`) values ($newid, $areaid)";

				$qryinsertarearesults = mysql_query($qryinsertarea, $db);

			}

		}

		

		if (file_exists("../images/assessors/$newid.jpg"))

		{

			unlink("../images/assessors/$newid.jpg");

		}



		move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/assessors/$newid.jpg");

		

		echo "<p>The Assessor has been saved successfully.</p>";

		

		Assessors(1);

		

	}

	

	function EditAssessor($assid)

	{

		require('connection.php');

						

		$qry = "select * from assessors where id = $assid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$name = stripslashes($row["name"]);

		$company = stripslashes($row['company']);

		$telno = stripslashes($row['telno']);

		$faxno = stripslashes($row['faxno']);

		$cellno = stripslashes($row['cellno']);

		$email = $row['email'];

		$comments = stripslashes($row['comments']);

		$password = stripslashes($row['password']);

		$bankdetails = stripslashes($row["bankdetails"]);
		
		$vatno = stripslashes($row["vatno"]);

		$adr1 = stripslashes($row["adr1"]);

		$adr2 = stripslashes($row["adr2"]);

		$adr3 = stripslashes($row["adr3"]);

		$adr4 = stripslashes($row["adr4"]);

								

		echo "<form method=\"post\" action=\"loggedinaction.php?action=assessoredited\" enctype=\"multipart/form-data\" name=\"theform\">

				  <p>Enter the new Assessor details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Name:</td>

							<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>

						</tr>

						<tr>

							<td>Company:</td>

							<td><input type=\"text\" name=\"company\" maxlength=\"50\" value=\"$company\"></td>

						</tr>

						<tr>

							<td>Telephone Number:</td>

							<td><input type=\"text\" name=\"telno\" maxlength=\"20\" value=\"$telno\"></td>

						</tr>

						<tr>

							<td>Fax Number:</td>

							<td><input type=\"text\" name=\"faxno\" maxlength=\"20\" value=\"$faxno\"></td>

						</tr>

						<tr>

							<td>Cell Number:</td>

							<td><input type=\"text\" name=\"cellno\" maxlength=\"20\" value=\"$cellno\"></td>

						</tr>

						<tr>

							<td>Email Address:</td>

							<td><input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$email\"></td>

						</tr>

						<tr>

							<td>Address:</td>

							<td><input type=\"text\" name=\"adr1\" maxlength=\"50\" value=\"$adr1\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr2\" maxlength=\"50\" value=\"$adr2\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr3\" maxlength=\"50\" value=\"$adr3\"></td>

						</tr>

						<tr>

							<td>&nbsp;</td>

							<td><input type=\"text\" name=\"adr4\" maxlength=\"50\" value=\"$adr4\"></td>

						</tr>						

						<tr>

							<td>Comments:</td>

							<td><input type=\"text\" name=\"comments\" value=\"$comments\"></td>

						</tr>

						<tr>

							<td>Password:</td>

							<td><input type=\"text\" name=\"password\" maxlength=\"20\" value=\"$password\"></td>

						</tr>

						<tr>

							<td>Bank Details:</td>

							<td><input type=\"text\" name=\"bankdetails\" maxlength=\"255\" value=\"$bankdetails\"></td>

						</tr>
						
						<tr>

							<td>VAT Number:</td>

							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\" value=\"$vatno\"></td>

						</tr>

						<tr>

							<td valign=\"top\">Select Areas for this Assessor:</td>

							<td>";

							

		$qryareas = "select * from areas order by `areaname`";

		$qryareasresults = mysql_query($qryareas, $db);

		

		while ($arearow = mysql_fetch_array($qryareasresults))

		{

			$areaid = $arearow["id"];

			$areaname = $arearow["areaname"];

			

			$qrycheckarea = "select count(areaid) as counted from assessor_area where areaid = $areaid and assessorid = $assid";

			$qrycheckarearesults = mysql_query($qrycheckarea, $db);

			

			$therow = mysql_fetch_array($qrycheckarearesults);

			

			$count = $therow["counted"];

			

			if ($count == 1)

			{

				$theareas .= "<input type=\"checkbox\" name=\"area-$areaid\" checked>$areaname <br />";

			}

			else

			{

				$theareas .= "<input type=\"checkbox\" name=\"area-$areaid\">$areaname <br />";

			}

		}



		echo "		$theareas</td>

				</tr>

				<tr>

							<td>Logo:</td><td>";

		

		if (file_exists("../images/administrators/$adminid.jpg"))

		{

			echo "<img src=\"../images/administrators/$adminid.jpg\"><input type=\"hidden\" value=\"1\" name=\"uploadnewfile\" /> <br>

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

<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"assessorid\" value=\"$assid\">

			  </form>";

	}

	

	function AssessorEdited($assid, $name, $company, $telno, $faxno, $cellno, $email, $comments, $password)

	{

		require('connection.php');

		

		$bankdetails = addslashes($_REQUEST["bankdetails"]);

		

		$adr1 = addslashes($_REQUEST["adr1"]);

		$adr2 = addslashes($_REQUEST["adr2"]);

		$adr3 = addslashes($_REQUEST["adr3"]);

		$adr4 = addslashes($_REQUEST["adr4"]);
		
		$vatno = $_REQUEST["vatno"];

		

		$qryupdate = "update assessors set `name` = '$name',

										   `company` = '$company', 

										   `telno` = '$telno',

										   `faxno` = '$faxno',

										   `cellno` = '$cellno',

										   `email` = '$email',

										   `adr1` = '$adr1',

										   `adr2` = '$adr2',

										   `adr3` = '$adr3',

										   `adr4` = '$adr4',

										   `comments` = '$comments',

										   `password` = '$password',

										   `bankdetails` = '$bankdetails',
										   
										   `vatno` = '$vatno' where `id` = $assid";

		$qryupdateresults = mysql_query($qryupdate, $db);

		

		$qrydeleteareas = "delete from assessor_area where assessorid = $assid";

		$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

		

		$qryareas = "select * from areas";

		$qryareasresults = mysql_query($qryareas, $db);

		

		while ($arearow = mysql_fetch_array($qryareasresults))

		{

			$areaid = $arearow["id"];

			

			$thearea = $_REQUEST["area-$areaid"];

			

			if ($thearea == "on")

			{

				$qryinsertarea = "insert into assessor_area (`assessorid`, `areaid`) values ($assid, $areaid)";

				$qryinsertarearesults = mysql_query($qryinsertarea, $db);

			}

		}

		

		$i = $_REQUEST["uploadnewfile"];

		

		if ($i == 1)

		{

			if (file_exists("../images/assessors/$assid.jpg"))

			{

				unlink("../images/assessors/$assid.jpg");

			}

	

			move_uploaded_file ($_FILES['uploadfile'] ['tmp_name'], "../images/assessors/$assid.jpg");

		}

		

		echo "<p>The Assessor has been edited successfully.</p>";

		

		Assessors(1);

	}

	

	function ConfirmDeleteAssessor($assid, $key)

	{

		require('connection.php');

								

		$qry = "select * from assessors where `id` = $assid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$name = $row["name"];

		

		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteassessor')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		

		echo "<p>Are you sure you want to delete the Assessor <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deleteassessor&amp;assessorid=$assid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						

		

	}

	

	function DeleteAssessor($assid, $key)

	{

		require('connection.php');

								

		$qry = "select * from `key` where `action` = 'deleteassessor' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		

		$keyrow = mysql_fetch_array($qryresults);

		

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		

		if ($count == 1)

		{

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydelete = "delete from assessors where `id` = $assid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydeleteareas = "delete from assessor_area where assessorid = $assid";

			$qrydeleteareasresults = mysql_query($qrydeleteareas, $db);

			

			if (file_exists("../images/assessors/$assid.jpg"))

			{

				unlink("../images/assessors/$assid.jpg");

			}

			

			echo "<p>The Assessor has been deleted successfully.</p>";

			

			Assessors(1);

		}

		else

		{

			echo "<p>It wont work if you enter the url just like that to delete a assessor...</p>";

			

			Assessors(1);

		}

	}



	function Areas($from)

	{

		require('connection.php');

		

		if ($from == "")

		{

			$from = 1;

		}

		

			//display first 30

		if ($from < 1)

		{

			$qry = "SELECT * FROM areas LIMIT 0 , 30";

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

			

			$qry = "SELECT * FROM areas LIMIT $frm , 30";

		}	//end else

		

		$qrycountareas = "select * from areas";

		$qrycount = mysql_query($qrycountareas, $db);

		

		$qryareas = mysql_query($qry, $db);

		$count = mysql_num_rows($qrycount);

		

		if ($count == 0)

		{

			echo "<p>There are no Areas in the database. Click <a href=\"loggedinaction.php?action=newarea\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Area\" title=\"Add new Area\"></a> to add one.</p>";

		}

		else

		{

			

			$pagesneeded = $count / 30;

			$pagesneeded = ceil($pagesneeded);

			$pageslinks = "<a href=\"loggedinaction.php?action=areas&amp;from=1\">Page 1</a> || ";

			

			//echo "pages that will be needed is $count today";

			

			if ($pagesneeded > 1)	//build next page links here

			{

				for ($i = 1; $i < $pagesneeded; $i++)

				{

					//echo "i is $i<br>";

					$fromrecord = $i * 30;

					$pagenumber = $i + 1;

					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=areas&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";

				}	//end for loop

			}	//end if

			

			$pageslinks = substr($pageslinks, 0, -4);

			

			echo "<div>

				  <table class=\"table table-striped\">

						  <tr>

							  <td><strong>Area</strong></td>

							  <td><strong>Remarks</strong></td>

							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>

						  </tr>";

						  

			while ($row = mysql_fetch_array($qryareas)) 

			{

				// give a name to the fields

				$id = $row['id'];

				$areaname = stripslashes($row['areaname']);

				$remarks = stripslashes($row['remarks']);



				//echo the results onscreen

				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";

				

				echo "<tr>

						  <td valign=\"top\">$areaname</td>

						  <td valign=\"top\">$remarks</td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=editarea&amp;areaid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Area\" border=\"0\" title=\"Edit this Area\"></td>

						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletearea&amp;areaid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Area\" border=\"0\" title=\"Delete this Area\"></td>

					  </tr>";

				

			}	//end while loop			

			

			echo "<tr>

					  <td colspan=\"2\">&nbsp;</td>

					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newarea\"><img src=\"../images/admin/add.gif\" alt=\"Add new Area\" border=\"0\" title=\"Add new Area\"></a></td>

				  </tr>

			</table><br>$pageslinks<br>

				";

		}

	}

	

	function NewArea()

	{

		require('connection.php');

		

		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewarea\" name=\"theform\">

				  <p>Enter the new Area details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Area Name:</td>

							<td><input type=\"text\" name=\"areaname\" maxlength=\"255\"></td>

						</tr>

						<tr>

							<td>Remarks:</td>

							<td><input type=\"text\" name=\"remarks\" maxlength=\"255\"></td>

						</tr>

					</table>

<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">

			  </form>";

	}

	

	function AddNewArea($areaname, $remarks)

	{

		require('connection.php');

		

		$qryinsert = "insert into areas (`id`, `areaname`, `remarks`)

										values ('', '$areaname', '$remarks')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		

		echo "<p>The Areas has been saved successfully.</p>";

		

		Areas(1);

		

	}

	

	function EditArea($areaid)

	{

		require('connection.php');

						

		$qry = "select * from areas where id = $areaid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$areaname = stripslashes($row["areaname"]);

		$remarks = stripslashes($row['remarks']);

								

		echo "<form method=\"post\" action=\"loggedinaction.php?action=areaedited\" name=\"theform\">

				  <p>Enter the new Area details and click Save</p>

					<table class=\"table table-striped\">

						<tr>

							<td>Area Name:</td>

							<td><input type=\"text\" name=\"areaname\" maxlength=\"255\" value=\"$areaname\"></td>

						</tr>

						<tr>

							<td>Remarks:</td>

							<td><input type=\"text\" name=\"remarks\" maxlength=\"255\" value=\"$remarks\"></td>

						</tr>

					</table>

<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"areaid\" value=\"$areaid\">

			  </form>";

	}

	

	function AreaEdited($areaid, $areaname, $remarks)

	{

		require('connection.php');

		

		$qryupdate = "update areas set `areaname` = '$areaname',

										   `remarks` = '$remarks' where `id` = $areaid";

		$qryupdateresults = mysql_query($qryupdate, $db);

		

		echo "<p>The Area has been edited successfully.</p>";

		

		Areas(1);

	}

	

	function ConfirmDeleteArea($areaid, $key)

	{

		require('connection.php');

								

		$qry = "select * from areas where `id` = $areaid";

		$qryresults = mysql_query($qry, $db);

		$row = mysql_fetch_array($qryresults);

		

		$areaname = $row["areaname"];

		

		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deletearea')";

		$qryinsertresults = mysql_query($qryinsert, $db);

		

		echo "<p>Are you sure you want to delete the Area <strong>$areaname</strong>?<br> <a href=\"loggedinaction.php?action=deletearea&amp;areaid=$areaid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						

		

	}

	

	function DeleteArea($areaid, $key)

	{

		require('connection.php');


		$qry = "select * from `key` where `action` = 'deletearea' and `key` = '$key'";

		$qryresults = mysql_query($qry, $db);						

		

		$keyrow = mysql_fetch_array($qryresults);

		

		$keyid = $keyrow["id"];						

		$count = mysql_num_rows($qryresults);

		

		if ($count == 1)

		{

			$qrydelete = "delete from `key` where `id` = $keyid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			$qrydelete = "delete from areas where `id` = $areaid";

			$qrydeleteresults = mysql_query($qrydelete, $db);

			

			echo "<p>The Areas has been deleted successfully.</p>";

			

			Areas(1);

		}

		else

		{

			echo "<p>It wont work if you enter the url just like that to delete a area...</p>";

			

			Areas(1);

		}

	}



?>