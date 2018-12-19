<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($api, $id) {
        //sleep(5);
        $query = "EXEC pr_to_admin_user_resetpass ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api, $id, hash('ripemd160', "@2018."));
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["apikey"], $_POST["id"]);
?>