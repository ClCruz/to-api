<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user,$id) {
        
        $query = "EXEC pr_admin_partner_wl_status ?, NULL, ?, ?";
        $params = array($id, 'database', 'init');
        $result = db_exec($query, $params);

        $json = array("success"=>true
                    ,"msg"=>'Processo executado');

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_user"],$_POST["id"]);
?>