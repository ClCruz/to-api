<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $id_local_evento, $id_base, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_room_list ?,?,?";
        $params = array($id_local_evento, $text, $currentPage, $perPage);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodSala" => $row["CodSala"]
                ,"NomSala" => $row["NomSala"]
                ,"NomRedSala" => $row["NomRedSala"]
                ,"id_local_evento" => $row["id_local_evento"]
                ,"IngressoNumerado" => $row["IngressoNumerado"]
                ,"in_venda_mesa" => $row["in_venda_mesa"]
                ,"isLegacy" => $row["isLegacy"]
                ,"nameonsite" => $row["nameonsite"]
                ,"id_base" => $id_base
                ,"StaSala" => $row["StaSala"]
                ,"seattypes_count" => $row["seattypes_count"]
                ,"seattypes" => $row["seattypes"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_POST["id_local_evento"], $_POST["id_base"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>