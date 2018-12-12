<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($token) {
        //sleep(5);
        $query = "EXEC pr_login_keepalive ?";
        $params = array($token);
        $result = db_exec($query, $params);

        $json = array("success"=>1);

        echo json_encode($json);
        logme();
        die();    
    }

set($_REQUEST["token"]);

?>