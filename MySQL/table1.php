<?php

include_once 'mySQLOlap.php';
header('Content-Type: application/json');

$query = new MySQLOlap();
$data =  $query->getDataForTableOne($_GET['yeartb1'], $_GET['statetb1'] );

$container = array();
$container["labels"] = $data[0];
$container["y"] = $data[1];
echo json_encode($container, JSON_NUMERIC_CHECK);











