<?php



$cookie = $_COOKIE['loggedincookie'];

	

setcookie("loggedincookie", "", mktime(12,0,0,1, 1, 1990));

setcookie("loggedincookie", $cookie, time() + 3600);



$loggedinuser = explode("-", $cookie);



$loggedinuserid = $loggedinuser[0];

$username = $loggedinuser[1];

$password = $loggedinuser[2];



require_once('connection.php');


if ( isset($_POST['action']) && $_POST['action']=='save-email-report') {
	
	$to_address = $_POST['to_address'];
	$claimid	= $_POST['claimid'];
	$type		= $_POST['type'];
	
	$now = time() + (7 * 3600);

	$now = date("Y-m-d H:i:00", $now);

	$qryinsertreport = "insert into `report` (`id`, `claimid`, `reportdate`, `description`, `userid`) values ('', $claimid, '$now', '$type Details sent to $to_address', $loggedinuserid)";

	$qryinsertreportresults = mysql_query($qryinsertreport, $db);
	
	die;

}
