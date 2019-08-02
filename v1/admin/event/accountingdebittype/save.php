<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id_base, $id_evento, $CodTipDebBordero, $start, $end) {
        $query = "EXEC pr_accountingdebittype_event_save ?,?,?,?";
        $params = array($id_evento, $CodTipDebBordero, $start, $end);
        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();      
    }

execute($_POST["id_base"], $_POST["id_evento"], $_POST["CodTipDebBordero"], $_POST["start"], $_POST["end"]);
?>