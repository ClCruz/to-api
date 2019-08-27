<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $CodTipBilhete) {
        //sleep(5);
        $query = "EXEC pr_tickettype_partner_list ?,?";
        $params = array($id_base, $CodTipBilhete);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"domain" => $row["domain"]
                ,"active" => $row["active"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"], $_REQUEST["CodTipBilhete"]);
?>