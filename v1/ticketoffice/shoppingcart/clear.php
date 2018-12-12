<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($id_ticketoffice_user) {
        $query = "EXEC pr_ticketoffice_shoppingcart_clear ?";
        //pr_ticketoffice_shoppingcart_add (@currentStep varchar(10), @id_ticketoffice_user UNIQUEIDENTIFIER, @id_event INT, @id_base INT, @id_apresentacao INT,@indice INT, @quantity INT, @id_payment_type INT, @amount INT, @amount_discount INT, @amount_topay INT)
        $params = array($id_ticketoffice_user);
        $result = db_exec($query, $params);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }    
call($_REQUEST["id_ticketoffice_user"]);
?>