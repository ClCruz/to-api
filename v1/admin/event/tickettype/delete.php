<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($loggedId, $codApresentacao, $id_base) {
        $query = "EXEC pr_admin_presentation_delete ?";
        $params = array($codApresentacao);
        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();      
    }

execute($_POST["loggedId"], $_POST["codApresentacao"], $_POST["id_base"]);
?>