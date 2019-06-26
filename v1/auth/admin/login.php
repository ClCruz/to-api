<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function checkPartner($id, $apikey) {
        $query = "EXEC pr_login_checkpartner ?,?";
        $params = array($id, $apikey);
        $result = db_exec($query, $params);
        $isok = false;

        foreach ($result as &$row) {
            $isok = $row["isok"] == 1;
        }        
        return $isok;
    }

    function codes($id) {
        $query = "EXEC pr_admin_authorization ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "code" => $row["code"]
            );
        }
        return $json;
    }

    function login($login, $password,$apikey) {
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
            if (checkPartner($id,$apikey)) {
                $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));


                $json = array("logged"=>true
                            ,"name"=>$name
                            ,"email"=>$email
                            ,"document"=>$document
                            ,"token"=>$token
                            ,"login"=>$login
                            ,"tokenValidUntil"=>$tokenValidUntil
                            ,"id"=>$id
                            ,"codes"=>codes($id)
                            ,"operator"=>$operator
                            ,"lastLogin"=>$lastLogin);
    
                $query = "EXEC pr_login_admin_successfully ?, ?";
                $params = array(db_param($login),$token);
                db_exec($query, $params);    
            } 
            else {
                $json = array("logged"=>false
                ,"login"=>$login
                ,"msg"=>"Sem permissão de dominío.");
            }            
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

login($_REQUEST["login"], $_POST["pass"],$_REQUEST["apikey"]);

?>