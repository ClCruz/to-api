<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $codPeca, $id_apresentacao) {
        $query = "EXEC pr_tickettype ?, ?";
        $params = array($codPeca, $id_apresentacao);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("CodTipBilhete"=>$row["CodTipBilhete"]
            ,"TipBilhete"=>$row["TipBilhete"]
            ,"PerDesconto"=>$row["PerDesconto"]
            ,"acrdscperc"=>$row["acrdscperc"]
            ,"acrdscvlr"=>$row["acrdscvlr"]
            ,"vl_preco_fixo"=>$row["vl_preco_fixo"]
            ,"StaTipBilhMeiaEstudante"=>$row["StaTipBilhMeiaEstudante"]
            ,"text"=>$row["TipBilhete"]
            ,"value"=>$row["CodTipBilhete"]);
            array_push($json,$aux);
        }
        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["codPeca"], $_REQUEST["id_apresentacao"]);
?>