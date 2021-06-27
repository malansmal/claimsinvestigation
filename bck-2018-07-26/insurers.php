<?php
 
 	

	function Insurers($from)
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM insurers LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM insurers LIMIT $frm , 30";
		}	//end else
		
		$qrycountinsurers = "select * from insurers";
		$qrycount = mysql_query($qrycountinsurers, $db);
		
		$qryinsurers = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Insurers in the database. Click <a href=\"loggedinaction.php?action=newinsurer\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Insurer\" title=\"Add new Insurer\"></a> to add one.</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=insurers&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=insurers&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
				}	//end for loop
			}	//end if
			
			$pageslinks = substr($pageslinks, 0, -4);
			
			echo "<div>
				  <table class=\"table table-striped\" cellspacing=\"0\" border=\"1\">
						  <tr>
							  <td><strong>Name</strong></td>
							  <td><strong>VAT Number</strong></td>
							  <td><strong>Contact Number</strong></td>
							  <td><strong>Email Address</strong></td>
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qryinsurers)) 
			{
				// give a name to the fields
				$id = $row['id'];
				$name = $row['name'];
				$contactno = $row["contactno"];
				$vatno = $row["vatno"];
				$email = $row["emailaddress"];

				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$vatno</td>
						  <td valign=\"top\">$contactno</td>
						  <td valign=\"top\">$email</td>";
	
						  
				echo "		  
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editinsurer&amp;insurerid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Insurer\" border=\"0\" title=\"Edit this Insurer\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteinsurer&amp;insurerid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Insurer\" border=\"0\" title=\"Delete this Insurer\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"4\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newinsurer\"><img src=\"../images/admin/add.gif\" alt=\"Add new Insurer\" border=\"0\" title=\"Add new Insurer\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}	
	}
	
	function NewInsurer()
	{
		require('connection.php');
		
		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewinsurer\" enctype=\"multipart/form-data\" name=\"theform\">
				  <p>Enter the new Insurer details and click Save</p>
					<table class=\"table table-striped\" cellspacing=\"1\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>VAT Number:</td>
							<td><input type=\"text\" name=\"vatno\" maxlength=\"10\"></td>
						</tr>
						<tr>
							<td>Contact Number:</td>
							<td><input type=\"text\" name=\"contactno\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><input type=\"text\" name=\"email\" maxlength=\"255\"></td>
						</tr>																							
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
			  </form>";
	}
	
	function AddNewInsurer($name, $vatno, $contactno, $email)
	{
		require('connection.php');
		
		$email = $_REQUEST["email"];
		
		$qryinsert = "insert into `insurers` (`id`, `name`, `vatno`, `contactno`, `emailaddress`)
										values ('', '$name', '$vatno', '$contactno', '$email')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>The Administrator has been saved successfully.</p>";
		
		Insurers(1);
		
	}
	
	function EditInsurer($insurerid)
	{
		require('connection.php');
						
		$qry = "select * from `insurers` where id = $insurerid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = stripslashes($row["name"]);
		$vatno = stripslashes($row["vatno"]);
		$contactno = stripslashes($row["contactno"]);
		$email = stripslashes($row["emailaddress"]);
								
		echo "<form method=\"post\" action=\"loggedinaction.php?action=insureredited\" enctype=\"multipart/form-data\" name=\"theform\">
				  <p>Enter the new Insurer details and click Save</p>
					<table class=\"table table-striped\" cellspacing=\"1\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>
						</tr>
						<tr>
							<td>VAT Number:</td>
							<td><input type=\"text\" name=\"vatno\" maxlength=\"50\" value=\"$vatno\"></td>
						</tr>
						<tr>
							<td>Contact Number:</td>
							<td><input type=\"text\" name=\"contactno\" maxlength=\"50\" value=\"$contactno\"></td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$email\"></td>
						</tr>
						
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"insurerid\" value=\"$insurerid\">
			  </form>";
	}
	
	function InsurerEdited($insurerid, $name, $vatno, $contactno, $email)
	{
		require('connection.php');
		
		$qryupdate = "update insurers set `name` = '$name',
											  `vatno` = '$vatno',
											  `contactno` = '$contactno',
											  `emailaddress` = '$email' where `id` = $insurerid";
		$qryupdateresults = mysql_query($qryupdate, $db);
		
		
		
		echo "<p>The Insurer has been edited successfully.</p>";
		
		Insurers(1);
		
	}
	
	function ConfirmDeleteInsurer($insurerid, $key)
	{
		require('connection.php');
		//include('functions.php');
		
		$qry = "select * from insurers where `id` = $adminid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = $row["name"];
		
		//$key = get_rand_id(32);
		
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteinsurer')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the Insurer <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deleteinsurer&amp;insurerid=$insurerid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";
		
	}
	
	function DeleteInsurer($insurerid, $key)
	{
		require('connection.php');
		
		$qry = "select * from `key` where `action` = 'deleteinsurer' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						
		
		$keyrow = mysql_fetch_array($qryresults);
		
		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from insurers where `id` = $insurerid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The Insurer has been deleted successfully.</p>";
			Insurers(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a administrator...</p>";
			Insurers(1);
		}
	}

?>