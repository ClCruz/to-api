<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_typepaymenttype_get ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "CodTipForPagto" => $row["CodTipForPagto"]
                ,"ClassifPagtoSAP" => $row["ClassifPagtoSAP"]
                ,"StaImprComprovante" => $row["StaImprComprovante"]
                ,"StaTipForPagto" => $row["StaTipForPagto"]
                ,"TipForPagto" => $row["TipForPagto"]
                ,"uniquename" => $row["uniquename"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"], $_POST["id_base"]);
?>