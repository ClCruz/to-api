<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function search() {
        //sleep(5);
        $query = "EXEC pr_user_documenttype";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"mask" => $row["mask"]
                ,"value"=>$row["id"]
                ,"text"=>$row["name"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

search();
?>