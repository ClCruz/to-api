<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_partner,$success,$msg) {
        // echo ":".json_encode($_POST["id_partner"]);
        // die();
        $query = "EXEC partner_sitemap_save ?,?,?";
        $params = array($id_partner,$success,$msg);
        $result = db_exec($query, $params);
        
        $json = array("success"=>1);

        echo json_encode($json);
        // logme();
        die();    
    }

    get($_POST["id_partner"],$_POST["success"],$_POST["msg"]);
?>