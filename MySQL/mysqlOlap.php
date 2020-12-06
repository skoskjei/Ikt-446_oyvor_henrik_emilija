<?php

include_once 'mySqlDb.php';
class MySQLOlap
{
    private $db;

    /**
     * StateNoCases constructor.
     * @param $db
     */
    public function __construct()
    {
        $this->db = new MySqlDb();
    }

    public function getYearDropDown_state(){
        $query = "SELECT DISTINCT year FROM state_fact Order by year";
        $res = $this->db->query($query);
        $resArray = array();
        foreach ($res as $r){
            array_push($resArray, $r['year'] );
        }

        return $resArray;
    }
    public function getDiseaseDropDown_state(){
        $query = "SELECT DISTINCT disease FROM state_fact Order by disease";
        $res = $this->db->query($query);
        $resArray = array();
        foreach ($res as $r){
            array_push($resArray, $r['disease'] );
        }

        return $resArray;
    }
    public function getStateDropDown_state(){
        $query = "SELECT DISTINCT state_locdim.name as name FROM state_fact, state_locdim WHERE state_fact.state = state_locdim.state Order by state_locdim.name";
        $res = $this->db->query($query);
        $resArray = array();
        foreach ($res as $r){
            array_push($resArray, $r['name'] );
        }

        return $resArray;
    }

    public function getYearDropDown_city(){
        $query = "SELECT DISTINCT year FROM city_fact Order by year";
        $res = $this->db->query($query);
        $resArray = array();
        foreach ($res as $r){
            array_push($resArray, $r['year'] );
        }
        return $resArray;
    }

    # 1.1 Number of cases of different diseases all years summed (before choosing year and state)
    public function getDataForTableOne($year, $state){

        if(empty($year) and empty($state)){
            $query = "SELECT disease, sum(no_cases) from state_fact group by disease";
        }
        elseif(empty($year)){
            $query = "SELECT disease, sum(no_cases) from state_fact, state_locdim where state_locdim.name = '$state' and state_fact.state=state_locdim.state 
                        group by disease";

        }
        elseif(empty($state)){
            $query = "SELECT disease, sum(no_cases) from state_fact where year = '$year' group by disease";
        }
        else{
            $query ="SELECT disease, sum(no_cases)  from state_fact, state_locdim where state_locdim.name = '$state' and state_fact.state=state_locdim.state and year = $year
                        group by disease";

        }
        $res = $this->db->query($query);

        $diseases = array();
        $no_cases = array();
        foreach ($res as $r){
            array_push($diseases, $r['disease']);
            array_push($no_cases,  $r['sum(no_cases)']);
        }

        return [$diseases, $no_cases] ;

    }

    public function getDataForTableTwo($year){
        $query = '';
        if(empty($year)){
            $query = "SELECT city, state, disease, sum(no_cases) from city_fact group by city, state, disease";
        }
        else{
            $query = "SELECT city, state, disease, sum(no_cases) from city_fact where year = $year group by city, state, disease";
        }
        $res = $this->db->query($query);
        $resArray = array();
        foreach ($res as $r){
            array_push($resArray,array($r['city'], $r['state'], $r['disease'], $r['sum(no_cases)'] ));
        }

        return $resArray;

    }

    public function getDataForTableThree($disease, $year, $state){

        if(empty($year) and empty($state)){
            $query ="select week, avg(avg_cases) as avg_cases from stateavg_fact where disease = '$disease' GROUP BY week";
        }
        elseif(empty($year)){
            $query ="select week, avg(avg_cases) as avg_cases    
                        from stateavg_fact, state_locdim 
                        where state_locdim.name = '$state' and stateavg_fact.state = state_locdim.state and disease = '$disease' GROUP BY week";
        }
        elseif(empty($state)){
            $query = "select week, avg(avg_cases) as avg_cases from stateavg_fact where year = '$year' and disease = '$disease' GROUP BY week";
        }
        else{

            $query = "select week, avg_cases from stateavg_fact, state_locdim  
                        where state_locdim.name = '$state' and stateavg_fact.state = state_locdim.state and disease = '$disease' and year = $year";

        }
        $res = $this->db->query($query);

        $weeks = array();
        $avg_cases = array();
        foreach ($res as $r){
            array_push($weeks, $r['week']);
            array_push($avg_cases, $r['avg_cases']);
        }

        return [$weeks, $avg_cases] ;
    }



    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

}