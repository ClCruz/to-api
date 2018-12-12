<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($codPeca, $id_base) {
        $query = "EXEC pr_split ?, ?";
        $params = array($codPeca, $id_base);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {

            $aux = array("recipient_id"=>$row["recipient_id"]
            ,"nr_percentual_split"=>$row["nr_percentual_split"]
            ,"liable"=>$row["liable"]
            ,"charge_processing_fee"=>$row["charge_processing_fee"]
            ,"percentage_credit_web"=>$row["percentage_credit_web"]
            ,"percentage_debit_web"=>$row["percentage_debit_web"]
            ,"percentage_boleto_web"=>$row["percentage_boleto_web"]
            ,"percentage_credit_box_office"=>$row["percentage_credit_box_office"]
            ,"percentage_debit_box_office"=>$row["percentage_debit_box_office"]
            ,"IsTicketPay"=>$row["IsTicketPay"]);
            
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["codPeca"],$_REQUEST["id_base"]);
?>