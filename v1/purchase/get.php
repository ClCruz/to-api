<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $codVenda, $nin, $id_apresentacao) {
        $query = "EXEC pr_getpurchase ?, ?, ?";
        $params = array(db_param($codVenda), db_param($nin), db_param($id_apresentacao));
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $aux = array("Nome"=>$row["Nome"]
                        ,"CPF"=>$row["CPF"]
                        ,"Indice"=>$row["Indice"]
                        ,"NomSetor"=>$row["NomSetor"]
                        ,"NomSala"=>$row["NomSala"]
                        ,"NomPeca"=>$row["NomPeca"]
                        ,"HorSessao"=>$row["HorSessao"]
                        ,"DatApresentacao"=>$row["DatApresentacao"]
                        ,"ValPagto"=>$row["ValPagto"]
                        ,"CodVenda"=>$row["CodVenda"]
                        ,"purchaseType"=>$row["purchaseType"]
                        ,"TipBilhete"=>$row["TipBilhete"]
                        ,"created"=>$row["created"]
                        ,"NomObjeto"=>$row["NomObjeto"]
                        ,"_rowVariant"=>""
                        ,"refundInGateway"=>$row["refundInGateway"]
                        ,"id_pedido_venda"=>$row["id_pedido_venda"]);
            
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["codVenda"], $_REQUEST["nin"], $_REQUEST["id_apresentacao"]);
?>