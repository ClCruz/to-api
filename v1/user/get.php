<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function search($token) {
        $query = "EXEC pr_user_get ?";

        $params = array($token);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "success" => true
                ,"ds_nome" => $row["ds_nome"]
                ,"ds_sobrenome" => $row["ds_sobrenome"]
                ,"dt_nascimento" => $row["dt_nascimento"]
                ,"in_sexo" => $row["in_sexo"]
                ,"cd_email_login" => $row["cd_email_login"]
                ,"cd_cpf" => $row["cd_cpf"]
                ,"cd_rg" => $row["cd_rg"]
                ,"ds_ddd_celular" => $row["ds_ddd_celular"]
                ,"ds_celular" => $row["ds_celular"]
                ,"cd_cep" => $row["cd_cep"]
                ,"id_estado" => $row["id_estado"]
                ,"ds_cidade" => $row["ds_cidade"]
                ,"ds_bairro" => $row["ds_bairro"]
                ,"ds_endereco" => $row["ds_endereco"]
                ,"nr_endereco" => $row["nr_endereco"]
                ,"ds_compl_endereco" => $row["ds_compl_endereco"]
                ,"in_recebe_info" => $row["in_recebe_info"]
                ,"isfb" => $row["isfb"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

search($_POST["token"]);
?>