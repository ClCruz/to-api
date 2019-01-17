<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_admin_partner_get ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"created"=> $row["created"]
                ,"dateEnd"=> $row["dateEnd"]
                ,"dateStart"=> $row["dateStart"]
                ,"domain"=> $row["domain"]
                ,"active"=> $row["active"]
                ,"key"=> $row["key"]
                ,"key_test"=> $row["key_test"]
                ,"isDemo"=> $row["isDemo"]
                ,"isTrial"=> $row["isTrial"]
                ,"isDev"=> $row["isDev"]
                ,"type"=> $row["type"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id"]);
?>