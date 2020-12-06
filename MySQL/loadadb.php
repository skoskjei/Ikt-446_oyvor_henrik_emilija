<?php
include_once 'mySqlDb.php';
function loadMySQLadb(){
    $db = new MySqlDb();
    $q = "delete from city_locdim;
delete from state_locdim;
delete from timedim;
delete from diseasedim;
delete from state_fact;
delete from stateavg_fact;
delete from city_fact;
insert into state_fact (select SUBSTR(epi_week, 1,4) as year, disease, state, sum(cases)
from odb where loc_type = 'STATE' group by year, disease, state );
insert into stateavg_fact (select SUBSTR(epi_week, 1,4) as year, SUBSTR(epi_week, -2) as week, disease, state, avg(incidence_per_100000) from odb O where loc_type = 'STATE' group by year, week, disease, state);
insert into city_fact (select SUBSTR(epi_week, 1,4) as year, disease, state, loc, sum(cases)
from odb where loc_type = 'CITY' group by year, disease, state, loc);
insert into city_locdim (select distinct state, loc from odb where loc_type = 'CITY');
insert into state_locdim (select distinct state, loc from odb where loc_type = 'STATE');
insert into timedim (SELECT DISTINCT SUBSTR(epi_week, 1,4) as year, SUBSTR(epi_week, -2) as week from odb O);
insert into diseasedim (select distinct disease from odb O);";
    $db->query($q);
}
