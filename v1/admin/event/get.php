<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_evento, $api) {
        //sleep(5);
        $query = "EXEC pr_adm_event ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_evento, $api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $imageOriginalURI = getDefaultMediaHost().str_replace("{default_ori}",getOriginalCardImageName(),str_replace("{id}",$row["id_evento"],$row["imageoriginal"]));
            $imageBigURI = getDefaultMediaHost().str_replace("{default_big}",getBigCardImageName(),str_replace("{id}",$row["id_evento"],$row["cardbigimage"]));
            $imageURI = getDefaultMediaHost().str_replace("{default_card}",getDefaultCardImageName(),str_replace("{id}",$row["id_evento"],$row["cardimage"]));
            //$imageBase64 = base64_encode(file_get_contents($imageURI));

            $json = array(
                "id_evento" => $row["id_evento"]
                ,"ds_evento" => $row["ds_evento"]
                ,"CodPeca" => $row["CodPeca"]
                ,"needed" => $row["needed"]
                ,"address" => $row["address"]
                ,"description" => $row["description"]
                ,"uri" => $row["uri"]
                ,"imageOriginalURI" => $imageOriginalURI
                ,"imageBigURI" => $imageBigURI
                ,"imageURI" => $imageURI
                ,"imageBase64" => ""//$imageBase64
                ,"ticketsPerPurchase" => $row["ticketsPerPurchase"]
                ,"minuteBefore" => $row["minuteBefore"]
                ,"meta_description" => $row["meta_description"]
                ,"meta_keyword" => $row["meta_keyword"]
                ,"id_genre" => $row["id_genre"]
                ,"showInBanner" => $row["showInBanner"]
                ,"bannerDescription" => $row["bannerDescription"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_evento"], $_REQUEST["apikey"]);
?>