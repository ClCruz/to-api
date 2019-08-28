<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $id_base) {
        // isuservalidordie($id_user);

        $query = "EXEC pr_report_clients ?";
        $params = array($id_base);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "id"=> $row["id"],
                "name"=> $row["name"],
                "document"=> documentformatBR($row["document"]),
                "email"=> $row["email"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_base"]);
?>