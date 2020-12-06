<!doctype html>
<html lang="en">
<?php
include_once 'header.php';
include_once 'MongoDB/loadadb.php';
include_once 'MySQL/loadadb.php';

?>

<main role="main" class="container">
    <div class="jumbotron">
        <h1 id="type_select">Admin</h1>
        <p class="lead">Load ADB</p>
        <form method="post">
            <input type="submit" name="loadMongoDB"
                   class="btn btn-success" value="Load MongoDB " />
            <input type="submit" name="loadMySQL"
                   class="btn btn-success" value="Load MySQL" />
        </form>

        <?php

        if(isset($_POST['loadMongoDB']))
        {
            echo 'Loading MongoDB';
            echo "<br>";
            loadMongoDBadb();
            echo "<br>";
            echo 'Loading done';
        }
        if(isset($_POST['loadMySQL']))
        {

            echo 'Loading MySQL';
            loadMySQLadb();
            echo "<br>";
            echo 'Loading done';
        }
        ?>


    </div>

</main>
</html>
