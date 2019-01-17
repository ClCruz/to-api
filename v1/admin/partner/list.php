<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $search, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_admin_partner ?,?,?,?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($loggedId, $search, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"active" => $row["active"]
                ,"created" => $row["created"]
                ,"dateEnd" => $row["dateEnd"]
                ,"dateStart" => $row["dateStart"]
                ,"isTrial" => $row["domain"]
                ,"isDemo" => $row["isDemo"]
                ,"isTrial" => $row["isTrial"]
                ,"isDev" => $row["isDev"]
                ,"key" => $row["key"]
                ,"key_test" => $row["key_test"]
                ,"name" => $row["name"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["search"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>