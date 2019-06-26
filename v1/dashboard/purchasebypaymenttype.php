<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $apikey, $id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd) {
        // die("oi");
        $date = modifyDateBRtoUS($date);
        if ($customPeriodInit!='') {
            $customPeriodInit = modifyDateBRtoUS($customPeriodInit);
            $customPeriodEnd = modifyDateBRtoUS($customPeriodEnd);
        }
        $query = "EXEC pr_dashboard_purchase_paymenttype ?,?,?,?,?,?,?";
        $params = array($id_evento, $id_apresentacao, $date, $hour, $periodtype, $customPeriodInit, $customPeriodEnd);
        $result = db_exec($query, $params);

        $json = array("all"=>array(), "web"=>array(), "ticketoffice"=>array());

        $json_all = array();
        $json_web = array();
        $json_ticketoffice = array();

        foreach ($result as &$row) {
            switch ($row["type"]) {
                case "all":
                    array_push($json_all, array($row["name"], $row["sold"]));
                break;
                case "web":
                    array_push($json_web, array($row["name"], $row["sold"]));
                break;
                case "boxoffice":
                    array_push($json_ticketoffice, array($row["name"], $row["sold"]));
                break;
            }
            
            // $json[] = array(
            //     "type" => $row["type"],
            //     "name" => $row["name"],
            //     "sold" => $row["sold"],
            // );
        }
        $json["all"] = $json_all;
        $json["web"] = $json_web;
        $json["ticketoffice"] = $json_ticketoffice;

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["loggedId"], $_REQUEST["apikey"], $_REQUEST["id_evento"], $_REQUEST["id_apresentacao"], $_REQUEST["date"], $_REQUEST["hour"], $_REQUEST["periodtype"], $_REQUEST["customPeriodInit"], $_REQUEST["customPeriodEnd"]);
?>