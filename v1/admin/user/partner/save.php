<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $id_partner, $loggedId) {
        $query = "EXEC pr_to_admin_user_add_partner ?, ?, ?";
        $params = array($loggedId, $id, $id_partner);

        $result = db_exec($query, $params);


        $json = array("success"=>true
        ,"msg"=>"Sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["id_partner"], $_POST["loggedId"]);
?>