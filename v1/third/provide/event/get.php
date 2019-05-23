<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

//    stopIfApiNotExist();


function get($city = null, $state = null, $api = null, $date = null, $filter = null) {
        $query = "EXEC pr_geteventsforcards ?, ?, ?, ?, ?";
        $params = array(db_param($city), db_param($state), $api, $date, $filter);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "isdiscovery"=>0
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["city"],$_POST["state"], $_REQUEST["apikey"], $_POST["date"], $_POST["filter"]);

?>