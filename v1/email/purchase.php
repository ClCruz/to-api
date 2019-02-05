<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    make($_REQUEST["id_pedido_venda"], null, null);
    echo json_encode(array("success"=>true, "msg"=>"E-mail enviado."));
    die();
?>