<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/cardbin.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/session.php");

    function refundme($id_pedido_venda) {

      $transaction = gettransactionbypedido($id_pedido_venda);
      
      //die("ddd".json_encode($transaction["cd_numero_transacao"]));
        $aux = pagarme_refund($transaction["cd_numero_transacao"], 0);
  
        echo json_encode($aux);
        logme();
        die();    
    }
  refundme($_POST["id_pedido_venda"]);
  // refundme($_REQUEST["id_pedido_venda"]);

?>