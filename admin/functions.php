<?php

	
	require_once 'connection.php';
	

	//echo "test";

	

	function GetNewID2($tablename, $idfield, $db)

	{

		$qry = "select max(`$idfield`) as newid from `$tablename`";

		$qryresults = mysql_query($qry, $db);

		

		$row = mysql_fetch_array($qryresults);

		

		$newid = $row["newid"];

		

		return $newid;

		

	}

	

	function FormatDate($date, $monthformat)

	{

		$thedate = explode("-", $date);

		

		if ($thedate[1] == "01") 

		{ 

			if ($monthformat == "M")

			{

				$month = "January";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jan";

			}

		}

		

		if ($thedate[1] == "02") 

		{ 

			if ($monthformat == "M")

			{

				$month = "February";

			}

			

			if ($monthformat == "m")

			{

				$month = "Feb";

			}

		}

		

		if ($thedate[1] == "03") 

		{ 

			if ($monthformat == "M")

			{

				$month = "March";

			}

			

			if ($monthformat == "m")

			{

				$month = "Mar";

			}

		}

		

		if ($thedate[1] == "04") 

		{ 

			if ($monthformat == "M")

			{

				$month = "April";

			}

			

			if ($monthformat == "m")

			{

				$month = "Apr";

			}

		}

		

		if ($thedate[1] == "05") 

		{ 

			if ($monthformat == "M")

			{

				$month = "May";

			}

			

			if ($monthformat == "m")

			{

				$month = "May";

			}

		}

		

		if ($thedate[1] == "06") 

		{ 

			if ($monthformat == "M")

			{

				$month = "June";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jun";

			}

		}

		

		if ($thedate[1] == "07") 

		{ 

			if ($monthformat == "M")

			{

				$month = "July";

			}

			

			if ($monthformat == "m")

			{

				$month = "Jul";

			}

		}

		

		if ($thedate[1] == "08") 

		{ 

			if ($monthformat == "M")

			{

				$month = "August";

			}

			

			if ($monthformat == "m")

			{

				$month = "Aug";

			}

		}

		

		if ($thedate[1] == "09") 

		{ 

			if ($monthformat == "M")

			{

				$month = "September";

			}

			

			if ($monthformat == "m")

			{

				$month = "Sep";

			}

		}

		

		if ($thedate[1] == "10") 

		{ 

			if ($monthformat == "M")

			{

				$month = "October";

			}

			

			if ($monthformat == "m")

			{

				$month = "Oct";

			}

		}

		

		if ($thedate[1] == "11") 

		{ 

			if ($monthformat == "M")

			{

				$month = "November";

			}

			

			if ($monthformat == "m")

			{

				$month = "Nov";

			}

		}

		

		if ($thedate[1] == "12") 

		{ 

			if ($monthformat == "M")

			{

				$month = "December";

			}

			

			if ($monthformat == "m")

			{

				$month = "Dec";

			}

		}

		

		return $thedate[2] . " " . $month . " " . $thedate[0];

	}



	function assign_rand_value($num)

	{

	// accepts 1 - 36

	  switch($num)

	  {

		case "1":

		 $rand_value = "a";

		break;

		case "2":

		 $rand_value = "b";

		break;

		case "3":

		 $rand_value = "c";

		break;

		case "4":

		 $rand_value = "d";

		break;

		case "5":

		 $rand_value = "e";

		break;

		case "6":

		 $rand_value = "f";

		break;

		case "7":

		 $rand_value = "g";

		break;

		case "8":

		 $rand_value = "h";

		break;

		case "9":

		 $rand_value = "i";

		break;

		case "10":

		 $rand_value = "j";

		break;

		case "11":

		 $rand_value = "k";

		break;

		case "12":

		 $rand_value = "l";

		break;

		case "13":

		 $rand_value = "m";

		break;

		case "14":

		 $rand_value = "n";

		break;

		case "15":

		 $rand_value = "o";

		break;

		case "16":

		 $rand_value = "p";

		break;

		case "17":

		 $rand_value = "q";

		break;

		case "18":

		 $rand_value = "r";

		break;

		case "19":

		 $rand_value = "s";

		break;

		case "20":

		 $rand_value = "t";

		break;

		case "21":

		 $rand_value = "u";

		break;

		case "22":

		 $rand_value = "v";

		break;

		case "23":

		 $rand_value = "w";

		break;

		case "24":

		 $rand_value = "x";

		break;

		case "25":

		 $rand_value = "y";

		break;

		case "26":

		 $rand_value = "z";

		break;

		case "27":

		 $rand_value = "0";

		break;

		case "28":

		 $rand_value = "1";

		break;

		case "29":

		 $rand_value = "2";

		break;

		case "30":

		 $rand_value = "3";

		break;

		case "31":

		 $rand_value = "4";

		break;

		case "32":

		 $rand_value = "5";

		break;

		case "33":

		 $rand_value = "6";

		break;

		case "34":

		 $rand_value = "7";

		break;

		case "35":

		 $rand_value = "8";

		break;

		case "36":

		 $rand_value = "9";

		break;

	  }

	return $rand_value;

	}

	

	function get_rand_id($length)

	{

	  if($length>0) 

	  { 

	  $rand_id="";

	   for($i=1; $i<=$length; $i++)

	   {

	   mt_srand((double)microtime() * 1000000);

	   $num = mt_rand(1,36);

	   $rand_id .= assign_rand_value($num);

	   }

	  }

	return $rand_id;

	}


	function get_vehicle_make_listing($db, $selected_vehicle_make=0) {
		$sql = "SELECT * FROM vehiclemake ORDER BY `vehiclemake` ASC ";
		$qryvehiclemakes = mysql_query($sql, $db);

		$html = '<select name="vehiclemake">';

		while($make = mysql_fetch_assoc($qryvehiclemakes)) {
			$selected = "";
			if ($make['id'] == $selected_vehicle_make) { $selected = 'selected="selected"';}
			$html .= '<option value="'.$make["id"].'" '.$selected.'>'.$make["vehiclemake"].'</option>';
		}

		$html .= '</select>';

		return $html;
	}

	function get_partsuppliers_for_vehicle_make($db, $vehiclemakeid, $areaid) {
		/*$sql = " SELECT ps.* FROM partssuppliers as ps 
				INNER JOIN partssupplier_vehiclemake as psvm ON ps.id = psvm.partssupplierid 
				INNER JOIN partsupplier_area as psa
				ON ps.partssupplierid = ps.id
				WHERE psvm.vehiclemakeid = '".$vehiclemakeid."'
				AND psa.areaid='".$areaid."'
				";*/
		$sql = " SELECT ps.* FROM partssuppliers as ps 
				INNER JOIN partssupplier_vehiclemake as psvm ON ps.id = psvm.partssupplierid 
				INNER JOIN partsupplier_area as psa
				ON psa.partssupplierid = ps.id
				WHERE psvm.vehiclemakeid = '".$vehiclemakeid."'
				AND psa.areaid='".$areaid."'
				";

		$result = mysql_query($sql);

		$partsuppliers = [];

		while ($row = mysql_fetch_assoc($result)) {
			$partsuppliers[] = $row;
		}

		return $partsuppliers;
	}

	function get_vehicle_make_by_id($db, $id) {
		$sql = "SELECT vehiclemake FROM vehiclemake WHERE id='".$id."' ";

		$result = mysql_query($sql);

		return mysql_fetch_assoc($result);
	}

	function generate_guid() {
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	function getVehicleTypesList() {
		global $db;
		$qry = "select vehicletype from vehicletype ORDER BY vehicletype ASC ";
		$qryresults = mysql_query($qry, $db);
		
		$list = [];

		while ( $row = mysql_fetch_array($qryresults) ) {
			$list[] = $row['vehicletype'];
		}

		return $list;
	
	}

	function getPanelBeaters() {
		global $db;
		$qry = " SELECT id, name FROM panelbeaters ORDER BY name ASC ";

		$qryresults = mysql_query($qry, $db);
		
		$list = [];

		while ( $row = mysql_fetch_array($qryresults) ) {
			$list[] = $row;
		}

		return $list;

	}

	function getTowingOperators() {
		global $db;
		$qry = " SELECT id, name FROM towingoperators ORDER BY name ASC ";

		$qryresults = mysql_query($qry, $db);
		
		$list = [];

		while ( $row = mysql_fetch_array($qryresults) ) {
			$list[] = $row;
		}

		return $list;

	}


?>