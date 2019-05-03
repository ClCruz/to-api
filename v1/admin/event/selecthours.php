<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function listevents($id_base, $id_evento, $datePresentation) {
        $query = "EXEC pr_admin_event_hour_select ?, ?";
        $params = array($id_evento, $datePresentation);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array(
                    "hr_apresentacao"=>$row["hr_apresentacao"]
                    ,"text"=>$row["hr_apresentacao"]
                    ,"value"=>$row["hr_apresentacao"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    listevents($_REQUEST["id_base"], $_REQUEST["id_evento"], $_REQUEST["datePresentation"]);
?>