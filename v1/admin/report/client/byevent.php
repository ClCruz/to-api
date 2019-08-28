<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $id_base, $id_evento, $date, $hour) {

        if ($date != '' && $date != '') {
            $date = modifyDateBRtoUS($date);
        }
        // isuservalidordie($id_user);

        $query = "EXEC pr_report_clients_by_event ?,?,?,?";
        $params = array($id_base, $id_evento, $date, $hour);
        $result = db_exec($query, $params, $id_base);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id"=> $row["id"],
                "name"=> $row["name"],
                "document"=> documentformatBR($row["document"]),
                "email"=> $row["email"],
                "indice"=> $row["indice"],
                "seat"=> $row["seat"],
                "room"=> $row["room"],
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_base"],$_REQUEST["id_evento"],$_REQUEST["date"],$_REQUEST["hour"]);
?>