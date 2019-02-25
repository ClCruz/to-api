<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_ticketoffice_cashregister_status ?, ?";
        $params = array($id, $id_base);
        $result = db_exec($query, $params);

        $aux = array();

        foreach ($result as &$row) {
            $aux = array("success"=>$row["success"]
            ,"isopen"=>$row["isopen"]
            ,"needclose"=>$row["needclose"]
            ,"openhours"=>$row["openhours"]);
        }

        $isOk = false;
        $isOpen = false;
        $needClose = false;

        if ($aux["isopen"] == 1 || $aux["isopen"] == true || $aux["isopen"] == "1") {
            $isOpen = true;
            if ($aux["needclose"] == 1 || $aux["needclose"] == true || $aux["needclose"] == "1") {
                $needClose = true;
            }
            else {
                $isOk = true;
            }
        }

        $json = array("isok"=>$isOk
                    ,"isopen"=>$isOpen
                    ,"needClose"=>$needClose
                    ,"hoursOpened"=>$aux["openhours"]);

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"]);
?>