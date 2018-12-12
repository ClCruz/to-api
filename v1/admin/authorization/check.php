<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $id_programa) {
        //sleep(5);
        $query = "EXEC pr_authorization_check ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_user, $id_programa);
        $result = db_exec($query, $params);

        $json = array(
            "allowed" => false
        );

        foreach ($result as &$row) {
            $json = array(
                "allowed" => $row["allowed"] == 1
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_user"], $_REQUEST["id_programa"]);
?>