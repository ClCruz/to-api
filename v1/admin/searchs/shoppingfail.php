<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($client_name, $client_document, $id_evento, $id_apresentacao, $currentPage, $perPage) {
        $query = "EXEC pr_shopping_fail_list ?,?,?,?,?,?,?";
        // $uniquename = "viveringressos";// gethost();
        $uniquename = gethost();
        $client_document = str_replace('.','', str_replace('-','',$client_document));
        // die(json_encode($client_document));
        $params = array($uniquename, $client_document, $client_name, $id_evento, $id_apresentacao, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "created_at" => $row["created_at"]
                ,"id" => $row["id"]
                ,"id_cliente" => $row["id_cliente"]
                ,"id_evento" => $row["id_evento"]
                ,"id_apresentacao" => $row["id_apresentacao"]
                ,"json_shopping" => $row["json_shopping"]
                ,"json_values" => $row["json_values"]
                ,"json_gateway_response" => $row["json_gateway_response"]
                ,"status" => $row["status"]
                ,"refuse_reason" => $row["refuse_reason"]
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

get($_POST["client_name"],$_POST["client_document"], $_POST["id_evento"], $_POST["id_apresentacao"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>