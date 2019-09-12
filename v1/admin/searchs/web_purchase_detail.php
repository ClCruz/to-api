<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");

    function get($id_pedido_venda) {
        $query = "EXEC pr_web_purchase_get ?,?";
        $uniquename = gethost();
        // $uniquename = "ingressoparatodos";
        // $uniquename = "viveringressos";
        $params = array($uniquename, $id_pedido_venda);
        $result = db_exec($query, $params);

        $gateway_info = null;
        $gateway_checked = false;

        $json = array();

        foreach ($result as &$row) {
            if ($gateway_checked == false) {
                $gateway_info = pagarme_get_transaction($row["cd_numero_transacao"]);
                $gateway_info_playable = pagarme_get_payables($row["cd_numero_transacao"]);

                $gateway_checked = true;
                if ($gateway_info == null) $gateway_info = array();

                foreach ($gateway_info->split_rules as &$split) {
                    //die(json_encode($split->recipient_id));
                    $recipient = pagarme_get_recipient($split->recipient_id);
                    $split->recipient_name = $recipient->bank_account->legal_name;
                    $split->recipient_document_type = $recipient->bank_account->document_type;
                    $split->recipient_document_number = $recipient->bank_account->document_number;

                    foreach ($gateway_info_playable as &$playable) {
                        if ($playable->recipient_id == $split->recipient_id) {
                            $split->playable_isanticipation = $playable->anticipation_fee != 0;
                            $split->playable_status = $playable->status;
                            $split->playable_amount = $playable->amount;
                            $split->playable_fee = $playable->fee;
                            $split->playable_anticipation_fee = $playable->anticipation_fee;
                            $split->playable_payment_date = $playable->payment_date;
                            $split->playable_original_payment_date = $playable->original_payment_date;
                        }
                    }
                }
            }

            $json[] = array(
                "id_pedido_venda" => $row["id_pedido_venda"]
                ,"gateway_info" => json_encode($gateway_info)
                ,"Indice" => $row["Indice"]
                ,"tickettype" => $row["tickettype"]
                ,"vl_taxa_conveniencia" => $row["vl_taxa_conveniencia"]
                ,"vl_unitario" => $row["vl_unitario"]
                ,"CodVenda" => $row["CodVenda"]
                ,"ds_setor" => $row["ds_setor"]
                ,"ds_localizacao" => $row["ds_localizacao"]
                ,"cd_bin_cartao" => $row["cd_bin_cartao"]
                ,"ds_meio_pagamento" => $row["ds_meio_pagamento"]
                ,"cd_numero_transacao" => $row["cd_numero_transacao"]
                ,"dt_pedido_venda" => $row["dt_pedido_venda"]
                ,"created_at" => $row["created_at"]
                ,"id_cliente" => $row["id_cliente"]
                ,"id_base" => $row["id_base"]
                ,"id_evento" => $row["id_evento"]
                ,"id_apresentacao" => $row["id_apresentacao"]
                ,"in_situacao" => $row["in_situacao"]
                ,"client_document" => documentformatBR($row["client_document"])
                ,"client_name" => $row["client_name"]
                ,"cd_email_login" => $row["cd_email_login"]
                ,"ds_ddd_celular" => $row["ds_ddd_celular"]
                ,"ds_celular" => $row["ds_celular"]
                ,"ds_evento" => $row["ds_evento"]
                ,"dt_apresentacao" => $row["dt_apresentacao"]
                ,"hr_apresentacao" => $row["hr_apresentacao"]
                ,"vl_total_pedido_venda" => $row["vl_total_pedido_venda"]
                ,"ds_nome_base_sql" => $row["ds_nome_base_sql"]
                ,"uri" => getwhitelabelURI_home($row["uri"])
                ,"img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"]))
                ,"tickets_count" => $row["tickets_count"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_pedido_venda"]);
?>