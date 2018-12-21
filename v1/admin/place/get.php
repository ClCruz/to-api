<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_local_evento) {
        //sleep(5);
        $query = "EXEC pr_place_get ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_local_evento);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id_local_evento" => $row["id_local_evento"]
                ,"ds_local_evento" => $row["ds_local_evento"]
                ,"ds_googlemaps" => $row["ds_googlemaps"]
                ,"in_ativo" => $row["in_ativo"]
                ,"id_municipio" => $row["id_municipio"]
                ,"id_tipo_local" => $row["id_tipo_local"]
                ,"id_estado" => $row["id_estado"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"sg_estado" => $row["sg_estado"]
                ,"ds_estado" => $row["ds_estado"]
                ,"ds_tipo_local" => $row["ds_tipo_local"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_local_evento"]);
?>