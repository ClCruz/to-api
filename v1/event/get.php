<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($key, $api) {
        $query = "EXEC pr_event ?, ?";
        $params = array($key, $api);
        $result = db_exec($query, $params);


        $codPeca = 0;
        $id_evento = 0;
        $codPeca = 0;

        foreach ($result as &$row) {
            $id_evento = $row["id_evento"];
            $id_base = $row["id_base"];
            $codPeca = $row["CodPeca"];
        }
        //die("ddd".print_r($result,true));
        if ($id_base == 0 && $codPeca == 0) {
            echo json_encode(array("error"=>true, "msg"=>"Não foi possível achar o evento."));    
            die();
        }

        $query = "EXEC pr_event_bybase ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);
        //die("oi".print_r($result,true));

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "key"=> $key,
                "CodPeca" => $row["CodPeca"],
                "NomPeca" => $row["NomPeca"],
                "ds_evento" => $row["ds_evento"],
                "CodTipPeca" => $row["CodTipPeca"],
                "TipPeca" => $row["TipPeca"],
                "CenPeca" => parentalRating($row["CenPeca"]),
                "ds_local_evento" => $row["ds_local_evento"],
                "ds_nome_teatro" => $row["ds_nome_teatro"],
                "address" => $row["address"],
                "valores" => $row["valores"],
                "id_evento" => $row["id_evento"],
                "description" => $row["description"],
                "cardimage" => $row["cardimage"],
                "cardbigimage" => $row["cardbigimage"],
                "id_base" => $row["id_base"],
                "meta_keyword" => $row["meta_keyword"],
                "meta_description" => $row["meta_description"],
                "ds_municipio" => $row["ds_municipio"],
                "sg_estado" => $row["sg_estado"],
                "badge_city_text" => $row["badge_city_text"],
                "img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "badge"=> splitBadge($row["badges"]),
                "promo"=> splitPromotion($row["promotion"])
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }
    function parentalRating($value) {
        $ret = "";

        if ($value<10) {
            $ret = "L";
        }
        else if ($value<12) {
            $ret = "10";
        }
        else if ($value<14) {
            $ret = "12";
        }
        else if ($value<16) {
            $ret = "14";
        }
        else if ($value<18) {
            $ret = "16";
        }
        else {
            $ret = "18";
        }

        return $ret;
    }
    function splitBadge($value) {
        $ret = array();

        if ($value === null || $value === '')
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

        if ($value === null || $value === '')
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

get($_REQUEST["key"], $_REQUEST["apikey"]);

?>