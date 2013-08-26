<?

#require_once('/includes/cb_db_login_info.php');
require_once('JSON.php');

$db_con = mysql_connect('trauma-computernode1.psychiatry.emory.edu','brainuser','z0mbiez!');
if(!$db_con) { die('Could not connect: ' . mysql_error() ); }

mysql_select_db('computable_brain', $db_con) or die('Could not select database.');


$atlas_map_list = $_REQUEST["atlas_map_id"]; # this will be a space or comma delimited set of atlas ids to use in my lookup

$src_x = $_REQUEST["src_x"];
$src_y = $_REQUEST["src_y"];
$src_z = $_REQUEST["src_z"];


$returnArray = array();
$atlas_lookup_list = array();
$atlas_lookup_list=explode(" ",$atlas_map_list);


$atlas_result_list = array();

for($k=0; $k<count($atlas_lookup_list); $k++)
	{

if($atlas_lookup_list[$k] > 500 ) { $atlas_lookup_value = $atlas_lookup_list[$k] - 500; }
else { $atlas_lookup_value = $atlas_lookup_list[$k]; }

 	
$statement = "select * from  probabilistic_atlas_image_info where prob_atlas_map_id=" . $atlas_lookup_value;
#echo $statement;

$stmt = mysql_query($statement);
while( $database_keys = mysql_fetch_assoc($stmt) )
	{


 $local_statement = "select * from probabilistic_atlas_maps a  inner join probabilistic_atlas_index_and_descriptions b on a.atlas_map_id=b.atlas_group and a.region_id=b.region_id where x_loc='$src_x' and y_loc='$src_y' and z_loc='$src_z' and a.atlas_map_id=".  $database_keys["prob_atlas_map_id"]; 	;

#echo $local_statement;
$statement_two = mysql_query($local_statement);

$atlas_result_list = array();
$atlas_result_list["atlas_name"] = $database_keys["map_description"];
$atlas_result_list["atlas_map_id"] = $database_keys["prob_atlas_map_id"];
$atlas_result_list["region_id"] = "";
$atlas_result_list["x_loc"] = "";
$atlas_result_list["y_loc"] = "";
$atlas_result_list["z_loc"] = "";
$atlas_result_list["intensity"] = "";
$atlas_result_list["region_description"] = "";
$atlas_result_list["max_x"] = "";
$atlas_result_list["max_y"] = "";
$atlas_result_list["max_z"] = "";


$found_it = 0;
while( $database_keys = mysql_fetch_assoc($statement_two) )
	{

$atlas_result_list["region_id"] = $database_keys["region_id"];
$atlas_result_list["x_loc"] = $database_keys["x_loc"];
$atlas_result_list["y_loc"] = $database_keys["y_loc"];
$atlas_result_list["z_loc"] = $database_keys["z_loc"];
$atlas_result_list["intensity"] = $database_keys["intensity"];
$atlas_result_list["region_description"] = $database_keys["region_description"];
$atlas_result_list["max_x"] = $database_keys["max_x"];
$atlas_result_list["max_y"] = $database_keys["max_y"];
$atlas_result_list["max_z"] = $database_keys["max_z"];


 array_push($returnArray, $atlas_result_list); 

 $found_it=1;
	};
if(! $found_it) { array_push($returnArray, $atlas_result_list); }
	};


	}

$json = new Services_JSON();
echo $json->encode($returnArray);

mysql_close();


