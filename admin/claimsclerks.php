<?php

	function ClaimsClerks($from)
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM claimsclerks LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM claimsclerks LIMIT $frm , 30";
		}	//end else
		
		$qrycountclaimsclerks = "select * from claimsclerks";
		$qrycount = mysql_query($qrycountclaimsclerks, $db);
		
		$qryclaimsclerks = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Claims Clerks in the database. Click <a href=\"loggedinaction.php?action=newclaimsclerk\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Claims Clerk\" title=\"Add new Claims Clerk\"></a> to add one.</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=claimsclerks&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=claimsclerks&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
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
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qryclaimsclerks)) 
			{
				// give a name to the fields
				$id = $row['id'];
				$name = $row['name'];
				$telno = $row["telno"];
				$faxno = $row["faxno"];
				$email = $row["email"];

				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$telno</td>
						  <td valign=\"top\">$faxno</td>
						  <td valign=\"top\">$email</td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editclaimsclerk&amp;claimsclerkid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Claims Clerk\" border=\"0\" title=\"Edit this Claims Clerk\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteclaimsclerk&amp;claimsclerkid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Claims Clerk\" border=\"0\" title=\"Delete this Claims Clerk\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"4\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newclaimsclerk\"><img src=\"../images/admin/add.gif\" alt=\"Add new Claims Clerk\" border=\"0\" title=\"Add new Claims Clerk\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}	
	}
	
	function NewClaimsClerk()
	{
		require('connection.php');
		
		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewclaimsclerk\" name=\"theform\">
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
							<td>Password:</td>
							<td><input type=\"text\" name=\"password\" maxlength=\"20\"></td>
						</tr>																			
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
			  </form>";
	}
	
	function AddNewClaimsClerk($name, $telno, $faxno, $email)
	{
		require('connection.php');
		
		$password = $_REQUEST["password"];
		
		$qryinsert = "insert into claimsclerks (`id`, `name`, `telno`, `faxno`, `email`, `password`)
										values ('', '$name', '$telno', '$faxno', '$email', '$password')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>The Claims Clerk has been saved successfully.</p>";
		
		ClaimsClerks(1);
		
	}
	
	function EditClaimsClerk($ccid)
	{
		require('connection.php');
						
		$qry = "select * from claimsclerks where id = $ccid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = stripslashes($row["name"]);
		$telno = stripslashes($row["telno"]);
		$faxno = stripslashes($row["faxno"]);
		$email = stripslashes($row["email"]);
		$password = $row["password"];
								
		echo "<form method=\"post\" action=\"loggedinaction.php?action=claimsclerkedited\" name=\"theform\">
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
							<td><input type=\"text\" name=\"email\" maxlength=\"50\" value=\"$email\"></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type=\"text\" name=\"password\" maxlength=\"20\" value=\"$password\"></td>
						</tr>
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"claimsclerkid\" value=\"$ccid\">
			  </form>";
	}
	
	function ClaimsClerkEdited($ccid, $name, $telno, $faxno, $email)
	{
		require('connection.php');
		
		$password = $_REQUEST["password"];
				
		$qryupdate = "update claimsclerks set `name` = '$name',
											  `telno` = '$telno',
											  `faxno` = '$faxno',
											  `email` = '$email',
											  `password` = '$password' where `id` = $ccid";
		$qryupdateresults = mysql_query($qryupdate, $db);
		
		echo "<p>The Claims Clerk has been edited successfully.</p>";
		
		ClaimsClerks(1);
		
	}
	
	function ConfirmDeleteClaimsClerk($ccid, $key)
	{
		require('connection.php');
		//include('functions.php');
		
		$qry = "select * from claimsclerks where `id` = $ccid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = $row["name"];
		
		//$key = get_rand_id(32);
		
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteclaimsclerk')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the Claims Clerk <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deleteclaimsclerk&amp;claimsclerkid=$ccid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";						
		
	}
	
	function DeleteClaimsClerk($ccid, $key)
	{
		require('connection.php');
		
		$qry = "select * from `key` where `action` = 'deleteclaimsclerk' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						
		
		$keyrow = mysql_fetch_array($qryresults);
		
		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from claimsclerks where `id` = $ccid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The Claims Clerk has been deleted successfully.</p>";
			ClaimsClerks(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a claims clerk...</p>";
			ClaimsClerks(1);
		}
	}

?>