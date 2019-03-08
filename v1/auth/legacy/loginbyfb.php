<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function loginbyfb($fb) {
        //sleep(5);
        //die("ddd: ".md5($password));
        $query = "EXEC pr_login_client_fb ?";
        $params = array($fb);
        $result = db_exec($query, $params);

        $json = array();
        $hasLogin = false;
        $hasActive = true;
        $passwordOk = false;

        $name = "";
        $email = "";
        $lastLogin = "";
        $id = "";
        $cd_cpf = "";

        foreach ($result as &$row) {
            $hasLogin = true;
            $cd_cpf = $row["cd_cpf"];
            $name = $row["name"];
            $email = $row["email"];
            $id = $row["id"];
            $operator = $row["operator"];
        }

        if ($hasLogin) {
            
            $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));

            $json = array("logged"=>true
                        ,"name"=>$name
                        ,"email"=>$email
                        ,"token"=>$token
                        ,"login"=>$email
                        ,"tokenValidUntil"=>$tokenValidUntil
                        ,"id"=>$id
                        ,"operator"=>$operator
                        ,"lastLogin"=>$lastLogin);

            $query = "EXEC pr_login_client_successfully ?, ?";
            $params = array($id,$token);
            db_exec($query, $params);
        }
        else {
            $json = array("logged"=>false
                ,"msg"=>"");
        }

        echo json_encode($json);
        logme();
        die();    
    }

loginbyfb($_POST["fb"]);

?>