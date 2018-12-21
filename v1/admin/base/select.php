<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user) {
        //sleep(5);
        $query = "EXEC pr_to_admin_user_base_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_user);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_base" => $row["id_base"]
                ,"ds_nome_teatro" => $row["ds_nome_teatro"]
                ,"ds_nome_base_sql" => $row["ds_nome_base_sql"]
                ,"value"=>$row["id_base"]
                ,"text"=>$row["ds_nome_teatro"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_user"]);
?>