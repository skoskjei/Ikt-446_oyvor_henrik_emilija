<?php

include_once 'neo4jOlap.php';
header('Content-Type: application/json');

$query = new Neo4jOlap();
$data =  $query->getDataForTableOne($_GET['yeartb1'], $_GET['statetb1'] );

$container = array();
$container["labels"] = $data[0];
$container["y"] = $data[1];
echo json_encode($container, JSON_NUMERIC_CHECK);











