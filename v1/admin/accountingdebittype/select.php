<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_accountingdebittype_select";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
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
                ,"QtdLimiteIngrParaVenda" => $row["QtdLimiteIngrParaVenda"]
                ,"StaDebBordero" => $row["StaDebBordero"]
                ,"StaDebBorderoLiq" => $row["StaDebBorderoLiq"]
                ,"TipValor" => $row["TipValor"]
                ,"ValIngressoExcedente" => $row["ValIngressoExcedente"]
                ,"VlMinimo" => $row["VlMinimo"]
                ,"value"=>$row["CodTipDebBordero"]
                ,"text"=>$row["DebBordero"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"]);
?>