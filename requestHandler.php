<?php

/** @var \STPH\recordHomeDashboard\recordHomeDashboard $module */

if ($_REQUEST['action'] == 'get-dashboard-data') {
    $module->getDashboardData();
}

 else if($_REQUEST['action'] == 'save-dashboard-data') {
    $module->saveDashboardData($_REQUEST['new']);
 }

else {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json; charset=UTF-8');    
    die("The action does not exist.");
}