<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get() {
        $query = "EXEC partner_sitemap_list";
        $params = array();
        $result = db_exec($query, $params);
        
        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "uniquename" => $row["uniquename"]
                ,"id" => $row["id"]
                ,"hourafterlastgenerated" => $row["hourafterlastgenerated"]
            );
        }

        echo json_encode($json);
        // logme();
        die();    
    }

    get();
?>