<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $name, $api, $active) {
        //sleep(5);
        $id = uniqueidentifier_default($id);

        $query = "EXEC pr_quotapartner_save ?, ?, ?, ?";
        $params = array($id, $name, $api, $active);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["name"], $_REQUEST["apikey"], $_POST["active"]);
?>