<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get() {
        //sleep(5);
        $query = "EXEC pr_state";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_estado" => $row["id_estado"]
                ,"ds_estado " => $row["ds_estado "]
                ,"sg_estado" => $row["sg_estado"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get();
?>