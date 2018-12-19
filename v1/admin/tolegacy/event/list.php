<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $api) {
        //sleep(5);
        $query = "EXEC pr_adm_events ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_base, $api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_evento" => $row["id_evento"]
                ,"ds_evento" => $row["ds_evento"]
                ,"CodPeca" => $row["CodPeca"]
                ,"needed" => $row["needed"]
                ,"text"=>$row["ds_evento"]." - ".$row["CodPeca"].($row["needed"] == 1 ? " (Sem preenchimento)" : "")
                ,"value"=>$row["id_evento"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"], $_REQUEST["apikey"]);
?>