<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function autoComplete($input, $city = null, $state = null, $api = null) {
        //createTimer("autoComplete","Creating query...");
        $query = "EXEC pr_autocomplete ?, ?, ?, ?";
        $params = array($input, db_param($city), db_param($state), $api);
        //createTimer("autoComplete","Calling database...");
        $result = db_exec($query, $params);
        //createTimer("autoComplete","Database executed...");

        $json = array();
        //createTimer("autoComplete","Starting Loop...");
        foreach ($result as &$row) {
            $json[] = array(
                "id_evento" => $row["id_evento"],
                "description" => $row["description"],
                "type" => $row["type"],
                "notselectable" => $row["notselectable"],
                "uri" => $row["uri"],
                "id" => $row["id"],
                "cardimage" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
            );
        }
        //createTimer("autoComplete","Loop ended...");

        echo json_encode($json);
        logme();
        //performance();
        die();    
    }

autoComplete($_REQUEST["input"],$_REQUEST["city"],$_REQUEST["state"], $_REQUEST["apikey"]);
?>