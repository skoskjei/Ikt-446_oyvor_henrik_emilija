<?php


function getYearUrlParamTable2()
{
    //set default if not provided.

    if (isset($_GET['yeartb2'])) {
        return $_GET['yeartb2'];
    }
    return '';
}
