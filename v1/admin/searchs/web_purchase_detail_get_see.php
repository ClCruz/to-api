<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");

    function log_see($id_pedido_venda, $loggedId) {
        $type = "see";
        $query = "EXEC pr_purchase_web_ticket_log_save ?,?,?";
        $params = array($type,$loggedId,$id_pedido_venda);
        db_exec($query, $params);
    }

    function get($loggedId, $id_pedido_venda) {
        $query = "EXEC pr_web_purchase_get ?,?";
        $uniquename = gethost();
        // $uniquename = "ingressoparatodos";
        // $uniquename = "viveringressos";
        $params = array($uniquename, $id_pedido_venda);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $see = getwhitelabelURI_api("v1/print/web/ticket_html?codVenda=".$row["CodVenda"]."&id_base=".$row["id_bae"]."&lme=y");
        }

        $json = array("see"=>$see);
        
        log_see($id_pedido_venda, $loggedId);

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["id_pedido_venda"]);
?>