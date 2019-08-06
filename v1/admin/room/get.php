<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_room_get ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "CodSala" => $row["CodSala"]
                ,"DescTitulo" => $row["DescTitulo"]
                ,"id_local_evento" => $row["id_local_evento"]
                ,"IngressoNumerado" => $row["IngressoNumerado"]
                ,"isLegacy" => $row["isLegacy"]
                ,"nameonsite" => $row["nameonsite"]
                ,"NomRedSala" => $row["NomRedSala"]
                ,"NomSala" => $row["NomSala"]
                ,"StaSala" => $row["StaSala"]
                ,"seattypes_count" => $row["seattypes_count"]
                ,"seattypes" => $row["seattypes"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"], $_POST["id_base"]);
?>