<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($api
        ,$id_base
        ,$id_produtor
        ,$id_to_admin_user
        ,$CodPeca
        ,$NomPeca
        ,$CodTipPeca
        ,$TemDurPeca
        ,$CenPeca
        ,$id_local_evento
        ,$description
        ,$meta_description
        ,$meta_keyword
        ,$opening_time
        ,$insurance_policy
        ,$showInBanner
        ,$bannerDescription
        ,$QtIngrPorPedido
        ,$in_obriga_cpf
        ,$qt_ingressos_por_cpf
    ) {
        //sleep(5);
        $query = "EXEC pr_event_save ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($api
        ,$id_produtor
        ,$id_to_admin_user
        ,$CodPeca
        ,$NomPeca
        ,$CodTipPeca
        ,$TemDurPeca
        ,$CenPeca
        ,$id_local_evento
        ,$ValIngresso
        ,$description
        ,$meta_description
        ,$meta_keyword
        ,$opening_time
        ,$insurance_policy
        ,$showInBanner
        ,$bannerDescription
        ,$QtIngrPorPedido
        ,$in_obriga_cpf
        ,$qt_ingressos_por_cpf);
        $result = db_exec($query, $params, $id_base);

        $json = array("success"=>true
        ,"msg"=>"Salvo com sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["api"]
,$_POST["id_base"]
,$_POST["id_produtor"]
,$_POST["id_to_admin_user"]
,$_POST["CodPeca"]
,$_POST["NomPeca"]
,$_POST["CodTipPeca"]
,$_POST["TemDurPeca"]
,$_POST["CenPeca"]
,$_POST["id_local_evento"]
,$_POST["ValIngresso"]
,$_POST["description"]
,$_POST["meta_description"]
,$_POST["meta_keyword"]
,$_POST["opening_time"]
,$_POST["insurance_policy"]
,$_POST["showInBanner"]
,$_POST["bannerDescription"]
,$_POST["QtIngrPorPedido"]
,$_POST["in_obriga_cpf"]
,$_POST["qt_ingressos_por_cpf"]);
?>