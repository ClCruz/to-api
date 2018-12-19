<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        $query = "EXEC pr_partner ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"created" => $row["created"]
                ,"key" => $row["key"]
                ,"key_test" => $row["key_test"]
                ,"name" => $row["name"]
                ,"active" => $row["active"]
                ,"dateStart" => $row["dateStart"]
                ,"dateEnd" => $row["dateEnd"]
                ,"domain" => $row["domain"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"]);
?>