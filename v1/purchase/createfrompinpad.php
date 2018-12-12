<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($key) {
        $query = "EXEC pr_pinpad_get ?";
        $params = array($key);
        $result = db_exec($query, $params);

        $id_ticketoffice_user = null;
        $id_payment = null;
        $isComplementoMeia = '';
        $codCliente = null;
        $id_base = null;

        foreach ($result as &$row) {
            $id_ticketoffice_user = $row["id_ticketoffice_user"];
            $id_base = $row["id_base"];
            $id_payment = $row["id_payment"];
            $codCliente = $row["codCliente"];
        }

        if ($id_base == null)
            die("error");

        $query = "EXEC pr_sell ?, ?, ?, ?";
        $params = array($id_ticketoffice_user, $id_payment, $isComplementoMeia, db_param($codCliente));
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {

            $json = array("codVenda"=>$row["codVenda"]
            ,"id_pedido_venda"=>$row["id_pedido_venda"]
            ,"nextStep"=>$row["nextStep"]
            ,"isMoney"=>$row["isMoney"]
            ,"isCreditCard"=>$row["isCreditCard"]
            ,"isDebitCard"=>$row["isDebitCard"]
            ,"isFree"=>$row["isFree"]
            ,"PagarMe"=>$row["PagarMe"]);
            
            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["key"]);
?>