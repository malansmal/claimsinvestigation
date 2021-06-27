<?php

	if ($_POST)
	{
		$action = $_POST["action"];
		
		require_once("connection.php");
		
		if ($action == "confirmdeletefile")
		{
			$fileid = $_POST["fileid"];
			
			$qryfile = "select * from `files` where `id` = $fileid";
			$qryfileresults = mysql_query($qryfile, $db);
			
			$filerow = mysql_fetch_array($qryfileresults);
			
			$filename = $filerow["filename"];
			
			echo "<p>Are you sure you want to delete the file: <strong>$filename</strong>?</p>
			
			<form action=\"deletefile.php\" method=\"post\" name=\"deleteitnowplease\">
			
				<input type=\"submit\" value=\"Yes\"> <input type=\"button\" value=\"No\" onclick=\"javascript:history.go(-1);\">
				
				<input type=\"hidden\" value=\"deletefile\" name=\"action\"> <input type=\"hidden\" name=\"fileid\" value=\"$fileid\">
			
			</form>";
			
		}
		
		if ($action == "deletefile")
		{
			$fileid = $_POST["fileid"];
			
			$qryfile = "select * from `files` where `id` = $fileid";
			$qryfileresults = mysql_query($qryfile, $db);
			
			$filerow = mysql_fetch_array($qryfileresults);
			
			$filename = $filerow["filename"];
			$claimid = $filerow["claimid"];
			
			unlink("claims/$claimid/$fileid-$filename");
			
			$qrydelete = "delete from `files` where `id` = $fileid";
			$qrydeleteresults = mysql_query($qrydelete, $db);			
			
			echo "<html>
				<head>
					<title></title>
				</head>
				
				<body onload=\"document.topform.submit();\">
		
		
		<form method=\"POST\" action=\"loggedinaction.php?action=editclaim\" name=\"topform\">

						<input type=\"hidden\" name=\"stepto\" value=\"6\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"100\" /> 		 



																		 </form>
																		 
				 </body>
			 </html>";
		}
		
	}

?>