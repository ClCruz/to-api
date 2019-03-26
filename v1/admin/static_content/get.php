<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_static_page) {
        //sleep(5);
        $query = "EXEC pr_partner_static_page_get ?,?";
        $params = array($apikey,$id_static_page);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"isvisible" => $row["isvisible"]
                ,"created"=> $row["created"]
                ,"changed"=> $row["changed"]
                ,"title"=> $row["title"]
                ,"content"=> $row["content"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["loggedId"],$_POST["api"],$_POST["id_static_page"]);
?>