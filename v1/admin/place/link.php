<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($apikey, $id_user, $id_local_evento) {
        //sleep(5);
        $query = "EXEC pr_place_link ?, ?, ?";
        $params = array($apikey, $id_user, $id_local_evento);
        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>"Executado com sucesso.");

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["apikey"], $_POST["id_user"], $_POST["id_local_evento"]);
?>