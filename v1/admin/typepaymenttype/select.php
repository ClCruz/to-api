<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_typepaymenttype_select";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
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
                ,"value"=>$row["CodTipForPagto"]
                ,"text"=>$row["TipForPagto"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"]);
?>