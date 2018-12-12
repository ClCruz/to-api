<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($api, $id, $name, $login, $email, $active) {
        //sleep(5);
        $query = "EXEC pr_adm_ticketoffice_users_save ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api, db_param2($id), $name, $login, $email, $active, hash('ripemd160', "@2018."));
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["apikey"], $_POST["id"], $_POST["name"], $_POST["login"], $_POST["email"], $_POST["active"]);
?>