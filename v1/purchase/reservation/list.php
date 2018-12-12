<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $nin, $codReserva, $id_apresentacao) {
        $query = "EXEC pr_reservation_list ?, ?, ?";
        $params = array($nin, $codReserva, $id_apresentacao);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("Indice"=>$row["Indice"]
            ,"CodReserva"=>$row["CodReserva"]
            ,"StaCadeira"=>$row["StaCadeira"]
            ,"Nome"=>$row["Nome"]
            ,"CPF"=>$row["CPF"]
            ,"RG"=>$row["RG"]
            ,"Telefone"=>"(".$row["DDD"].") ".$row["Telefone"].($row["Ramal"] == "" ? "" :" - ".$row["Ramal"])
            ,"EMail"=>$row["EMail"]
            ,"NomObjeto"=>$row["NomObjeto"]
            ,"NomSala"=>$row["NomSala"]
            ,"DatApresentacao"=>$row["DatApresentacao"]
            ,"HorSessao"=>$row["HorSessao"]
            ,"NomPeca"=>$row["NomPeca"]
            ,"ds_local_evento"=>$row["ds_local_evento"]
            ,"weekdayName"=>$row["weekdayName"]
            ,"day"=>$row["day"]
            ,"year"=>$row["year"]
            ,"id_evento"=>$row["id_evento"]
            ,"id_apresentacao"=>$row["id_apresentacao"]
            ,"CodApresentacao"=>$row["CodApresentacao"]
            );
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["nin"], $_REQUEST["codReserva"], $_REQUEST["id_apresentacao"]);
?>