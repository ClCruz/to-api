<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd) {
        // die("oi");
        $date = modifyDateBRtoUS($date);
        if ($customPeriodInit!='') {
            $customPeriodInit = modifyDateBRtoUS($customPeriodInit);
            $customPeriodEnd = modifyDateBRtoUS($customPeriodEnd);
        }
        $query = "EXEC pr_dashboard_purchase_channel ?,?,?,?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            array_push($json, array($row["web"] == 1 ? 'Internet' : 'Bilheteria', $row["sold"]));

            // $json[] = array(
            //     "web" => $row["web"],
            //     "sold" => $row["sold"],
            // );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"], $_REQUEST["periodtype"], $_REQUEST["customPeriodInit"], $_REQUEST["customPeriodEnd"]);
?>