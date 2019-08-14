<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_partner, $loggedId, $id_base, $id_meio_pagamento, $codForPagto) {
        $query = "EXEC pr_partner_paymentmethod_save ?, ?, ?";
        $params = array($id_base, $id_meio_pagamento, $codForPagto);

        $result = db_exec($query, $params);

        $json = array("success"=>true
        ,"msg"=>"");

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_partner"], $_POST["loggedId"], $_POST["id_base"], $_POST["id_meio_pagamento"], $_POST["codForPagto"]);
?>