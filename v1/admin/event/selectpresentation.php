<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $id_evento) {

        $query = "EXEC pr_admin_event_presentation_select ?";

        $params = array($id_evento);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_apresentacao" => $row["id_apresentacao"]
                ,"dt_apresentacao" => $row["dt_apresentacao"]
                ,"hr_apresentacao" => $row["hr_apresentacao"]
                ,"ds_piso" => $row["ds_piso"]

                ,"value"=>$row["id_apresentacao"]
                ,"text"=>$row["dt_apresentacao"]." ".$row["hr_apresentacao"]." - ".$row["ds_piso"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_evento"]);
?>