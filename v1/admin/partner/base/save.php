<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_partner, $id_base, $loggedId) {
        $query = "EXEC pr_to_admin_partner_add_base ?, ?";
        $params = array($id_partner, $id_base);

        $result = db_exec($query, $params);


        $json = array("success"=>true
        ,"msg"=>$row["msg"]);

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_partner"], $_POST["id_base"], $_POST["loggedId"]);
?>