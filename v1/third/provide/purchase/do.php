<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function seatsselect($id_event, $id_presentation, $seats) {
        $query = "EXEC pr_api_seatselect ?,?,?";
        $params = array($$id_event,$id_presentation, $seats);
        $result = db_exec($query, $params);
        $json = array();
    
        foreach ($result as &$row) {
            $json = array(
                "code"=>$row["code"]
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

    if (array_key_exists("key", getallheaders()) == false) {
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada. ERR.1.", "result"=>"")));
    }

    $key = getallheaders()["key"];
    if ($key == '') {
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada. ERR.2.", "result"=>"")));
    }

    if ($data == "") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Nenhum dado encontrado. ERR.1.", "result"=>"")));
    }

    $json = json_decode($data);

    if (json_last_error() != JSON_ERROR_NONE) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Nenhum dado encontrado. ERR.2.", "result"=>"")));
    }

    $validatekey = validatekey($key);

    if ($validatekey["has"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada. ERR.3.", "result"=>"")));
    }

    if ($validatekey["active"] == 0) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada. ERR.4.", "result"=>"")));
    }

    $code = code_generate($key);



    die(json_encode($code));


//    stopIfApiNotExist();

?>