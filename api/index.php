<?php

require "../admin/connection.php";

require "vendor/autoload.php";
use RestService\Server;


Server::create('/')
    ->addGetRoute('provinces', function(){
        
		$sql = " SELECT id, areaname FROM `areas` ORDER BY areaname ASC ";
		$result = mysql_query($sql);
		
		$areas = [];

		while($row = mysql_fetch_assoc($result)) {
			$areas[] = $row;
		}

		return array( "status" => 1, "provinces" => $areas );

    })
    ->addGetRoute('foo/(.*)', function($bar){
        return $bar;
    })
->run();