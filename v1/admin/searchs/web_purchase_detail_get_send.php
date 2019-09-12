<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    
    function log_see($id_pedido_venda, $loggedId) {
        $type = "send";
        $query = "EXEC pr_purchase_web_ticket_log_save ?,?,?";
        $params = array($type,$loggedId,$id_pedido_venda);
        db_exec($query, $params);
    }

    function get($loggedId, $id_pedido_venda) {
        
        make_purchase_email($id_pedido_venda, null, null, false);
        log_see($id_pedido_venda, $loggedId);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["id_pedido_venda"]);
?>