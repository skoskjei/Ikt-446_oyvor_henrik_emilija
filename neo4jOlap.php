<?php

include_once 'neo4jDb.php';
class Neo4jOlap
{
    private $db;

    /**
     * StateNoCases constructor.
     * @param $db
     */
    public function __construct()
    {
        $this->db = new Neo4jDb();
    }
    public function getYearDropDown_state(){
        $query = "MATCH (s:State)--(e:Event)--(d:Date)
                WHERE e.loc_type = 'STATE' 
                RETURN DISTINCT d.year as year
                ORDER BY d.year";
        return $this->db->query($query)->get('year');
    }
    public function getDiseaseDropDown_state(){
        $query = "MATCH (e:Event)
        WHERE e.loc_type = 'STATE'
        RETURN DISTINCT e.disease as disease
        ORDER BY e.disease";
        $res = $this->db->query($query)->get('disease');


        if (empty($res)) {
            return array();
        }
        return $res;
    }
    public function getStateDropDown_state(){
        $query = "MATCH (s:State)--(e:Event)
                WHERE e.loc_type = 'STATE'
                RETURN DISTINCT s.sname as state
                ORDER BY s.sname";
        return $this->db->query($query)->get('state');
    }

    public function getYearDropDown_city(){
        $query = "MATCH (s:State)--(c:City)--(e:Event)--(d:Date)
                WHERE e.loc_type = 'CITY'
                RETURN DISTINCT d.year as year
                ORDER BY d.year";
        return $this->db->query($query)->get('year');
    }


    # 1.1 Number of cases of different diseases all years summed (before choosing year and state)
    public function getDataForTableOne($year='', $state=''){
        $query = '';

        if(empty($year) and empty($state)){
            $query = "MATCH (d:Date)--(e:Event)
                WHERE e.loc_type = 'STATE'
                RETURN SUM(e.cases) as no_cases, e.disease as disease";
        }
        elseif(empty($year)){
            $query = "MATCH (d:Date)--(e:Event)--(s:State)
                WHERE e.loc_type = 'STATE' AND s.sname = '$state'
                RETURN SUM(e.cases) as no_cases, e.disease as disease";
        }
        elseif(empty($state)){
            $query = "MATCH (d:Date)--(e:Event)
                WHERE e.loc_type = 'STATE' AND d.year = '$year'
                RETURN SUM(e.cases) as no_cases, e.disease as disease";
        }
        else{
            $query ="MATCH (d:Date)--(e:Event)--(s:State)
                WHERE e.loc_type = 'STATE' AND s.sname = '$state' AND d.year = '$year'
                RETURN SUM(e.cases) as no_cases, e.disease as disease";

        }
        $result = $this->db->query($query);
        return [$result->get('disease'), $result->get('no_cases')];


    }

    public function getDataForTableTwo($year){
        $query = '';
        if($year==''){
            $query = "MATCH (s:State)--(c:City)--(e:Event)
                WHERE e.loc_type = 'CITY'
                RETURN c.cname as city, s.st as state, sum(e.cases) as no_cases, e.disease as disease
                ORDER BY c.cname";
        }
        else{
            $query = "MATCH (s:State)--(c:City)--(e:Event)--(d:Date)
                WHERE e.loc_type = 'CITY' AND d.year = '$year'
                RETURN c.cname as city, s.st as state, sum(e.cases) as no_cases, e.disease as disease
                ORDER BY c.cname";
        }
        $result = $this->db->query($query);
        $cities = $result->get('city');
        if (empty($cities)) {
            echo 'No cases for this combo';
        }
        else {
            $states = $result->get('state');
            $no_cases = $result->get('no_cases');
            $diseases = $result->get('disease');
            $resArray = array();
            for ($i = 0; $i < sizeof($cities); $i++){
                array_push($resArray,array($cities[$i], $states[$i], $diseases[$i],$no_cases[$i]));
            }
            return $resArray;
        }

    }


    public function getDataForTableThree($disease, $year, $state){
        if(empty($year) and empty($state)){
            $query ="MATCH (d:Date)--(e:Event)
                    WHERE e.loc_type = 'STATE' AND e.disease = '$disease'
                    RETURN d.week as week, AVG(e.incidence_per_100000) as incidence_per_100000 ORDER BY d.week";
        }
        elseif(empty($year)){
            $query ="MATCH (d:Date)--(e:Event)--(s:State)
                WHERE e.loc_type = 'STATE' AND e.disease = '$disease' AND s.sname = '$state'
                RETURN d.week as week, AVG(e.incidence_per_100000) as incidence_per_100000 ORDER BY d.week";
        }
        elseif(empty($state)){
            $query = "MATCH (d:Date)--(e:Event)
                WHERE e.loc_type = 'STATE' AND e.disease = '$disease' AND d.year = '$year'
                RETURN d.week as week, AVG(e.incidence_per_100000) as incidence_per_100000 ORDER BY d.week";
        }
        else{

            $query = "MATCH (d:Date)--(e:Event)--(s:State)
                WHERE e.loc_type = 'STATE' AND e.disease = '$disease' AND d.year = '$year' AND s.sname = '$state'
                RETURN d.week as week, e.incidence_per_100000 as incidence_per_100000 ORDER BY d.week";

        }
        $result = $this->db->query($query);
        return [$result->get('week'), $result->get('incidence_per_100000')];

    }


    /**
     * @return mixed
     */
    public function getDiseases()
    {
        return $this->db->query("SELECT * from diseasedim");
    }

    /**
     * @param mixed $disease
     */
    public function setDiseases($disease)
    {
        $this->diseases = $disease;
    }
}
