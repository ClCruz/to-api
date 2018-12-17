<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function login($login, $password) {
        //sleep(5);
        $query = "EXEC pr_login_admin ?";
        $params = array(db_param($login));
        $result = db_exec($query, $params);

        $json = array();
        $hasLogin = false;
        $hasActive = false;
        $passwordOk = false;

        $passwordHash = hash('ripemd160', $password);

        $name = "";
        $email = "";
        $lastLogin = "";
        $id = "";
        $document = "";

        foreach ($result as &$row) {
            $hasLogin = true;

            if ($row["active"] == 1)
                $hasActive = true;


            //die("aqui... ".$passwordHash . " .... " . $row["password"]);
            if ($passwordHash == $row["password"]) {
                $passwordOk = true;
                if ($hasActive) {
                    $name = $row["name"];
                    $email = $row["email"];
                    $document = $row["document"];
                    $lastLogin = $row["lastLogin"];
                    $id = $row["id"];
                    $tokenValidUntil = $row["tokenValidUntil"];
                    $operator = $row["operator"];
                }
            }
        }

        if ($hasLogin && $hasActive && $passwordOk) {
            
            $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));

            $json = array("logged"=>true
                        ,"name"=>$name
                        ,"email"=>$email
                        ,"document"=>$document
                        ,"token"=>$token
                        ,"login"=>$login
                        ,"tokenValidUntil"=>$tokenValidUntil
                        ,"id"=>$id
                        ,"operator"=>$operator
                        ,"lastLogin"=>$lastLogin);

            $query = "EXEC pr_login_admin_successfully ?, ?";
            $params = array(db_param($login),$token);
            db_exec($query, $params);
        }
        else {
            $json = array("logged"=>false
            ,"login"=>$login
            ,"msg"=>"Senha ou Usuário não conferem.");
        }

        echo json_encode($json);
        logme();
        die();    
    }

login($_POST["login"], $_POST["password"]);

?>