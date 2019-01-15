<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        //sleep(5);
        $query = "EXEC pr_admin_presentation_room_list";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodSala" => $row["CodSala"]
                ,"NomSala" => $row["NomSala"]
                ,"isconfigured" => $row["isconfigured"]

                ,"value" => $row["CodSala"]
                ,"text" => $row["NomSala"].($row["isconfigured"] == 0 ? ' (Não configurada)' : '')
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_base"]);
?>