<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($key) {
        $query = "EXEC pr_pinpad_get ?";
        $params = array($key);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array(
                "id_ticketoffice_user"=>$row["id_ticketoffice_user"]
                ,"key"=>$row["key"]
                ,"id_base"=>$row["id_base"]
                ,"base"=>$row["base"]
                ,"amount"=>$row["amount"]
                ,"codPeca"=>$row["codPeca"]
                ,"id_apresentacao"=>$row["id_apresentacao"]
                ,"id_evento"=>$row["id_evento"]
                ,"pinpad_acquirerResponseCode"=>$row["pinpad_acquirerResponseCode"]
                ,"pinpad_transactionId"=>$row["pinpad_transactionId"]
                ,"pinpad_executed"=>$row["pinpad_executed"]
                ,"pinpad_error"=>$row["pinpad_error"]
                ,"pinpad_cancel"=>$row["pinpad_cancel"]
                ,"pinpad_ok"=>$row["pinpad_ok"]
                ,"pinpad_fail"=>$row["pinpad_fail"]
                ,"codVenda"=>$row["codVenda"]
                ,"id_payment"=>$row["id_payment"]
                ,"codCliente"=>$row["codCliente"]);

        }

        echo json_encode($json);
        logme();
        die();    
    }    

    call($_REQUEST["key"]);
?>