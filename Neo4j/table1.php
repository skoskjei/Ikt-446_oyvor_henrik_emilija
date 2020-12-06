<?php

include_once 'neo4jOlap.php';
header('Content-Type: application/json');

$query = new Neo4jOlap();
$year = isset($_GET['yeartb1']) ? $_GET['yeartb1'] : '';
$state = isset($_GET['statetb1']) ? $_GET['statetb1'] : '';

$data =  $query->getDataForTableOne($year, $state);

$container = array();
$container["labels"] = $data[0];
$container["y"] = $data[1];
echo json_encode($container, JSON_NUMERIC_CHECK);











