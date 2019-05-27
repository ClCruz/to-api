<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchaseb2b.php");

    function doafter($id, $id_pedido_venda) {        
        $ret = change_situacao_boleto($id);

        if ($ret["success"] == true || $ret["success"] == 1) {
            make_purchase_email($id_pedido_venda, $ret["email_address"],$ret["email_name"],"");
            make_purchase_email_b2b($id_pedido_venda);
        }

        echo json_encode(array("success"=>true));
        logme();
        die();    
    }

doafter($_POST["id"], $_POST["id_pedido_venda"]);


?>