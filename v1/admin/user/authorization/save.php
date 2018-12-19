<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $id_auth, $loggedId) {
        $query = "EXEC pr_to_admin_user_add_auth ?,?,?";
        $params = array($loggedId, $id, $id_auth);

        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>$row["msg"]);

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["id_auth"], $_POST["loggedId"]);
?>