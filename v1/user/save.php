<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function validatefield($field) {
        return strlen(trim($field)) > 0;
    }

    function validateall($firstname, $lastname, $gender
    , $birthdate, $document, $documenttype
    , $brazilian_rg, $phone_ddd, $phone_number
    , $zipcode, $city_state, $city
    , $neighborhood, $address, $address_number
    , $address_more, $login, $pass
    , $newsletter, $agree, $fb
    , $isforeign) {
        $ret = array("success"=>1,"msg"=>"","id"=>0);;

        if ($agree!=1) {
            return array("success"=>0,"msg"=>"Selecione que você concorda com os termos do site.","id"=>0);
        }

        if (validatefield($firstname)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Nome é obrigatório.","id"=>0);
            }
        }
        if (validatefield($lastname)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Nome é obrigatório.","id"=>0);
            }
        }
        if (validatefield($gender)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Sexo é obrigatório.","id"=>0);
            }
        }
        if (validatefield($birthdate)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Data de nascimento é obrigatória.","id"=>0);
            }
        }
        if (validatefield($document)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Documento é obrigatório.","id"=>0);
            }
        }
        // if (validatefield($documenttype)==false) {
        //     $continueerror = true;

        //     if ($continueerror) {
        //         return false;
        //     }
        // }
        if (validatefield($brazilian_rg)==false) {
            $continueerror = true;

            if ($isforeign == 1) {
                $continueerror = false;
            }

            if ($continueerror) {
                return array("success"=>0,"msg"=>"RG é obrigatório.","id"=>0);
            }
        }
        if (validatefield($phone_ddd)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Telefone é obrigatório.","id"=>0);
            }
        }
        if (validatefield($phone_number)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Telefone é obrigatório.","id"=>0);
            }
        }
        if (validatefield($zipcode)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"CEP é obrigatório.","id"=>0);
            }
        }
        if (validatefield($city_state)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Estado é obrigatório.","id"=>0);
            }
        }
        if (validatefield($city)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Cidade é obrigatória.","id"=>0);
            }
        }
        if (validatefield($neighborhood)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Bairro é obrigatório.","id"=>0);
            }
        }
        if (validatefield($address)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Endereço é obrigatório.","id"=>0);
            }
        }
        if (validatefield($address_number)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Endereço é obrigatório.","id"=>0);
            }
        }
        // if (validatefield($address_more)==false) {
        //     $continueerror = true;

        //     if ($continueerror) {
        //         return false;
        //     }
        // }
        if (validatefield($login)==false) {
            $continueerror = true;

            if ($continueerror) {
                return array("success"=>0,"msg"=>"E-mail é obrigatório.","id"=>0);
            }
        }
        if (validatefield($pass)==false) {
            $continueerror = true;

            if ($fb != '') {
                $continueerror = false;
            }

            if ($continueerror) {
                return array("success"=>0,"msg"=>"Senha é obrigatória.","id"=>0);
            }
        }
        else {
            if ($pass == '' || strlen($pass)<6) {
                return array("success"=>0,"msg"=>"Verifique a senha.","id"=>0);
            }
        }
        return $ret;
    }

    function set(
        $firstname, $lastname, $gender
        , $birthdate, $document, $documenttype
        , $brazilian_rg, $phone_ddd, $phone_number
        , $zipcode, $city_state, $city
        , $neighborhood, $address, $address_number
        , $address_more, $login, $pass
        , $newsletter, $agree, $fb
        , $isforeign, $isadd
    ) {

        $isok = validateall($firstname, $lastname, $gender
        , $birthdate, $document, $documenttype
        , $brazilian_rg, $phone_ddd, $phone_number
        , $zipcode, $city_state, $city
        , $neighborhood, $address, $address_number
        , $address_more, $login, $pass
        , $newsletter, $agree, $fb
        , $isforeign, $isadd);

        if ($isok["success"] == 0) {
            echo json_encode($isok);
            logme();
            die();
        }

        $token = hash('ripemd160', $email.strtotime(date_default_timezone_get()));
        $passwordHash = md5($pass);

        $birthdateSplit = explode("/", $birthdate);

        $birthdate = $birthdateSplit[2]."-".$birthdateSplit[1]."-".$birthdateSplit[0];

        $query = "EXEC pr_user_save ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($firstname, $lastname, $gender
        , $birthdate, $document, $documenttype
        , $brazilian_rg, $phone_ddd, $phone_number
        , $zipcode, $city_state, $city
        , $neighborhood, $address, $address_number
        , $address_more, $login, $passwordHash
        , $newsletter, $agree, $fb
        , $isforeign, gethost(), $isadd, $token);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array(
            "success"=>$row["success"]
            ,"msg"=>$row["msg"]
            ,"id"=>$row["id"]
            ,"name"=>$row["name"]
            ,"login"=>$row["login"]
            ,"token"=>$row["token"]
            ,"dologin"=>$isadd==1 && $row["success"] == 1
            );
        }

        echo json_encode($json);
        logme();
        die();     
    }
    
set($_POST["firstname"], $_POST["lastname"], $_POST["gender"]
    , $_POST["birthdate"], $_POST["document"], $_POST["documenttype"]
    , $_POST["brazilian_rg"], $_POST["phone_ddd"], $_POST["phone_number"]
    , $_POST["zipcode"], $_POST["city_state"], $_POST["city"]
    , $_POST["neighborhood"], $_POST["address"], $_POST["address_number"]
    , $_POST["address_more"], $_POST["login"], $_POST["pass"]
    , $_POST["newsletter"], $_POST["agree"], $_POST["fb"]
    , $_POST["isforeign"], $_POST["type"]);
?>