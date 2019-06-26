<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd) {
        // die("oi");
        $date = modifyDateBRtoUS($date);

        if ($customPeriodInit!='') {
            $customPeriodInit = modifyDateBRtoUS($customPeriodInit);
            $customPeriodEnd = modifyDateBRtoUS($customPeriodEnd);
        }
        $query = "EXEC pr_dashboard_purchase_values ?,?,?,?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd);
        $result = db_exec($query, $params);

        $json = array(
            "total_sold"=>0,
            "total_soldamount"=>0,
            "averageticket"=>0,
            "total_soldamountformatted"=>"",
            "averageticket_formatted"=>"",
            "typeofdiff" => "",
            "typeofdiffAmount" => "",
            "per_total_diff_formatted" => "",
            "perAmount_total_formatted" => ""

        );
            
        foreach ($result as &$row) {
            $json = array(
                "total_sold" => $row["total_sold"],
                "total_soldamount" => $row["total_soldamount"],
                "averageticket" => $row["averageticket"],
                "total_soldamountformatted" => $row["total_soldamountformatted"],
                "averageticket_formatted" => $row["averageticket_formatted"],
                "typeofdiff" => $row["typeofdiff"],
                "typeofdiffAmount" => $row["typeofdiffAmount"],
                "per_total_diff_formatted" => $row["per_total_diff_formatted"],
                "perAmount_total_formatted" => $row["perAmount_total_formatted"]
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"], $_REQUEST["periodtype"], $_REQUEST["customPeriodInit"], $_REQUEST["customPeriodEnd"]);
?>