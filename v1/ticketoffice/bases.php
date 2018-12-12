<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function bases($id_ticketoffice_user) {
        $query = "EXEC pr_bases ?";
        $params = array($id_ticketoffice_user);
        $result = db_exec($query, $params);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("id_base"=>$row["id_base"]
                        ,"ds_nome_base_sql"=>$row["ds_nome_base_sql"]
                        ,"ds_nome_teatro"=>$row["ds_nome_teatro"]);
            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }
bases($_REQUEST["id_ticketoffice_user"]);
?>