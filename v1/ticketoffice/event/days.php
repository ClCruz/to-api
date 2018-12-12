<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function listevents($id_base, $codPeca) {
        $query = "EXEC pr_eventsdays ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("DatApresentacao"=>$row["DatApresentacao"]
                    ,"value"=>$row["DatApresentacao"]
                    ,"text"=>$row["DatApresentacao"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    listevents($_REQUEST["id_base"], $_REQUEST["codPeca"]);
?>