<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get() {
        //sleep(5);
        $query = "EXEC pr_placetype_select";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_tipo_local" => $row["id_tipo_local"]
                ,"ds_tipo_local" => $row["ds_tipo_local"]
                ,"value"=>$row["id_tipo_local"]
                ,"text"=>$row["ds_tipo_local"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get();
?>