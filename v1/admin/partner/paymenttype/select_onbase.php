<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_partner_paymentmethod_base_select";
        $params = array();
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodForPagto" => $row["CodForPagto"]
                ,"ForPagto" => $row["ForPagto"]

                ,"text" => $row["ForPagto"]
                ,"value" => $row["CodForPagto"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"]);
?>