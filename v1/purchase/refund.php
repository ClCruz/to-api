<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/config/pagarme.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");

    function call($id_base, $id_ticketoffice_user, $codVenda, $all, $indiceList, $dogateway) {
        $query = "EXEC pr_refund ?, ?, ?, ?";
        $params = array($id_ticketoffice_user, $codVenda, $all, $indiceList);
        $result = db_exec($query, $params, $id_base);
        $retPagarme = "";
        $aux = array();
        $info = array();
        foreach ($result as &$row) {
            if ($row["success"] == "1" || $row["success"] == 1) {
                $info = array("key" => $row["key"], "amount" => $row["amount"]);
                $retPagarme = pagarme_refund($row["key"], $row["amount"]);
            }

//            $aux = array("key"=>$row["key"]
//            ,"amount"=>$row["amount"]);

 //           array_push($json,$aux);
        }

        $json = array("success"=>true, "msg"=>$retPagarme, "moreinfo"=>$info);

        echo json_encode($json);
        logme();
        die();    
    }


//    refundPagarme($_REQUEST["id"], $_REQUEST["amount"]);
call($_REQUEST["id_base"],$_REQUEST["id_ticketoffice_user"], $_REQUEST["codVenda"], $_REQUEST["all"], $_REQUEST["indiceList"], $_REQUEST["dogateway"]);
?>