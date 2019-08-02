<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $id_base, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_accountingdebittype_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "Ativo" => $row["Ativo"]
                ,"CodTipBilhete" => $row["CodTipBilhete"]
                ,"CodTipDebBordero" => $row["CodTipDebBordero"]
                ,"DebBordero" => $row["DebBordero"]
                ,"in_DescontaCartao" => $row["in_DescontaCartao"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"PerDesconto_formatted" => $row["PerDesconto_formatted"]
                ,"QtdLimiteIngrParaVenda" => $row["QtdLimiteIngrParaVenda"]
                ,"StaDebBordero" => $row["StaDebBordero"]
                ,"StaDebBorderoLiq" => $row["StaDebBorderoLiq"]
                ,"TipValor" => $row["TipValor"]
                ,"TipValorDesc" => $row["TipValorDesc"]
                ,"ValIngressoExcedente" => $row["ValIngressoExcedente"]
                ,"VlMinimo" => $row["VlMinimo"]
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