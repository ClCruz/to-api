<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_currentCashRegister ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $aux = array();

        foreach ($result as &$row) {
            $aux = array("id"=>$row["id"]
            ,"login"=>$row["login"]
            ,"name"=>$row["name"]
            ,"opened"=>$row["opened"]
            ,"Codmovimento"=>$row["Codmovimento"]
            ,"Saldo"=>$row["Saldo"]
            ,"ValDiferenca"=>$row["ValDiferenca"]
            ,"ObsDiferenca"=>$row["ObsDiferenca"]
            ,"DatMovimento"=>$row["DatMovimento"]
            ,"hoursOpened"=>$row["hoursOpened"]);
            //array_push($json,$aux);
        }
        $isOk = false;
        $isOpen = false;
        $needClose = false;

        if ($aux["opened"] == 1 || $aux["opened"] == true || $aux["opened"] == "1") {
            $isOpen = true;
            if (intval($aux["hoursOpened"]) < 24) {
                $isOk = true;
            }
            else {
                $needClose = true;
            }
        }

        $json = array("isok"=>$isOk
                    ,"isopen"=>$isOpen
                    ,"needClose"=>$needClose
                    ,"hoursOpened"=>$row["hoursOpened"]);

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"]);
?>