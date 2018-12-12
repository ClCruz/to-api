<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($currentStep,$id_ticketoffice_user,$id_event,$id_base,$id_apresentacao,$indice,$quantity,$id_payment_type,$amount,$amount_discount,$amount_topay) {
        $query = "EXEC pr_ticketoffice_shoppingcart_add ?,?,?,?,?,?,?,?,?,?,?";
        //pr_ticketoffice_shoppingcart_add (@currentStep varchar(10), @id_ticketoffice_user UNIQUEIDENTIFIER, @id_event INT, @id_base INT, @id_apresentacao INT,@indice INT, @quantity INT, @id_payment_type INT, @amount INT, @amount_discount INT, @amount_topay INT)
        $params = array($currentStep,$id_ticketoffice_user,$id_event,$id_base,$id_apresentacao,$indice,$quantity,db_param($id_payment_type),db_param($amount),db_param($amount_discount),db_param($amount_topay));
        $result = db_exec($query, $params, $id_base);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }    
call($_REQUEST["currentStep"],$_REQUEST["id_ticketoffice_user"],$_REQUEST["id_event"],$_REQUEST["id_base"],$_REQUEST["id_apresentacao"],$_REQUEST["indice"],$_REQUEST["quantity"],$_REQUEST["id_payment_type"],$_REQUEST["amount"],$_REQUEST["amount_discount"],$_REQUEST["amount_topay"]);
?>