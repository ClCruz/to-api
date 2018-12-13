<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function getCardUP($api = null) {
        //die("teste: ".getwhitelabeldb()["host"]);
        $query = "EXEC pr_geteventsforbanner ?";
        $params = array($api);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id_evento" => $row["id_evento"],
                "ds_evento" => $row["ds_evento"],
                "ds_nome_teatro" => $row["ds_nome_teatro"],
                "ds_municipio" => $row["ds_municipio"],
                "sg_estado" => $row["sg_estado"],
                "datas" => $row["datas"],
                "bannerDescription" => $row["bannerDescription"],
                "imgBanner" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_big}", getBigCardImageName(),$row["cardbigimage"])),
                "uri" => $row["uri"],
                "badge"=> splitBadge($row["badges"]),
                "id_genre"=> $row["id_genre"],
                "genreName"=> $row["genreName"],
                "promo"=> splitPromotion($row["promotion"])
            );
        }
        //createTimer("getCardUP","Loop ended...");

        echo json_encode($json);
        logme();
        //performance();
        die();    
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
//splitBadge('CompreIngressos|/badge/ci.png,ItauCard|/badge/itaucard.png');
getCardUP($_REQUEST["apikey"]);

?>