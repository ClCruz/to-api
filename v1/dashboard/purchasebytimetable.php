<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd) {
        // die("oi");
        $query = "EXEC pr_dashboard_purchase_timetable ?,?,?,?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "web" => $row["web"],
                "hour" => $row["hour"],
                "sold" => $row["sold"],
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"], $_REQUEST["periodtype"], $_REQUEST["customPeriodInit"], $_REQUEST["customPeriodEnd"]);
?>