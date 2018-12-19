<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($search, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_to_admin_user ?,?,?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($search, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"login" => $row["login"]
                ,"email" => $row["email"]
                ,"active" => $row["active"]
                ,"document" => $row["document"]
                ,"lastLogin" => $row["lastLogin"]
                ,"created" => $row["created"]
                ,"updated" => $row["updated"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["search"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>