<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_to_admin_user_get ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"document" => $row["document"]
                ,"login" => $row["login"]
                ,"email" => $row["email"]
                ,"active" => $row["active"]
                ,"created" => $row["created"]
                ,"updated" => $row["updated"]
                ,"pair" => $row["pair"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"]);
?>