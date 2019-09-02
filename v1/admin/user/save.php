<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function doit($loggedId, $api, $id, $name, $login, $email, $document, $active, $pass, $changedpass) {
       isuservalidordie($loggedId);

        if ($changedpass != 1)
        {
            $pass = "";
        }

        $query = "EXEC pr_to_admin_user_save ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($api, db_param2($id), $name, $login, $email, $document, $active, hash('ripemd160', $pass), $changedpass);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

doit($_POST["loggedId"], $_REQUEST["apikey"], $_POST["id"], $_POST["name"], $_POST["login"], $_POST["email"], $_POST["document"], $_POST["active"], $_POST["pass"], $_POST["changedpass"]);
?>
