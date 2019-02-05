<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/cardbin.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");

    function check_halfpricestudents_lot_validate($id_purchase, $id_client, $id_session) {

        // $basesandreserva = getbases4purchasedistinct($id_session);

        // $validated = array();
        // $hasError = false;
        // $errorMsg = "";

        // traceme($id_purchase, "Validate Half Price Students/Lot - looping in bases", json_encode(array("id_client"=>$id_client, "id_session"=> $id_session)),0);
        // foreach ($basesandreserva as &$row) {
        //     $query = "EXEC pr_purchase_halfpricestudents_lot_validate ?";
        //     $params = array($bin, $id_session);
        //     $result = db_exec($query, $params, $row["id_base"]);
        //     foreach ($result as &$row2) {
        //         if ($row2["success"] == 0 && $hasError == false) {
        //             $hasError = true;
        //             $errorMsg = $row2["msg"];
        //         }

        //         $validated[] = array("id_base"=>$row2["id_base"]
        //                             ,"success"=>$row2["success"]
        //                             ,"msg"=>$row2["msg"]);
        //     }
        // }
        // traceme($id_purchase, "Validate Half Price Students/Lot - looping result", json_encode($validated),0);

        // return array("success"=>$hasError == false, "msg"=>$errorMsg);
    }

    function dopurchase($id_client, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments, $vouchername, $voucheremail) {
        $start = $_SERVER["REQUEST_TIME"];
        $ip = "";
        if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
    
        $ip = ($ip == '' ? '' : '|').$_SERVER['REMOTE_ADDR'];
    
  
        $id_purchase = get_id_purchase();
        
        traceme($id_purchase, "Initiating purchase", json_encode(array("id_client"=>$id_client, "card_number"=>$card_number, "card_holdername"=>$card_holdername, "card_expirationdate"=>$card_expirationdate, "card_cvv"=>$card_cvv, "payment_method"=>$payment_method, "installments"=>$installments)),0);

        // $postValidate = validate_post($id_client, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments);
        // if ($postValidate["success"] == false) {
        //     echo json_encode($postValidate);
        //     logme();
        //     traceme($id_purchase, "Finished purchase", json_encode($postValidate),0);
        //     die();
        // }

        $mysession = getmysession($id_purchase, $id_client);
        $id_session = $mysession["id_session"];
        $shopping = getcurrentpurchase($id_session);

        if (count($shopping) == 0) {
            echo json_encode(array("success"=>false, "msg"=>"Não há nenhum item selecionado."));
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }

        $client = getclient($id_purchase, $id_client);
        
        
        $paymentmethod = getpaymentmethod($id_purchase, $id_payment_method);
        
        $isCreditCard = $paymentmethod["in_tipo_meio_pagamento"] == 'CC';
        
        $bin = '';
        $card_number_original = $card_number;
        
        if ($isCreditCard) {
            $card_number = preg_replace("/[^0-9]/", "", $card_number);
            $bin = substr($card_number, 0, 5);

            // $binValidate = validateBIN($id_purchase, $id_client, "itau", $id_session, $bin);
            // if ($binValidate["success"] == false) {
            //     echo json_encode($binValidate);
            //     logme();
            //     traceme($id_purchase, "Finished purchase", '',0);
            //     die();
            // }
        }

        // $halfValidate = check_halfpricestudents_lot_validate($id_purchase, $id_client, $id_session);
        // if ($halfValidate["success"] == false) {
        //     echo json_encode($halfValidate);
        //     logme();
        //     traceme($id_purchase, "Finished purchase", '',0);
        //     die();
        // }

        $ispaymentmethodok = ispaymentmethodok($id_purchase, $id_client, $id_session, $id_payment_method, $shopping);
        if ($ispaymentmethodok["success"] == false) {
            echo json_encode($ispaymentmethodok);
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }
        
        $multiple = checkmultipleevents($id_purchase, $id_client);
        if ($multiple["success"] == false) {
            echo json_encode($multiple);
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }
        
        renew($id_client);
        
        $values = getvaluesofmyshoppig($id_client);

        //die(json_encode($values));
        
        $amount = $values[0]["totalwithservice"];
        $totalwithdiscount = $values[0]["totalwithdiscount"];
        $totalservice = $values[0]["totalservice"];
        
        $installment_config = getinstallments($id_purchase, $id_client);
        $installment_gateway = pagarme_installments($installment_config["free_installments"], $installment_config["max_installments"], $installment_config["interest_rate"], $amount);

        $installment_choosed = $installment_gateway->installments->{$installments};

        $amountToPay = $amount;

        if (intval($installments)>1) {
            $amountToPay = $installment_choosed->amount;
        }

        $split = getpurchasesplit($id_purchase, $id_client, "pagarme", $amountToPay, "web", $payment_method);

        //die(json_encode($shopping));

        $metadata = pagarme_setMetadata("123", $shopping[0]["id_evento"]);

        $charge = array("amount"=>$amountToPay
                        ,"split"=>$split
                        ,"iscreditcard"=> $isCreditCard ? 1 : 0
                        ,"ispaymentslip"=> $isCreditCard ? 0 : 1
                        ,"card_cvv"=>$card_cvv
                        ,"card_expiration_date"=>$card_expirationdate
                        ,"card_holder_name"=>$card_holdername
                        ,"card_number"=>$card_number
                        ,"installments"=>$installments);

        $buyer = array("name"=> $client["fullname"]
                        ,"document"=> $client["cd_cpf"]
                        ,"email"=> $client["cd_email_login"]
                        ,"sex"=> $client["in_sexo"]
                        ,"born"=> $client["dt_nascimento"]
                        ,"address"=> array(
                            "street"=> $client["ds_endereco"]
                            ,"neighborhood"=> $client["ds_bairro"]
                            ,"zipcode"=> $client["cd_cep"]
                            ,"number"=> $client["nr_endereco"]
                            ,"complementary"=> $client["ds_compl_endereco"]
                            ,"city"=> $client["ds_cidade"]
                            ,"state"=> "SP" //fixado
                        )
                    );
        $purchase_gateway = pagarme_payment($id_purchase, $id_client, $metadata, $charge, $buyer);

        $retofsevice = array("success"=>false, "seconds"=>0, "id_pedido_venda"=> 0, "codVenda"=> '', "msg"=> '');

        if ($purchase_gateway["success"]) {
            $bin = $purchase_gateway["card_first_digits"];
            $amount = $values[0]["totalwithservice"];
            $totalwithdiscount = $values[0]["totalwithdiscount"];
            $totalservice = $values[0]["totalservice"];
            $host = gethost();
            $nr_beneficio = '';
    
            $pedidovenda = generate_pedido_venda($id_client, null, $amountToPay,intval($amountToPay)-intval($totalservice),$totalservice,$bin,$installments,$ip,$host,$nr_beneficio,$vouchername,$voucheremail,$card_holdername,"pagarme", $purchase_gateway["authorization_code"], $purchase_gateway["id"], $purchase_gateway["authorization_code"], $paymentmethod["id_meio_pagamento"]);

            $sell = sell($id_client, $amountToPay, $pedidovenda["id_pedido_venda"], $id_payment_method);
            
            if ($sell["success"]) {
                $capture_gateway = pagarme_capture($id_purchase,$purchase_gateway["id"], $id_client, $metadata, $charge, $buyer);

                $retofsevice = array("success"=>true, "seconds"=>0, "id_pedido_venda"=> $sell["id_pedido_venda"], "codVenda"=> $sell["codVenda"], 'msg' => '');
            }
            else {
                $retofsevice = array("success"=>false, "seconds"=>0, "id_pedido_venda"=> $sell["id_pedido_venda"], "codVenda"=> $sell["codVenda"], 'msg' => json_encode($sell));
            }
        }
        else {
            $retofsevice = array("success"=>false, "seconds"=>0, "id_pedido_venda"=> 0, "codVenda"=> 0, 'msg' => json_encode($purchase_gateway));
        }

        $end = time();
        $duration = $end-$start;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;

        $retofsevice["seconds"] = $seconds;

        echo json_encode($retofsevice);
        logme();
        die();    
        
        //$id_client, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments
        

        // clearcurrentsessionclient($id_client);
    }
    /*
        id_client: client id    
        id_payment_method: code of payment method
        card_number: credit card number
        card_holdername: credit card holder name 
        card_expirationdate: credit card expiration date
        card_cvv: credit card verification code
        payment_method: method of payment (credit_card, payment_slip)
        installments: installment number for this payment
    */
  //dopurchase($_POST["id_client"], $_POST["id_payment_method"], $_POST["card_number"], $_POST["card_holdername"], $_POST["card_expirationdate"], $_POST["card_cvv"], $_POST["payment_method"], $_POST["installments"]);
  dopurchase($_REQUEST["id_client"], $_REQUEST["id_payment_method"], $_REQUEST["card_number"], $_REQUEST["card_holdername"], $_REQUEST["card_expirationdate"], $_REQUEST["card_cvv"], $_REQUEST["payment_method"], $_REQUEST["installments"], $_REQUEST["voucher_name"], $_REQUEST["voucher_email"]);

  //27902689_YWVB5h1IxqjniCvBl1pEOA2L2/XFR1MbmLDEXz6tbaIoW0E5VQ5TG1CWtGb8Cy2NxH+2Lq87ChSvkLIjXi1HDc/ZjZhdogfZfUo4AA8qxp343/hiEOR6ouzxYEPIQi9YJKlbR2NjTfahwhxiKE6w/cZGWHyBZNFjKeMvnn/eD/gtJ4kL5hYrYi9dJIZr2OpENa4ghuoIgyV1vJfQVWPY5EyRwkVZPNCb7UXNW6R8xCZSLvSg7yvNvkBpeifnN7GW3EmV/0/BhXTgNLpkE4EUoNLdDuiL133hGaDF8296bcVxydxLoohLwivYOc9D35RNPtAQrXpCyu8rUhcvpMGM0w==
  //https://compra.bringressos.com.br/comprar/etapa1.php?apresentacao=167576
  //http://localhost:2002/v1/purchase/site/session?id_client=30&session=thslkr39i6nhon6qgbgs5bnoc2
  //http://localhost:2002/v1/purchase/site/do.php?id_client=30&id_payment_method=910&card_number=4242424242424242&card_holdername=matt murdock&card_expirationdate=0121&card_cvv=123&payment_method=credit_card&installments=1&voucher_name=&voucher_email=
  //http://localhost:2002/v1/purchase/site/do.php?id_client=30&id_payment_method=910&card_number=4242424242424242&card_holdername=matt%20murdock&card_expirationdate=0121&card_cvv=623&payment_method=credit_card&installments=1

//     function get($key, $bin) {

//         $query = "EXEC pr_pinpad_get_base ?";
//         $params = array($key);
//         $result = db_exec($query, $params);

//         $id_base = null;
//         foreach ($result as &$row) {
//             $id_base = $row["id_base"];
//         }

//         $query = "EXEC pr_checkbin ?, ?";
//         $params = array($key, $bin);
//         $result = db_exec($query, $params, $id_base);

//         $json = array();
//         foreach ($result as &$row) {
//             $json = array("check"=>$row["check"]
//                         ,"success"=>$row["success"]);
            
//             //array_push($json,$aux);
//         }

//         echo json_encode($json);
//         logme();
//         die();    
//     }
// get($_REQUEST["key"], $_REQUEST["bin"]);
?>