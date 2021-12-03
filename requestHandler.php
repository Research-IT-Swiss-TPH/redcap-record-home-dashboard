<?php

/** @var \STPH\recordHomeDashboard\recordHomeDashboard $module */

if ($_REQUEST['action'] == 'get-dashboard-data') {
    $module->getDashboardData();
}

else if($_POST['action'] == 'save-dashboard-data') {
    $module->saveDashboardData($_POST['new']);
}

else if($_REQUEST['action'] == 'render-element-content') {
    $type = htmlentities($_REQUEST['type'], ENT_QUOTES);
    $content = json_decode($_REQUEST['content']);
    $params = json_decode($_REQUEST['params']);    
    $module->renderElementContent($type, $content, $params);
}

else if($_REQUEST['action'] == 'get-field-for-instrument') {
    $instrument = htmlentities($_REQUEST['instrument'], ENT_QUOTES);
    $module->getFieldForInstrument($instrument);
}

else {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json; charset=UTF-8');    
    die("The action does not exist.");
}