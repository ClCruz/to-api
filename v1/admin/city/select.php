<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_state) {
        //sleep(5);
        $query = "EXEC pr_city_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_state);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_municipio" => $row["id_municipio"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"value"=>$row["id_municipio"]
                ,"text"=>$row["ds_municipio"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_state"]);
?>