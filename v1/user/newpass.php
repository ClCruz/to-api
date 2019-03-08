<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($code, $pass) {

        if ($pass == '' || strlen($pass)<6) {
            return array("success"=>0,"msg"=>"Verifique a senha.", "name"=> "", "token"=>"", "login"=> "");
        }

        $passwordHash = md5($pass);

        $query = "EXEC pr_user_alterpass ?, ?";
        $params = array($code, $passwordHash);
        $result = db_exec($query, $params);

        $json = array();

        $name = "";
        $email = "";
        $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));
        $tokenValidUntil = "";
        $id = "";
        $success = false;


        foreach ($result as &$row) {
            $success = $row["success"];
            $name = $row["name"];
            $email = $row["email"];
            $id = $row["id"];
        }

        if ($success) {
            $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));
    
            $json = array("logged"=>true
                        ,"success"=>true
                        ,"name"=>$name
                        ,"email"=>$email
                        ,"token"=>$token
                        ,"login"=>$email
                        ,"tokenValidUntil"=>$tokenValidUntil
                        ,"id"=>$id);
    
            $query = "EXEC pr_login_client_successfully ?, ?";
            $params = array($id,$token);
            db_exec($query, $params);
        }
        else {
            $json = array("success"=>false, "msg"=>"Não foi possível realizar a mudança de senha.");
        }


        echo json_encode($json);
        logme();
        die();     
    }
    
set($_POST["code"], $_POST["pass"]);
?>