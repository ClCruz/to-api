<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function open($id_base, $id) {

        $query = "EXEC pr_ticketoffice_cashregister_open ?, ?";
        $params = array($id, $id_base);
        $result = db_exec($query, $params);

        $isOpen = false;
        
        foreach ($result as &$row) {
            $json = array("success"=>$row["success"], "alreadyopened"=>$row["alreadyopened"], "msg"=>$row["msg"]);
        }
        echo json_encode($json);    

        logme();
        die();    
    }
open($_POST["id_base"],$_POST["id"]);
?>