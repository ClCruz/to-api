<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id_ticketoffice_user, $date) {
        $query = "EXEC pr_ticketoffice_cashregister_closedbydate ?, ?, ?";
        $params = array($id_base, $id_ticketoffice_user, $date);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id"=>$row["id"]
                ,"created"=>$row["created"]
                ,"closed"=>$row["closed"]
                ,"justification_closed"=>$row["justification_closed"]
                ,"name"=>$row["name"]
                ,"login"=>$row["login"]
                ,"email"=>$row["email"]
                ,"hasDiff"=>$row["hasDiff"]
                ,"text"=>$row["closed"]
                ,"value"=>$row["id"]
            );
        }

        echo json_encode($json);
        logme();
        die();     
    }
    
set($_REQUEST["id_base"], $_POST["id_ticketoffice_user"], $_POST["date"]);
?>