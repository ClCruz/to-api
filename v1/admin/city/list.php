<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_city_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $img = getDefaultMediaHost().$row["img"]."?".randomintbydate();
            $img_extra = getDefaultMediaHost().$row["img_extra"]."?".randomintbydate();
            $json[] = array(
                "id_municipio" => $row["id_municipio"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"id_estado" => $row["id_estado"]
                ,"ds_estado" => $row["ds_estado"]
                ,"sg_estado" => $row["sg_estado"]
                ,"img" => $img
                ,"img_extra" => $img_extra
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>