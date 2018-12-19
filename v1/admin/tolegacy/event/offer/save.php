<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_apresentacao,$onOffer,$onOfferPercentage,$onOfferOlderValue,$onOfferText) {
        //sleep(5);
        $query = "EXEC pr_apresentacao_offer_save ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_apresentacao,$onOffer,$onOfferPercentage,$onOfferOlderValue,$onOfferText);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }
        
        echo json_encode($json);
        logme();
        die();    
    }

set($_POST["id_apresentacao"],$_POST["onOffer"],$_POST["onOfferPercentage"],$_POST["onOfferOlderValue"],$_POST["onOfferText"]);
?>