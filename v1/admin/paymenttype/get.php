<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_paymenttype_get ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "CodBanco" => $row["CodBanco"]
                ,"CodForPagto" => $row["CodForPagto"]
                ,"CodTipForPagto" => $row["CodTipForPagto"]
                ,"ForPagto" => $row["ForPagto"]
                ,"PcTxAdm" => $row["PcTxAdm"]
                ,"PrzRepasseDias" => $row["PrzRepasseDias"]
                ,"showorder" => $row["showorder"]
                ,"StaDebBordLiq" => $row["StaDebBordLiq"]
                ,"StaForPagto" => $row["StaForPagto"]
                ,"StaPagarMe" => $row["StaPagarMe"]
                ,"StaTaxaCartoes" => $row["StaTaxaCartoes"]
                ,"TipCaixa" => $row["TipCaixa"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"], $_POST["id_base"]);
?>