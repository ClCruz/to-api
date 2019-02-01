<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function token($token) {
        $query = "EXEC pr_token ?";
        $params = array(db_param($token));
        $result = db_exec($query, $params);

        $json = array();
        $isValid = false;

        foreach ($result as &$row) {
            if ($row["isValid"] == 1) {
                $isValid = true;
                $name = $row["name"];
                $email = $row["email"];
                $lastLogin = $row["lastLogin"];
                $login = $row["login"];
                $id = $row["id"];
                $tokenValidUntil = $row["tokenValidUntil"];
                $operator = $row["operator"];
            }
        }

        if ($isValid) {
            $json = array("logged"=>true
                        ,"name"=>$name
                        ,"email"=>$email
                        ,"token"=>$token
                        ,"login"=>$login
                        ,"tokenValidUntil"=>$tokenValidUntil
                        ,"id"=>$id
                        ,"operator"=>$operator
                        ,"lastLogin"=>$lastLogin);

            $query = "EXEC pr_login_successfully ?, ?";
            $params = array(db_param($login),$token);
            db_exec($query, $params);
        }
        else {
            $json = array("logged"=>false
            ,"login"=>$login
            ,"msg"=>"Token invalido.");
        }

        echo json_encode($json);
        logme();
        die();    
    }

token($_REQUEST["id"]);

?>