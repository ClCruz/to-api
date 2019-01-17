<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user,$id,$keyTo) {
        isuservalidordie($id_user);
        
        //sleep(5);
        $query = "EXEC pr_admin_partner_generate ?, ?, ?";
        $params = array($id_user, $id, $keyTo);
        //die("aaa.".json_encode($params));        
        $result = db_exec($query, $params);

        //foreach ($result as &$row) {
            $json = array("success"=>true
                        ,"msg"=>"Salvo com sucesso");
        //}

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_user"],$_POST["id"],$_POST["keyTo"]);
?>