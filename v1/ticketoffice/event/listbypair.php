<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function listevents($code) {
        $query = "EXEC pr_events_bypair ?";
        $params = array($code);
        $result = db_exec($query, $params);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("id_evento"=>$row["id_evento"]
                    ,"CodPeca"=>$row["CodPeca"]
                    ,"img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"]))
                    ,"id_base"=>$row["id_base"]
                    ,"ds_evento"=>$row["ds_evento"]
                    ,"uri"=>$row["uri"]
                    ,"text"=>$row["ds_evento"]
                    ,"value"=>$row["CodPeca"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    listevents($_POST["code"]);
    //listevents($_REQUEST["code"]);
?>