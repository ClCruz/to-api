<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($api) {
        //sleep(5);
        $query = "EXEC pr_adm_ticketoffice_users ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"login" => $row["login"]
                ,"email" => $row["email"]
                ,"active" => $row["active"]
                ,"created" => $row["created"]
                ,"updated" => $row["updated"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"], $_REQUEST["apikey"]);
?>