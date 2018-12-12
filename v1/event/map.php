<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $id_base) {
        $query = "EXEC pr_map ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "CodPeca" => $row["CodPeca"],
                "NomPeca" => $row["NomPeca"],
                "CodApresentacao" => $row["CodApresentacao"],
                "DatApresentacao" => $row["DatApresentacao"],
                "HorSessao" => $row["HorSessao"],
                "FotoImagemSite" => str_replace("{DEFAULT}", getDefaultMap(),$row["FotoImagemSite"]),
                //"FotoImagemSite" => getDefaultMediaHost() . str_replace("{DEFAULT}", getDefaultMap(),$row["FotoImagemSite"]),
                "AlturaSite" => $row["AlturaSite"],
                "LarguraSite" => $row["LarguraSite"],
                "IngressoNumerado" => $row["IngressoNumerado"],
                "seatsPurchased" => $row["seatsPurchased"],
                "seatsTotal" => $row["seatsTotal"],
                "seatsAvailable" => $row["seatsAvailable"],
                "maxSeatsAvailableToBuy" => $row["maxSeatsAvailableToBuy"],
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["id"], $_REQUEST["id_base"]);

?>