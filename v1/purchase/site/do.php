<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/cardbin.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");

    function check_halfpricestudents_lot_validate($id_purchase, $id_client, $id_session) {

        $basesandreserva = getbases4purchasedistinct($id_session);

        $validated = array();
        $hasError = false;
        $errorMsg = "";

        traceme($id_purchase, "Validate Half Price Students/Lot - looping in bases", json_encode(array("id_client"=>$id_client, "id_session"=> $id_session)),0);
        foreach ($basesandreserva as &$row) {
            $query = "EXEC pr_purchase_halfpricestudents_lot_validate ?";
            $params = array($bin, $id_session);
            $result = db_exec($query, $params, $row["id_base"]);
            foreach ($result as &$row2) {
                if ($row2["success"] == 0 && $hasError == false) {
                    $hasError = true;
                    $errorMsg = $row2["msg"];
                }

                $validated[] = array("id_base"=>$row2["id_base"]
                                    ,"success"=>$row2["success"]
                                    ,"msg"=>$row2["msg"]);
            }
        }
        traceme($id_purchase, "Validate Half Price Students/Lot - looping result", json_encode($validated),0);

        return array("success"=>$hasError == false, "msg"=>$errorMsg);
    }

    function dopurchase($id_client, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments) {
        
        traceme($id_purchase, "Initiating purchase", json_encode(array("id_client"=>$id_client, "card_number"=>$card_number, "card_holdername"=>$card_holdername, "card_expirationdate"=>$card_expirationdate, "card_cvv"=>$card_cvv, "payment_method"=>$payment_method, "installments"=>$installments)),0);
        $id_purchase = get_id_purchase();

        $postValidate = validate_post($id_client, $id_payment_method, $card_number, $card_holdername, $card_expirationdate, $card_cvv, $payment_method, $installments);
        if ($postValidate["success"] == false) {
            echo json_encode($postValidate);
            logme();
            traceme($id_purchase, "Finished purchase", json_encode($postValidate),0);
            die();
        }

        $shopping = getcurrentpurchase($id_client);
        $mysession = getmysession($id_purchase, $id_client);
        $client = getclient($id_purchase, $id_client);
        $id_session = $mysession["id_session"];
        $paymentmethod = getpaymentmethod($id_purchase, $id_payment_method);
        
        $isCreditCard = $payment_method["in_tipo_meio_pagamento"] == 'CC';
        
        $bin = '';
        $card_number_original = $card_number;
        
        if ($isCreditCard) {
            $card_number = preg_replace("/[^0-9]/", "", $card_number);
            $bin = substr($card_number, 0, 5);

            $binValidate = validateBIN($id_purchase, $id_client, "itau", $id_session, $bin);
            if ($binValidate["success"] == false) {
                echo json_encode($binValidate);
                logme();
                traceme($id_purchase, "Finished purchase", '',0);
                die();
            }
        }

        $halfValidate = check_halfpricestudents_lot_validate($id_purchase, $id_client, $id_session);
        if ($halfValidate["success"] == false) {
            echo json_encode($halfValidate);
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }

        $ispaymentmethodok = ispaymentmethodok($id_purchase, $id_client, $id_session, $id_payment_method, $shopping);
        if ($ispaymentmethodok["success"] == false) {
            echo json_encode($ispaymentmethodok);
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }

        renew($id_client);




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

    dopurchase($_POST["id_client"], $_POST["id_payment_method"], $_POST["card_number"], $_POST["card_holdername"], $_POST["card_expirationdate"], $_POST["card_cvv"], $_POST["payment_method"], $_POST["installments"]);

    

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