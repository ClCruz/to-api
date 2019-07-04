<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");


    
function get($id_pedido_venda) {
        $query = "EXEC pr_boleto_get ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);
        $obj = array();

        foreach ($result as &$row) {
            $obj = array(
                "cd_numero_transacao"=>$row["cd_numero_transacao"]
                ,"isboletogenerated"=>$row["isboletogenerated"]
                ,"url_boleto"=>$row["url_boleto"]
                ,"name"=>$row["name"]
            );
        }

        $json = array("success"=>false,"msg"=>"something went terribly wrong.");

        if ($obj["isboletogenerated"] == null) {
            $response = pagarme_get_transaction($obj["cd_numero_transacao"]);

            if ($response->status == "waiting_payment") {
                $boleto_url = $response->boleto_url;
                $boleto_barcode = $response->boleto_barcode;
                $boleto_expiration_date = $response->boleto_expiration_date;

                $query = "EXEC pr_boleto_save ?,?,?,?";
                $params = array($id_pedido_venda,$boleto_url,$boleto_barcode,$boleto_expiration_date);
                $result = db_exec($query, $params);

                if (new DateTime($boleto_expiration_date)>=new DateTime(date('Y-m-d'))) {
                    $json = array("success"=>true,"msg"=>"alright.");
                    make_purchase_boleto_email($id_pedido_venda, $boleto_url);
                }
                else {
                    $json = array("success"=>false,"msg"=>"the expiration date isn't ok.");
                }
            }
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_pedido_venda"]);

?>