<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId) {
        //sleep(5);
        //die("ddd".json_encode($loggedId));
        $query = "EXEC pr_partner_list_withpermission ?";
        $params = array($loggedId);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "key" => $row["key"]
                ,"name" => $row["name"]
                ,"uniquename"=> $row["uniquename"]
                ,"value"=>$row["key"]
                ,"text"=>$row["name"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["loggedId"]);
?>