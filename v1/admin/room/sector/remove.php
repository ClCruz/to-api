<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $CodSala, $CodSetor) {
        //sleep(5);
        $query = "EXEC pr_room_sectors_remove ?,?";
        $params = array($CodSala, $CodSetor);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json= array(
                "success" => $row["success"]
                ,"msg" => $row["msg"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id_base"], $_POST["CodSala"], $_POST["CodSetor"]);
?>