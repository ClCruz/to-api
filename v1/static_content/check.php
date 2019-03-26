<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($apikey) {
        //sleep(5);
        $query = "EXEC pr_partner_static_page_check ?";
        $params = array($apikey);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"isvisible"=> $row["isvisible"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["apikey"]);
?>