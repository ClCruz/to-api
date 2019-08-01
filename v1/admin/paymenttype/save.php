<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function get(
        $id_base
        ,$CodForPagto
        ,$CodBanco
        ,$CodTipForPagto
        ,$ForPagto
        ,$PcTxAdm
        ,$PrzRepasseDias
        ,$showorder
        ,$StaDebBordLiq
        ,$StaForPagto
        ,$StaPagarMe
        ,$StaTaxaCartoes
        ,$TipCaixa
        ) {
            
            $StaDebBordLiq = $StaDebBordLiq == 1 ? 'S' : 'N';
            $StaForPagto = $StaForPagto == 1 ? 'A' : 'I';
            $StaPagarMe = $StaPagarMe == 1 ? 'S' : 'N';
            $StaTaxaCartoes = $StaTaxaCartoes == 1 ? 'S' : 'N';
            $TipCaixa = $TipCaixa == 1 ? 'A' : 'C';
        

        $query = "EXEC pr_paymenttype_save ?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array(
            $CodForPagto
            ,$CodBanco
            ,$CodTipForPagto
            ,$ForPagto
            ,$PcTxAdm
            ,$PrzRepasseDias
            ,$showorder
            ,$StaDebBordLiq
            ,$StaForPagto
            ,$StaPagarMe
            ,$StaTaxaCartoes
            ,$TipCaixa
        );

        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id_base"]
    ,$_POST["CodForPagto"]
    ,$_POST["CodBanco"]
    ,$_POST["CodTipForPagto"]
    ,$_POST["ForPagto"]
    ,$_POST["PcTxAdm"]
    ,$_POST["PrzRepasseDias"]
    ,$_POST["showorder"]
    ,$_POST["StaDebBordLiq"]
    ,$_POST["StaForPagto"]
    ,$_POST["StaPagarMe"]
    ,$_POST["StaTaxaCartoes"]
    ,$_POST["TipCaixa"]
);
?>