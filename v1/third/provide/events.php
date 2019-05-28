<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

//    stopIfApiNotExist();

function presentation($key, $date) {
    $query = "EXEC pr_api_presentation_list ?,?";
    $params = array($key,$date);
    $result = db_exec($query, $params);
    $json = array();

    foreach ($result as &$row) {
        $json[] = array(
            "event_id"=>$row["event_id"]
            ,"id_presentantion"=>$row["id_presentantion"]
            ,"dt_presentation"=>$row["dt_presentation"]
            ,"hour_presentation"=>$row["hour_presentation"]
        );
    }
    return $json;
}
function seat($key, $date) {
    $query = "EXEC pr_api_seats_list ?,?";
    $params = array($key,$date);
    $result = db_exec($query, $params);
    $json = array();

    foreach ($result as &$row) {
        $json[] = array(
            "event_id"=>$row["event_id"]
            ,"id_presentantion"=>$row["id_presentantion"]
            ,"sectorName"=>$row["sectorName"]
            ,"seatName"=>$row["seatName"]
            ,"seatId"=>$row["seatId"]
        );
    }
    return $json;
}
function tickets($key, $date) {
    $query = "EXEC pr_api_tickets_list ?,?";
    $params = array($key,$date);
    $result = db_exec($query, $params);
    $json = array();

    foreach ($result as &$row) {
        $json[] = array(
            "event_id"=>$row["event_id"]
            ,"id_presentantion"=>$row["id_presentantion"]
            // ,"sectorName"=>$row["sectorName"]
            // ,"seatName"=>$row["seatName"]
            ,"seatId"=>$row["seatId"]
            ,"price"=>$row["price"]
            ,"allowticketoffice"=>$row["allowticketoffice"]
            ,"allowweb"=>$row["allowweb"]
            // ,"PerDesconto"=>$row["PerDesconto"]
            ,"ticketType"=>$row["ticketType"]
            ,"sell_sun"=>$row["sell_sun"]
            ,"sell_mon"=>$row["sell_mon"]
            ,"sell_tue"=>$row["sell_tue"]
            ,"sell_wed"=>$row["sell_wed"]
            ,"sell_thu"=>$row["sell_thu"]
            ,"sell_fri"=>$row["sell_fri"]
            ,"sell_sat"=>$row["sell_sat"]
        );
    }
    return $json;
}

function get($key, $date) {
        $query = "EXEC pr_api_events_list ?,?";
        $params = array($key,$date);
        $result = db_exec($query, $params);
        $json = array();

        $presentations = presentation($key,$date);
        $seats = seat($key,$date);
        $tickets = tickets($key,$date);

        foreach ($result as &$row) {
            $presentation = array();
            $seathelper = array();
            $tickethelper = array();

            foreach ($presentations as &$presentation_value) {
                if ($presentation_value["event_id"] == $row["id"]) {
                    foreach ($seats as &$seat_value) {
                        if ($seat_value["event_id"] == $row["id"] && $seat_value["id_presentantion"] == $presentation_value["id_presentantion"]) {
                            foreach ($tickets as &$ticket_value) {
                                if ($seat_value["event_id"] == $row["id"] && $ticket_value["id_presentantion"] == $seat_value["id_presentantion"] && $ticket_value["seatId"] == $seat_value["seatId"]) {
                                    $tickethelper[] = $ticket_value;
                                }
                            }
                            $seat_value["tickets"] = $tickethelper;
                            $seathelper[] = $seat_value;
                        }
                    }
                    $presentation_value["seats"] = $seathelper;
                    $presentation[] = $presentation_value;
                }
            }

            $json[] = array(
                "id"=>$row["id"]
                ,"base"=>$row["base"]
                ,"name"=>$row["name"]
                ,"code"=>$row["code"]
                ,"place"=>$row["place"]
                ,"city"=>$row["city"]
                ,"state"=>$row["state"]
                ,"state_acronym"=>$row["state_acronym"]
                ,"image_card"=>$row["image_card"]
                ,"image_big"=>$row["image_big"]
                ,"uri"=>$row["uri"]
                ,"dates"=>$row["dates"]
                ,"genre"=>array(array("id"=>$row["id_genre"],"name"=>$row["genreName"]))
                ,"created"=>$row["created"]
                ,"amounts"=>$row["amounts"]
                ,"minAmount"=>$row["minAmount"]
                ,"maxAmount"=>$row["maxAmount"]
                ,"presentations"=>$presentation
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["key"],$_REQUEST["date"]);

?>