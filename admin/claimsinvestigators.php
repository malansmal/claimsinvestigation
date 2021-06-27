<?php

	function ClaimsInvestigators($from)
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM claimsinvestigators LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM claimsinvestigators LIMIT $frm , 30";
		}	//end else
		
		$qrycountclaimsinvestigators = "select * from claimsinvestigators";
		$qrycount = mysql_query($qrycountclaimsinvestigators, $db);
		
		$qryclaimsinvestigators = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Claims Investigators in the database. Click <a href=\"loggedinaction.php?action=newclaimsinvestigator\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Claims Investigator\" title=\"Add new Claims Investigator\"></a> to add one.</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=claimsinvestigators&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=claimsinvestigators&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
				}	//end for loop
			}	//end if
			
			$pageslinks = substr($pageslinks, 0, -4);
			
			echo "<div>
				  <table class=\"table table-striped\">
						  <tr>
							  <td><strong>Name</strong></td>
							  <td><strong>Telephone No</strong></td>
							  <td><strong>Fax No</strong></td>
							  <td><strong>Email Address</strong></td>
							  <td><strong>Cellphone No</strong></td>
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qryclaimsinvestigators)) 
			{
				// give a name to the fields
				$id = $row['id'];
				$name = $row['name'];
				$telno = $row["telno"];
				$faxno = $row["faxno"];
				$email = $row["email"];
				$cellno = $row["cellno"];

				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$telno</td>
						  <td valign=\"top\">$faxno</td>
						  <td valign=\"top\">$email</td>
						  <td valign=\"top\">$cellno</td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaimsinvestigator&amp;claimsinvestigatorid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claims Investigator\" border=\"0\" title=\"Edit this Claims Investigator\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteclaimsinvestigator&amp;claimsinvestigatorid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Claims Investigator\" border=\"0\" title=\"Delete this Claims Investigator\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"5\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newclaimsinvestigator\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claims Investigator\" border=\"0\" title=\"Add new Claims Investigator\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}	
	}
	
	function NewClaimsInvestigator()
	{
		require('connection.php');
		
		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewclaimsinvestigator\" name=\"theform\">
				  <p>Enter the new Claims Clerk details and click Save</p>
					<table class=\"table table-striped\" cellspacing=\"1\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>Telephone Number:</td>
							<td><input type=\"text\" name=\"telno\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>Fax Number:</td>
							<td><input type=\"text\" name=\"faxno\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><input type=\"text\" name=\"email\" maxlength=\"255\"></td>
						</tr>
						<tr>
							<td>Cellphone Number:</td>
							<td><input type=\"text\" name=\"cellno\" maxlength=\"20\"></td>
						</tr>																			
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
			  </form>";
	}
	
	function AddNewClaimsInvestigator($name, $telno, $faxno, $email, $cellno)
	{
		require('connection.php');
		
		$qryinsert = "insert into claimsinvestigators (`id`, `name`, `telno`, `faxno`, `email`, `cellno`)
										values ('', '$name', '$telno', '$faxno', '$email', '$cellno')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>The Claims Investigator has been saved successfully.</p>";
		
		ClaimsInvestigators(1);
		
	}
	
	function EditClaimsInvestigator($ciid)
	{
		require('connection.php');
						
		$qry = "select * from claimsinvestigators where id = $ciid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = stripslashes($row["name"]);
		$telno = stripslashes($row["telno"]);
		$faxno = stripslashes($row["faxno"]);
		$email = stripslashes($row["email"]);
		$cellno = stripslashes($row["cellno"]);
								
		echo "<form method=\"post\" action=\"loggedinaction.php?action=claimsinvestigatoredited\" name=\"theform\">
				  <p>Enter the new Claims Clerk details and click Save</p>
					<table class=\"table table-striped\" cellspacing=\"1\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>
						</tr>
						<tr>
							<td>Telephone Number:</td>
							<td><input type=\"text\" name=\"telno\" maxlength=\"50\" value=\"$telno\"></td>
						</tr>
						<tr>
							<td>Fax Number:</td>
							<td><input type=\"text\" name=\"faxno\" maxlength=\"50\" value=\"$faxno\"></td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><input type=\"text\" name=\"email\" maxlength=\"255\" value=\"$email\"></td>
						</tr>
						<tr>
							<td>Cellphone Number:</td>
							<td><input type=\"text\" name=\"cellno\" maxlength=\"20\" value=\"$cellno\"></td>
						</tr>
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimsinvestigatorid\" value=\"$ciid\">
			  </form>";
	}
	
	function ClaimsInvestigatorEdited($ciid, $name, $telno, $faxno, $email, $cellno)
	{
		require('connection.php');
				
		$qryupdate = "update claimsinvestigators set `name` = '$name',
											  `telno` = '$telno',
											  `faxno` = '$faxno',
											  `email` = '$email',
											  `cellno` = '$cellno' where `id` = $ciid";
		$qryupdateresults = mysql_query($qryupdate, $db);
		
		echo "<p>The Claims Investigator has been edited successfully.</p>";
		
		ClaimsInvestigators(1);
		
	}
	
	function ConfirmDeleteClaimsInvestigator($ciid, $key)
	{
		require('connection.php');
		//include('functions.php');
		
		$qry = "select * from claimsinvestigators where `id` = $ciid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = $row["name"];
		
		//$key = get_rand_id(32);
		
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteclaimsinvestigator')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the Claims Investigator <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deleteclaimsinvestigator&amp;claimsinvestigatorid=$ciid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						
		
	}
	
	function DeleteClaimsInvestigator($ciid, $key)
	{
		require('connection.php');
		
		$qry = "select * from `key` where `action` = 'deleteclaimsinvestigator' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						
		
		$keyrow = mysql_fetch_array($qryresults);
		
		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from claimsinvestigators where `id` = $ciid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The Claims Investigator has been deleted successfully.</p>";
			ClaimsInvestigators(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a claims investigator...</p>";
			ClaimsInvestigators(1);
		}
	}

?>