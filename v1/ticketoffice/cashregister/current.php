<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_ticketoffice_cashregister_status ?, ?";
        $params = array($id, $id_base);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"]
            ,"isopen"=>$row["isopen"]
            ,"needclose"=>$row["needclose"]
            ,"openhours"=>$row["openhours"]);
            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"]);
?>