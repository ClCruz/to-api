<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function login($login, $password) {
        //sleep(5);
        //die("ddd: ".md5($password));
        $query = "EXEC pr_login_client ?, ?";
        $params = array(db_param($login), gethost());
        $result = db_exec($query, $params);

        $json = array();
        $hasLogin = false;
        $hasActive = true;
        $passwordOk = false;
        $tokenValidUntil = "";

        $passwordHash = md5($password);

        $name = "";
        $email = "";
        $lastLogin = "";
        $id = "";
        $cd_cpf = "";

        foreach ($result as &$row) {
            $hasLogin = true;
            if ($passwordHash == $row["cd_password"]) {
                $passwordOk = true;
                if ($hasActive) {
                    $cd_cpf = $row["cd_cpf"];
                    $name = $row["name"];
                    $email = $row["email"];
                    $id = $row["id"];
                    $operator = $row["operator"];
                }
            }
        }

        if ($hasLogin && $hasActive && $passwordOk) {
            
            $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));

            $json = array("logged"=>true
                        ,"name"=>$name
                        ,"email"=>$email
                        ,"token"=>$token
                        ,"login"=>$login
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
            ,"login"=>$login
            ,"msg"=>"e-mail ou senha não conferem.");
        }

        echo json_encode($json);
        logme();
        die();    
    }

$testlogin = $_REQUEST["login"];
if ($testlogin == '') {
    $testlogin = $_POST["login"]
}

login($testlogin, $_POST["pass"]);

?>