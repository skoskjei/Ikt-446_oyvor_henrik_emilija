<?php
include_once 'MongoDB/mongoDBOlap.php';
$query = new MongoDBOlap();
include_once 'ides.php';

?>

<!doctype html>
<html lang="en">
<?php include_once 'header.php'; ?>

<main role="main" class="container">
    <div class="jumbotron">
        <h1 id="type_select">MongoDB</h1>
        <p class="lead">Welcome! This site explores the Tycho level 1 database</p>

        <div class="container">

            <div class="row">
                <div class="col">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="yeartb1"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <a class="dropdown-item year_state" href="#" onclick="setYearTb1('')">All</a>
                                        <?php
                                        $years = $query->getYearDropDown_state();

                                        foreach ($years as $row) {
                                            echo '<a class="dropdown-item year_state" href= "#" onclick="setYearTb1('. $row .' )">';
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="statetb1"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        State
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <a class="dropdown-item year_state" href="#" onclick="setStateTb1('')">All</a>
                                        <?php
                                        $states = $query->getStateDropDown_state();

                                        foreach ($states as $row) {
                                            echo '<a class="dropdown-item year_state" href= "#"';
                                            echo "onclick=setStateTb1('$row')>";
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <!-- Page on the right -->
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    <hr>
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="yeartb3"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <a class="dropdown-item year_state" href="#" onclick="setYearTb3('')">All</a>
                                        <?php
                                        $years = $query->getYearDropDown_state();

                                        foreach ($years as $row) {
                                            echo '<a class="dropdown-item year_state" href= "#"';
                                            echo "onclick=setYearTb3('$row')>";
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="statetb3"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        State
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <a class="dropdown-item year_state" href="#" onclick="setStateTb3('')">All</a>
                                        <?php
                                        $states = $query->getStateDropDown_state();

                                        foreach ($states as $row) {
                                            echo '<a class="dropdown-item year_state" href= "#"';
                                            echo "onclick=setStateTb3('$row')>";
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="disease"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Disease
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <?php
                                        $diseases = $query->getDiseaseDropDown_state();

                                        foreach ($diseases as $row) {
                                            echo '<a class="dropdown-item year_state" href= "#"';
                                            echo "onclick=setDisease('$row')>";
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <!-- Page on the right -->
                    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                </div>
                <div class="col">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year
                                        <?php
                                        echo  getYearUrlParamTable2()
                                        ?>

                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"
                                         style="overflow-y:scroll; height: 200px;">
                                        <?php
                                        $years = $query->getYearDropDown_city();

                                        foreach ($years as $row) {
                                            echo '<a class="dropdown-item" href="?yeartb2=' . $row . '">';
                                            echo $row;
                                            echo '</a>';
                                        }

                                        ?>
                                    </div>

                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div style="overflow-y:scroll; height: 820px;">

                        <table class="table table-striped tableFixHead">
                            <thead>
                            <tr>
                                <th scope="col">City</th>
                                <th scope="col">State</th>
                                <th scope="col">Disease</th>
                                <th scope="col">Number of cases</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Get data blabla from db
                            #$result = $query->getDataForTableTwo(getYearUrlParam());
                            #$result = $graph_query->getDataForTableTwo(getYearUrlParam());
                            $result = $query->getDataForTableTwo(getYearUrlParamTable2());

                            foreach ($result as $r) {
                                echo "<tr>";
                                echo "<td>" . $r[0] . "</td>";
                                echo "<td>" . $r[1] . "</td>";
                                echo "<td>" . $r[2] . "</td>";
                                echo "<td>" . $r[3] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>


        </div>
</main>

<script>
    let select = 'MongoDB'
    let yearTb1 = '', yearTb3 = '', stateTb1 = '', stateTb3 = '', disease = 'MUMPS';

    function setYearTb1(year){
        yearTb1 = year;
        renderTable_1();

    }
    function setStateTb1(state){
        stateTb1 = state
        renderTable_1();

    }
    function setYearTb3(year){
        yearTb3 = year

        renderTable_3();
    }
    function setStateTb3(state){
        stateTb3 = state;

        renderTable_3();
    }
    function setDisease(d = 'MUMPS'){
        disease = d
        renderTable_3();
    }

    window.onload = function (e) {
        renderTable_1();
        renderTable_3();
    }


    function DrawTableOne(dataTable1) {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "dark1", // "light1", "light2", "dark1", "dark2"
            title: {
                text: "Cases of diseases"
            },
            axisY: {
                title: "Number of cases"
            },
            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: "Diseases",
                dataPoints: dataTable1
            }]
        });
        chart.render();

    }

    function DrawTableThree(dataTable2) {

        var chart = new CanvasJS.Chart("chartContainer2", {
            animationEnabled: true,
            zoomEnabled: true,
            title: {
                text: "Weekly cases"
            },
            data: dataTable2
        });
        chart.render();

    }


    function renderTable_1() {
        document.getElementById("yeartb1").innerText = 'Year  ' +yearTb1;

        document.getElementById("statetb1").innerText = 'State ' +stateTb1;
        let url = `/MongoDB/table1.php?yeartb1=${yearTb1}&statetb1=${stateTb1}`
        //console.log(url);
        $.get(url, function (result) {
            const dataTable1 = []
            for (let i = 0; i < result.y.length; i++) {
                dataTable1.push({y: result.y[i], label: result.labels[i]})
            }
            DrawTableOne(dataTable1)
        })
    }


    function renderTable_3() {
        document.getElementById("yeartb3").innerText = 'Year  ' +yearTb3;
        document.getElementById("statetb3").innerText = 'State ' +stateTb3;
        document.getElementById("disease").innerText = 'Disease ' +disease;
        $.get(`/MongoDB/table3.php?yeartb3=${yearTb3}&disease=${disease}&statetb3=${stateTb3}`, function (result) {
            let data = [];
            const dataTable2 =[];


            let dataSeries = {type: "line"}

            for (let i = 0; i < result.y.length; i++) {
                data.push({x: result.x[i], y: result.y[i]})
            }
            dataSeries.dataPoints = data;
            dataTable2.push(dataSeries);
            console.log(dataTable2)
            DrawTableThree(dataTable2)
        })
    }


</script>
</html>
