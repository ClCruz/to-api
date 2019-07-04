<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
    
function get() {
        $query = "EXEC pr_boleto_list";
        $params = array();
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id_pedido_venda"=>$row["id_pedido_venda"]
                ,"name"=>$row["name"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get();

?>