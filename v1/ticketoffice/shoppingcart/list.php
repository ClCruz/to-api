<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_ticketoffice_user) {
        $query = "EXEC pr_ticketoffice_shoppingcart ?";
        $params = array($id_ticketoffice_user);
        $result = db_exec($query, $params);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $json = array("id"=>$row["id"]
            ,"created"=>$row["created"]
            ,"id_ticketoffice_user"=>$row["id_ticketoffice_user"]
            ,"id_event"=>$row["id_event"]
            ,"id_base"=>$row["id_base"]
            ,"id_apresentacao"=>$row["id_apresentacao"]
            ,"indice"=>$row["indice"]
            ,"quantity"=>$row["quantity"]
            ,"currentStep"=>$row["currentStep"]
            ,"id_payment_type"=>$row["id_payment_type"]
            ,"amount"=>$row["amount"]
            ,"amount_discount"=>$row["amount_discount"]
            ,"amount_topay"=>$row["amount_topay"]);
            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_ticketoffice_user"]);
?>