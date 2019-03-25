<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $loggedid, $id_apresentacao) {
        $query = "EXEC pr_reservation_select ?";
        $params = array($id_apresentacao);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            $text = $row["Nome"];

            if ($row["CPF"] != "" && $row["CPF"] != null) {
                $text .= " (".documentformatBR($row["CPF"]).")";
            }

            $text .= " - ".$row["howmany"];
            if ($row["howmany"] == 1 || $row["howmany"] == "1") {
                $text .= " reserva";
            }
            else {
                $text .= " reservas";
            }

            $aux = array("CodReserva"=>$row["CodReserva"]
            ,"Nome"=>$row["Nome"]
            ,"CPF"=>$row["CPF"]
            ,"howmany"=>$row["howmany"]
            ,"value"=>$row["CodReserva"]
            ,"text"=>$text
            );
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_POST["id_base"], $_POST["loggedid"], $_POST["id_apresentacao"]);
?>