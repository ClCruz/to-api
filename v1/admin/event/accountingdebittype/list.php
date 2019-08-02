<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $id_base, $id_evento) {
        //sleep(5);
        $query = "EXEC pr_accountingdebittype_event_list ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodPeca" => $row["CodPeca"]
                ,"CodTipDebBordero" => $row["CodTipDebBordero"]
                ,"DebBordero" => $row["DebBordero"]
                ,"DatIniDebito" => $row["DatIniDebito"]
                ,"DatFinDebito" => $row["DatFinDebito"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"PerDesconto_formatted" => $row["PerDesconto_formatted"]
                ,"TipValor" => $row["TipValor"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_base"],$_REQUEST["id_evento"]);
?>