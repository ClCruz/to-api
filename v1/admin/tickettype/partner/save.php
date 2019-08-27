<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $CodTipBilhete, $id_partner) {
        $query = "EXEC pr_tickettype_partner_save ?, ?, ?";
        $params = array($id_base, $CodTipBilhete, $id_partner);

        $result = db_exec($query, $params);


        $json = array("success"=>true
        ,"msg"=>"Sucesso");

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_base"], $_POST["CodTipBilhete"], $_POST["id_partner"]);
?>