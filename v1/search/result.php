<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function search($input, $type, $startAt, $howMany, $api, $city = null, $state = null) {
        //sleep(5);
        $query = "EXEC pr_search ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($input, $type, db_param($startAt), db_param($howMany), db_param($city), db_param($state), $api);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_evento" => $row["id_evento"],
                "ds_evento" => $row["ds_evento"],
                "cardimage" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "cardbigimage" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_big}", getDefaultCardImageName(),$row["cardbigimage"])),
                "description" => $row["description"],
                "uri" => $row["uri"],
                "ds_nome_teatro" => $row["ds_local_evento"],//$row["ds_nome_teatro"],
                "ds_local_evento" => $row["ds_local_evento"],
                "ds_municipio" => $row["ds_municipio"],
                "ds_estado" => $row["ds_estado"],
                "sg_estado" => $row["sg_estado"],
                "datas" => $row["datas"],
                "badge"=> splitBadge($row["badges"]),
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

search($_REQUEST["input"], $_REQUEST["type"], $_REQUEST["startat"], $_REQUEST["howmany"], $_REQUEST["apikey"],$_REQUEST["city"],$_REQUEST["state"]);
?>