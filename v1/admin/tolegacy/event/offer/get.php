<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_apresentacao) {
        //sleep(5);
        $query = "EXEC pr_apresentacao_offer ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_apresentacao);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "onOffer" => $row["onOffer"]
                ,"onOfferOlderValue" => $row["onOfferOlderValue"]
                ,"onOfferPercentage" => $row["onOfferPercentage"]
                ,"onOfferText" => $row["onOfferText"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_apresentacao"]);
?>