<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($api) {
        //sleep(5);
        $query = "EXEC pr_quotapartner_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"value"=>$row["id"]
                ,"text"=>$row["name"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["apikey"]);
?>