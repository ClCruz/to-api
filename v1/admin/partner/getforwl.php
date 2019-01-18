<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_admin_partner_get_wl ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"uniquename" => $row["uniquename"]
                ,"domain"=> $row["domain"]
                ,"databaseOK"=> $row["databaseOK"]
                ,"userOK"=> $row["userOK"]
                ,"databaseStatus"=> $row["databaseStatus"]
                ,"userStatus"=> $row["userStatus"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id"]);
?>