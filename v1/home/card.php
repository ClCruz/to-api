<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

//    stopIfApiNotExist();


function getCardUP($city = null, $state = null, $api = null, $date = null, $filter = null) {
        //die("teste: ".getwhitelabeldb()["host"]);
        //createTimer("getCardUP","Creating query...");
        $query = "EXEC pr_geteventsforcards ?, ?, ?, ?, ?";
        $params = array(db_param($city), db_param($state), $api, $date, $filter);
        //createTimer("getCardUP","Calling database...");
        $result = db_exec($query, $params);
        //createTimer("getCardUP","Database executed...");
        $json = array();
        //createTimer("getCardUP","Starting Loop...");

        foreach ($result as &$row) {
            $json[] = array(
                "isdiscovery"=>0
                ,"id_evento" => $row["id_evento"],
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
getCardUP($_POST["city"],$_POST["state"], $_REQUEST["apikey"], $_POST["date"], $_POST["filter"]);

?>