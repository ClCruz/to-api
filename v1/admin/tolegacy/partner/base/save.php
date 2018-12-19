<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_partner, $id_base) {
        $query = "EXEC pr_partner_base_add ?, ?";
        $params = array($id_partner, $id_base);

        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_partner"], $_POST["id_base"]);
?>