<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id, $oldpass, $newpass) {

        if ($oldpass=="" || $newpass == "" || $id == "") {
            echo json_encode(array("success"=>false, "msg"=> "Preencha os campos necessários."));
            logme();
            die();        
        }

        $query = "EXEC pr_to_admin_user_changepass ?,?,?";
        $params = array($id, hash('ripemd160', $oldpass), hash('ripemd160', $newpass));
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_POST["id"], $_POST["oldpass"], $_POST["newpass"]);
?>