<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id_ticketoffice_user, $amount, $justificative) {
        $query = "EXEC pr_cashregister_close ?, ?, ?";
        $params = array($id_ticketoffice_user, $amount, $justificative);
        $result = db_exec($query, $params, $id_base);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }
    
set($_REQUEST["id_base"], $_POST["id_ticketoffice_user"], $_POST["amount"], $_POST["justificative"]);
?>