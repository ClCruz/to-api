<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_city_get ?";
        $params = array($id);
        $result = db_exec($query, $params);

        
        $json = array();
        foreach ($result as &$row) {
            $img = isset($row["img"]) ? getDefaultMediaHost().$row["img"]."?".randomintbydate() : '';
            $img_extra = isset($row["img_extra"]) ?  getDefaultMediaHost().$row["img_extra"]."?".randomintbydate() : '';
            $json = array(
                "id_municipio" => $row["id_municipio"]
                ,"ds_municipio" => $row["ds_municipio"]
                ,"id_estado" => $row["id_estado"]
                ,"img" => $img
                ,"img_extra" => $img_extra
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id"]);
?>