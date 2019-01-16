<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($apikey, $search, $id_state, $id_city, $in_ativo, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_place ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($apikey, $search, $id_state, $id_city, $in_ativo, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_local_evento" => $row["id_local_evento"]
                ,"ds_local_evento" => $row["ds_local_evento"]
                ,"ds_googlemaps" => $row["ds_googlemaps"]
                ,"in_ativo" => $row["in_ativo"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"sg_estado" => $row["sg_estado"]
                ,"ds_estado" => $row["ds_estado"]
                ,"ds_tipo_local" => $row["ds_tipo_local"]
                ,"linked" => $row["linked"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["apikey"], $_REQUEST["search"],$_REQUEST["id_state"],$_REQUEST["id_city"], $_REQUEST["in_ativo"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>