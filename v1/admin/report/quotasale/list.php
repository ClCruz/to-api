<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $comission, $start, $end, $id_quotapartner) {
        $query = "EXEC pr_report_quotasale ?,?,?,?";
        // die("dd".json_encode($codVenda));
        $params = array($comission,$start,$end,$id_quotapartner);
        $result = db_exec($query, $params);
    
        $json = array();
        foreach ($result as &$row) {    
            $json[] = array(
                "CodVenda"=>$row["CodVenda"]
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
                ,"CPF"=>documentformatBR($row["CPF"])
                ,"total_formatted"=>$row["total_formatted"]
                ,"total_comission_formatted"=>$row["total_comission_formatted"]
                ,"total"=>$row["total"]
                ,"total_comission"=>$row["total_comission"]
                ,"comission_amount_formatted"=>$row["comission_amount_formatted"]
                ,"db_name"=>$row["db_name"]
                ,"id_base"=>$row["id_base"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["amount"], $_REQUEST["start"], $_REQUEST["end"], $_REQUEST["id_quotapartner"]);
?>