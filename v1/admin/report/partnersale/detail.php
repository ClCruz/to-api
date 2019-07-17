<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $comission, $id_pedido_venda) {
        $uniquename = gethost();
        // $uniquename = "sazarte";
        $query = "EXEC pr_report_partnersale_detail ?,?";
        // die("dd".json_encode($codVenda));
        $params = array($comission,$id_pedido_venda);
        $result = db_exec($query, $params);
    
        $json = array();
        foreach ($result as &$row) {    
            $json[] = array(
                "id_pedido_venda"=>$row["id_pedido_venda"]
                ,"dt_pedido_venda"=>$row["dt_pedido_venda"]
                ,"ds_evento"=>$row["ds_evento"]
                ,"CodVenda"=>$row["CodVenda"]
                ,"uri"=>getwhitelabelobjforced($row["host"])["uri"].$row["uri"]
                ,"img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"]))
                ,"dt_apresentacao"=>$row["dt_apresentacao"]
                ,"hr_apresentacao"=>$row["hr_apresentacao"]
                ,"vl_total_pedido_venda"=>$row["vl_total_pedido_venda"]
                ,"ds_meio_pagamento"=>$row["ds_meio_pagamento"]
                ,"comission"=>$row["comission"]
                ,"comission_amount"=>$row["comission_amount"]
                ,"comission_amount_formatted"=>$row["comission_amount_formatted"]
                ,"client_name"=>$row["client_name"]
                ,"cd_cpf"=>documentformatBR($row["cd_cpf"])
                ,"cd_email_login"=>$row["cd_email_login"]
                ,"dt_nascimento"=>$row["dt_nascimento"]
                ,"ds_tipo_bilhete"=>$row["ds_tipo_bilhete"]
                ,"nr_parcelas_pgto"=>$row["nr_parcelas_pgto"]
                ,"isInstallment"=>$row["isInstallment"]
                ,"host"=>$row["host"]
                ,"ds_nome_base_sql"=>$row["ds_nome_base_sql"]
                ,"Indice"=>$row["Indice"]
                ,"ds_localizacao"=>$row["ds_localizacao"]
                ,"vl_unitario"=>$row["vl_unitario"]
                ,"vl_taxa_conveniencia"=>$row["vl_taxa_conveniencia"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["amount"], $_REQUEST["id_pedido_venda"]);
?>