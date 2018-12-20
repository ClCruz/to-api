<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $search, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_genre ?, ?, ?, ?";
        $params = array($loggedId, $search, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"active"=>$row["active"]
                
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["loggedId"],$_REQUEST["search"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>