<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_to_admin_user_base_list ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_base" => $row["id_base"]
                ,"ds_nome_base_sql" => $row["ds_nome_base_sql"]
                ,"ds_nome_teatro" => $row["ds_nome_teatro"]
                ,"active" => $row["active"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"]);
?>