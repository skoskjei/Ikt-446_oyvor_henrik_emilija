<?php
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

try {
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "fact"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByYearCityCases"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByYearStateCases"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByYearCountryCases"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseAllTimeCases"]));
  	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseStateAllTimeCases"]));
        $manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseCityStateAllTimeCases"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseYearWeekIncPer100Kavg"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseWeekIncPer100Kavg"]));
	$manager->executeCommand('adb', new \MongoDB\Driver\Command(["drop" => "factAggrByDiseaseStateWeekIncPer100Kavg"]));
} catch (Exception $e) {
}

$filter = [];
$options = ['projection' => ['_id' => 0]];

$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('odb.tycho', $query); 

$bulkInsertDataFact = new MongoDB\Driver\BulkWrite();

foreach ($cursor as $document) {
    $year = substr($document->epi_week, 0, 4);
    $week = substr($document->epi_week, -2);

    unset($document->epi_week);

    $document->year = $year;
    $document->week = $week;

    $bulkInsertDataFact->insert($document);
}

$result = $manager->executeBulkWrite('adb.fact', $bulkInsertDataFact);

$insertedCount = $result->getInsertedCount();

print "Inserted " . $insertedCount . " documents<br>";

$command = new MongoDB\Driver\Command([
    'aggregate' => 'fact',
    'pipeline' => [
        ['$addFields' => ['cases' => ['$toInt' => '$cases']]],
        ['$match' => ['loc_type' => 'CITY']],
        ['$group' => ['_id' => ['disease' => '$disease', 'year' => '$year', 'loc' => '$loc', 'state' => '$state'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'year' => '$_id.year', 'loc' => '$_id.loc', 'state' => '$_id.state', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByYearCityCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'fact',
    'pipeline' => [
        ['$addFields' => ['cases' => ['$convert' => ['input' => '$cases', 'to' => 'int', 'onError' => 'NumberInt(0)']]]],
        ['$match' => ['loc_type' => 'STATE']],
        ['$group' => ['_id' => ['disease' => '$disease', 'year' => '$year', 'loc' => '$loc', 'state' => '$state'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'year' => '$_id.year', 'loc' => '$_id.loc', 'state' => '$_id.state', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByYearStateCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'factAggrByYearStateCases',
    'pipeline' => [
        ['$group' => ['_id' => ['disease' => '$disease', 'year' => '$year'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'year' => '$_id.year', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByYearCountryCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'factAggrByYearCountryCases',
    'pipeline' => [
        ['$group' => ['_id' => ['disease' => '$disease'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByDiseaseAllTimeCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'factAggrByYearStateCases',
    'pipeline' => [
        ['$group' => ['_id' => ['disease' => '$disease', 'loc' => '$loc', 'state' => '$state'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'loc' => '$_id.loc', 'state' => '$_id.state', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByDiseaseStateAllTimeCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'factAggrByYearCityCases',
    'pipeline' => [
        ['$group' => ['_id' => ['disease' => '$disease', 'loc' => '$loc', 'state' => '$state'], 
                     'cases' => ['$sum' => '$cases']]],
        ['$project' => ['disease' => '$_id.disease', 'loc' => '$_id.loc', 'state' => '$_id.state', 'cases' => '$cases', '_id' => 0]],
        ['$out' => 'factAggrByDiseaseCityStateAllTimeCases']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'fact',
    'pipeline' => [
        ['$addFields' => ['incidence_per_100000' => ['$convert' => ['input' => '$incidence_per_100000', 'to' => 'decimal', 'onError' => 'NumberDecimal(0)']]]],
        ['$group' => ['_id' => ['disease' => '$disease', 'loc_type' => 'STATE', 'loc' => '$loc', 'week' => '$week'], 
                     'incidence_per_100000' => ['$avg' => '$incidence_per_100000']]],
        ['$project' => ['disease' => '$_id.disease', 'loc' => '$_id.loc', 'week' => '$_id.week', 'incidence_per_100000' => ['$trunc' => ['$incidence_per_100000', 2]], '_id' => 0]],
        ['$out' => 'factAggrByDiseaseStateWeekIncPer100Kavg']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'fact',
    'pipeline' => [
        ['$addFields' => ['incidence_per_100000' => ['$convert' => ['input' => '$incidence_per_100000', 'to' => 'decimal', 'onError' => 'NumberDecimal(0)']]]],
        ['$group' => ['_id' => ['disease' => '$disease', 'year' => '$year', 'week' => '$week'], 
                     'incidence_per_100000' => ['$avg' => '$incidence_per_100000']]],
        ['$project' => ['disease' => '$_id.disease', 'year' => '$_id.year', 'week' => '$_id.week', 'incidence_per_100000' => ['$trunc' => ['$incidence_per_100000', 2]], '_id' => 0]],
        ['$out' => 'factAggrByDiseaseYearWeekIncPer100Kavg']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);

$command = new MongoDB\Driver\Command([
    'aggregate' => 'factAggrByDiseaseYearWeekIncPer100Kavg',
    'pipeline' => [
        ['$group' => ['_id' => ['disease' => '$disease', 'week' => '$week'], 
                     'incidence_per_100000' => ['$avg' => '$incidence_per_100000']]],
        ['$project' => ['disease' => '$_id.disease', 'week' => '$_id.week', 'incidence_per_100000' => ['$trunc' => ['$incidence_per_100000', 2]], '_id' => 0]],
        ['$out' => 'factAggrByDiseaseWeekIncPer100Kavg']],
    'cursor' => new stdClass]);

$manager->executeCommand('adb', $command);
?>