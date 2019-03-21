<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");

    function resendmail($id_pedido_venda,$email) {        
        make_purchase_email($id_pedido_venda, "","", $email);

        echo json_encode(array("success"=>true));
        logme();
        die();    
    }
resendmail($_POST["id_pedido_venda"], $_POST["email"]);
?>