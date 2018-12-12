<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($id_base, $nin, $code) {
        if ($code == "") {
            $query = "EXEC pr_client_get ?, NULL";
        }
        else {
            $query = "EXEC pr_client_get ?, ?";
        }
        $params = array($nin, $code);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $json = array(
                "Codigo"=>$row["Codigo"]
                ,"cpfclean"=>$row["cpfclean"]
                ,"Nome"=>$row["Nome"]
                ,"Sexo"=>$row["Sexo"]
                ,"DatNascimento"=>$row["DatNascimento"]
                ,"RG"=>$row["RG"]
                ,"CPF"=>$row["CPF"]
                ,"Endereco"=>$row["Endereco"]
                ,"Numero"=>$row["Numero"]
                ,"Complemento"=>$row["Complemento"]
                ,"Bairro"=>$row["Bairro"]
                ,"Cidade"=>$row["Cidade"]
                ,"UF"=>$row["UF"]
                ,"CEP"=>$row["CEP"]
                ,"DDD"=>$row["DDD"]
                ,"Telefone"=>$row["Telefone"]
                ,"Ramal"=>$row["Ramal"]
                ,"DDDCelular"=>$row["DDDCelular"]
                ,"Celular"=>$row["Celular"]
                ,"DDDComercial"=>$row["DDDComercial"]
                ,"TelComercial"=>$row["TelComercial"]
                ,"RamComercial"=>$row["RamComercial"]
                ,"MalDireta"=>$row["MalDireta"]
                ,"EMail"=>$row["EMail"]
                ,"StaCliente"=>$row["StaCliente"]
                ,"Assinatura"=>$row["Assinatura"]
                ,"CardBin"=>$row["CardBin"]
                ,"created"=>$row["created"]
                ,"id_cliente"=>$row["id_cliente"]
                ,"cd_cep"=>$row["cd_cep"]
                ,"cd_cpf"=>$row["cd_cpf"]
                ,"cd_email_login"=>$row["cd_email_login"]
                ,"cd_password"=>$row["cd_password"]
                ,"cd_rg"=>$row["cd_rg"]
                ,"ds_bairro"=>$row["ds_bairro"]
                ,"ds_celular"=>$row["ds_celular"]
                ,"ds_cidade"=>$row["ds_cidade"]
                ,"ds_compl_endereco"=>$row["ds_compl_endereco"]
                ,"ds_ddd_celular"=>$row["ds_ddd_celular"]
                ,"ds_ddd_telefone"=>$row["ds_ddd_telefone"]
                ,"ds_endereco"=>$row["ds_endereco"]
                ,"ds_nome"=>$row["ds_nome"]
                ,"ds_sobrenome"=>$row["ds_sobrenome"]
                ,"ds_telefone"=>$row["ds_telefone"]
                ,"dt_inclusao"=>$row["dt_inclusao"]
                ,"dt_nascimento"=>$row["dt_nascimento"]
                ,"id_doc_estrangeiro"=>$row["id_doc_estrangeiro"]
                ,"id_estado"=>$row["id_estado"]
                ,"in_assinante"=>$row["in_assinante"]
                ,"in_concorda_termos"=>$row["in_concorda_termos"]
                ,"in_recebe_info"=>$row["in_recebe_info"]
                ,"in_recebe_sms"=>$row["in_recebe_sms"]
                ,"in_sexo"=>$row["in_sexo"]
                ,"nr_endereco"=>$row["nr_endereco"]
                ,"ds_estado"=>$row["ds_estado"]
                ,"sg_estado"=>$row["sg_estado"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }    

    call($_REQUEST["id_base"], $_REQUEST["nin"], $_REQUEST["code"]);
?>