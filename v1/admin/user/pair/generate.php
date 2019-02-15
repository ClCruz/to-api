<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($id) {
        //isuservalidordie($id);
        //sleep(5);
        $query = "EXEC pr_admin_user_mobile_pair_generate ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_POST["id"]);
?>