<?php

	function Users($from)
	{
		require('connection.php');
		
		if ($from == "")
		{
			$from = 1;
		}
		
			//display first 30
		if ($from < 1)
		{
			$qry = "SELECT * FROM users where admin = 0 order by username LIMIT 0 , 30";
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
			
			$qry = "SELECT * FROM users where admin = 0 order by username LIMIT $frm , 30";
		}	//end else
		
		$qrycountusers = "select * from users where admin = 0";
		$qrycount = mysql_query($qrycountusers, $db);
		
		$qryusers = mysql_query($qry, $db);
		$count = mysql_num_rows($qrycount);
		
		if ($count == 0)
		{
			echo "<p>There are no regular Users in the database. Click <a href=\"loggedinaction.php?action=newuser\"><img src=\"../images/admin/add.gif\" border=\"0\" alt=\"Add new User\" title=\"Add new User\"></a> to add one.</p>";
		}
		else
		{
			
			$pagesneeded = $count / 30;
			$pagesneeded = ceil($pagesneeded);
			$pageslinks = "<a href=\"loggedinaction.php?action=users&amp;from=1\">Page 1</a> || ";
			
			//echo "pages that will be needed is $count today";
			
			if ($pagesneeded > 1)	//build next page links here
			{
				for ($i = 1; $i < $pagesneeded; $i++)
				{
					//echo "i is $i<br>";
					$fromrecord = $i * 30;
					$pagenumber = $i + 1;
					$pageslinks = $pageslinks . "<a href=\"loggedinaction.php?action=users&amp;from=" . $fromrecord . "\">Page $pagenumber</a> || ";
				}	//end for loop
			}	//end if
			
			$pageslinks = substr($pageslinks, 0, -4);
			
			echo "
				  <table class=\"table table-striped\">
						  <tr>
							  <td><strong>User</strong></td>
							  <td colspan=\"2\" align=\"center\"><strong>Actions</strong></td>
						  </tr>";
						  
			while ($row = mysql_fetch_array($qryusers)) 
			{
				// give a name to the fields
				$user_id = $row['id'];
				$username = stripslashes($row['username']);

				//echo the results onscreen
				//echo "The ID is $users_id and the Username is $users_username and the Password is $users_password <br><br><br>";
				
				echo "<tr>
						  <td>$username</td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=edituser&amp;userid=$user_id\"><img src=\"../images/admin/edit.gif\" alt=\"Edit this User\" border=\"0\" title=\"Edit this User\"></td>
						  <td align=\"center\"><a href=\"loggedinaction.php?action=confirmdeleteuser&amp;userid=$user_id\"><img src=\"../images/admin/delete.gif\" alt=\"Delete this User\" border=\"0\" title=\"Delete this User\"></td>
					  </tr>";
				
			}	//end while loop			
			
			echo "<tr>
					  <td>&nbsp;</td>
					  <td colspan=\"2\" align=\"center\"><a href=\"loggedinaction.php?action=newuser\"><img src=\"../images/admin/add.gif\" alt=\"Add new User\" border=\"0\" title=\"Add new User\"></a></td>
				  </tr>
			</table><br>$pageslinks<br>
				";
		}
	}
	
	function NewUser()
	{
		require('connection.php');
		
		echo "<p>	<form action=\"loggedinaction.php?action=addnewuser\" method=\"post\">
						<table class=\"table table-striped\">
							<tr>
								<td>Username:</td>
								<td><input type=\"text\" name=\"username\" maxlength=\"20\"></td>
							</tr>
							<tr>
								<td>Password:</td>
								<td><input type=\"password\" name=\"password\"></td>
							</tr>
							<tr>
								<td>Retype Password:</td>
								<td><input type=\"password\" name=\"retypepassword\"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type=\"submit\" value=\"Save User\"> <input type=\"reset\" value=\"Clear\">
							</tr>
						</table>
					</form></p>";
	}
	
	function AddNewUser($username, $password, $retypepassword)
	{
		require('connection.php');
		
		$checkusername = "select count(id) as counted from users where username = '$username'";
		$checkusernameresults = mysql_query($checkusername, $db);
		$checkusernamerow = mysql_fetch_array($checkusernameresults);
		
		$counted = $checkusernamerow["counted"];
		
		if ($counted == 0)
		{
			if (strlen($password) == 0)
			{
				echo "<p>You must enter a password for the user. <a href=\"javascript:history.go(-1);\">Go Back</a></p>";
			}
			else
			{									
				if ($password == $retypepassword)
				{
					$pwd = md5($password);
					$qryinsert = "insert into users (`id`, `username`, `password`) values ('', '$username', '$pwd');";
					$qryinsertresults = mysql_query($qryinsert, $db);
					
					echo "<p>The user was stored successfully. <a href=\"loggedinaction.php?action=users&amp;from=1\">Go back to Users</a></p>";
				}
				else
				{
					echo "<p>The passwords do not match. <a href=\"javascript:history.go(-1);\">Go Back</a></p>";
				}
			}
		}
		else
		{
			echo "<p>The username: <strong>$username</strong> is already taken. <a href=\"javascript:history.go(-1);\">Go Back</a></p>";
		}	
	}
	
	function EditUser($userid)
	{
		require('connection.php');
							
		$qry = "select * from users where `id` = $userid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$username = stripslashes($row["username"]);
		
		echo "<p>
					<form action=\"loggedinaction.php?action=useredited\" method=\"post\">
						<table class=\"table table-striped\">
							<tr>
								<td>Username:</td>
								<td><input type=\"text\" name=\"username\" value=\"$username\"></td>
							</tr>
							<tr>
								<td>Password:</td>
								<td><a href=\"loggedinaction.php?action=edituserpassword&amp;userid=$userid\">Click here to edit this users password</a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type=\"submit\" value=\"Save Changes\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"userid\" value=\"$userid\"></td>
							</tr>
						</table>
					</form></p>";
	}
	
	function UserEdited($userid, $username)
	{
		require('connection.php');
				
		$qry = "update users set username = '$username' where `id` = $userid";
		$qryresults = mysql_query($qry, $db);
		
		echo "<p>The username was successfully edited.</p>";
		
		Users(1);
	}
	
	function EditUserPassword($userid)
	{
		require('connection.php');
		
		echo "<p>
					<form action=\"loggedinaction.php?action=saveuserpassword\" method=\"post\">
						<table class=\"table table-striped\">
							<tr>
								<td>Enter new password:</td>
								<td><input type=\"password\" name=\"password\"></td>
							</tr>
							<tr>
								<td>Retype new password:</td>
								<td><input type=\"password\" name=\"retypepassword\"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type=\"submit\" value=\"Save new Password\"> <input type=\"reset\" value=\"Clear\"> <input type=\"hidden\" name=\"userid\" value=\"$userid\"></td>
							</tr>
						</table>
					</form></p>";
	}
	
	function SaveUserPassword($userid, $password, $retypepassword)
	{
		require('connection.php');
				
		if ($password == $retypepassword)
		{
			$pwd = md5($password);
			$qry = "update users set password = '$pwd' where `id` = $userid";
			$qryresults = mysql_query($qry, $db);
			
			echo "<p>The password has been changed successfully.</p>";
			
			Users(1);
		}
		else
		{
			echo "<p>The passwords do not match. <a href=\"javascript:history.go(-1)\">Go Back</a></p>";
		}
	}
	
	function ConfirmDeleteUser($userid, $key)
	{
		require('connection.php');
		//include('functions.php');
							
		$qry = "select * from users where `id` = $userid";
		$qryresults = mysql_query($qry, $db);
		$row = mysql_fetch_array($qryresults);
		
		$username = stripslashes($row["username"]);
		
		//s$key = get_rand_id(32);
	
		$qryinsert = "insert into `key` (`id`, `key`, `action`) values ('', '$key', 'deleteuser')";
		$qryinsertresults = mysql_query($qryinsert, $db);
		
		echo "<p>Are you sure you want to delete the User <strong>$username</strong>?<br> <a href=\"loggedinaction.php?action=deleteuser&amp;userid=$userid&amp;key=$key\">Yes</a> | <a href=\"javascript:history.go(-1)\">No</a></p>";
	}
	
	function DeleteUser($userid, $key)
	{
		require('connection.php');
		
		$qry = "select * from `key` where `action` = 'deleteuser' and `key` = '$key'";
		$qryresults = mysql_query($qry, $db);						
		
		$keyrow = mysql_fetch_array($qryresults);
		
		$keyid = $keyrow["id"];						
		$count = mysql_num_rows($qryresults);
		
		if ($count == 1)
		{
			$qrydelete = "delete from `key` where `id` = $keyid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			$qrydelete = "delete from users where `id` = $userid";
			$qrydeleteresults = mysql_query($qrydelete, $db);
			
			echo "<p>The User has been deleted successfully.</p>";
			
			Users(1);
		}
		else
		{
			echo "<p>It wont work if you enter the url just like that to delete a user...</p>";
			
			Users(1);
		}
	}

?>