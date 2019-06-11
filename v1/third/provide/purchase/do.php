<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    $data = file_get_contents('php://input');

    if (array_key_exists("key", getallheaders()) == false) {
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada.", "result"=>"")));
    }

    $key = getallheaders()["key"];
    if ($key == '') {
        die(json_encode(array("success"=>false, "msg"=> "Chave não encontrada.", "result"=>"")));
    }

    die(json_encode($key));

    if ($data == "") {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Nenhum dado encontrado. ERR.1.", "result"=>"")));
    }
    // die($data);
    $json = json_decode($data);
    // die(json_encode(json_last_error_msg()));

    if (json_last_error() != JSON_ERROR_NONE) {
        logme();
        die(json_encode(array("success"=>false, "msg"=> "Nenhum dado encontrado. ERR.2.", "result"=>"")));
    }

    die(json_encode($json));


//    stopIfApiNotExist();

?>