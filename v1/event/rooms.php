<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $codPeca) {
        $query = "EXEC pr_presentation_room ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $aux = array("NomSala"=>$row["NomSala"]
            ,"NomSetor"=>$row["NomSetor"]
            ,"CodSala"=>$row["CodSala"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();

        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["codPeca"]);
?>