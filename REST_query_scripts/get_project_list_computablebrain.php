<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



require_once('/includes/cb_db_login_info.php');


require_once('JSON.php');
 $returnArray = array();

$function_result = mysql_query("select * from project_group_info ");


while( $database_keys = mysql_fetch_assoc($function_result) )
        {
 array_push($returnArray, $database_keys);
	}

 mysql_close();
$json = new Services_JSON();
echo $json->encode($returnArray);
?>



