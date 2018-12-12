<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $ticketoffice) {
        $query = "EXEC pr_payment ?";
        $params = array($ticketoffice);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("CodForPagto"=>$row["CodForPagto"]
            ,"ForPagto"=>$row["ForPagto"]
            ,"CodTipForPagto"=>$row["CodTipForPagto"]
            ,"TipForPagto"=>$row["TipForPagto"]
            ,"text"=>$row["ForPagto"]
            ,"value"=>$row["CodForPagto"]);
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["ticketoffice"]);
?>