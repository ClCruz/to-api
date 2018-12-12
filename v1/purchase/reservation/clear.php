<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function set($id_base, $id, $all) {        
        $allHelper = $all == "true" ? 1 : 0;
        $query = "EXEC pr_seat_reservation_clear ?, ?";
        $params = array($id, $allHelper);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "success" => $row["success"],
            );
        }
        echo json_encode($json);
        logme();
        die();    
    }

set($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["all"]);
?>