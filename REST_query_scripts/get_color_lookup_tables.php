<?

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once('/includes/cb_db_login_info.php');
require_once('JSON.php');


$returnArray = array();


$statement = "select * from  color_lookup_tables ";


$stmt = mysql_query($statement);

while( $database_keys = mysql_fetch_assoc($stmt) )
	{
  array_push($returnArray, $database_keys);
	};




$json = new Services_JSON();
echo $json->encode($returnArray);


 mysql_close();

?>
	

