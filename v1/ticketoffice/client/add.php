<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $nin, $rg,$name,$email,$cardBin,$phone,$makeCode,$partner) {
        $hasError = false;
        $jsonError = array();

        if (!isset($name))
        {
            array_push($jsonError,array("field"=>"name", "msg"=> "Preencha o Nome")); 
            $hasError = true;
        }

        if ($partner == 0) {
            if (!isset($nin) || $nin == "")
            {
                array_push($jsonError,array("field"=>"cpf", "msg"=> "Preencha o CPF")); 
                $hasError = true;
            }
        }

        if ($hasError)
        {
            $help = array("error"=>true, "message"=>"Campos obrigatórios não preenchidos.");
            echo json_encode($help);
            die();    
        }

        $phoneddd = "";
        $phonenumber = "";
        $phoneramal = "";

        if (isset($phone)) {
            $phoneddd = $phone["ddd"];
            $phonenumber = $phone["number"];
            $phoneramal = $phone["ramal"];
        }

        $query = "EXEC pr_client ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($nin, $rg,$name,$email,$cardBin,$phoneddd,$phonenumber,$phoneramal, $makeCode, $partner);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $json = array("codigo"=>$row["codigo"]
                            ,"codReserva"=>$row["codReserva"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    set($_REQUEST["id_base"], $_POST["nin"], $_POST["rg"], $_POST["name"],$_POST["email"],$_POST["cardBin"],$_POST["phone"],$_POST["makeCode"],$_POST["partner"]);
?>