<?php
	
	$cookie = $_COOKIE['loggedincookie'];
	
	setcookie("loggedincookie", "", mktime(12,0,0,1, 1, 1990));
	setcookie("loggedincookie", $cookie, time() + 3600);

	$loggedinuser = explode("-", $cookie);
	$loggedinuserid = $loggedinuser[0];


	if ($_POST)
	{
		$claimid = $_POST["claimid"];
		

		if (!file_exists("claims/$claimid"))
		{
			mkdir("claims/$claimid");
		}
		
		$desc = $_POST["desc"];
		
		require_once('connection.php');
		
		$now = time() + (7 * 3600);
		
		$now = date("Y-m-d H:i:s", $now);
		
		$files = $_FILES["fileupload"];

		for ($i=0 ; $i < count($files['name']) ; $i++ ) {

			if ( !($files["error"][$i] === UPLOAD_ERR_OK) ) {
				continue;
			}
			
			$descriptionText = $desc[$i];

			$fileName		= $files["name"][$i];
			$fileSize		= $files["size"][$i];
			$fileTmpName	= $files["tmp_name"][$i];


			$qryinsert = "insert into `files` (`userid`, `claimid`, `filename`, `description`, `filesize`, `datetime`) values ('$loggedinuserid', $claimid, '" . $fileName . "', '$descriptionText', $fileSize, '$now')";
			$qryinsertresults = mysql_query($qryinsert, $db);
			
			$qrygetnewid = "select max(`id`) as `newid` from `files`";
			$qrygetnewidresults = mysql_query($qrygetnewid, $db);
			
			$newidrow = mysql_fetch_array($qrygetnewidresults);
			
			$newid = $newidrow["newid"];
			
			@move_uploaded_file ($fileTmpName, "claims/$claimid/$newid-" . $fileName);

		}
		
		
		//header("Location: loggedinaction.php?action=editclaim");
		
		$usertype = $_POST["usertype"];
		
		if ($usertype == "user")
		{
			$usertype = "loggedinaction";
			$stepto = 6;
			$action = "editclaim";
		}
		
		if ($usertype == "assessor")
		{
			$usertype = "asloggedinaction";
			$stepto = 5;
			$action = "editclaim";
		}
		
		if ($usertype == "claimsclerk")
		{
			$usertype = "ccloggedinaction";
			$stepto = 6; 
			$action = "cceditclaim";
		}
		
		echo "<html>
				<head>
					<title></title>
				</head>
				
				<body onload=\"document.topform.submit();\">
		
		
		<form method=\"POST\" action=\"$usertype.php?action=$action\" name=\"topform\">

						<input type=\"hidden\" name=\"stepto\" value=\"$stepto\" /> <input type=\"hidden\" name=\"claimid\" value=\"$claimid\">									<input type=\"hidden\" name=\"fromstep\" value=\"100\" /> 		 



																		 </form>
																		 
				 </body>
			 </html>";
	}

?>