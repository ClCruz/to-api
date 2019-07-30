<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $id_base, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_typepaymenttype_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodTipForPagto" => $row["CodTipForPagto"]
                ,"ClassifPagtoSAP" => $row["ClassifPagtoSAP"]
                ,"StaImprComprovante" => $row["StaImprComprovante"]
                ,"StaTipForPagto" => $row["StaTipForPagto"]
                ,"TipForPagto" => $row["TipForPagto"]
                ,"uniquename" => $row["uniquename"]
                ,"id_base" => $id_base
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_POST["id_base"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>