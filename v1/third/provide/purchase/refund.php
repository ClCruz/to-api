<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function purchase_refund($code, $id_seat, $id_base) {  
        $query = "EXEC pr_api_refund ?,?,?";
        $id_seat = $id_seat == null ? 0 : $id_seat;

        $all = $id_seat == 0 ? 1 : 0;

        $params = array($code, $all, $id_seat);
        $result = db_exec($query, $params, $id_base);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "success"=>$row["success"]
                ,"msg"=>$row["success"] == 1 ? "executed." : 'not executed.'
            );
        }

        return $json;
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
                ,"presentationPast"=>$row["presentationPast"]
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

    // die(json_encode($data));

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

    // die(json_last_error_msg());

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

    $validate = validate_data($json->id_event, $json->id_presentation, $json->id_seat, $json->purchase_code, $key);

    if ($validate["eventOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_event not valid.", "result"=>"")));
    }
    if ($validate["presentationOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_presentation not valid.", "result"=>"")));
    }
    if ($validate["presentationPast"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "presentation already occurred.", "result"=>"")));
    }

    if ($validate["codVendaOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "purchase code not valid.", "result"=>"")));
    }
    if ($validate["seatOK"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "id_seat not valid.", "result"=>"")));
    }

    $id_base = get_id_base_from_id_evento($json->id_event);

    $ret = purchase_refund($json->purchase_code, $json->id_seat, $id_base);

    echo json_encode($ret);
    logme();
    die();
?>