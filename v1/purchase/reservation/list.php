<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $nin, $codReserva, $id_apresentacao, $name, $id_quotapartner) {

        $id_quotapartner = uniqueidentifier_default($id_quotapartner);

        $query = "EXEC pr_reservation_list ?, ?, ?, ?, ?";
        $params = array($nin, $codReserva, $id_apresentacao, $name, $id_quotapartner);
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
            ,"Telefone"=>$row["Telefone"] == "" ? "" : ("(".$row["DDD"].") ".$row["Telefone"].($row["Ramal"] == "" ? "" :" - ".$row["Ramal"]))
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
get($_POST["id_base"], $_POST["nin"], $_POST["codReserva"], $_POST["id_apresentacao"], $_POST["name"], $_POST["id_quotapartner"]);
?>