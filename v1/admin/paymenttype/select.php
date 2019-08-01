<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_paymenttype_select";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
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
                ,"value"=>$row["CodForPagto"]
                ,"text"=>$row["ForPagto"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"]);
?>