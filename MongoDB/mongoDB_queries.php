<?php
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

echo "Table 1:<br>";
echo "All years that have state-data (for drop-down)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'factAggrByYearStateCases',
    'key' => 'year'
]);

$cursor = $manager->executeCommand('adb', $cmd);

$years = current($cursor->toArray())->values;

foreach ($years as $year) {
    echo "Year: " . $year;
    echo "<br>";
}

echo "---<br>";

echo "All states that have state-data (for drop-down)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'factAggrByYearStateCases',
    'key' => 'loc'
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "State: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All years that have state-data for the chosen state (for drop-down når state er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'factAggrByYearStateCases',
    'key' => 'year',
    'query' => ['loc' => 'ALABAMA']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Year: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All states that have state-data for the chosen year (for drop-down når år er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'factAggrByYearStateCases',
    'key' => 'loc',
    'query' => ['year' => '2010']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "State: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "Number of cases of different diseases all years summed (before choosing year and state)<br>";

$filter = [];

$options = [
    'sort' => ['disease' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);

$before = microtime(true) * 1000;
$cursor = $manager->executeQuery('adb.factAggrByDiseaseAllTimeCases', $query);
$after =  microtime(true) * 1000;

$execution_mills = $after - $before;

echo 'Milliseconds to execute: ' . $execution_mills . "<br>";

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of cases of different diseases specific year summed (before choosing state)<br>";

$filter = ['year' => '2000'];

$options = [
    'sort' => ['disease' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByYearCountryCases', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "year: " . $document->year . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of cases of different diseases all years summed for specific state (before choosing year)<br>";

$filter = ['loc' => 'MINNESOTA'];

$options = [
    'sort' => ['disease' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByDiseaseStateAllTimeCases', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "state: " . $document->state . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of cases of different diseases specific year summed for specific state<br>";

$filter = ['loc' => 'MINNESOTA', 'year' => '2000'];

$options = [
    'sort' => ['disease' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByYearStateCases', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "year: " . $document->year . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "state: " . $document->state . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Table 2:<br>";

echo "All years that have city-data (for drop-down)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'factAggrByYearCityCases',
    'key' => 'year'
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Year: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "Number of cases per city per disease - all years summed (before choosing year)<br>";

$filter = [];

$options = [
    'sort' => ['loc' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByDiseaseCityStateAllTimeCases', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "state: " . $document->state . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of cases per city per disease - specific year<br>";

$filter = ['year' => '1920'];

$options = [
    'sort' => ['loc' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByYearCityCases', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "year: " . $document->year . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "state: " . $document->state . "<br>";
    echo "cases: " . $document->cases . "<br>";
    echo "<br>";
}

echo "---<br>";
echo "Table 3:<br>";

echo "All years that have state-data for the chosen disease (for drop-down når disease er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'year',
    'query' => ['disease' => 'HEPATITIS A']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Year: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All states that have state-data for the chosen disease (for drop-down når disease er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'loc',
    'query' => ['loc_type' => 'STATE', 'disease' => 'HEPATITIS A']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "State: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All diseases that have state-data (for drop-down)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'disease',
    'query' => ['loc_type' => 'STATE']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Disease: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All years that have state-data for the chosen state and disease (for drop-down når state og disease er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'year',
    'query' => ['loc_type' => 'STATE', 'loc' => 'MINNESOTA', 'disease' => "HEPATITIS A"]
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Year: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All diseases that have state-data for the chosen state (for drop-down når state er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'disease',
    'query' => ['loc_type' => 'STATE', 'loc' => 'MINNESOTA']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Disease: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All states that have state-data for the chosen year and disease (for drop-down når år og disease er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'loc',
    'query' => ['loc_type' => 'STATE', 'year' => '2000', 'disease' => 'HEPATITIS A']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "State: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All diseases that have state-data for the chosen year (for drop-down når år er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'disease',
    'query' => ['loc_type' => 'STATE', 'year' => '2000']
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Disease: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "All diseases that have state-data for the chosen year and state (for drop-down når år og state er valgt)<br>";

$cmd = new MongoDB\Driver\Command([
    'distinct' => 'fact',
    'key' => 'disease',
    'query' => ['loc_type' => 'STATE', 'year' => '2000', 'loc' => "MINNESOTA"]
]);

$cursor = $manager->executeCommand('adb', $cmd);

$values = current($cursor->toArray())->values;

foreach ($values as $value) {
    echo "Disease: " . $value;
    echo "<br>";
}

echo "---<br>";

echo "Number of inc_per_100000 per week for specific disease - avg for country (before choosing state and year)<br>";

$filter = ['disease' => 'HEPATITIS A'];

$options = [
    'sort' => ['week' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByDiseaseWeekIncPer100Kavg', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "week: " . $document->week . "<br>";
    echo "incidence_per_100000: " . $document->incidence_per_100000 . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of inc_per_100000 per week for specific disease and state (before choosing year)<br>";

$filter = ['loc' => 'MINNESOTA', 'disease' => 'HEPATITIS A'];

$options = [
    'sort' => ['week' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByDiseaseStateWeekIncPer100Kavg', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "week: " . $document->week . "<br>";
    echo "incidence_per_100000: " . $document->incidence_per_100000 . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of inc_per_100000 per week for specific disease and year - avg for country(before choosing state)<br>";

$filter = ['disease' => 'PERTUSSIS', 'year' => '1950'];

$options = [
    'sort' => ['week' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.factAggrByDiseaseYearWeekIncPer100Kavg', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "year: " . $document->year . "<br>";
    echo "week: " . $document->week . "<br>";
    echo "incidence_per_100000: " . $document->incidence_per_100000 . "<br>";
    echo "<br>";
}

echo "---<br>";

echo "Number of inc_per_100000 per week for specific disease, state and year<br>";

$filter = ['loc_type' => 'STATE', 'loc' => 'MINNESOTA', 'disease' => 'PERTUSSIS', 'year' => '1950'];

$options = [
    'sort' => ['week' => 1]
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('adb.fact', $query);

foreach ($cursor as $document) {
    echo "disease: " . $document->disease . "<br>";
    echo "loc: " . $document->loc . "<br>";
    echo "year: " . $document->year . "<br>";
    echo "week: " . $document->week . "<br>";
    echo "incidence_per_100000: " . $document->incidence_per_100000 . "<br>";
    echo "<br>";
}
?>