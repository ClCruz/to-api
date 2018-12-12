<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_cashregister_list ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array("CodTipForPagto"=>$row["CodTipForPagto"]
            ,"TipForPagto"=>$row["TipForPagto"]
            ,"amount"=>$row["amount"]
            ,"amountInput"=>$row["amount"]
            ,"date"=>$row["date"]);
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"]);
?>