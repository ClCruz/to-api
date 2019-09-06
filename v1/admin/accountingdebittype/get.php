<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_accountingdebittype_get ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
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
                ,"sell_channel" => $row["sell_channel"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"], $_POST["id_base"]);
?>