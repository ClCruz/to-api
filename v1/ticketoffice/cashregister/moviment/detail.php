<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $date, $type) {
        $query = "EXEC pr_ticketoffice_cashregister_detail ?, ?, ?";
        $params = array($id_user, $date, $type);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array(
            "id"=>$row["id"]
            ,"amount"=>$row["amount"]
            ,"id_base"=>$row["id_base"]
            ,"ds_nome_base_sql"=>$row["ds_nome_base_sql"]
            ,"ds_nome_teatro"=>$row["ds_nome_teatro"]
            ,"type"=>$row["type"]
            ,"desc"=>$row["desc"]
            ,"codForPagto"=>$row["codForPagto"]
            ,"id_evento"=>$row["id_evento"]
            ,"ds_evento"=>$row["ds_evento"]
            ,"nameMoviment"=>$row["nameMoviment"]
            ,"justification"=>$row["justification"]
            ,"created"=>$row["created"]
            );
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_user"], $_REQUEST["date"], $_REQUEST["type"]);
?>