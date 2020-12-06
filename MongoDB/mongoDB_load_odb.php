<?php

ini_set('memory_limit', '-1');

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

try {
    $manager->executeCommand('odb', new MongoDB\Driver\Command(["drop" => "tycho"]));
} catch (Exception $e) {
    print_r($e);
}

$bulk = new MongoDB\Driver\BulkWrite();

$filename = 'C:\ikt446_oyvor_henrik_emilija\MongoDB\tycho.json';
$lines = file($filename, FILE_IGNORE_NEW_LINES);

foreach ($lines as $line) {
    $bson = MongoDB\BSON\fromJSON($line);
    $document = MongoDB\BSON\toPHP($bson);
    $bulk->insert($document);
}

$result = $manager->executeBulkWrite('odb.tycho', $bulk);

$insertedCount = $result->getInsertedCount();

print "Inserted " . $insertedCount . " documents<br>";
?>