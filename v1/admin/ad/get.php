<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_ad_get ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $imageURI = "";
            $imageURIOriginal = "";
            if ($row["type"]=="banner")
            {
                $imageURI = getDefaultMediaHost()."/discovery/".$row["id"]."/".getDefaultBannerAdImageName();
                $imageURIOriginal = getDefaultMediaHost()."/ori_discovery/".$row["id"]."/".getDefaultBannerAdImageName();
            }

            if ($row["type"]=="card")
            {
                $imageURI = getDefaultMediaHost()."/discovery/".$row["id"]."/".getDefaultCardAdImageName();
                $imageURIOriginal = getDefaultMediaHost()."/ori_discovery/".$row["id"]."/".getDefaultCardAdImageName();
            }

            $json = array(
                "id" => $row["id"]
                ,"id_partner" => $row["id_partner"]
                ,"isactive" => $row["isactive"]
                ,"startdate" => $row["startdate"]
                ,"enddate" => $row["enddate"]
                ,"title" => $row["title"]
                ,"content" => $row["content"]
                ,"link" => $row["link"]
                ,"type" => $row["type"]
                ,"imageURI" => $imageURI
                ,"imageURIOriginal" => $imageURIOriginal
                ,"campaign" => $row["campaign"]
                ,"created" => $row["created"]
                ,"name" => $row["name"]
                ,"priority" => $row["priority"]
                ,"index" => $row["index"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"]);
?>