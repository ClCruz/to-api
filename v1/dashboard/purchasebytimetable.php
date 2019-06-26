<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd) {
        // die("oi");
        $date = modifyDateBRtoUS($date);
        if ($customPeriodInit!='') {
            $customPeriodInit = modifyDateBRtoUS($customPeriodInit);
            $customPeriodEnd = modifyDateBRtoUS($customPeriodEnd);
        }
        $query = "EXEC pr_dashboard_purchase_timetable ?,?,?,?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd);
        $result = db_exec($query, $params);

        $json = array();
        $json_web = array();
        $json_boxoffice = array();

        array_push($json_web, 'web', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        array_push($json_boxoffice, 'ticketoffice', 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($result as &$row) {
            $aux = $row["hour"]+1;
            if ($row["web"] == 1) {
                $json_web[$aux] = $row["sold"];
            }
            else {
                $json_boxoffice[$aux] = $row["sold"];
            }

            // $json[] = array(
            //     "web" => $row["web"],
            //     "hour" => $row["hour"],
            //     "sold" => $row["sold"],
            // );
        }

        $json = array("web"=>$json_web,"ticketoffice"=>$json_boxoffice);

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"], $_REQUEST["periodtype"], $_REQUEST["customPeriodInit"], $_REQUEST["customPeriodEnd"]);
?>