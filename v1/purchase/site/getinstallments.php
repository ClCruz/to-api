
<?php
     require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
     require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");
     require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/cardbin.php");
     require_once($_SERVER['DOCUMENT_ROOT']."/v1/gateway/payment/pagarme.php");
     require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/session.php");
     
    function get($codCliente, $idSession) {
      setsession($codCliente, $idSession);

      $values = getvaluesofmyshoppig($codCliente);
      $amount = $values[0]["totalwithservice"];
      $id_purchase = get_id_purchase($idSession, $codCliente);

      $installment_config = getinstallments($id_purchase, $codCliente);
      $installment_gateway = pagarme_installments($id_purchase, $installment_config["free_installments"], $installment_config["max_installments"], $installment_config["interest_rate"], $amount);
      
      echo json_encode($installment_gateway);
        logme();
        die();    
    }

    get($_REQUEST["codCliente"], $_REQUEST["idSession"]);
?>