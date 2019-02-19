<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $date, $result) {
        $query = "EXEC pr_ticketoffice_cashregister_list ?, ?, ?";
        $params = array($id_user, $date, $result);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array(
            "id"=>$row["id_base"]
            ,"id_base"=>$row["id_base"]
            ,"ds_nome_teatro"=>$row["ds_nome_teatro"]
            ,"qtdbybase"=>$row["qtdbybase"]
            ,"amountbybase"=>$row["amountbybase"]
            ,"rowspan"=>$row["total"]
            );
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["id_user"], $_REQUEST["date"], $_REQUEST["result"]);
?>