<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_evento) {
        //sleep(5);
        $query = "EXEC pr_apresentacao_list ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_evento);
        $result = db_exec($query, $params);


        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_apresentacao" => $row["id_apresentacao"]
                ,"dt_apresentacao" => $row["dt_apresentacao"]
                ,"hr_apresentacao" => $row["hr_apresentacao"]
                ,"text"=>$row["date"]
                ,"value"=>$row["id_apresentacao"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_evento"]);
?>