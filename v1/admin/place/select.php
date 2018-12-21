<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_city) {
        //sleep(5);
        $query = "EXEC pr_place_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_city);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_local_evento" => $row["id_local_evento"]
                ,"ds_local_evento" => $row["ds_local_evento"]
                ,"ds_googlemaps" => $row["ds_googlemaps"]
                ,"value"=>$row["id_local_evento"]
                ,"text"=>$row["ds_local_evento"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_city"]);
?>