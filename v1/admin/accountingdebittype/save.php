<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get(
        $id_base
        ,$CodTipDebBordero
        ,$DebBordero
        ,$PerDesconto
        ,$StaDebBordero
        ,$TipValor
        ,$Ativo
        ,$VlMinimo
        ,$StaDebBorderoLiq
        ,$QtdLimiteIngrParaVenda
        ,$ValIngressoExcedente
        ,$CodTipBilhete
        ,$in_DescontaCartao
        ) {
            $StaDebBordero = $StaDebBordero == 1 ? 'A' : 'I';
            $Ativo = $StaDebBordero;
            $in_DescontaCartao = $in_DescontaCartao == 1 ? 'S' : 'N';
            $StaDebBorderoLiq = $StaDebBorderoLiq == 1 ? 'S' : 'N';
            $ValIngressoExcedente = $ValIngressoExcedente == '' ? '0' : $ValIngressoExcedente;

            $query = "EXEC pr_accountingdebittype_save ?,?,?,?,?,?,?,?,?,?,?,?";
            $params = array(
                $CodTipDebBordero
                ,$DebBordero
                ,$PerDesconto
                ,$StaDebBordero
                ,$TipValor
                ,$Ativo
                ,$VlMinimo
                ,$StaDebBorderoLiq
                ,$QtdLimiteIngrParaVenda
                ,$ValIngressoExcedente
                ,$CodTipBilhete
                ,$in_DescontaCartao
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
    ,$_POST["CodTipDebBordero"]
    ,$_POST["DebBordero"]
    ,$_POST["PerDesconto"]
    ,$_POST["StaDebBordero"]
    ,$_POST["TipValor"]
    ,$_POST["Ativo"]
    ,$_POST["VlMinimo"]
    ,$_POST["StaDebBorderoLiq"]
    ,$_POST["QtdLimiteIngrParaVenda"]
    ,$_POST["ValIngressoExcedente"]
    ,$_POST["CodTipBilhete"]
    ,$_POST["in_DescontaCartao"]
);
?>