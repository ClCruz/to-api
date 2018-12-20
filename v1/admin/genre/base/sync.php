<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base) {
        $query = "EXEC pr_genre_sync ?";
        $params = array($id_base);

        $result = db_exec($query, $params);

        $json = array("success"=>true
                    ,"msg"=>"Sincronizado com sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_base"]);
?>