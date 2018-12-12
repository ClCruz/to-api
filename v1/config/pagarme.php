<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function getConfigPagarme() {
        $gw_pagarme = array("apikey" => getwhitelabel_gateway_pagarme()["api"],
                            "postbackURI"=> getwhitelabel("legacy")."/comprar/pagarme_receiver.php",
                            "apiURI"=>getwhitelabel_gateway_pagarme()["uri"]);
        return $gw_pagarme;
    }
?>