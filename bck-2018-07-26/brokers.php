<?php
 
	function Brokers($from)
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM brokers LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM brokers LIMIT $frm , 30";
		}	//end else
		
		$qrycountbrokers = "select * from brokers";
		$qrycount = mysql_query($qrycountbrokers, $db);
		
		$qrybrokers = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no Brokers in the database. Click <a href=\"loggedinaction.php?action=newbroker\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new Broker\" title=\"Add new Broker\"></a> to add one.</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=brokers&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=brokers&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
				}	//end for loop
			}	//end if
			
			$pageslinks = substr($pageslinks, 0, -4);
			
			echo "<div>
				  <table class=\"table table-striped\">
						  <tr>
							  <td><strong>Name</strong></td>
							  <td><strong>Contact Person</strong></td>
							  <td><strong>Telephone Number</strong></td>
							  <td><strong>Fax Number</strong></td>
							  <td><strong>Email Address</strong></td>
							  <td><strong>Cellphone Number</strong></td>
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qrybrokers)) 
			{
				// give a name to the fields
				$id = $row['id'];
				$name = $row['name'];
				$contact = $row["contactperson"];
				$telno = $row["telephone"];
				$faxno = $row["faxno"];
				$email = $row["email"];
				$cellno = $row["cellno"];

				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td valign=\"top\">$name</td>
						  <td valign=\"top\">$contact</td>
						  <td valign=\"top\">$telno</td>
						  <td valign=\"top\">$faxno</td>
						  <td valign=\"top\">$email</td>
						  <td valign=\"top\">$cellno</td>";
						  
										  
				echo "		  
						  <td align=\"center\"><a href=\"loggedinaction.php?action=editbroker&amp;brokerid=$id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this Broker\" border=\"0\" title=\"Edit this Broker\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeletebroker&amp;brokerid=$id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this Broker\" border=\"0\" title=\"Delete this Broker\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td colspan=\"6\">&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newbroker\"><img src=\"../images/admin/add.gif\" alt=\"Add new Broker\" border=\"0\" title=\"Add new Broker\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}	
	}
	
	function NewBroker()
	{
		require('connection.php');
		
		echo "<form method=\"post\" action=\"loggedinaction.php?action=addnewbroker\" name=\"theform\">
				  <p>Enter the new Broker details and click Save</p>
					<table class=\"table table-striped\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\"></td>
						</tr>
						<tr>
							<td>Contact Person:</td>
							<td><input type=\"text\" name=\"contactperson\" maxlength=\"50\"></td>
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
							<td><input type=\"text\" name=\"cellno\" maxlength=\"50\"></td>
						</tr>																			
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\">
			  </form>";
	}
	
	function AddNewBroker($name, $contact, $telno, $faxno, $email, $cellno)
	{
		require('connection.php');		
		
		$qryinsert = "insert into brokers (`id`, `name`, `contactperson`, `telephone`, `faxno`, `email`, `cellno`)
										values ('', '$name', '$contact', '$telno', '$faxno', '$email', '$cellno')";
		echo $qryinsert;
		$qryinsertresults = mysql_query($qryinsert, $db);
				
		echo "<p>The Broker has been saved successfully.</p>";
		
		Brokers(1);
		
	}
	
	function EditBroker($brokerid)
	{
		require('connection.php');
						
		$qry = "select * from brokers where id = $brokerid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = stripslashes($row["name"]);
		$contact = stripslashes($row["contactperson"]);
		$telno = stripslashes($row["telephone"]);
		$faxno = stripslashes($row["faxno"]);
		$email = stripslashes($row["email"]);
		$cellno = stripslashes($row["cellno"]);
								
		echo "<form method=\"post\" action=\"loggedinaction.php?action=brokeredited\" name=\"theform\">
				  <p>Enter the new Broker details and click Save</p>
					<table class=\"table table-striped\">
						<tr>
							<td>Name:</td>
							<td><input type=\"text\" name=\"name\" maxlength=\"50\" value=\"$name\"></td>
						</tr>
						<tr>
							<td>Contact Person:</td>
							<td><input type=\"text\" name=\"contact\" maxlength=\"50\" value=\"$contact\"></td>
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
							<td>Cellphone Number</td>
							<td><input type=\"text\" name=\"cellno\" maxlength=\"255\" value=\"$cellno\"></td>
						</tr>
					</table>
<br><input type=\"submit\" value=\"Save\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"brokerid\" value=\"$brokerid\">
			  </form>";
	}
	
	function BrokerEdited($brokerid, $name, $contact, $telno, $faxno, $email, $cellno)
	{
		require('connection.php');
				
		$qryupdate = "update brokers set `name` = '$name',											  
											  `contactperson` = '$contact',
											  `telephone` = '$telno',
											  `faxno` = '$faxno',
											  `email` = '$email',
											  `cellno` = '$cellno' where `id` = $brokerid";
		$qryupdateresults = mysql_query($qryupdate, $db);
		
		echo "<p>The Broker has been edited successfully.</p>";
		
		Brokers(1);
		
	}
	
	function ConfirmDeleteBroker($brokerid, $key)
	{
		require('connection.php');
		//include('functions.php');
		
		$qry = "select * from brokers where `id` = $brokerid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$name = $row["name"];
		
		//$key = get_rand_id(32);
		
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deletebroker')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the Broker <strong>$name</strong>?<br> <a href=\"loggedinaction.php?action=deletebroker&amp;brokerid=$brokerid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";
		
	}
	
	function DeleteBroker($brokerid, $key)
	{
		require('connection.php');
		
		$qry = "select * from `key` where `action` = 'deletebroker' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						
		
		$keyrow = mysql_fetch_array($qryresults);
		
		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from brokers where `id` = $brokerid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The Broker has been deleted successfully.</p>";
			Brokers(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a broker...</p>";
			Brokers(1);
		}
	}

?>