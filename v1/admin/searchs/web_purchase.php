<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_pedido_venda, $client_name, $client_document, $id_evento, $id_apresentacao, $currentPage, $perPage) {
        $perPage = 50;
        $query = "EXEC pr_web_purchase_list ?,?,?,?,?,?,?,?";
        // $uniquename = "viveringressos";// gethost();
        $uniquename = gethost();
        $client_document = str_replace('.','', str_replace('-','',$client_document));
        // die(json_encode($client_document));
        $params = array($uniquename, $id_pedido_venda, $client_document, $client_name, $id_evento, $id_apresentacao, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "created_at" => $row["created_at"]
                ,"id_pedido_venda" => $row["id_pedido_venda"]
                ,"ds_meio_pagamento" => $row["ds_meio_pagamento"]
                ,"cd_numero_transacao" => $row["cd_numero_transacao"]
                ,"dt_pedido_venda" => $row["dt_pedido_venda"]
                ,"tickets_count" => $row["tickets_count"]
                ,"in_situacao" => $row["in_situacao"]
                ,"vl_total_pedido_venda" => $row["vl_total_pedido_venda"]
                ,"id_cliente" => $row["id_cliente"]
                ,"id_evento" => $row["id_evento"]
                ,"id_apresentacao" => $row["id_apresentacao"]
                ,"client_document" => documentformatBR($row["client_document"])
                ,"client_name" => $row["client_name"]
                ,"ds_evento" => $row["ds_evento"]
                ,"dt_apresentacao" => $row["dt_apresentacao"]
                ,"hr_apresentacao" => $row["hr_apresentacao"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_pedido_venda"], $_POST["client_name"],$_POST["client_document"], $_POST["id_evento"], $_POST["id_apresentacao"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>