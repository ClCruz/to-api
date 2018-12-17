<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function token($login) {
        $query = "EXEC pr_logout_admin_successfully ?";
        $params = array(db_param($login));
        $result = db_exec($query, $params);
        $json = array("logged"=>false
        ,"login"=>$login
        ,"msg"=>"Executado com sucesso.");

        echo json_encode($json);
        logme();
        die();    
    }

token($_REQUEST["id"]);

?>