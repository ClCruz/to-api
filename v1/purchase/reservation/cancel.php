<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function call($id_base, $codReserva, $indice) {
        $query = "EXEC pr_seat_reservation_cancel ?, ?";
        $params = array($codReserva, $indice);
        $result = db_exec($query, $params, $id_base);

        $aux = array();

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }
//    refundPagarme($_REQUEST["id"], $_REQUEST["amount"]);
call($_REQUEST["id_base"], $_REQUEST["codReserva"], $_REQUEST["indice"]);
?>