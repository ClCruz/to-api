<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get_id_purchase($session, $id_client) {
        return date("Ymdhis")."-".$session."-".$id_client;
    }
    function ispaymentmethodok($id_purchase, $id_client, $id_session, $id_payment_method, $shopping) {

        traceme($id_purchase, "Validate Payment Method - start", '',0);
        $query = "EXEC pr_purchase_payment_method_hoursinadvance ?";
        $params = array($id_payment_method);
        $result = db_exec($query, $params);
        $db = null;
        $ret = array("success"=>true, "msg"=>"");
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $db = array("QT_HR_ANTECED"=>$row["QT_HR_ANTECED"]
                        ,"id_payment_method"=>$id_payment_method);
        }

        if ($db !== null && count($db) > 0) {
            foreach ($shopping as &$row) {
                if ($db["QT_HR_ANTECED"]>$row["hoursinadvance"]) {
                    $hasError = true;
                    $errorMsg = "Esta forma de pagamento não pode ser utilizada no momento. Por favor, selecione outra. (".$db["QT_HR_ANTECED"].")";
                    break;
                }
            }
        }


        traceme($id_purchase, "Validate Payment Method - end", '',0);

        return array("success"=>$hasError == false, "msg"=>$errorMsg, "msgtobuyer"=>$errorMsg);
    }

    function getlocal($id_purchase, $id_client, $id_event) {
        traceme($id_purchase, "get event local - start", json_encode($id_client),0);
        $query = "EXEC pr_event_local_byid ?";
        $params = array($id_event);
        $result = db_exec($query, $params);
        $ret = array();
        foreach ($result as &$row) {
            $ret = array("id_evento"=>$row["id_evento"]
            ,"ds_evento"=>$row["ds_evento"]
            ,"ds_googlemaps"=>$row["ds_googlemaps"]
            ,"ds_local_evento"=>$row["ds_local_evento"]);
        }

        traceme($id_purchase, "get event local - end", json_encode($ret),0);

        return $ret;
    }

    function getmysession($id_purchase, $id_client) {

        traceme($id_purchase, "get my session - start", json_encode($id_client),0);
        $query = "EXEC pr_purchase_get_current_session ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("id_session"=>$row["id_session"]
                        ,"created"=>$row["created"]
                        ,"id_cliente"=>$row["id_cliente"]);
        }

        traceme($id_purchase, "get my session - end", json_encode($ret),0);

        return $ret;
    }

    function getpaymentmethod($id_purchase, $id_payment_method) {

        traceme($id_purchase, "get payment method - start", json_encode($id_payment_method),0);
        $query = "EXEC pr_purchase_payment_method ?";
        $params = array($id_payment_method);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("id_meio_pagamento"=>$row["id_meio_pagamento"]
            ,"in_tipo_meio_pagamento"=>$row["in_tipo_meio_pagamento"]
            ,"cd_meio_pagamento"=>$row["cd_meio_pagamento"]
            ,"ds_meio_pagamento"=>$row["ds_meio_pagamento"]
            ,"in_ativo"=>$row["in_ativo"]
            ,"nm_cartao_exibicao_site"=>$row["nm_cartao_exibicao_site"]
            ,"in_transacao_pdv"=>$row["in_transacao_pdv"]
            ,"qt_hr_anteced"=>$row["qt_hr_anteced"]
            ,"id_gateway"=>$row["id_gateway"]);
        }

        traceme($id_purchase, "get payment method - end", json_encode($ret),0);

        return $ret;
    }

    function getclient($id_purchase, $id_client) {

        traceme($id_purchase, "get client - start", json_encode($id_client),0);
        $query = "EXEC pr_purchase_get_client ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("id_cliente"=>$row["id_cliente"]
            ,"cd_cep"=>$row["cd_cep"]
            ,"cd_cpf"=>$row["cd_cpf"]
            ,"cd_email_login"=>$row["cd_email_login"]
            ,"cd_rg"=>$row["cd_rg"]
            ,"ds_bairro"=>$row["ds_bairro"]
            ,"ds_celular"=>$row["ds_celular"]
            ,"ds_cidade"=>$row["ds_cidade"]
            ,"ds_compl_endereco"=>$row["ds_compl_endereco"]
            ,"ds_ddd_celular"=>$row["ds_ddd_celular"]
            ,"ds_ddd_telefone"=>$row["ds_ddd_telefone"]
            ,"ds_endereco"=>$row["ds_endereco"]
            ,"ds_nome"=>$row["ds_nome"]
            ,"ds_sobrenome"=>$row["ds_sobrenome"]
            ,"ds_telefone"=>$row["ds_telefone"]
            ,"dt_inclusao"=>$row["dt_inclusao"]
            ,"dt_nascimento"=>$row["dt_nascimento"]
            ,"id_doc_estrangeiro"=>$row["id_doc_estrangeiro"]
            ,"id_estado"=>$row["id_estado"]
            ,"in_concorda_termos"=>$row["in_concorda_termos"]
            ,"in_recebe_info"=>$row["in_recebe_info"]
            ,"in_recebe_sms"=>$row["in_recebe_sms"]
            ,"in_sexo"=>$row["in_sexo"]
            ,"nr_endereco"=>$row["nr_endereco"]
            ,"fullname"=>$row["fullname"]);
        }

        traceme($id_purchase, "get client - end", json_encode($ret),0);

        return $ret;
    }


    function getpurchasesplit($id_purchase, $id_client, $type, $amount, $where, $payment_method) {

        traceme($id_purchase, "get split for purchase - start", json_encode(array("id_client"=>$id_client, "type"=>$type, "amount"=>$amount, "where"=>$where, "payment_method"=>$payment_method)),0);
        $query = "EXEC pr_purchase_get_split ?";
        $params = array($id_client);
        traceme($id_purchase, "get split for purchase - get from db - start", '',0);
        $result = db_exec($query, $params);
        $splitdb = array();
        $split = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $splitdb[] = array("id_evento"=>$row["id_evento"]
            ,"recipient_id"=>$row["recipient_id"]
            ,"nr_percentual_split"=>$row["nr_percentual_split"]
            ,"liable"=>$row["liable"]
            ,"charge_processing_fee"=>$row["charge_processing_fee"]
            ,"percentage_credit_web"=>$row["percentage_credit_web"]
            ,"percentage_debit_web"=>$row["percentage_debit_web"]
            ,"percentage_boleto_web"=>$row["percentage_boleto_web"]
            ,"percentage_credit_box_office"=>$row["percentage_credit_box_office"]
            ,"percentage_debit_box_office"=>$row["percentage_debit_box_office"]
            ,"howmanysplits"=>$row["howmanysplits"]);
        }

        traceme($id_purchase, "get split for purchase - get from db - end", json_encode($result),0);

        traceme($id_purchase, "get split for purchase - loop - start", '',0);

        if (count($splitdb)>0) {
            $count = $splitdb[0]["howmanysplits"];
        
            $i = 0;
            $amountUsed = 0;
            $amount = $amount/100;
        
            $split = array();
            foreach ($splitdb as &$row) {
                $i = $i+1;
                $perToUse = 0;
                $amountToUse = 0;
        
                switch ($where) {
                    case "web":
                        switch ($payment_method) {
                            case "credit":
                            case "credit_card":
                                    $perToUse = $row["percentage_credit_web"];
                                break;
                            case "boleto":
                            case "payment_slip":
                                $perToUse = $row["percentage_boleto_web"];
                                break;
                            case "debit":
                            case "debit_card":
                                $perToUse = $row["percentage_debit_web"];
                                break;							
                        }
                        break;
                    case "bilheteria":
                        switch ($payment_method) {
                            case "credit":
                            case "credit_card":
                                    $perToUse = $row["percentage_credit_box_office"];
                                break;
                            case "debit":
                            case "debit_card":
                                $perToUse = $row["percentage_debit_box_office"];
                                break;							
                        }
                        break;
                }
        
                if ($count==$i) {
                    $amoutToUse = round($amount-$amountUsed, 2);
                }
                else {
                    $amoutToUse = round($amount*($perToUse/100), 2);
                }
        
                $amountUsed = $amountUsed + $amoutToUse;
        
                switch ($type) {
                    case "pagarme":
                        $split[] = array(
                            "recipient_id" => $row["recipient_id"],
                            "amount" => $amoutToUse*100,
                            "liable" => $row["liable"],
                            "charge_processing_fee" => $row["charge_processing_fee"]);
                        break;
                }                        
            }
        }


        traceme($id_purchase, "get split for purchase - loop - end", '',0);

        traceme($id_purchase, "get split for purchase - end", json_encode($split),0);

        return $split;
    }

    function getinstallments($id_purchase, $id_client) {

        traceme($id_purchase, "get installments - start", '',0);
        $query = "EXEC pr_purchase_installments ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("id_evento"=>$row["id_evento"]
                        ,"interest_rate"=> intval($row["interest_rate"])/100
                        ,"max_installments"=>$row["max_installments"]
                        ,"free_installments"=>$row["free_installments"]);
        }

        traceme($id_purchase, "get installments - end", json_encode($ret),0);

        return $ret;
    }

    function checksplitevents($id_purchase, $id_client) {

        traceme($id_purchase, "check split events - start", '',0);
        $query = "EXEC pr_purchase_check_split ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("success"=>$row["success"]
            ,"msg"=>$row["msg"]
            ,"msgtobuyer"=>$row["msg"]);
        }

        traceme($id_purchase, "check split events - end", json_encode($ret),0);

        return $ret;
    }

    function checkmultipleevents($id_purchase, $id_client) {

        traceme($id_purchase, "check multiples events - start", '',0);
        $query = "EXEC pr_purchase_check_multiple_event ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("success"=>$row["success"]
            ,"msg"=>$row["msg"]
            ,"msgtobuyer"=>$row["msg"]);
        }

        traceme($id_purchase, "check multiples events - end", json_encode($ret),0);

        return $ret;
    }

    function makeitmine($id_client, $id_session) {
        $query = "EXEC pr_purchase_makeitbemine ?, ?";
        $params = array($id_session, $id_client);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("success"=>$row["success"]
                        ,"msg"=>$row["msg"]
                        ,"session"=>$row["session"]);
        }

        return $ret;
    }

    function gettransactionbypedido($id_pedido_venda) {
        $query = "EXEC pr_purchase_refund_transaction_by_pedido ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);
        $ret = array();
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $ret = array("cd_numero_transacao"=>$row["cd_numero_transacao"]);
        }

        return $ret;
    }

    function workaround_pagseguro($id_pedido_venda, $obj, $status) {
        $query = "EXEC pr_workaround_pagseguro ?,?,?";
        $params = array($id_pedido_venda, $obj, $status);
        $result = db_exec($query, $params);
    }
    function renew($id_session, $id_user) {
        $query = "EXEC pr_purchase_renew ?,?";
        $params = array($id_session, $id_user);
        $result = db_exec($query, $params);
    }
    function clearcurrentsessionclient($id_client) {
        $query = "EXEC pr_purchase_clear_sessionclient ?";
        $params = array($id_client);
        $result = db_exec($query, $params);
    }
    function setinproc($id_pedido_venda, $cd_meio_pagamento) {
        $query = "EXEC pr_purchase_setinproc ?, ?";
        $params = array($cd_meio_pagamento, $id_pedido_venda);
        $result = db_exec($query, $params);
    }
    function change_situacao($id_pedido_venda) {
        $query = "EXEC pr_purchase_change_situacao ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);
    }
    function save_fail($id_cliente,$id_evento,$id_apresentacao,$json_shopping,$json_values
                        ,$json_gateway_response,$status,$refuse_reason,$status_reason,$uniquename_site) {
        $query = "EXEC pr_shopping_fail ?,?,?,?,?,?,?,?,?,?";
        $params = array($id_cliente,$id_evento,$id_apresentacao,$json_shopping,$json_values,$json_gateway_response,$status,$refuse_reason,$status_reason,$uniquename_site);
        $result = db_exec($query, $params);
    }
    function change_situacao_boleto($id) {
        $query = "EXEC pr_sell_web_boleto ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array("success"=>$row["success"]
            ,"email_address"=>$row["email_address"]
            ,"email_name"=>$row["email_name"]
            ,"msg"=>$row["msg"]);
        }
        return $json;
    }
    

    function getbases4purchase($id_session) {
        $query = "EXEC pr_purchase_get_all_bases ?";
        $params = array($id_session);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array("id_base"=>$row["id_base"]
            ,"id_evento"=>$row["id_evento"]
            ,"CodPeca"=>$row["CodPeca"]
            ,"CodApresentacao"=>$row["CodApresentacao"]
            ,"id_apresentacao"=>$row["id_apresentacao"]
            ,"id_reserva"=>$row["id_reserva"]);
        }
        return $json;
    }
    
    function generate_pedido_venda($id_client, $id_operador, $amountTotalINT
                                ,$amountTotalwithoutserviceINT,$amountTotalServiceINT,$bin,$installment
                                ,$ip,$host,$nr_beneficio,$nm_cliente_voucher,$ds_email_voucher,$nm_titular_cartao
                                ,$id_pedido_ipagare, $cd_numero_autorizacao, $cd_numero_transacao, $id_transaction_braspag, $id_meio_pagamento) {
        $query = "EXEC pr_purchase_generate_pedido_venda ?, ?, ?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?";
        $params = array($id_client, $id_operador, $amountTotalINT
        ,$amountTotalwithoutserviceINT,$amountTotalServiceINT,$bin,$installment
        ,$ip,$host,$nr_beneficio,$nm_cliente_voucher,$ds_email_voucher,$nm_titular_cartao
        ,$id_pedido_ipagare, $cd_numero_autorizacao, $cd_numero_transacao, $id_transaction_braspag, $id_meio_pagamento);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array("id_pedido_venda"=>$row["id_pedido_venda"]);
        }
        return $json;
    }

    function sell($id_client, $totalamount, $id_pedido_venda, $cd_meio_pagamento) {
        $query = "EXEC pr_purchase_sell ?,?,?,?";
        $params = array($id_client, $totalamount, $id_pedido_venda, $cd_meio_pagamento);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
        $json = array("success"=>$row["success"]
                        ,"id_base"=>$row["id_base"]
                        ,"codVenda"=>$row["codVenda"]
                        ,"id_pedido_venda"=>$row["id_pedido_venda"]
                        ,"ErrorNumber"=>$row["ErrorNumber"]
                        ,"ErrorSeverity"=>$row["ErrorSeverity"]
                        ,"ErrorState"=>$row["ErrorState"]
                        ,"ErrorProcedure"=>$row["ErrorProcedure"]
                        ,"ErrorLine"=>$row["ErrorLine"]
                        ,"ErrorMessage"=>$row["ErrorMessage"]);
        }
        return $json;
    }

    function getvaluesofmyshoppig($id_client,$id_session) {
        if ($id_session == null) {
            $query = "EXEC pr_purchase_get_values ?, NULL";
        }
        else {
            $query = "EXEC pr_purchase_get_values NULL, ?";
        }
        $params = array($id_session);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array("id"=>$row["id"]
            ,"indice"=>$row["indice"]
            ,"amount"=>$row["amount"]
            ,"amountallWithoutService"=>$row["amountallWithoutService"]
            ,"amountallWithService"=>$row["amountallWithService"]
            ,"serviceAmountINT"=>$row["serviceAmountINT"]
            ,"discountOther"=>$row["discountOther"]
            ,"discountOtherIsPer"=>$row["discountOtherIsPer"]
            ,"discountSector"=>$row["discountSector"]
            ,"discountSectorIsPer"=>$row["discountSectorIsPer"]
            ,"discountTicket"=>$row["discountTicket"]
            ,"discountTicketIsPer"=>$row["discountTicketIsPer"]
            ,"serviceAmount"=>$row["serviceAmount"]
            ,"totalservice"=>$row["totalservice"]
            ,"totalwithoutdiscount"=>$row["totalwithoutdiscount"]
            ,"totalwithdiscount"=>$row["totalwithdiscount"]
            ,"totalwithservice"=>$row["totalwithservice"]);
        }
        return $json;
    }
    function getcurrentpurchase($id_session) {
        $query = "EXEC pr_purchase_get_current ?";
        $params = array($id_session);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array("id"=>$row["id"]
            ,"id_base"=>$row["id_base"]
            ,"CodApresentacao"=>$row["CodApresentacao"]
            ,"CodTipBilhete"=>$row["CodTipBilhete"]
            ,"Indice"=>$row["Indice"]
            ,"StaCadeira"=>$row["StaCadeira"]
            ,"DatApresentacao"=>$row["DatApresentacao"]
            ,"HorSessao"=>$row["HorSessao"]
            ,"ValPeca"=>$row["ValPeca"]
            ,"CodPeca"=>$row["CodPeca"]
            ,"NomPeca"=>$row["NomPeca"]
            ,"qt_parcelas"=>$row["qt_parcelas"]
            ,"NomSala"=>$row["NomSala"]
            ,"StaSala"=>$row["StaSala"]
            ,"active"=>$row["active"]
            ,"allowticketoffice"=>$row["allowticketoffice"]
            ,"allowweb"=>$row["allowweb"]
            ,"NomObjeto"=>$row["NomObjeto"]
            ,"NomSetor"=>$row["NomSetor"]
            ,"PerDescontoSetor"=>$row["PerDescontoSetor"]
            ,"Status"=>$row["Status"]
            ,"PerDesconto"=>$row["PerDesconto"]
            ,"StaTipBilhMeiaEstudante"=>$row["StaTipBilhMeiaEstudante"]
            ,"StaTipBilhete"=>$row["StaTipBilhete"]
            ,"TipBilhete"=>$row["TipBilhete"]
            ,"id_evento"=>$row["id_evento"]
            ,"id_apresentacao"=>$row["id_apresentacao"]
            ,"id_reserva"=>$row["id_reserva"]
            ,"hoursinadvance"=>$row["hoursinadvance"]
            ,"in_taxa_por_pedido"=>$row["in_taxa_por_pedido"]
            ,"id_apresentacao_bilhete"=>$row["id_apresentacao_bilhete"]
            ,"nr_beneficio"=>$row["nr_beneficio"]
            ,"QT_INGRESSOS_POR_CPF"=>$row["QT_INGRESSOS_POR_CPF"]
            ,"purchasebythiscpf"=>$row["purchasebythiscpf"]
            );
        }
        return $json;
    }
    function luhn_check($number) {

        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number=preg_replace('/\D/', '', $number);
      
        // Set the string length and parity
        $number_length=strlen($number);
        $parity=$number_length % 2;
      
        // Loop through each digit and do the maths
        $total=0;
        for ($i=0; $i<$number_length; $i++) {
          $digit=$number[$i];
          // Multiply alternate digits by two
          if ($i % 2 == $parity) {
            $digit*=2;
            // If the sum is two digits, add them together (in effect)
            if ($digit > 9) {
              $digit-=9;
            }
          }
          // Total up the digits
          $total+=$digit;
        }
      
        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? TRUE : FALSE;
      
    }
    
    function validate_post($id_client, $isCreditCard, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments) {
        $hasError = false;
        $errs = array();

        if ($id_payment_method == '') {
            $hasError = true;
            array_push($errs, array("field"=>"id_payment_method", "msgtobuyer"=> "Código do tipo de pagamento é obrigatório."));
        }
        if ($isCreditCard) {
            if ($card_number == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msgtobuyer"=> "Número do cartão de crédito é obrigatório."));
            }
            if (strlen($card_number) > 16) {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msgtobuyer"=> "Número do cartão de crédito é inválido. (ERR-1)"));
            }
            if (!luhn_check($card_number)) {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msgtobuyer"=> "Número do cartão de crédito é inválido. (ERR-2)"));
            }
            if ($card_holdername == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_holdername", "msgtobuyer"=> "Nome no cartão de crédito é obrigatório."));
            }
            if ($card_expirationdate == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_holdername", "msgtobuyer"=> "Data de expiração de crédito é obrigatório."));
            }
            $card_expirationdate_explode = explode("/", $card_expirationdate);
            if (!is_numeric($card_expirationdate_explode[0])) {
                $hasError = true;
                array_push($errs, array("field"=>"card_expirationdate", "msgtobuyer"=> "Data de expiração de crédito é inválida. (ERR-1)"));
            }
            if (intval(date('Y'))<=intval($card_expirationdate_explode[1]) && intval(date('m'))<intval($card_expirationdate_explode[0])) {
                $hasError = true;
                array_push($errs, array("field"=>"card_expirationdate", "msgtobuyer"=> "Data de expiração de crédito é inválida. (ERR-3)"));
            }
            if ($card_cvv == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_cvv", "msgtobuyer"=> "CVV é obrigatório."));
            }
            if (!is_numeric($card_cvv)) {
                $hasError = true;
                array_push($errs, array("field"=>"card_cvv", "msgtobuyer"=> "Data de expiração de crédito é inválida. (ERR-1)"));
            }
            if ($payment_method!="credit_card" && $payment_method!="payment_slip") {
                $hasError = true;
                array_push($errs, array("field"=>"payment_method", "msgtobuyer"=> "Método de pagamento é inválido. (ERR-1)"));
            }
            if ($installments == '') {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msgtobuyer"=> "Número de parcelas é obrigatório."));
            }
            if (!is_numeric($installments)) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msgtobuyer"=> "Número de parcelas é inválido. (ERR-1)"));
            }
            if (!intval($installments)<1) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msgtobuyer"=> "Número de parcelas é inválido. (ERR-2)"));
            }
            if (!intval($installments)>12) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msgtobuyer"=> "Número de parcelas é inválido. (ERR-3)"));
            }   
        }

        if ($hasError) {
            $msg = "Falha na validação dos dados enviados.";
        }

        return array("success"=>$hasError == false, "msg_obj"=>$errs, "msgtobuyer"=>$msg);
    }
    function getbases4purchasedistinct($id_session) {
        $query = "EXEC pr_purchase_get_all_bases_distinct ?";
        $params = array($id_session);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array("id_base"=>$row["id_base"]);
        }
        return $json;
    }

    function traceme($id_purchase, $title, $values, $isforeign) {
        $uri = $_SERVER["REQUEST_URI"];
        $file = $_SERVER["PHP_SELF"];
        $agent = $_SERVER["HTTP_USER_AGENT"];
        $ip = "";

        if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }

        $ip2 = $_SERVER['REMOTE_ADDR'];
        $host = $_SERVER['HTTP_HOST'];


        $HTTP_ORIGIN = '';
        if (array_key_exists("HTTP_ORIGIN", $_SERVER)) {
            $HTTP_ORIGIN = $_SERVER['HTTP_ORIGIN'];
        }
        $HTTP_REFERER = '';
        if (array_key_exists("HTTP_REFERER", $_SERVER)) {
            $HTTP_REFERER = $_SERVER['HTTP_REFERER'];
        }


        $query = "EXEC pr_traceme ?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($id_purchase, $uri, $file
                        , json_encode($_REQUEST), json_encode($_POST), json_encode($agent)
                        , json_encode($ip), json_encode($ip2), $host
                        , $HTTP_ORIGIN, $HTTP_REFERER
                        , $title, $values, $isforeign);
        $result = db_exec($query, $params);
    }
?>