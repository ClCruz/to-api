<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_ticketoffice_userwithpermission ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_base);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"login" => $row["login"]
                ,"email" => $row["email"]
                ,"value" => $row["id"]
                ,"text" => $row["name"]." (".$row["login"].")"
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"]);
?>