<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        $query = "EXEC pr_admin_user_revalid ?";
        $params = array($id);
        $result = db_exec($query, $params);
        
        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "valid" => $row["valid"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"]);
?>