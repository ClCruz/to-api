<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($id_ticketoffice_user,$id_base,$codPeca, $id_apresentacao, $payment, $clientCode) {
        $query = "EXEC pr_pinpad ?,?,?,?,?,?";
        $params = array($id_ticketoffice_user, $id_base, $codPeca, $id_apresentacao, $payment, db_param3($clientCode));
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $json = array("key"=>$row["key"]);
            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }    

    call($_REQUEST["id_ticketoffice_user"],$_REQUEST["id_base"],$_REQUEST["codPeca"],$_REQUEST["id_apresentacao"],$_REQUEST["payment"],$_REQUEST["clientCode"]);
?>