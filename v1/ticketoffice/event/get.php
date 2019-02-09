<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_events ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $json = array("codPeca"=>$row["CodPeca"]
                    ,"NomPeca"=>$row["NomPeca"]
                    ,"ValIngresso"=>$row["ValIngresso"]
                    ,"in_vende_site"=>$row["in_vende_site"]
                    ,"days"=>$row["days"]
                    ,"TemDurPeca"=>$row["TemDurPeca"]
                    ,"TipPeca"=>$row["TipPeca"]
                    ,"needCPF" => $row["needCPF"]
                    ,"needRG" => $row["needRG"]
                    ,"needPhone" => $row["needPhone"]
                    ,"needName" => $row["needName"]
                    ,"ticketoffice_askemail" => $row["ticketoffice_askemail"]
                    ,"needCardBin" => "0"
                    ,"needClient" => $row["needCPF"] == "1" || $row["needRG"] == "1" || $row["needPhone"] == "1" || $row["needName"] == "1"    
                    ,"text"=>$row["NomPeca"]
                    ,"value"=>$row["CodPeca"]);

            //array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id_base"], $_REQUEST["id"]);
?>