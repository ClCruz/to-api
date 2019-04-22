<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $id_base, $id_evento) {
        //sleep(5);
        $query = "EXEC pr_admin_presentation_list ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodApresentacao" => $row["CodApresentacao"]
                ,"weekday" => $row["weekday"]
                ,"CodSala" => $row["CodSala"]
                ,"NomSala" => $row["NomSala"]
                ,"weekdayName" => $row["weekdayName"]
                ,"DatApresentacao" => $row["DatApresentacao"]
                ,"HorSessao" => $row["HorSessao"]
                ,"ValPeca" => $row["ValPeca"]
                ,"amount" => $row["amount"]
                ,"StaAtivoWeb" => $row["StaAtivoWeb"]
                ,"StaAtivoBilheteria" => $row["StaAtivoBilheteria"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_base"],$_REQUEST["id_evento"]);
?>