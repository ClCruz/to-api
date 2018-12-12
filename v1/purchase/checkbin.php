<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($key, $bin) {

        $query = "EXEC pr_pinpad_get_base ?";
        $params = array($key);
        $result = db_exec($query, $params);

        $id_base = null;
        foreach ($result as &$row) {
            $id_base = $row["id_base"];
        }

        $query = "EXEC pr_checkbin ?, ?";
        $params = array($key, $bin);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array("check"=>$row["check"]
                        ,"success"=>$row["success"]);
            
            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["key"], $_REQUEST["bin"]);
?>