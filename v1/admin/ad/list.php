<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_ad_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"id_partner" => $row["id_partner"]
                ,"isactive" => $row["isactive"]
                ,"startdate" => $row["startdate"]
                ,"enddate" => $row["enddate"]
                ,"title" => $row["title"]
                ,"content" => $row["content"]
                ,"link" => $row["link"]
                ,"type" => $row["type"]
                ,"image" => $row["image"]
                ,"campaign" => $row["campaign"]
                ,"name" => $row["name"]
                ,"priority" => $row["priority"]
                ,"index" => $row["index"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>