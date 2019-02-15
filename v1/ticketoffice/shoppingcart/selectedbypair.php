<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $code) {
        $query = "EXEC pr_ticketoffice_mobile_selected ?";
        $params = array($code);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $json[] = array("indice"=>$row["indice"]
            ,"NomObjeto"=>$row["NomObjeto"]
            ,"NomSala"=>$row["NomSala"]
            ,"NomSetor"=>$row["NomSetor"]
            ,"StaCadeira"=>$row["StaCadeira"]
            ,"created"=>$row["created"]
            ,"ds_evento"=>$row["ds_evento"]
            ,"date"=>$row["date"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["code"]);
?>