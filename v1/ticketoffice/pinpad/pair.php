<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($key, $device) {
        $query = "EXEC pr_ticketoffice_mobile_pair_set ?, ?";
        $params = array($key, $device);
        $result = db_exec($query, $params);

        $json = array("success"=>false);

        foreach ($result as &$row) {
            $json = array(
                "success"=>$row["success"]
                ,"msg"=>$row["msg"]
                ,"id"=>$row["id"]
                ,"id_ticketoffice_user"=>$row["id_ticketoffice_user"]
                ,"deviceid"=>$row["deviceid"]
                ,"isPaired"=>$row["isPaired"]
                ,"login"=>$row["login"]
                ,"name"=>$row["name"]
                ,"email"=>$row["email"]
                ,"unpair"=>$row["unpair"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }    

    call($_POST["key"],$_POST["device"]);
?>