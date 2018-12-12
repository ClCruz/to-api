<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id, $date) {
        $query = "EXEC pr_movimentday ?, ?";
        $params = array($id, $date);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array("Codmovimento"=>$row["Codmovimento"]
            ,"login"=>$row["login"]
            ,"name"=>$row["name"]
            ,"text"=>"(".$row["Codmovimento"].") ".$row["name"]." - ".$row["login"]
            ,"value"=>$row["Codmovimento"]);
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["date"]);
?>