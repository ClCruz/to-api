<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;

    function getticket($id_base, $codVenda) {
        
        if ($id_base == null || $id_base == '') {
            $id_base = get_id_base_by_codvenda($codVenda);
        }
        
        $query = "EXEC pr_print_ticket_info ?, NULL,?,?";
        $params = array($codVenda, gethost(),getwhitelabelobj()["apikey"]);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $code = $row['barcode'];

            $code = Encoder::encode($code);
            $renderer = new PngRenderer();
            $render = $renderer->render($code);

            $png = imagecreatefromstring($renderer->render($code));
            ob_start();
            imagepng($png);
            $imagedata = ob_get_contents();
            ob_end_clean();
            imagedestroy($png);
        

            $json[] = array(
                "qrcode"=>base64_encode($imagedata)
                ,"roomName"=>$row["roomName"]
                ,"seatNameFull"=>$row["seatNameFull"]
                ,"seatIndice"=>$row["seatIndice"]
                ,"ticket"=>$row["ticket"]
                ,"insurance_policy"=>$row["insurance_policy"]
                ,"opening_time"=>$row["opening_time"]
                ,"countTicket"=>$row["countTicket"]
                ,"price"=>$row["price"]
                ,"countTotal"=>$row["count_total"]
            );
        }

        return $json;

    }

    function paymenttype_string_to_code($text) {
        $ret = "";
        switch ($text) {
            case "credit":
                $ret = "601";
            break;
            case "debit":
                $ret = "602";
            break;
            case "money":
                $ret = "604";
            break;
            case "boleto":
                $ret = "603";
            break;
        }
        return $ret;
    }

    function purchase_save($id_client, $id_session, $obj, $id_payment, $qpcode, $seats, $id_base, $bin, $date) {  
        // die(json_encode($id_client));      
        $start = $_SERVER["REQUEST_TIME"];
        $ip = "";
        if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        
        $ip = ($ip == '' ? '' : '|').$_SERVER['REMOTE_ADDR'];
        
        $id_purchase = get_id_purchase($id_session, $id_client)."-API";
        
        traceme($id_purchase, "Initiating purchase - API", json_encode(array("id_client"=>$id_client, "obj"=>json_encode($obj))),0);
        
        $shopping = getcurrentpurchase($id_session);
        traceme($id_purchase, "shopping cart", json_encode($shopping),0);

        if (count($shopping) == 0) {
            echo json_encode(array("success"=>false, "msgtobuyer"=>"Não há nenhum item selecionado."));
            logme();
            traceme($id_purchase, "Finished purchase", '',0);
            die();
        }

        $values = getvaluesofmyshoppig(NULL, $id_session);

        traceme($id_purchase, "values", json_encode($values),0);

        $retofservice = makethesell($id_session, $seats, $id_payment, $id_client, $qpcode, $id_base, $bin, $date);        

        $end = time();
        $duration = $end-$start;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;

        $retofservice["seconds"] = $seconds;

        traceme($id_purchase, "do purchase final", json_encode($retofservice),0);

        logme();
        return $retofservice;
    }

    function seats_object_to_string($seats) {
        $ret = "";

        foreach ($seats as &$seat) {
            if ($ret != "") {
                $ret.="|";
            }
            $ret.=$seat->id_seat."#".$seat->id_ticket;
        }

        return $ret;
    }

    function validate_date($date) {
        if ($date == "")
            return false;

        $format = "Y-m-d H:i:s";

        $d = DateTime::createFromFormat($format, $date);
        $valid = $d && $d->format($format) === $date;
        if (!$valid)
            return false;

        $dayinit = new DateTime('now');
        $dayinit->setTime(0,0,0);

        $dayend = new DateTime('now');
        $dayend->setTime(23,59,59);

        $now = new DateTime('now');

        $valid = $d >= $dayinit && $d <= $dayend;

        if (!$valid)
            return false;

        $valid = $d<$now;

        if (!$valid)
            return false;

        
        return true;
    }

    function makethesell($id_session, $seats, $id_payment, $id_client, $qpcode, $id_base, $bin, $date) {
        $query = "EXEC pr_api_sell ?,?,?,?,?,?,?";
        $params = array($id_session, $seats, $id_payment, $id_client, $qpcode, $bin,$date);
        $result = db_exec($query, $params, $id_base);
        $json = array();
    
        foreach ($result as &$row) {
            if ($row["hasError"] == 0) {
                $json = array(
                    "hasError"=>$row["hasError"]
                    ,"codVenda"=>$row["codVenda"]
                    ,"id_pedido_venda"=>$row["id_pedido_venda"]
                );                    
            }
            else {
                $json = array(
                    "hasError"=>$row["hasError"]
                    ,"ErrorNumber"=>$row["ErrorNumber"]
                    ,"ErrorSeverity"=>$row["ErrorSeverity"]
                    ,"ErrorState"=>$row["ErrorState"]
                    ,"ErrorProcedure"=>$row["ErrorProcedure"]
                    ,"ErrorLine"=>$row["ErrorLine"]
                    ,"ErrorMessage"=>$row["ErrorMessage"]
                );
            }
        }

        return $json;
    }

    function seat_save($id_event, $id_presentation, $seats,$key, $code) {
        $query = "EXEC pr_api_seats_save ?,?,?,?,?";
        $params = array($id_event, $id_presentation, $seats,$key, $code);
        $result = db_exec($query, $params);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "success"=>$row["success"]
                ,"msg"=>$row["msg"]
            );
        }

        return $json;
    }
    

    function saveclient($document, $name, $email, $id_base) {
        $query = "EXEC pr_api_client_save ?,?,?";
        $params = array($document, $name, $email);
        $result = db_exec($query, $params, $id_base);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "id"=>$row["id"]
            );
        }
// die(json_encode($json));
        return $json;
    }

    function validate_data($id_event, $id_presentation, $seats, $key) {
        $query = "EXEC pr_api_validate_data ?,?,?,?";
        $params = array($id_event, $id_presentation, $seats, $key);
        $result = db_exec($query, $params);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "tickettypeOK"=>$row["tickettypeOK"]
                ,"seatOK"=>$row["seatOK"]
                ,"eventOK"=>$row["eventOK"]
                ,"presentationOK"=>$row["presentationOK"]
            );
        }

        return $json;
    }

    function validatekey($key) {
        $query = "EXEC pr_api_keyvalidate ?";
        $params = array($key);
        $result = db_exec($query, $params);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "id_partner"=>$row["id_partner"]
                ,"active"=>$row["active"]
                ,"has"=>$row["has"]
            );
        }

        return $json;
    }

    function checkpayment($id_payment, $id_base) {
        $query = "EXEC pr_api_check_payment ?,?";
        $params = array($id_payment, $id_base);
        $result = db_exec($query, $params);
        $has = false;

    
        foreach ($result as &$row) {
            $has = $row["has"] == 1;
        }

        return $has;
    }

    function code_generate($key) {
        $query = "EXEC pr_api_code_generate ?";
        $params = array($key);
        $result = db_exec($query, $params);
        $code = "";
    
        foreach ($result as &$row) {
            $code = $row["code"];
        }

        return $code;
    }

    $data = file_get_contents('php://input');

    if ($data == "") {
        die(json_encode(array("success"=>false, "msg"=> "Check body content or HTTPS.", "result"=>"")));
    }

    if (array_key_exists("key", getallheaders()) == false) {
        die(json_encode(array("success"=>false, "msg"=> "Key not found. ERR.1.", "result"=>"")));
    }

    $key = getallheaders()["key"];
    if ($key == '') {
        die(json_encode(array("success"=>false, "msg"=> "Key not found. ERR.2.", "result"=>"")));
    }

    if ($data == "") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Body not found. ERR.1.", "result"=>"")));
    }

    $json = json_decode($data);

    if (json_last_error() != JSON_ERROR_NONE) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Body not found. ERR.2.", "result"=>"")));
    }

    $validatekey = validatekey($key);

    if ($validatekey["has"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Key not found. ERR.3.", "result"=>"")));
    }

    if ($validatekey["active"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Key not found. ERR.4.", "result"=>"")));
    }

    if (paymenttype_string_to_code($json->payment_method->type)=="") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Payment method not valid.", "result"=>"")));
    }

    if (trim($json->buyer->document) == "") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Document CPF not valid.", "result"=>"")));
    }

    if (documentValidateBR($json->buyer->document) == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Document CPF not valid.", "result"=>"")));
    }

    if (trim($json->buyer->name) == "") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Buyer name is empty.", "result"=>"")));
    }

    if (strlen((trim($json->buyer->name))) <= 3) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Buyer name not valid.", "result"=>"")));
    }
    
    $seats = seats_object_to_string($json->seat);
    $validate = validate_data($json->id_event, $json->id_presentation, $seats, $key);

    if (!validate_date($json->date)) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "date not valid.", "result"=>"")));
    }

    if ($validate["eventOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_event not valid.", "result"=>"")));
    }
    if ($validate["presentationOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_presentation not valid.", "result"=>"")));
    }
    if ($validate["tickettypeOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "ticket type not valid.", "result"=>"")));
    }
    if ($validate["seatOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_seat not valid.", "result"=>"")));
    }

    $id_base = get_id_base_from_id_evento($json->id_event);
    $id_payment = paymenttype_string_to_code($json->payment_method->type);

    if (checkpayment($id_payment, $id_base) == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Payment method no configured.", "result"=>"")));
    }

    if ($json->payment_method->type != "boleto") {
        if ($json->payment_method->bin == "") {
            logme();
            die(json_encode(array("success"=>false, "msg"=> "bin is not valid.", "result"=>"")));    
        }
    }

    $code = code_generate($key);
    // $code = "J8B9K533OOG8KY8";

    $id_client = saveclient($json->buyer->document,$json->buyer->name,$json->buyer->email,$id_base)["id"];
    
    $seat_response = seat_save($json->id_event, $json->id_presentation, $seats, $key, $code);

    $ret = array();

    if ($seat_response["success"] == 1) {
        $purchase_response = purchase_save($id_client, $code, $json, $id_payment, $key, $seats, $id_base, $json->payment_method->bin,$json->date);
        if ($purchase_response["hasError"] == 0) {
            $print = generate_email_print_code(0, $purchase_response["codVenda"], $id_base);
            // die(json_encode($print));
            // die(getwhitelabelobj()["api"]."/v1/print/web/ticket?code=".$print["code"]);
            // $print = generate_email_print_code(0, $purchase_response["codVenda"], $id_base);
            // $link = getwhitelabelobj()["api"]."/v1/print/web/ticket?code=".$print["code"];

            $tickets = getticket($id_base, $purchase_response["codVenda"]);

            $ret = array("success"=>true, "msg"=> "Venda efetivada.", "purchase"=>array("code"=>$purchase_response["codVenda"], "tickets"=>$tickets), "transaction"=>array("code"=>$code, "seconds"=>$purchase_response["seconds"]));
        }
        else {
            // , "error"=>$purchase_response
            $ret = array("success"=>false, "msg"=>"Falha na venda.");
        }
    }

    echo json_encode($ret);
    logme();
    die();
?>