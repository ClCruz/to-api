<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id_base, $id_evento, $CodTipDebBordero) {
        $query = "EXEC pr_accountingdebittype_event_delete ?,?";
        $params = array($id_evento, $CodTipDebBordero);
        // die(json_encode($params));
        $result = db_exec($query, $params, $id_base);
        // die(json_encode($id_base));

        foreach ($result as &$row) {
            // die(json_encode($row));
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();      
    }

execute($_POST["id_base"], $_POST["id_evento"], $_POST["CodTipDebBordero"]);
?>