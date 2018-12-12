<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id, $date, $codMovimento) {
        $query = "EXEC pr_movimentCashRegister ?, ?, ?";
        $params = array($id, $date, $codMovimento);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array("id"=>$row["id"]
            ,"CodCaixa"=>$row["CodCaixa"]
            ,"CodMovimento"=>$row["CodMovimento"]
            ,"DatApresentacao"=>$row["DatApresentacao"]
            ,"DatHorApresentacao"=>$row["DatHorApresentacao"]
            ,"DatMovimento"=>$row["DatMovimento"]
            ,"HorSessao"=>$row["HorSessao"]
            ,"IdOperacao"=>$row["IdOperacao"]
            ,"NomPeca"=>$row["NomPeca"]
            ,"Operacao"=>$row["Operacao"]
            ,"Qtde"=>$row["Qtde"]
            ,"Tipo"=>$row["Tipo"]
            ,"TipSaque"=>$row["TipSaque"]
            ,"Valor"=>$row["Valor"]
            ,"ValorInt"=>$row["ValorInt"]);
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["date"], $_REQUEST["codMovimento"]);
?>