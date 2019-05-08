<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id_evento) {
        $query = "EXEC pr_admin_event_date_select ?";
        $params = array($id_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("DatApresentacao"=>$row["DatApresentacao"]
                    ,"value"=>$row["DatApresentacao"]
                    ,"text"=>$row["DatApresentacao"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id_base"], $_REQUEST["id_evento"]);
?>