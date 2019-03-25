<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $codPeca) {
        $query = "EXEC pr_presentation ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $aux = array("CodApresentacao"=>$row["CodApresentacao"]
            ,"id_apresentacao"=>$row["id_apresentacao"]
            ,"weekday"=>$row["weekday"]
            ,"day"=>$row["day"]
            ,"year"=>$row["year"]
            ,"NomPeca"=>$row["NomPeca"]
            ,"ds_local_evento"=>$row["ds_local_evento"]
            ,"ds_municipio"=>$row["ds_municipio"]
            ,"ds_estado"=>$row["ds_estado"]
            ,"sg_estado"=>$row["sg_estado"]
            ,"NomSala"=>$row["NomSala"]
            ,"NomSetor"=>$row["NomSetor"]
            ,"weekdayName"=>$row["weekdayName"]
            ,"CodSala"=>$row["CodSala"]
            ,"HorSessao"=>$row["HorSessao"]
            ,"istoday"=>$row["istoday"]
            ,"istomorrow"=>$row["istomorrow"]
            ,"ValPeca"=>$row["ValPeca"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();

        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["codPeca"]);
?>