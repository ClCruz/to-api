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
            "id_event"=>$row["id_event"]
            ,"id"=>$row["id_presentantion"]
            ,"date"=>$row["dt_presentation"]
            ,"hour"=>$row["hour_presentation"]
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
            "id_event"=>$row["id_event"]
            ,"id_presentantion"=>$row["id_presentantion"]
            ,"sectorName"=>$row["sectorName"]
            ,"name"=>$row["seatName"]
            ,"id"=>$row["id_seat"]
            ,"numered"=>$row["numered"]
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
            "id_event"=>$row["id_event"]
            ,"id_presentantion"=>$row["id_presentantion"]
            // ,"sectorName"=>$row["sectorName"]
            // ,"seatName"=>$row["seatName"]
            ,"id_seat"=>$row["id_seat"]
            ,"price"=>$row["price"]
            ,"allowticketoffice"=>$row["allowticketoffice"]
            ,"allowweb"=>$row["allowweb"]
            // ,"PerDesconto"=>$row["PerDesconto"]
            // ,"CodTipBilhete"
            ,"id"=>$row["id_ticket"]
            ,"type"=>$row["ticketType"]
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
        // die(json_encode($result));
        $json = array();

        $presentations = presentation($key,$date);
        $seats = seat($key,$date);
        $tickets = tickets($key,$date);

        $uri_home = "";
        $uri_media = "";

        if (count($result)!=0) {
            $uri_home = getwhitelabelobjforced($result[0]["baseName"])["uri"];
            $uri_media = getDefaultMediaHost();
        }

        //die(json_encode(getwhitelabelobjforced($result[0]["baseName"])));

        
        foreach ($result as &$row) {
            $presentation = array();
            $seathelper = array();
            $tickethelper = array();
            // die(json_encode($row));
            foreach ($presentations as &$presentation_value) {
                $seathelper = array();
                $tickethelper = array();
                // die(json_encode($presentation_value));
                if ($row["id"] == $presentation_value["id_event"]) {
                    foreach ($seats as &$seat_value) {
                        $tickethelper = array();
                        // die(json_encode($seat_value));
                        if ($row["id"] == $seat_value["id_event"] && $presentation_value["id"] == $seat_value["id_presentantion"]) {
                            foreach ($tickets as &$ticket_value) {
                                //die(json_encode($ticket_value));
                                //if ($seat_value["id"] == 74)
                                //    die(json_encode($ticket_value));

                                if ($row["id"] == $ticket_value["id_event"] 
                                        && $presentation_value["id"] == $ticket_value["id_presentantion"] 
                                        && $seat_value["id"] == $ticket_value["id_seat"]) {
                                    $tickethelper[] = $ticket_value;
                                }
                            }
                            $seat_value["tickets"] = $tickethelper;
                            $seathelper[] = $seat_value;
                        }
                    }
                    $presentation_value["seats"] = $seathelper;
                    $presentation_value["seat_total"] = count($seathelper);
                    if ($presentation_value["seat_total"]!=0) {
                        $presentation[] = $presentation_value;
                    }
                }
            }
            $imageBigURI = getDefaultMediaHost().str_replace("{default_big}",getBigCardImageName(),str_replace("{id}",$row["id"],$row["image_big"]))."?".randomintbydate();
            $imageURI = getDefaultMediaHost().str_replace("{default_card}",getDefaultCardImageName(),str_replace("{id}",$row["id"],$row["image_card"]))."?".randomintbydate();

            $json[] = array(
                "id"=>$row["id"]
                ,"base"=>$row["base"]
                ,"name"=>$row["name"]
                ,"code"=>$row["code"]
                ,"place"=>$row["place"]
                ,"city"=>$row["city"]
                ,"state"=>$row["state"]
                ,"state_acronym"=>$row["state_acronym"]
                ,"image_card"=>$imageURI
                ,"image_big"=>$imageBigURI
                ,"uri"=>$uri_home.$row["uri"]
                ,"dates"=>$row["dates"]
                ,"genre"=>array(array("id"=>$row["id_genre"],"name"=>$row["genreName"]))
                ,"created"=>$row["created"]
                ,"amounts"=>$row["amounts"]
                ,"minAmount"=>$row["minAmount"]
                ,"maxAmount"=>$row["maxAmount"]
                ,"changed"=>$row["changed"]
                ,"presentations"=>$presentation
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["key"],$_REQUEST["date"]);

?>