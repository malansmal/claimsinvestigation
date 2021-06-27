 <?php
$servername = "localhost";
$username = "claims_admin";
$password = "admin";
$dbname = "claims_aci";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$sqlarea = "SELECT areas.id, areas.areaname FROM areas INNER JOIN partsupplier_area ON areas.id = partsupplier_area.areaid GROUP BY areas.areaname";
$sqlarea = "SELECT areas.id, areas.areaname FROM areas GROUP BY areas.areaname";
$resultarea = $conn->query($sqlarea);

$sqlmake = "SELECT vehiclemake.id, vehiclemake.vehiclemake FROM vehiclemake ORDER BY vehiclemake.vehiclemake";
$resultmake = $conn->query($sqlmake);

 
 //This is the email parts
 
 $submit = $_POST['submit'];
 $human = $_POST['human'];
 $bot = $_POST['url'];
 $name = $_POST['name'];
 $contact = $_POST['contact'];
 $email = $_POST['email'];
 $make = $_POST['make'];
 $model = $_POST['model'];
 $year = $_POST['year'];
 $vin = $_POST['vin'];
 $area = $_POST['area'];
 $parts = $_POST['parts'];
 $foodnotes = nl2br ($parts);
 
 
if(isset($submit) && $human == 10 && empty($bot)){
	
	$sqlmail = "SELECT partsupplier_area.areaid, areas.areaname, partsupplier_area.partssupplierid, partssuppliers.name, partssuppliers.contactname, partssuppliers.telno, partssuppliers.faxno, partssuppliers.email FROM (partsupplier_area INNER JOIN partssuppliers ON partsupplier_area.partssupplierid = partssuppliers.id) INNER JOIN areas ON partsupplier_area.areaid = areas.id WHERE partsupplier_area.areaid = $area GROUP BY partsupplier_area.areaid, areas.areaname, partsupplier_area.partssupplierid, partssuppliers.name, partssuppliers.contactname, partssuppliers.telno, partssuppliers.faxno, partssuppliers.email";
	$resultmail = $conn->query($sqlmail);

	if ($resultmail->num_rows > 0) {
		while($rowmail = $resultmail->fetch_assoc()) { 
		 
				$supname = $rowmail['name'];
				$areamail = $rowmail['areaname']; 
				$to = $rowmail['email'];
				$subject = "Please quote on the following parts needed";

				$message = "<html>
				<head>
				<title>Part Finder</title>
				</head>
				<body>
				<p>Dear $supname</p>
				<p>Please quote on the following parts as requested via www.partfinders.co.za. Please reply direct to buyer. (Indicate New, Used or Alternate Available)</p>
				<p>
				<b>Name:</b> $name<br/>
				<b>Contact No:</b> $contact<br/>
				<b>Email:</b> $email<br/>
				<b>Make of Vehicle:</b> $make<br/>
				<b>Model:</b> $model<br/>
				<b>Year:</b> $year<br/>
				<b>VIN (If available):</b> $vin<br/>
				<b>Area Needed:</b> $areamail<br/>
				<b>Parts Required:</b><br/>
				$foodnotes
				</p>
				<br/><br/><br/><br/>
				<img src='http://www.partfinders.co.za/geo_templates/my_templates/external/images/logo.png' width='326' height='43'>
				</body>
				</html>
				";

				$from_email = 'webmaster@partfinders.co.za'; //from mail, sender email addrress 
				$to = $rowmail['email'];
			    $recipient_email = $to; //recipient email addrress 
			      
			    //Load POST data from HTML form 
			    $sender_name    = $supname; //sender name 
			    $reply_to_email = 'webmaster@partfinders.co.za'; //sender email, it will be used in "reply-to" header 
			    $subject = "Please quote on the following parts needed";
			    $message  = $message; //body of the email 
			      
			      
			    //Get uploaded file data using $_FILES array 
			    $tmp_name    = $_FILES['attach']['tmp_name']; // get the temporary file name of the file on the server 

			    $name        = $_FILES['attach']['name'];  // get the name of the file 

			    $size        = $_FILES['attach']['size'];  // get size of the file for size validation 
			    $type        = $_FILES['attach']['type'];  // get type of the file 
			    $error       = $_FILES['attach']['error']; // get the error (if any) 
			  
			    //validate form field for attaching the file 
			    if($file_error > 0) 
			    { 
			        die('Upload error or No files uploaded'); 
			    } 
			  
			    //read from the uploaded file & base64_encode content 
			    $handle = fopen($tmp_name, "r");  // set the file handle only for reading the file 
			    $content = fread($handle, $size); // reading the file 
			    fclose($handle);                  // close upon completion 
			  
			    $encoded_content = chunk_split(base64_encode($content)); 
			  
			    $boundary = md5("random"); // define boundary with a md5 hashed value 
			  	
			    //header 
			    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
			    $headers .= "From:".$from_email."\r\n"; // Sender Email
			    $headers .= "Reply-To: ".$reply_to_email."\r\n"; // Email addrress to reach back 

			    $headers .= "Content-Type: multipart/mixed;\r\n"; // Defining Content-Type
			    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary 
			          
			    //plain text
			    $body = "--$boundary\r\n"; 
			    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n"; 
			    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";  
			    $body .= chunk_split(base64_encode($message));  
			          
			    //attachment 
			    $body .= "--$boundary\r\n"; 
			    $body .="Content-Type: $file_type; name=".$file_name."\r\n"; 
			    $body .="Content-Disposition: attachment; filename=".$file_name."\r\n"; 
			    $body .="Content-Transfer-Encoding: base64\r\n"; 
			    $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";  
			    $body .= $encoded_content; // Attaching the encoded file with email 
			      
			    $sentMailResult = mail($recipient_email, $subject, $body, $headers); 

				if ($sentMailResult) {
					$errorMessage =	'<strong>Success!</strong> Your request was send to all parts suppliers in your area.';
   				}
				else {

					$errorMessage = error_get_last()['message'];
					die("Sorry but the email could not be sent. 
                    Please go back and try again!"); 
				}
		 }
	}
}
if($human != 10 && $human != '' ){
$errorMessage =	'<strong>Oops!</strong> Wrong Answer on spam filter.';	
}
?> 
 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Part Finder</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style type="text/css">
	#main-box {width:35%;}
	@media(max-width:460px) {
		#main-box {width:100%;}
	}
  </style>
</head>
<body>

<div class="container" id="main-box">
 <div class="well">
 <div class="row">
 <div class="col-md-12">
 <a href="http://partfinders.co.za"><img src="images/Partfinders.png" class="img-responsive" alt="Part Finders"></a> <br />
 </div>
  <div class="col-md-12">
  <div class="alert alert-info">
  <p>If you are looking for parts you can not find on the site, Please complete the form below with the parts you need and we will forward your request to our all parts suppliers in your area. Part Suppliers will reply direct to you, via phone or email with availability and prices.</p>
  </div><!-- /alert-info -->
  </div>
  </div><!-- /row -->
  </div><!-- /well -->
  <hr/>
  <?php if(isset($errorMessage)){ ?>
  <div class="alert alert-success alert-dismissable">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <?php echo $errorMessage; ?>
</div><!-- /alert-success -->
  <?php } //errorMessage  ?>
  <div class="container" style="width:100%;" >
<div class="row">
<div class="col-md-12">
<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="index.php">
<div class="form-group">
<label for="name"><i class="glyphicon glyphicon-asterisk text-danger"></i>Name:</label>
<input type="text" class="form-control" id="name" name="name" required>
</div>
<div class="form-group">
<label for="contact"><i class="glyphicon glyphicon-asterisk text-danger"></i>Contact No:</label>
<input type="text" class="form-control" id="contact" name="contact" required>
</div>
<div class="form-group">
    <label for="email"><i class="glyphicon glyphicon-asterisk text-danger"></i>Email address:</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
<div class="form-group">
<label for="make"><i class="glyphicon glyphicon-asterisk text-danger"></i>Make of Vehicle:</label>
<select class="form-control" id="make" name="make" required>
<?php while($rowmake = $resultmake->fetch_assoc()) { ?>
<option value="<?php echo $rowmake['vehiclemake']; ?>"><?php echo $rowmake['vehiclemake']; ?></option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label for="model"><i class="glyphicon glyphicon-asterisk text-danger"></i>Model:</label>
<input type="text" class="form-control" id="model" name="model" required>
</div>
<div class="form-group">
<label for="year"><i class="glyphicon glyphicon-asterisk text-danger"></i>Year:</label>
<input type="text" class="form-control" id="year" name="year" required>
</div>
<div class="form-group">
<label for="vin">VIN (If available):</label>
<input type="text" class="form-control" id="vin" name="vin" >
</div>
<div class="form-group">
<label for="area"><i class="glyphicon glyphicon-asterisk text-danger"></i>Area Needed:</label>
<select class="form-control" id="area" name="area" required>
<?php while($rowarea = $resultarea->fetch_assoc()) { ?>
<option value="<?php echo $rowarea['id']; ?>"><?php echo $rowarea['areaname']; ?></option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label for="parts"><i class="glyphicon glyphicon-asterisk text-danger"></i>Parts Required:</label>
<textarea class="form-control" id="parts" name="parts" required></textarea>
</div>
<div class="form-group">
	<label for="file" class="control-label">Image</label>
	<input type="file" id="file" class="form-control" name="attach" >
</div>
<div class="form-group">
		<label for="human" class="control-label"><img src="captcha.php" /></label>
		
			<input type="text" class="form-control" id="human" name="human" placeholder="Your Answer" required>
			
            <p class="antispam hidden">Leave this empty: <input type="text" name="url" /></p>
		
	</div>

<hr />
<button class="btn btn-primary form-control" type="submit" name="submit">
        <i class="glyphicon glyphicon-envelope"></i> Send Email
</button>
<hr />
</form>

</div>
</div><!-- /row -->
</div><!-- /container -->
</div><!-- /container -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html> 
<?php 
$conn->close();
?>
