<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

//    stopIfApiNotExist();


function getCardUP($city = null, $state = null, $api = null, $date = null, $filter = null) {
        $query = "EXEC pr_geteventsforcards ?, ?, ?, ?, ?";
        $params = array(db_param($city), db_param($state), $api, $date, $filter);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "isdiscovery"=>0
                ,"id_evento" => $row["id_evento"],
                "ds_evento" => $row["ds_evento"],
                "valores" => $row["valores"],
                "mmAmountIsPer" => $row["mmAmountIsPer"],
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

        echo json_encode($json);
        logme();
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

getCardUP($_POST["city"],$_POST["state"], $_REQUEST["apikey"], $_POST["date"], $_POST["filter"]);

?>