<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId) {
        //sleep(5);
        $query = "EXEC pr_producer_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($loggedId);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_produtor" => $row["id_produtor"]
                ,"cd_cpf_cnpj" => $row["cd_cpf_cnpj"]
                ,"ds_razao_social" => $row["ds_razao_social"]
                ,"value"=>$row["id_produtor"]
                ,"text"=>$row["ds_razao_social"]."(".$row["cd_cpf_cnpj"].")"
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"]);
?>