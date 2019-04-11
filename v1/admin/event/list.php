<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $search, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_admin_event ?,?,?,?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($loggedId, $search, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $imageURI = getDefaultMediaHost().str_replace("{default_card}",getDefaultCardImageName(),str_replace("{id}",$row["id_evento"],$row["cardimage"]));

            $json[] = array(
                "id_evento" => $row["id_evento"]
                ,"id_base" => $row["id_base"]
                ,"CodPeca" => $row["CodPeca"]
                ,"id_local_evento" => $row["id_local_evento"]
                ,"in_ativo" => $row["in_ativo"]
                ,"ds_evento" => $row["ds_evento"]
                ,"showInBanner" => $row["showInBanner"]
                ,"showonline" => $row["showonline"]
                ,"hasshowyet" => $row["hasshowyet"]
                ,"imageURI" => $imageURI
                ,"uri" => $row["uri"]
                ,"ds_local_evento" => $row["ds_local_evento"]
                ,"genreName" => $row["genreName"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"ds_estado" => $row["ds_estado"]
                ,"sg_estado" => $row["sg_estado"]
                ,"created" => $row["created"]

                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["search"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>