<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;

    function purchase_refund($id_client, $id_session, $obj, $id_payment, $qpcode, $seats, $id_base) {  
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

        $retofservice = makethesell($id_session, $seats, $id_payment, $id_client, $qpcode, $id_base);        

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

    function validate_data($id_event, $id_presentation, $id_seat, $codVenda, $key) {
        $query = "EXEC pr_api_validate_data_refund ?,?,?,?,?";
        $params = array($id_event, $id_presentation, $id_seat, $codVenda, $key);
        $result = db_exec($query, $params);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "seatOK"=>$row["seatOK"]
                ,"eventOK"=>$row["eventOK"]
                ,"presentationOK"=>$row["presentationOK"]
                ,"codVendaOK"=>$row["codVendaOK"]
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

    $validate = validate_data($json->id_event, $json->id_presentation, $json->id_seat, $key);

    if ($validate["eventOK"] == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_event not valid.", "result"=>"")));
    }
    if ($validate["presentationOK"] == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_presentation not valid.", "result"=>"")));
    }
    if ($validate["codVendaOK"] == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "purchase code not valid.", "result"=>"")));
    }
    if ($validate["seatOK"] == false) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_seat not valid.", "result"=>"")));
    }
// die("ddd");
    // die(json_encode());

    $id_base = get_id_base_from_id_evento($json->id_event);


    // echo json_encode($ret);
    // logme();
    die();
?>