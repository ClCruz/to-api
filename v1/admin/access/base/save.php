<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $id_base) {
        $query = "EXEC pr_to_admin_user_add_base ?, ?, NULL";
        $params = array($id, $id_base);

        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>$row["msg"]);

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["id_base"]);
?>