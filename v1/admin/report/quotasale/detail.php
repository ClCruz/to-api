<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $comission, $codVenda, $id_base, $id_quotapartner) {
        $uniquename = gethost();
        // $uniquename = "sazarte";
        $query = "EXEC pr_report_quotasale_detail ?,?,?,?";
        // die("dd".json_encode($codVenda));
        $params = array($comission,$codVenda, $id_base, $id_quotapartner);
        $result = db_exec($query, $params);
    
        $json = array();
        foreach ($result as &$row) {    
            $json[] = array(
                "CodVenda"=>$row["CodVenda"]
                ,"img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"]))
                ,"NomPeca"=>$row["NomPeca"]
                ,"DatVenda"=>$row["DatVenda"]
                ,"DatApresentacao"=>$row["DatApresentacao"]
                ,"HorSessao"=>$row["HorSessao"]
                ,"vl_total_pedido_venda"=>$row["vl_total_pedido_venda"]
                ,"TipForPagto"=>$row["TipForPagto"]
                ,"comission_amount"=>$row["comission_amount"]
                ,"comission"=>$row["comission"]
                ,"Nome"=>$row["Nome"]
                ,"EMail"=>$row["EMail"]
                ,"total_formatted"=>$row["total_formatted"]
                ,"total_comission_formatted"=>$row["total_comission_formatted"]
                ,"total"=>$row["total"]
                ,"total_comission"=>$row["total_comission"]
                ,"comission_amount_formatted"=>$row["comission_amount_formatted"]
                ,"db_name"=>$row["db_name"]
                ,"id_base"=>$row["id_base"]
                ,"indice"=>$row["indice"]
                ,"NomObjeto"=>$row["NomObjeto"]
                ,"NomSala"=>$row["NomSala"]
                ,"NomSetor"=>$row["NomSetor"]
                ,"id_evento"=>$row["id_evento"]
                ,"cardimage"=>$row["cardimage"]
                ,"TipBilhete"=>$row["TipBilhete"]
                ,"CPF"=>documentformatBR($row["CPF"])
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["amount"], $_REQUEST["codVenda"], $_REQUEST["id_base"], $_REQUEST["id_quotapartner"]);
?>