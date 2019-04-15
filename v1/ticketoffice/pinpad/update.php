<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($key,$pinpad_acquirerResponseCode,$pinpad_transactionId,$pinpad_executed,$pinpad_error,$pinpad_cancel,$pinpad_ok,$pinpad_fail,$codVenda) {        
        $query = "EXEC pr_pinpad_update ?,?,?,?,?,?,?,?,?";
        //key,pinpad_acquirerResponseCode,pinpad_transactionId,pinpad_executed,pinpad_error,pinpad_cancel,pinpad_ok,pinpad_fail
        $params = array($key,$pinpad_acquirerResponseCode,$pinpad_transactionId,$pinpad_executed,$pinpad_error,$pinpad_cancel,$pinpad_ok,$pinpad_fail,$codVenda);
        $result = db_exec($query, $params);

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }    
call($_REQUEST["key"],$_REQUEST["pinpad_acquirerResponseCode"],$_REQUEST["pinpad_transactionId"],$_REQUEST["pinpad_executed"],$_REQUEST["pinpad_error"],$_REQUEST["pinpad_cancel"],$_REQUEST["pinpad_ok"],$_REQUEST["pinpad_fail"],$_REQUEST["codVenda"]);
?>