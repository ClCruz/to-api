<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id_local_evento) {
        //sleep(5);
        $query = "EXEC pr_room_select ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_local_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodSala" => $row["CodSala"]
                ,"DescTitulo" => $row["DescTitulo"]
                ,"id_local_evento" => $row["id_local_evento"]
                ,"IngressoNumerado" => $row["IngressoNumerado"]
                ,"isLegacy" => $row["isLegacy"]
                ,"nameonsite" => $row["nameonsite"]
                ,"NomRedSala" => $row["NomRedSala"]
                ,"NomSala" => $row["NomSala"]
                ,"value"=>$row["CodSala"]
                ,"text"=>$row["NomSala"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id_local_evento"]);
?>