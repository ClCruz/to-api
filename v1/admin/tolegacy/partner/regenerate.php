<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_partner_key ?, 1";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>true
                        ,"msg"=>"Nova chave gerada com sucesso");
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"]);
?>