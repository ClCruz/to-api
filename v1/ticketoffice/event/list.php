<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function listevents($id_base) {
        $query = "EXEC pr_events";
        $params = array();
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("codPeca"=>$row["CodPeca"]
                    ,"NomPeca"=>$row["NomPeca"]
                    ,"img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"]))
                    ,"ValIngresso"=>$row["ValIngresso"]
                    ,"in_vende_site"=>$row["in_vende_site"]
                    ,"days"=>$row["days"]
                    ,"TemDurPeca"=>$row["TemDurPeca"]
                    ,"TipPeca"=>$row["TipPeca"]
                    ,"text"=>$row["NomPeca"]
                    ,"value"=>$row["CodPeca"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    listevents($_REQUEST["id_base"]);
?>