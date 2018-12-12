<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $name) {
        //sleep(5);
        $query = "EXEC pr_genre_save ?, ?";
        $params = array($id, $name);
        //die("aaa.".print_r(db_param($startAt),true));
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["name"]);
?>