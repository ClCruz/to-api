<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($token) {
        //sleep(5);
        $query = "EXEC pr_login_client_validtoken ?";
        $params = array($token);
        $result = db_exec($query, $params);

        $json = array("isvalid"=>0);

        foreach ($result as &$row) {
            $json = array("isvalid"=>$row["isvalid"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["token"]);

?>