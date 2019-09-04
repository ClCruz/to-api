<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $id_evento, $date, $hour) {
        $dateex = explode("/", $date);
        $date2 = $dateex[2]."-".$dateex[1]."-".$dateex[0];
        $query = "EXEC pr_accounting_key_add ?, ?, ?, ?, NULL";
        $params = array($loggedId, $id_evento, $date2, $hour);
        $result = db_exec($query, $params);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $json = array("id"=>$row["id"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_POST["loggedId"], $_POST["id_evento"], $_POST["date"], $_POST["hour"]);
?>