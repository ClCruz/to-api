<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($api) {
        //sleep(5);
        $query = "EXEC pr_adm_bases ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_base" => $row["id_base"]
                ,"ds_nome_base_sql" => $row["ds_nome_base_sql"]
                ,"ds_nome_teatro" => $row["ds_nome_teatro"]
                ,"value"=>$row["id_base"]
                ,"text"=>$row["ds_nome_teatro"]." (".$row["ds_nome_base_sql"].")"
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["apikey"]);
?>