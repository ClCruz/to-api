<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id_apresentacao, $indice, $id, $NIN, $codCliente, $codReserva, $overwrite) {
        $query = "";
        if ($codCliente == "null") {
            $codCliente = null;
            $codReserva = null;
        }
        $query = "EXEC pr_seat_reservation_multi ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($id_apresentacao, $indice, $id, $NIN, purchaseMinutesToExpireReservation(), $codCliente, $codReserva, $overwrite);
        
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $msg = "Assento escolhido.";

            if ($row["hasError"] == 1) {
                switch ($aux["code"]) {
                    case 1: //seat taken
                        $msg = "Assento já vendido, por favor escolha outro.";
                    break;
                    case 2: //seat taken by package
                        $msg = "Assento reservado para pacote.";
                    break;
                    case 3: //seat taken by reservation 
                        $msg = "Assento reservado.";
                    break;
                    case 4: //seat taken by temp
                        $msg = "Assento reservado por outro caixa.";
                    break;
                    case 5: //seat taken by site
                        $msg = "Assento reservado pelo site.";
                    break;
                    case 6: //limited by purchase
                        $msg = "Seu limite de compra por pedido foi excedido.";
                    break;
                    case 7: //limited by cpf
                        $msg = "Seu limite de compra por CPF foi excedido.";
                    break;
                    case 10: //unknown
                        $msg = "Falha para reservar o assento.";
                    break;
                }
            }

            $json [] = array(
                "indice" => $row["indice"],
                "seatTaken" => $row["seatTaken"],
                "seatTakenByPackage" => $row["seatTakenByPackage"],
                "seatTakenTemp" => $row["seatTakenTemp"],
                "seatTakenReserved" => $row["seatTakenReserved"],
                "seatTakenBySite" => $row["seatTakenBySite"],
                "limitedByPurchase" => $row["limitedByPurchase"],
                "limitedByNIN" => $row["limitedByNIN"],
                "hasError" => $row["hasError"],
                "ds_cadeira" => $row["ds_cadeira"],
                "ds_setor" => $row["ds_setor"],
                "code" => $row["code"],
                "isAdd" => $row["isAdd"],
                "hasanyerror" => $row["hasanyerror"],
                "alliserror" => $row["alliserror"],
                "message"=>$msg,
            );
            
        }

        echo json_encode($json);
        logme();
        die();    
    }

set($_POST["id_base"], $_POST["id_apresentacao"], $_POST["indice"], $_POST["id"], $_POST["nin"], $_POST["codCliente"], $_POST["codReserva"], $_POST["overwrite"]);
?>