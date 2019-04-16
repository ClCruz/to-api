<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function execute($loggedId, $codApresentacao, $HorSessao, $amount, $allowweb, $allowticketoffice, $id_base) {
        $query = "EXEC pr_admin_presentation_modify ?,?,?,?,?";
        $params = array($codApresentacao,$HorSessao, $amount, $allowweb, $allowticketoffice);
        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();      
    }

execute($_POST["loggedId"], $_POST["codApresentacao"], $_POST["HorSessao"], $_POST["amount"], $_POST["allowweb"], $_POST["allowticketoffice"], $_POST["id_base"]);
?>