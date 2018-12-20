<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id_user,$id_produtor,$cd_cpf_cnpj
                    ,$cd_email,$ds_ddd_celular,$ds_celular
                    ,$ds_ddd_telefone,$ds_telefone,$ds_endereco
                    ,$ds_razao_social,$in_ativo) {
        //sleep(5);
        $query = "EXEC pr_producer_save ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_user,db_param3($id_produtor),$cd_cpf_cnpj
        ,$cd_email,$ds_ddd_celular,$ds_celular
        ,$ds_ddd_telefone,$ds_telefone,$ds_endereco
        ,$ds_razao_social,$in_ativo);
        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>"Salvo com sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

execute($_POST["id_user"],$_POST["id_produtor"],$_POST["cd_cpf_cnpj"]
    ,$_POST["cd_email"],$_POST["ds_ddd_celular"],$_POST["ds_celular"]
    ,$_POST["ds_ddd_telefone"],$_POST["ds_telefone"],$_POST["ds_endereco"]
    ,$_POST["ds_razao_social"],$_POST["in_ativo"]);
?>