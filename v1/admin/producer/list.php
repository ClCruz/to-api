<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $search, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_producer ?,?,?,?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($loggedId, $search, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_produtor" => $row["id_produtor"]
                ,"cd_cpf_cnpj" => $row["cd_cpf_cnpj"]
                ,"cd_email" => $row["cd_email"]
                ,"ds_celular" => $row["ds_celular"]
                ,"ds_ddd_celular" => $row["ds_ddd_celular"]
                ,"ds_ddd_telefone" => $row["ds_ddd_telefone"]
                ,"ds_endereco" => $row["ds_endereco"]
                ,"ds_nome_contato" => $row["ds_nome_contato"]
                ,"ds_razao_social" => $row["ds_razao_social"]
                ,"ds_telefone" => $row["ds_telefone"]
                ,"id_gateway" => $row["id_gateway"]
                ,"in_ativo" => $row["in_ativo"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["search"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>