<?php
include('admin/connection.php');
include('admin/functions.php');
include('panelbeaters.php');


if ( !isset($_GET['pbid']) || !isset($_GET['token'])) {
	die("Link is Invalid!");
}

$pbid	= $_GET['pbid'];
$token	= $_GET['token'];

$sql = " SELECT COUNT(*) as count FROM panelbeaters WHERE id='" . $pbid . "' AND profile_access_token = '" . $token . "' ";

$result = mysql_query($sql);

$pbcount = mysql_fetch_object($result);
$pbcount = $pbcount->count;

if ($pbcount == 0) {
	die("The link has expired!");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>ACI Administration Section</title>

<script type="text/javascript" src="CalendarPopup.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
	input[name="receivedday"], input[name="receivedmonth"] {padding:0;}
	input[name="receivedyear"] {padding:0;}

	.table {margin-top:30px;}
	.pad-10 {padding:10px;}

	.pad-table tr td:first-child {padding:10px;}

	.blue-bg {background:#d3d3ff;}

	@media print {
		table, input, select {font-size:12px;}
		input {width:170px;}
		@page {
			size: A4;
			margin: 0;
		}

		.no-show-in-print, a.send-email {display:none;}
		select[name="assessor"] {width:220px;}
		#anchor1 {display:none;}
	}

</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body>


<?php
	$containerClass = "container";
	
	$fluidPages = ["editclaim", "newclaim", "panelbeaters", "assessors", "addnewassessor", "confirmdeleteassessor", "assessoredited", "partssuppliers", "addnewpartsupplier", "partssupplieredited", "confirmdeletepartssupplier"];

	$pageaction = isset($_GET['action']) ? $_GET['action'] : "";

	if (in_array($pageaction, $fluidPages) || isset($fluidPageLayout)) {
		$containerClass = "container-fluid";
	}
	?>

	<div class="<?php echo $containerClass; ?>" style="padding:30px;margin-top:40px;border:1px solid #e1e1e1;">

	<?php

		if ( isset($_GET['action']) && $_GET['action']=='panelbeateredited' && !empty($_POST) ) {
			PanelbeaterEdited($pbid, $_POST['pbname'], $_POST['owner'], $_POST['costingclerk'], $_POST['pbcontactperson'], $_POST['pbadr1'], $_POST['pbadr2'], $_POST['pbadr3'], $_POST['pbadr4'], $_POST['pbcontactno'], $_POST['pbfaxno'], $_POST['pbemail'], $_POST['latitude'], $_POST['longitude']);

			
			// Send a notification email to the admin regarding the sigining up of the panelbeater.

			require_once "vendor/autoload.php";

			$mail = new PHPMailer;

			$mail->setFrom('info@panelshop.co.za');
			$mail->addAddress('info@panelshop.co.za');		// Add a recipient

			$mail->isHTML(true);							// Set email format to HTML

			$mail->Subject = 'Panelbeater is updated.';

			$body = file_get_contents('templates/panelbeater-profile-update-notification-to-admin.html');

			$details = '
					<table >
						<tr>
							<td>Name</td>
							<td><strong>' . $_POST['pbname'] . '</strong></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><strong>' . $_POST['pbemail'] . '</strong></td>
						</tr>
						<tr>
							<td>Contact Person</td>
							<td><strong>' . $_POST['pbcontactperson'] . '</strong></td>
						</tr>
						<tr>
							<td>Contact Number</td>
							<td><strong>' . $_POST['pbcontactno'] . '</strong></td>
						</tr>
					</table>
			';

			$profileLink = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/loggedinaction.php?action=editpanelbeater&panelbeaterid=' . $pbid;

			$body = str_replace(['{{PANELBEATER_NAME}}', '{{PROFILE_UPDATE_LINK}}', '{{PROFILE_DETAILS}}'], [$_POST['pbname'], $profileLink, $details], $body);

			$mail->Body    = $body;
			$mail->AltBody = strip_tags($body);

			$mail->send();

		} else {
			EditPanelbeater($pbid);
		}

	?>

	<script>
	$(document).ready(function(){
		$(".newWindow").click(function(e){
			e.preventDefault(); // this will prevent the browser to redirect to the href
			// if js is disabled nothing should change and the link will work normally
			var url = $(this).attr('href');
			var windowName = $(this).attr('id');
			window.open(url, windowName, "height=800,width=600,scrollbars=yes,menubar=yes,toolbar=yes,titlebar=yes");
		});
	});

	</script>

	</div>
</body>

</html>