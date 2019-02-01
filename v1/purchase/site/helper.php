<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get_id_purchase() {
        return "teste";
    }
    function ispaymentmethodok($id_purchase, $id_client, $id_session, $id_payment_method, $shopping) {

        traceme($id_purchase, "Validate Payment Method - start", '',0);
        $query = "EXEC pr_purchase_payment_method_hoursinadvance ?";
        $params = array($bin, $id_payment_method);
        $result = db_exec($query, $params);
        $db = array();
        $ret = array("success"=>true, "msg"=>"");
        $hasError = false;
        $errorMsg = "";
        foreach ($result as &$row) {
            $db = array("QT_HR_ANTECED"=>$row["QT_HR_ANTECED"]
                        ,"id_payment_method"=>$id_payment_method);
        }

        foreach ($shopping as &$row) {
            if ($db["QT_HR_ANTECED"]>$row["hoursinadvance"]) {
                $hasError = true;
                $errorMsg = "Esta forma de pagamento não pode ser utilizada no momento. Por favor, selecione outra. (".$db["QT_HR_ANTECED"].")";
                break;
            }
        }

        traceme($id_purchase, "Validate Payment Method - end", '',0);

        return array("success"=>$hasError == false, "msg"=>$errorMsg);
    }

    function getmysession($id_purchase, $id_client) {

        traceme($id_purchase, "get my session - start", '',0);
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

        traceme($id_purchase, "get my session - end", '',0);

        return $ret;
    }

    function getpaymentmethod($id_purchase, $id_payment_method) {

        traceme($id_purchase, "get payment method - start", '',0);
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

        traceme($id_purchase, "get payment method - end", '',0);

        return $ret;
    }

    function getclient($id_purchase, $id_client) {

        traceme($id_purchase, "get client - start", '',0);
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

        traceme($id_purchase, "get client - end", '',0);

        return $ret;
    }

    function makeitmine($id_purchase, $id_client, $id_session) {

        traceme($id_purchase, "Make it mine - start", '',0);
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

        traceme($id_purchase, "Make it mine - end", '',0);

        return $ret;
    }

    function renew($id_session) {
        $query = "EXEC pr_purchase_renew ?,?";
        $params = array($id_session, 10);
        $result = db_exec($query, $params);
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
            ,"in_taxa_por_pedido"=>$row["in_taxa_por_pedido"]);
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
            array_push($errs, array("field"=>"id_payment_method", "msg"=> "Código do tipo de pagamento é obrigatório."));
        }
        if ($isCreditCard) {
            if ($card_number == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msg"=> "Número do cartão de crédito é obrigatório."));
            }
            if (strlen($card_number) > 16) {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msg"=> "Número do cartão de crédito é inválido. (ERR-1)"));
            }
            if (!luhn_check($card_number)) {
                $hasError = true;
                array_push($errs, array("field"=>"card_number", "msg"=> "Número do cartão de crédito é inválido. (ERR-2)"));
            }
            if ($card_holdername == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_holdername", "msg"=> "Nome no cartão de crédito é obrigatório."));
            }
            if ($card_expirationdate == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_holdername", "msg"=> "Data de expiração de crédito é obrigatório."));
            }
            $card_expirationdate_explode = explode("/", $card_expirationdate);
            if (!is_numeric($card_expirationdate_explode[0])) {
                $hasError = true;
                array_push($errs, array("field"=>"card_expirationdate", "msg"=> "Data de expiração de crédito é inválida. (ERR-1)"));
            }
            if (intval(date('Y'))<=intval($card_expirationdate_explode[1]) && intval(date('m'))<intval($card_expirationdate_explode[0])) {
                $hasError = true;
                array_push($errs, array("field"=>"card_expirationdate", "msg"=> "Data de expiração de crédito é inválida. (ERR-3)"));
            }
            if ($card_cvv == '') {
                $hasError = true;
                array_push($errs, array("field"=>"card_cvv", "msg"=> "CVV é obrigatório."));
            }
            if (!is_numeric($card_cvv)) {
                $hasError = true;
                array_push($errs, array("field"=>"card_cvv", "msg"=> "Data de expiração de crédito é inválida. (ERR-1)"));
            }
            if ($payment_method!="credit_card" && $payment_method!="payment_slip") {
                $hasError = true;
                array_push($errs, array("field"=>"payment_method", "msg"=> "Método de pagamento é inválido. (ERR-1)"));
            }
            if ($installments == '') {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msg"=> "Número de parcelas é obrigatório."));
            }
            if (!is_numeric($installments)) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msg"=> "Número de parcelas é inválido. (ERR-1)"));
            }
            if (!intval($installments)<1) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msg"=> "Número de parcelas é inválido. (ERR-2)"));
            }
            if (!intval($installments)>12) {
                $hasError = true;
                array_push($errs, array("field"=>"installments", "msg"=> "Número de parcelas é inválido. (ERR-3)"));
            }   
        }

        if ($hasError) {
            $msg = "Falha na validação dos dados enviados.";
        }

        return array("success"=>$hasError == false, "msg_obj"=>$errs, "msg"=>$msg);
    }
    function getbases4purchasedistinct($id_session) {
        $query = "EXEC pr_purchase_get_all_bases_distinct ?";
        $params = array($id_session);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array("id_base"=>$row["id_base"]);
        }
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