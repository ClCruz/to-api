<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/config/pagarme.php");
//    require($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

    //getConfigPagarme() 

    function pagarme_installments($free_installments, $max_installments, $interest_rate, $amount) {
        $conf = getConfigPagarme();

        $url = $conf["apiURI"]."transactions/calculate_installments_amount";

        $fields = array(
            'api_key' => urlencode($conf["apikey"]),
            'amount' => urlencode($amount),
            'free_installments' => urlencode($free_installments),
            'max_installments' => urlencode($max_installments),
            'interest_rate' => urlencode($interest_rate),
        );

        $fields_string = "";

        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }

        $fields_string = rtrim($fields_string, '&');

        $url = $url."?".$fields_string;

        $curl = curl_init();

        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        
        $curl_exec = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($curl_exec);

        // die(print_r($result[0]->installments, true));

        return $result;
    }

    function pagarme_setMetadata($id_pedido, $id_evento) {
        return array("id_pedido_venda"=>$id_pedido, "id_evento"=>$id_evento);
    }
    /*
        $metadata: informações para ficarem gravadas no pagarme
        $charge: objeto da cobrança
            amount: total a ser cobrado (int)
            split: informações do split
        $buyer: dados do comprador
            name: nome
            document: numero do documento
            email: email
            sex: sexo
            born: data de nascimento
            address: endereço
                street: rua
                neighborhood: bairro
                zipcode: cep
                number: numero
                complementary: complemento
                city: cidade
                state: estado
            phone: numero do telefone/celular
                ddd: numero ddd
                number: numero
    */
    function pagarme_payment($id_purchase, $id_client, $metadata, $charge,$buyer) {
        $conf = getConfigPagarme();
        //$pagarme = new PagarMe\Client($conf["apikey"]);

        $transaction_data = array(
            "api_key" => $conf["apikey"],    
            // "metadata" => $metadata,    
            "amount" => $charge["amount"],
            "customer" => array(
                "name" => $buyer["name"],
                "document_number" => $buyer["document"],
                "email" => $buyer["email"],
                "sex" => $buyer["sex"],
                "born_at" => $buyer["born"],
                "address" => array(
                    "street" => $buyer["address"]["street"],
                    "neighborhood" => $buyer["address"]["neighborhood"],
                    "zipcode" => $buyer["address"]["zipcode"],
                    "street_number" => $buyer["address"]["number"],
                    "complementary" => $buyer["address"]["complementary"],
                    "city" => $buyer["address"]["city"],
                    "state" => $buyer["address"]["state"]
                )
                // "phone" => array(
                //     "ddd" => $buyer["phone"]["ddd"] == null ? '' : $buyer["phone"]["ddd"],
                //     "number" => $buyer["phone"]["number"] == null ? '' : $buyer["phone"]["number"]
                // )
            ),
            "postback_url" => $conf["postbackURI"]
        );
        
        if ($charge["iscreditcard"] == 1) {
            $transaction_data = array_merge($transaction_data, array(
                "card_holder_name" => $charge['card_holder_name'],
                "card_cvv" => $charge['card_cvv'],
                "card_number" => $charge['card_number'],
                "card_expiration_date" => $charge['card_expiration_date'],
                "installments" => $charge['installments'],
                "payment_method" => "credit_card",
                "soft_descriptor" => NULL,
                "capture" => false,
                "async" => false
            ));
        }
        elseif ($charge["ispaymentslip"] == 1) {
            $transaction_data = array_merge($transaction_data, array(
                "payment_method" => "boleto"
            ));
        }
        
        if (is_array($charge["split"])) {
            $transaction_data = array_merge($transaction_data, array(
                "split_rules" => $charge["split"]
            ));
        }
        
        
        $url = $conf["apiURI"]."transactions";
        
        //die(json_encode($transaction_data));
        
        $post_data = json_encode($transaction_data);     
        //$out = fopen('php://output', 'w');
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);                                                                  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
        // curl_setopt($curl, CURLOPT_VERBOSE, true);
        // curl_setopt($curl, CURLOPT_STDERR, $out);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($post_data))                                                                       
        );             

        $response = curl_exec($curl);
        // fclose($out);
        $errno = curl_errno($curl);

        // $data = ob_get_clean();
        // $data .= PHP_EOL . $response . PHP_EOL;
        // echo $data;

        $responseJSON = array();

        if ($errno) {
            $error_message = curl_strerror($errno);
            $responseJSON = array("success"=>false, "object"=>"payment","msg"=>"Erro no pagamento do gateway.", "gatewayinfo"=>json_decode($error_message));
        }
        else {
            $json_response = json_decode($response);
            if (property_exists($json_response, 'errors')) {
                $responseJSON = array("success"=>false, "object"=>"payment","msg"=>"Falha no pagamento do gateway.", "gatewayinfo"=>$json_response);
            }
            else {
                $responseJSON = array("success"=>true, "object"=>"payment","msg"=>"Sucesso.", "gatewayinfo"=>$json_response);
            }
            
        }
        curl_close($curl);
        $ret = array();
        if ($responseJSON["success"]) {
            switch ($responseJSON["gatewayinfo"]->status) {
                case "paid":
                case "authorized":
                case "waiting_payment":
                    $ret = array("success"=>true
                        ,"object"=>"payment"
                        ,"msg"=>"purchase approved."
                        ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                        ,"authorization_code"=>$responseJSON["gatewayinfo"]->authorization_code
                        ,"authorization_desc"=>translateacquirerresponsecode($responseJSON["gatewayinfo"]->acquirer_response_code)
                        ,"authorized_amount"=>$responseJSON["gatewayinfo"]->authorized_amount
                        ,"status"=>$responseJSON["gatewayinfo"]->status
                        ,"cost"=>$responseJSON["gatewayinfo"]->cost
                        ,"id"=>$responseJSON["gatewayinfo"]->tid
                        ,"card_last_digits"=>$responseJSON["gatewayinfo"]->card_last_digits
                        ,"card_first_digits"=>$responseJSON["gatewayinfo"]->card_first_digits
                        ,"card_brand"=>$responseJSON["gatewayinfo"]->card_brand
                        ,"ip"=>$responseJSON["gatewayinfo"]->ip);
                break;
                case "refused":
                    switch($responseJSON["gatewayinfo"]->refuse_reason) {
                        case "acquirer":
                        case "antifraud":
                        case "internal_error":
                        case "no_acquirer":
                        case "acquirer_timeout":
                            $ret = array("success"=>false
                            ,"object"=>"payment"
                            ,"msg"=>"purchase not approved."
                            ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                            ,"authorization_code"=>$responseJSON["gatewayinfo"]->authorization_code
                            ,"authorization_desc"=>translateacquirerresponsecode($responseJSON["gatewayinfo"]->acquirer_response_code)
                            ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                            ,"authorized_amount"=>$responseJSON["gatewayinfo"]->authorized_amount
                            ,"status"=>$responseJSON["gatewayinfo"]->status
                            ,"cost"=>$responseJSON["gatewayinfo"]->cost
                            ,"id"=>$responseJSON["gatewayinfo"]->tid
                            ,"card_last_digits"=>$responseJSON["gatewayinfo"]->card_last_digits
                            ,"card_first_digits"=>$responseJSON["gatewayinfo"]->card_first_digits
                            ,"card_brand"=>$responseJSON["gatewayinfo"]->card_brand
                            ,"ip"=>$responseJSON["gatewayinfo"]->ip);

                        break;
                    }                     
                break;
            }
        }
        else {
            $ret = array("success"=>false
            ,"object"=>"payment"
            ,"msg"=>"purchase not approved."
            ,"acquirer_response_code"=>"9999"
            ,"authorization_code"=>""
            ,"authorization_desc"=>translateacquirerresponsecode("9999")
            ,"acquirer_response_code"=>""
            ,"authorized_amount"=>""
            ,"status"=>"error"
            ,"cost"=>""
            ,"id"=>""
            ,"card_last_digits"=>""
            ,"card_first_digits"=>""
            ,"card_brand"=>""
            ,"ip"=>"");
        }
        return $ret;
    }
    function translateacquirerresponsecode($codeAux) {
        $code = (string)$codeAux;
        $ret = "";
        $guindance = "";

        switch ($code) {
            case "0000": 
                $ret = "Transação autorizada";
            break;
            case "1000": 
                $ret = "Transação não autorizada";
            break;
            case "1001": 
                $ret = "Cartão vencido";
            break;
            case "1002": 
                $ret = "Transação não permitida";
            break;
            case "1003": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "1004": 
                $ret = "Cartão com restrição";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "1005": 
                $ret = "Transação não autorizada";
            break;
            case "1006": 
                $ret = "Tentativas de senha excedidas";
            break;
            case "1007": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "1008": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "1009": 
                $ret = "Transação não autorizada";
            break;
            case "1010": 
                $ret = "Valor inválido";
            break;
            case "1011": 
                $ret = "Cartão inválido";
            break;
            case "1013": 
                $ret = "Transação não autorizada";
            break;
            case "1014": 
                $ret = "Tipo de conta inválido";
                $guindance = "O tipo de conta selecionado não existe.Ex: transação de crédito num de débito.";
            break;
            case "1016": 
                $ret = "Saldo insuficiente";
            break;
            case "1017": 
                $ret = "Senha inválida";
            break;
            case "1019": 
                $ret = "Transação não permitida";
            break;
            case "1020": 
                $ret = "Transação não permitida";
            break;
            case "1021": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão.";
            break;
            case "1022": 
                $ret = "Cartão com restrição";
            break;
            case "1023": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão.";
            break;
            case "1024": 
                $ret = "Transação não permitida";
            break;
            case "1025": 
                $ret = "Cartão bloqueado";
                $guindance = "Entre em contato com o banco emissor do cartão.";
            break;
            case "1042": 
                $ret = "Tipo de conta inválido";
                $guindance = "O tipo de conta selecionado não existe.Ex: transação de crédito num de débito.";
            break;
            case "1045": 
                $ret = "Código de segurança inválido";
            break;
            case "1045": 
                $ret = "Código de segurança inválido";
            break;
            case "2000": 
                $ret = "Cartão com restrição";
            break;
            case "2001": 
                $ret = "Cartão vencido";
            break;
            case "2002": 
                $ret = "Transação não permitida";
            break;
            case "2003": 
                $ret = "Rejeitado emissor";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "2004": 
                $ret = "Cartão com restrição";
                $guindance = "Entre em contato com o banco emissor do cartão";
            break;
            case "2005": 
                $ret = "Transação não autorizada";
            break;
            case "2006": 
                $ret = "Tentativas de senha excedidas";
            break;
            case "2007": 
                $ret = "Cartão com restrição";
            break;
            case "2008": 
                $ret = "Cartão com restrição";
            break;
            case "2009": 
                $ret = "Cartão com restrição";
            break;
            case "9102": 
                $ret = "Transação inválida";
            break;
            case "9108": 
                $ret = "Erro no processamento";
            break;
            case "9109": 
                $ret = "Erro no processamento";
            break;
            case "9111": 
                $ret = "Time -out na transação";
            break;
            case "9112": 
                $ret = "Emissor indisponível";
            break;
            case "9999": 
                $ret = "Erro não especificado";
            break;
            default:
                $ret = "Erro não especificado";
            break;
        }
        return $ret.($guindance != "" ? (" - ".$guindance) : "");
    }
    function pagarme_capture($id_purchase, $id_gateway, $id_client, $metadata, $charge,$buyer) {
        $conf = getConfigPagarme();
        //$pagarme = new PagarMe\Client($conf["apikey"]);

        $transaction_data = array(
            "api_key" => $conf["apikey"],    
            "metadata" => $metadata,    
            "amount" => $charge["amount"],
        );
                
        if (is_array($charge["split"])) {
            $transaction_data = array_merge($transaction_data, array(
                "split_rules" => $charge["split"]
            ));
        }
        
        
        $url = $conf["apiURI"]."transactions/".$id_gateway."/capture";
        
        //die(json_encode($transaction_data));
        
        $post_data = json_encode($transaction_data);     
        //$out = fopen('php://output', 'w');
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);                                                                  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
        // curl_setopt($curl, CURLOPT_VERBOSE, true);
        // curl_setopt($curl, CURLOPT_STDERR, $out);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($post_data))                                                                       
        );             

        $response = curl_exec($curl);
        // fclose($out);
        $errno = curl_errno($curl);

        // $data = ob_get_clean();
        // $data .= PHP_EOL . $response . PHP_EOL;
        // echo $data;

        $responseJSON = array();

        if ($errno) {
            $error_message = curl_strerror($errno);
            $responseJSON = array("success"=>false, "object"=>"capture","msg"=>"Erro no pagamento do gateway.", "gatewayinfo"=>json_decode($error_message));
        }
        else {
            $json_response = json_decode($response);
            if (property_exists($json_response, 'errors')) {
                $responseJSON = array("success"=>false, "object"=>"capture","msg"=>"Falha no pagamento do gateway.", "gatewayinfo"=>$json_response);
            }
            else {
                $responseJSON = array("success"=>true, "object"=>"capture","msg"=>"Sucesso.", "gatewayinfo"=>json_decode($response));
            }
            
        }
        curl_close($curl);
        $ret = array();
        if ($responseJSON["success"]) {
            switch ($responseJSON["gatewayinfo"]->status) {
                case "paid":
                case "authorized":
                case "waiting_payment":
                    $ret = array("success"=>true
                        ,"object"=>"capture"
                        ,"msg"=>"purchase approved."
                        ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                        ,"authorization_code"=>$responseJSON["gatewayinfo"]->authorization_code
                        ,"authorization_desc"=>translateacquirerresponsecode($responseJSON["gatewayinfo"]->acquirer_response_code)
                        ,"authorized_amount"=>$responseJSON["gatewayinfo"]->authorized_amount
                        ,"status"=>$responseJSON["gatewayinfo"]->status
                        ,"cost"=>$responseJSON["gatewayinfo"]->cost
                        ,"id"=>$responseJSON["gatewayinfo"]->tid
                        ,"card_last_digits"=>$responseJSON["gatewayinfo"]->card_last_digits
                        ,"card_first_digits"=>$responseJSON["gatewayinfo"]->card_first_digits
                        ,"card_brand"=>$responseJSON["gatewayinfo"]->card_brand
                        ,"ip"=>$responseJSON["gatewayinfo"]->ip);
                break;
                case "refused":
                    switch($responseJSON["gatewayinfo"]->refuse_reason) {
                        case "acquirer":
                        case "antifraud":
                        case "internal_error":
                        case "no_acquirer":
                        case "acquirer_timeout":
                            $ret = array("success"=>false
                            ,"object"=>"capture"
                            ,"msg"=>"purchase not approved."
                            ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                            ,"authorization_code"=>$responseJSON["gatewayinfo"]->authorization_code
                            ,"authorization_desc"=>translateacquirerresponsecode($responseJSON["gatewayinfo"]->acquirer_response_code)
                            ,"acquirer_response_code"=>$responseJSON["gatewayinfo"]->acquirer_response_code
                            ,"authorized_amount"=>$responseJSON["gatewayinfo"]->authorized_amount
                            ,"status"=>$responseJSON["gatewayinfo"]->status
                            ,"cost"=>$responseJSON["gatewayinfo"]->cost
                            ,"id"=>$responseJSON["gatewayinfo"]->tid
                            ,"card_last_digits"=>$responseJSON["gatewayinfo"]->card_last_digits
                            ,"card_first_digits"=>$responseJSON["gatewayinfo"]->card_first_digits
                            ,"card_brand"=>$responseJSON["gatewayinfo"]->card_brand
                            ,"ip"=>$responseJSON["gatewayinfo"]->ip);

                        break;
                    }                     
                break;
            }
        }
        else {
            $ret = array("success"=>false
            ,"object"=>"capture"
            ,"msg"=>"purchase not approved."
            ,"acquirer_response_code"=>"9999"
            ,"authorization_code"=>""
            ,"authorization_desc"=>translateacquirerresponsecode("9999")
            ,"acquirer_response_code"=>""
            ,"authorized_amount"=>""
            ,"status"=>"error"
            ,"cost"=>""
            ,"id"=>""
            ,"card_last_digits"=>""
            ,"card_first_digits"=>""
            ,"card_brand"=>""
            ,"ip"=>"");
        }
        return $ret;
    }

?>