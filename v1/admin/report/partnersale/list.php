<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $comission, $start, $end) {
        $uniquename = gethost();
        // $uniquename = "sazarte";
        $query = "EXEC pr_report_partnersale ?,?,?,?";
        // die("dd".json_encode($codVenda));
        $params = array($comission,$start,$end,$uniquename);
        $result = db_exec($query, $params);
    
        $json = array();
        foreach ($result as &$row) {    
            $json[] = array(
                "id_pedido_venda"=>$row["id_pedido_venda"]
                ,"total"=>$row["total"]
                ,"total_formatted"=>$row["total_formatted"]
                ,"total_comission"=>$row["total_comission"]
                ,"total_comission_formatted"=>$row["total_comission_formatted"]
                ,"dt_pedido_venda"=>$row["dt_pedido_venda"]
                ,"ds_evento"=>$row["ds_evento"]
                ,"uri"=>$row["uri"]
                ,"dt_apresentacao"=>$row["dt_apresentacao"]
                ,"hr_apresentacao"=>$row["hr_apresentacao"]
                ,"vl_total_pedido_venda"=>$row["vl_total_pedido_venda"]
                ,"ds_meio_pagamento"=>$row["ds_meio_pagamento"]
                ,"comission"=>$row["comission"]
                ,"comission_amount"=>$row["comission_amount"]
                ,"comission_amount_formatted"=>$row["comission_amount_formatted"]
                ,"client_name"=>$row["client_name"]
                ,"cd_cpf"=>$row["cd_cpf"]
                ,"cd_email_login"=>$row["cd_email_login"]
                ,"dt_nascimento"=>$row["dt_nascimento"]
                ,"ds_tipo_bilhete"=>$row["ds_tipo_bilhete"]
                ,"nr_parcelas_pgto"=>$row["nr_parcelas_pgto"]
                ,"isInstallment"=>$row["isInstallment"]
                ,"host"=>$row["host"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["amount"], $_REQUEST["start"], $_REQUEST["end"]);
?>