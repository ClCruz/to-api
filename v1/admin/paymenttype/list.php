<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $id_base, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_paymenttype_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
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
                ,"id_base" => $id_base
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_POST["id_base"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>