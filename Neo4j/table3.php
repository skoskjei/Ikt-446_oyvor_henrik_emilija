
<?php

include_once 'neo4jOlap.php';
header('Content-Type: application/json');

$query = new Neo4jOlap();
$data =  $query->getDataForTableThree($_GET['disease'], $_GET['yeartb3'], $_GET['statetb3']);

$container = array();
$container["x"] = $data[0];
$container["y"] = $data[1];
echo json_encode($container, JSON_NUMERIC_CHECK);

