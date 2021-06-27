<?php

error_reporting(1);
$db = mysql_pconnect ("localhost", "claimsin_admin", "claimsin_admin") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("claimsin_claims_aci");

$db1 = mysqli_connect("localhost","claimsin_admin","claimsin_admin","claimsin_claims_aci");

?>