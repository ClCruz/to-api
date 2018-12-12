<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $codPeca) {
        $query = "EXEC pr_presentation_date ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $aux = array("date"=>$row["date"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();

        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["codPeca"]);
?>