<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function get($id_pedido_venda) {
        $query = "EXEC pr_purchase_web_ticket_log_list ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"type" => $row["type"]
                ,"login" => $row["login"]
                ,"created" => $row["created"]
            );
        }


        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_pedido_venda"]);
?>