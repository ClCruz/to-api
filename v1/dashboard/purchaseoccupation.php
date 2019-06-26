<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour) {
        // die("oi");
        $date = modifyDateBRtoUS($date);
        if ($customPeriodInit!='') {
            $customPeriodInit = modifyDateBRtoUS($customPeriodInit);
            $customPeriodEnd = modifyDateBRtoUS($customPeriodEnd);
        }
        $query = "EXEC pr_dashboard_purchase_occupation ?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            if ($row["result_notsold"] != 0)
                array_push($json, array('Disponivel', $row["result_notsold"]));
            if ($row["result_paid"] != 0)
                array_push($json, array('Vendido', $row["result_paid"]));
            if ($row["result_free"] != 0)
                array_push($json, array('Gratuito', $row["result_free"]));
            if ($row["result_waiting_payment"] != 0)
                array_push($json, array('Aguardando pagamento', $row["result_waiting_payment"]));
            if ($row["result_reserved"] != 0)
                array_push($json, array('Reservado', $row["result_reserved"]));
            
            // $json = array(
            //     "result_all" => $row["result_all"],
            //     "result_notsold" => $row["result_notsold"],
            //     "result_free" => $row["result_free"],
            //     "result_paid" => $row["result_paid"],
            //     "result_waiting_payment" => $row["result_waiting_payment"],
            //     "result_reserved" => $row["result_reserved"],
            //     "result_paid_and_free" => $row["result_paid_and_free"],
            //     "result_occupancyrate" => $row["result_occupancyrate"],
            //     "result_occupancyrateformatted" => $row["result_occupancyrateformatted"],
            // );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"]);
?>