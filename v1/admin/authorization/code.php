<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_admin_authorization ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "code" => $row["code"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"]);
?>