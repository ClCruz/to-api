<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/cardbin.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/session.php");

    make_purchase_boleto_email(188, "https://api.pagar.me/1/boletos/live_cjt5u2p5f1pij823c3q6oojis");//, $purchase_gateway["boleto_barcode"], $purchase_gateway["boleto_expiration_date"]);
?>