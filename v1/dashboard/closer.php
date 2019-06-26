<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey) {

        $query = "EXEC pr_dashboard_closer_event ?";
        $params = array($loggedId);
        $result = db_exec($query, $params);

        $json = array();
            
        foreach ($result as &$row) {
            $json = array(
                "id_evento" => $row["id_evento"],
                "id_base" => $row["id_base"],
                "date" => $row["date"],
                "hour" => $row["hour"],
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"]);
?>