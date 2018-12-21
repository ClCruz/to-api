<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id_user, $id_local_evento, $ds_local_evento, $ds_googlemaps, $in_ativo, $id_municipio, $id_tipo_local) {
        //sleep(5);
        $query = "EXEC pr_place_save ?, ?, ?, ?, ?, ?";
        $params = array($id_local_evento, $ds_local_evento, $id_tipo_local, $id_municipio, $in_ativo, $ds_googlemaps);
        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>"Salvo com sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

execute($_POST["id_user"], $_POST["id_local_evento"], $_POST["ds_local_evento"], $_POST["ds_googlemaps"], $_POST["in_ativo"], $_POST["id_municipio"], $_POST["id_tipo_local"]);
?>