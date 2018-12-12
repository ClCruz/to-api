<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id_ticketoffice_user, $id_payment) {
        $query = "EXEC pr_sell_type ?, ?";
        $params = array($id_ticketoffice_user, $id_payment);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {

            $json = array("nextStep"=>$row["nextStep"]
            ,"isMoney"=>$row["isMoney"]
            ,"isCreditCard"=>$row["isCreditCard"]
            ,"isDebitCard"=>$row["isDebitCard"]
            ,"isFree"=>$row["isFree"]
            ,"PagarMe"=>$row["PagarMe"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id_ticketoffice_user"], $_REQUEST["id_payment"]);
?>