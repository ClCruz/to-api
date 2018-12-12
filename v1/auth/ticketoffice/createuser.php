<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function createuser($login, $password, $passwordConfirm, $name, $email) {

        $login = trim($login);
        //$password = trim($password);
        $name = trim($name);
        $email = trim($email);

        if (trim($password)=='') {
            $json = array("added"=>false
            ,"msg"=>"Senha não pode ser em branco.");
            echo json_encode($json);
            die();
        }
        if (trim($passwordConfirm)=='') {
            $json = array("added"=>false
            ,"msg"=>"Digite a senha de confirmação.");
            echo json_encode($json);
            die();
        }
        if ($passwordConfirm!=$password) {
            $json = array("added"=>false
            ,"msg"=>"Senhas não conferem.");
            echo json_encode($json);
            die();
        }
        if ($login=='') {
            $json = array("added"=>false
            ,"msg"=>"Login não pode ser em branco.");
            echo json_encode($json);
            die();
        }
        if ($name=='') {
            $json = array("added"=>false
            ,"msg"=>"Nome não pode ser em branco.");
            echo json_encode($json);
            die();
        }
        if ($email=='') {
            $json = array("added"=>false
            ,"msg"=>"E-mail não pode ser em branco.");
            echo json_encode($json);
            die();
        }

        $passwordHash = hash('ripemd160', $password);

        $query = "EXEC pr_ticketoffice_user_add ?, ?, ?, ?";
        $params = array($login, $name, $passwordHash, $email);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            if ($row["added"] == false) {
                $json = array("added"=>false
                            ,"msg"=>"Login já existente.");
                echo json_encode($json);
                die();
            }
            else {
                $json = array("added"=>true
                            ,"msg"=>"Usuário criado. (".$row["id"].")");
                echo json_encode($json);
                die();    
            }
        }

        $json = array("added"=>false
                    ,"msg"=>"Something went wrong.");
        echo json_encode($json);
        logme();
        die();    
    }

    createuser($_REQUEST["login"], $_REQUEST["password"], $_REQUEST["passwordconfirm"], $_REQUEST["name"], $_REQUEST["email"]);
?>