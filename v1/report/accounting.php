<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_evento, $id_apresentacao, $date, $hour) {
        $query = "EXEC pr_accounting ?, ?, ?, ?";
        $params = array($id_evento, $id_apresentacao, $date, $hour);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "local"=> $row["local"],
                "event"=> $row["event"],
                "responsible"=> $row["responsible"],
                "responsibleAddress"=> $row["responsibleAddress"],
                "number"=> $row["number"],
                "presentation_number"=> $row["presentation_number"],
                "presentation_date"=> $row["presentation_date"],
                "presentation_hour"=> $row["presentation_hour"],
                "sector"=> $row["sector"],
                "totalizer_all"=> $row["totalizer_all"],
                "totalizer_notsold"=> $row["totalizer_notsold"],
                "totalizer_free"=> $row["totalizer_free"],
                "totalizer_paid"=> $row["totalizer_paid"],
                "totalizer_paid_and_free"=> $row["totalizer_paid_and_free"],
                "NomSetor"=> $row["NomSetor"],
                "TipBilhete"=> $row["TipBilhete"],
                "sold"=> $row["sold"],
                "refund"=> $row["refund"],
                "ValPagto"=> $row["ValPagto"],
                "soldamount"=> $row["soldamount"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_evento"],$_POST["id_apresentacao"], $_REQUEST["date"], $_POST["hour"]);
?>