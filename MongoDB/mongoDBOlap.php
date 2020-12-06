<?php


class MongoDBOlap
{
    private $db;

    /**
     * StateNoCases constructor.
     * @param $db
     */
    public function __construct()
    {
        $this->db = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    }
    public function getYearDropDown_state(){
        $dropdown_list = [];
        $cmd = new MongoDB\Driver\Command([
            'distinct' => 'factAggrByYearStateCases',
            'key' => 'year'
        ]);

        $cursor = $this->db->executeCommand('adb', $cmd);
        $res = current($cursor->toArray())->values;

        foreach($res as $r){
            array_push($dropdown_list, $r);
        }

        return $dropdown_list;

    }
    public function getDiseaseDropDown_state(){
        $cmd = new MongoDB\Driver\Command([
            'distinct' => 'fact',
            'key' => 'disease',
            'query' => ['loc_type' => 'STATE']
        ]);

        $cursor = $this->db->executeCommand('adb', $cmd);

        return current($cursor->toArray())->values;
    }
    public function getStateDropDown_state(){
        $dropdown_list = [];
        $cmd = new MongoDB\Driver\Command([
            'distinct' => 'factAggrByYearStateCases',
            'key' => 'loc'
        ]);

        $cursor = $this->db->executeCommand('adb', $cmd);

        $res = current($cursor->toArray())->values;

        foreach($res as $r){
            array_push($dropdown_list, $r);
        }

        return $dropdown_list;
    }
    public function getYearDropDown_city(){
        $dropdown_list = [];
        $cmd = new MongoDB\Driver\Command([
            'distinct' => 'factAggrByYearCityCases',
            'key' => 'year'
        ]);

        $cursor = $this->db->executeCommand('adb', $cmd);

        $res = current($cursor->toArray())->values;

        foreach($res as $r){
            array_push($dropdown_list, $r);
        }

        return $dropdown_list;
    }
    public function getDataForTableOne($year, $state){

        if(empty($year) and empty($state)){
            $filter = [];

            $options = [
                'sort' => ['disease' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseAllTimeCases', $query);
        }
        elseif(empty($year)){
            $filter = ['loc' => $state];

            $options = [
                'sort' => ['disease' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseStateAllTimeCases', $query);


        }
        elseif(empty($state)){
            $filter = ['year' => $year];

            $options = [
                'sort' => ['disease' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByYearCountryCases', $query);
        }
        else{
            $filter = ['loc' => $state, 'year' => $year];

            $options = [
                'sort' => ['disease' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByYearStateCases', $query);

        }
        $diseases = array();
        $cases = array();


        foreach ($cursor as $document) {
            array_push($diseases, $document->disease);
            array_push($cases, $document->cases);

        }

        return [$diseases,$cases] ;

    }




    public function getDataForTableTwo($year){
        $filter = [];

        $options = [
            'sort' => ['loc' => 1]

        ];
        if(empty($year)){
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseCityStateAllTimeCases', $query);
        }
        else{
            $filter = ['year' => $year];
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByYearCityCases', $query);
        }

        $resArray = array();
        foreach ($cursor as $r){
            array_push($resArray,array($r->loc, $r->state, $r->disease, $r->cases ));
        }

        return $resArray;

    }
    public function getDataForTableThree($disease, $year, $state){

        if(empty($year) and empty($state)){
            $filter = ['disease' => $disease];

            $options = [
                'sort' => ['week' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseWeekIncPer100Kavg', $query);
        }
        elseif(empty($year)){
            $filter = ['loc' => $state, 'disease' => $disease];

            $options = [
                'sort' => ['week' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseStateWeekIncPer100Kavg', $query);


        }
        elseif(empty($state)){
            $filter = ['disease' => $disease, 'year' => $year];

            $options = [
                'sort' => ['week' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.factAggrByDiseaseYearWeekIncPer100Kavg', $query);
        }
        else{
            $filter = ['loc_type' => 'STATE', 'loc' => $state, 'disease' => $disease, 'year' => $year];

            $options = [
                'sort' => ['week' => 1]
            ];

            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->db->executeQuery('adb.fact', $query);

        }
        $weeks = array();
        $incidence_per_100000 = array();

        foreach ($cursor as $document) {
            array_push($weeks, $document->week);
            $inc = (string) new MongoDB\BSON\Decimal128($document->incidence_per_100000);
            array_push($incidence_per_100000, $inc);

        }

        return [$weeks,$incidence_per_100000] ;

    }

}