<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($apikey, $id_static_page, $id_to_admin_user, $isvisible, $title, $content) {
        $query = "EXEC pr_partner_static_page_save ?, ?, ?, ?, ?, ?";
        $params = array($apikey, $id_static_page, $id_to_admin_user, $isvisible, $title, $content);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["api"], $_POST["id_static_page"], $_POST["id_to_admin_user"]
, $_POST["isvisible"], $_POST["title"], $_POST["content"]);
?>