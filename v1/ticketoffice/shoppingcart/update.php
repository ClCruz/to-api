<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($id_base, $id, $indice, $id_ticket_type) {
        $query = "EXEC pr_ticketoffice_shoppingcart_tickettype ?,?,?";
        $params = array($id, $indice, db_param($id_ticket_type));
        $result = db_exec($query, $params, $id_base);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }    
call($_REQUEST["id_base"],$_REQUEST["id"],$_REQUEST["indice"],$_REQUEST["id_ticket_type"]);
?>