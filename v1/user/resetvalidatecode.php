<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($code) {
        $query = "EXEC pr_user_resetpass_validate ?";
        $params = array($code);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array(
                "success"=>$row["success"]
                ,"login"=>$row["login"]
                ,"msg"=>$row["msg"]
            );
        }

        echo json_encode($json);
        logme();
        die();     
    }
    
get($_POST["code"]);
?>