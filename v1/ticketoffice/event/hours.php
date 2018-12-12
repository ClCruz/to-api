<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function listevents($id_base, $codPeca, $datePresentation) {
        $query = "EXEC pr_eventsdayshours ?, ?";
        $params = array($codPeca, $datePresentation);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("codApresentacao"=>$row["codApresentacao"]
                    ,"id_apresentacao"=>$row["id_apresentacao"]
                    ,"DatApresentacao"=>$row["DatApresentacao"]
                    ,"HorSessao"=>$row["HorSessao"]
                    ,"NomSala"=>$row["NomSala"]
                    ,"NomRedSala"=>$row["NomRedSala"]
                    ,"ValPeca"=>$row["ValPeca"]
                    ,"PerDesconto"=>$row["PerDesconto"]
                    ,"cost"=>$row["cost"]
                    ,"IngressoNumerado"=>$row["IngressoNumerado"]
                    ,"text"=>$row["NomSala"]." (".$row["HorSessao"].")"//.$row["id_apresentacao"]
                    ,"value"=>$row["id_apresentacao"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    listevents($_REQUEST["id_base"], $_REQUEST["codPeca"], $_REQUEST["datePresentation"]);
?>