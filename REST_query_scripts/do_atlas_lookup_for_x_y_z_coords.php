<?

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

require_once('/includes/cb_db_login_info.php');
require_once('JSON.php');

$atlas_map_list = $_REQUEST["atlas_map_id"]; # this will be a space or comma delimited set of atlas ids to use in my lookup

$src_x = $_REQUEST["src_x"];
$src_y = $_REQUEST["src_y"];
$src_z = $_REQUEST["src_z"];

$returnArray = array();

$statement = "select * from  probabilistic_atlas_maps where x_loc='src_x' and y_loc='$src_y' and z_loc='$src_z' and atlas_map_id in ($atlas_map_list) ";

 #select * from probabilistic_atlas_maps a inner join probabilistic_atlas_index_and_descriptions b on a.atlas_map_id=b.atlas_group and a.region_id=b.region_id where x_loc='59' and y_loc='64' and z_loc='12' and a.atlas_map_id in (1, 2, 3, 4)


$atlas_lookup_list = array();

$atlas_lookup_list=explode(" ",$atlas_map_list);

$atlas_map_list = implode(",",$atlas_lookup_list);


$statement = "select * from  probabilistic_atlas_maps a inner join probabilistic_atlas_index_and_descriptions b on a.atlas_map_id=b.atlas_group and a.region_id=b.region_id where x_loc='$src_x' and y_loc='$src_y' and z_loc='$src_z' and a.atlas_map_id in ($atlas_map_list) ";

#echo $statement;




$stmt = mysql_query($statement);

while( $database_keys = mysql_fetch_assoc($stmt) )
	{
  array_push($returnArray, $database_keys);
	};

$json = new Services_JSON();
echo $json->encode($returnArray);


 mysql_close();

