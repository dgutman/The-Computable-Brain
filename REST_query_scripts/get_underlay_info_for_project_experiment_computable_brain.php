<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past


require_once('JSON.php');
require_once('/includes/cb_db_login_info.php');

 $returnArray = array();

$experiment_group = $_REQUEST["experiment_group"];
$project_group = $_REQUEST["project_group"];


/* DO MYSQL QUERIES      */  
$db_con = mysql_connect('trauma-computernode1.psychiatry.emory.edu','brainuser','z0mbiez!');
if(!$db_con) { die('Could not connect: ' . mysql_error() ); }

mysql_select_db('computable_brain', $db_con) or die('Could not select database.');


$statement = "select * from underlay_image_for_proj_experiment where project_group='$project_group' and experiment_group='$experiment_group'";
#echo $statement;

$function_result = mysql_query("$statement");


while( $database_keys = mysql_fetch_assoc($function_result) )
        {
 array_push($returnArray, $database_keys);
	}

 mysql_close();
$json = new Services_JSON();
echo $json->encode($returnArray);
?>

