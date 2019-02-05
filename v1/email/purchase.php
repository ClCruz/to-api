<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    /*
    $buyer: informações do comprador
        name: nome
        email: email
        document: documento
    $voucher: objeto do ingresso
        id: id do pedido
        link: link para impressão
        $event: array de eventos
            linkimage: link da imagem do evento
            link: link para o evento
            name: nome do evento
            city: cidade do evento
            state: estado do evento
            tickettype: tipo do bilhete
            date: data do evento
            hour: hora do evento
            amount: valor do evento
        totalamount: valor total do voucher

    */
    function send($id_pedido_venda) {
        $query = "EXEC pr_purchase_info_email ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            //die(json_encode($row["uri"]));
            $json[] = array(
                "buyer_name" => $row["buyer_name"],
                "buyer_email" => $row["buyer_email"],
                "buyer_document" => $row["buyer_document"],
                "voucher_id" => $row["voucher_id"],
                "voucher_code" => $row["voucher_code"],
                "voucher_event_image" => getDefaultMediaHost(). str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "voucher_event_link" => getwhitelabelURI_home($row["uri"]),
                "voucher_event_name" => $row["voucher_event_name"],
                "voucher_event_city" => $row["voucher_event_city"],
                "voucher_event_state" => $row["voucher_event_state"],
                "voucher_event_tickettype" => $row["voucher_event_tickettype"],
                "voucher_event_date" => $row["voucher_event_date"],
                "voucher_event_hour" => $row["voucher_event_hour"],
                "voucher_event_amount" => $row["voucher_event_amount"],
                "voucher_event_service" => $row["voucher_event_service"],
                "voucher_link" => getwhitelabelURI_legacy("/comprar/reimprimirEmail.php?pedido=".$row["voucher_id"]),
            );
        }

        // echo json_encode($json);
        logme();

        // die();    
    }
send($_REQUEST["id_pedido_venda"]);
?>