<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_to_admin_partner_base_select ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_base" => $row["id_base"]
                ,"ds_nome_base_sql" => $row["ds_nome_base_sql"]
                ,"ds_nome_teatro" => $row["ds_nome_teatro"]

                ,"text" => $row["ds_nome_base_sql"]
                ,"value" => $row["id_base"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"]);
?>