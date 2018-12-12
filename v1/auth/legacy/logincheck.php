<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function login($login) {
        //sleep(5);
        $query = "EXEC pr_login_client_check ?";
        $params = array(db_param($login));
        $result = db_exec($query, $params);

        $json = array("exist"=>0);

        foreach ($result as &$row) {
            $json = array("exist"=>$row["exist"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

login($_REQUEST["login"]);

?>