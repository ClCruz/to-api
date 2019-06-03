<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $search, $api, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_quotapartner ?, ?, ?, ?, ?";
        $params = array($loggedId, $search,$api, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"active"=>$row["active"]
                ,"key"=>$row["key"]
                
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["loggedId"],$_REQUEST["search"],$_REQUEST["apikey"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>