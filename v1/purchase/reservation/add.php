<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id_apresentacao, $indice, $id, $NIN, $codCliente, $codReserva) {
        $query = "";
        if ($codCliente == "null") {
            $codCliente = null;
            $codReserva = null;
        }
        if ($codCliente != "" || $codCliente != null) {
            $query = "EXEC pr_seat_reservation ?, ?, ?, ?, ?, ?, ?";
            $params = array($id_apresentacao, $indice, $id, $NIN, purchaseMinutesToExpireReservation(), $codCliente, $codReserva);
        }
        else {
            $query = "EXEC pr_seat_reservation ?, ?, ?, ?, ?";
            $params = array($id_apresentacao, $indice, $id, $NIN, purchaseMinutesToExpireReservation());
        }
        
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $aux = array();
        foreach ($result as &$row) {
            $aux = array(
                "error" => $row["error"],
                "info" => $row["info"],
                "code" => $row["code"],
            );
        }
        if ($aux["error"] == 0)
        {
            $json = array("success"=>1, "message"=>"Assento escolhido.");
        }
        else {
            switch ($aux["code"]) {
                case 1: //seat taken
                    $json = array("success"=>0, "message"=>"Assento já vendido, por favor escolha outro.");
                break;
                case 2: //seat taken by package
                    $json = array("success"=>0, "message"=>"Assento reservado para pacote.");
                break;
                case 3: //seat taken by reservation 
                    $json = array("success"=>0, "message"=>"Assento reservado.");
                break;
                case 4: //seat taken by temp
                    $json = array("success"=>0, "message"=>"Assento reservado por outro caixa.");
                break;
                case 5: //seat taken by site
                    $json = array("success"=>0, "message"=>"Assento reservado pelo site.");
                break;
                case 6: //limited by purchase
                    $json = array("success"=>0, "message"=>"Seu limite de compra por pedido foi excedido.");
                break;
                case 7: //limited by cpf
                    $json = array("success"=>0, "message"=>"Seu limite de compra por CPF foi excedido.");
                break;
                case 10: //unknown
                    $json = array("success"=>0, "message"=>"Falha para reservar o assento.");
                break;
            }
        }

        echo json_encode($json);
        logme();
        die();    
    }

set($_REQUEST["id_base"], $_REQUEST["id_apresentacao"], $_REQUEST["indice"], $_REQUEST["id"], $_REQUEST["nin"], $_REQUEST["codCliente"], $_REQUEST["codReserva"]);
?>