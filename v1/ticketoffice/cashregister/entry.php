<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id_ticketoffice_user, $type, $amount, $justification) {
        $query = "EXEC pr_ticketoffice_cashregister_add ?,?,?,?,?";
        $params = array($id_base, $id_ticketoffice_user, $type, $amount, $justification);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array("success"=> $row["success"], "msg"=> $row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }
    
set($_POST["id_base"], $_POST["id_ticketoffice_user"], $_POST["type"], $_POST["amount"], $_POST["justification"]);
?>