<?php
$urls=array();
$urls['project']="http://computablebrain.cci.emory.edu/php/get_project_list_computablebrain.php";
$urls['experiment']='http://computablebrain.cci.emory.edu/php/get_experiments_for_project_computable_brain.php?';
if ($_GET['type']=='experiment'){
    $urls['experiment'].='project_group='.$_GET['project_group'];
}
$urls['underlay']='http://computablebrain.cci.emory.edu/php/get_underlay_info_for_project_experiment_computable_brain.php?';
$urls['overlay']='http://computablebrain.cci.emory.edu/php/get_static_overlays_for_project_and_experiment_for_computable_brain.php?';
$urls['atlaslist']='http://computablebrain.cci.emory.edu/php/get_atlas_list_for_project_experiment.php?';
if ($_GET['type']=='underlay'||$_GET['type']=='overlay'||$_GET['type']=='atlaslist'){
    $urls[$_GET['type']].='project_group='.$_GET['project_group'];
    $urls[$_GET['type']].='&experiment_group='.$_GET['experiment_group'];
}

if (!isset($_GET['type']) || !isset($urls[$_GET['type']])){
    echo '[]';
    die();
}
$url=$urls[$_GET['type']];
$handle = fopen($url, "r");
$contents = stream_get_contents($handle);
fclose($handle);
echo $contents;

    
?>
