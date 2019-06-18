<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function events($name, $api) {
        $query = "EXEC pr_site_place_events_get ?,?";
        $params = array($name, $api);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "isdiscovery"=>0,
                "id_evento" => $row["id_evento"],
                "ds_evento" => $row["ds_evento"],
                "valores" => $row["valores"],
                "minAmount" => $row["minAmount"],
                "maxAmount" => $row["maxAmount"],
                "ds_nome_teatro" => $row["ds_nome_teatro"],
                "ds_municipio" => $row["ds_municipio"],
                "sg_estado" => $row["sg_estado"],
                "datas" => $row["dates"],
                "orderhelper" => $row["orderhelper"],
                "showPartnerInfo" => $row["show_partner_info"],
                "partner" => $row["partner"],
                "created" => $row["created"],
                "img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "uri" => $row["uri"],
                "badge"=> splitBadge($row["badges"]),
                "id_genre"=> $row["id_genre"],
                "genreName"=> $row["genreName"],
                "promo"=> splitPromotion($row["promotion"])
            );
        }
        return $json;
    }
    function splitBadge($value) {
        $ret = array();

        if ($value === null)
            return $ret;

        $aux1 = explode(",", $value);
        foreach ($aux1 as &$row) {
            $aux2 = explode("|", $row);
            $aux = array("tag"=>$aux2[0]
                        ,"img"=>getDefaultMediaHost().$aux2[1]);
            array_push($ret,$aux);
        }

        return $ret;
    }
    function splitPromotion($value) {
        $ret = array();

        if ($value === null)
            return $ret;

        $aux1 = explode(",", $value);
        foreach ($aux1 as &$row) {
            $aux2 = explode("|", $row);
            $aux = array("tag"=>$aux2[1]
                        ,"img"=>getDefaultMediaHost()."/promo/".$aux2[2]);
            array_push($ret,$aux);
        }

        return $ret;
    }


    function search($api, $name) {
        //sleep(5);
        $query = "EXEC pr_site_place_get ?";

        $params = array($name);
        $result = db_exec($query, $params);
        $uri = '';

        $json = array();
        foreach ($result as &$row) {
            $uri = $row['url'];
            
            if (startsWith($row['url'], "http") == false) {
                $uri = "http://".$row['url'];
            }

            $json = array(
                "id_local_evento" => $row["id_local_evento"],
                "ds_local_evento" => $row["ds_local_evento"],
                "id_tipo_local" => $row["id_tipo_local"],
                "id_municipio" => $row["id_municipio"],
                "ds_googlemaps" => $row["ds_googlemaps"],
                "url" => $uri,
                "ticketbox_info" => $row["ticketbox_info"],
                "occupation_info" => $row["occupation_info"],
                "meta_description" => $row["meta_description"],
                "meta_keywords" => $row["meta_keywords"],
                "id_estado" => $row["id_estado"],
                "ds_municipio" => $row["ds_municipio"],
                "sg_estado" => $row["sg_estado"],
                "ds_estado" => $row["ds_estado"],
                "ds_tipo_local" => $row["ds_tipo_local"],
            );
        }



        $json["events"] = events($name, $api);

        echo json_encode($json);
        logme();
        die();    
    }

search($_REQUEST["apikey"], $_REQUEST["name"]);
?>