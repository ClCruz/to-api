<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function getTipValor($code,$value) {
        $ret = "";
        switch ($code) {
            case "F":
                $ret = "Valor fixo de R$ ".$value;
            break;
            case "V":
                $ret = "Valor de R$ ".$value;
            break;
            case "P":
                $ret = "Valor por porcentagem de ".$value."%";
            break;
        }

        return $ret;
    }

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
                ,"PerDesconto_formatted" => $row["PerDesconto_formatted"]
                ,"QtdLimiteIngrParaVenda" => $row["QtdLimiteIngrParaVenda"]
                ,"StaDebBordero" => $row["StaDebBordero"]
                ,"StaDebBorderoLiq" => $row["StaDebBorderoLiq"]
                ,"TipValor" => $row["TipValor"]
                ,"ValIngressoExcedente" => $row["ValIngressoExcedente"]
                ,"VlMinimo" => $row["VlMinimo"]
                ,"value"=>$row["CodTipDebBordero"]
                ,"text"=>$row["DebBordero"]." - ".getTipValor($row["TipValor"], $row["PerDesconto_formatted"])
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"]);
?>