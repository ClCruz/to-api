<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_partner_paymentmethod_list ?,?";
        $params = array($id,$id_base);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_meio_pagamento" => $row["id_meio_pagamento"]
                ,"ds_meio_pagamento" => $row["ds_meio_pagamento"]
                ,"cd_meio_pagamento" => $row["cd_meio_pagamento"]
                ,"CodForPagto" => $row["CodForPagto"]
                ,"ForPagto" => $row["ForPagto"]
                ,"active" => $row["active"]
                ,"edit" => $row["edit"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id"],$_REQUEST["id_base"]);
?>