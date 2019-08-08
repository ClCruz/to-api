<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $CodSala) {
        //sleep(5);
        $query = "EXEC pr_room_sectors_list ?";
        $params = array($CodSala);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodSetor" => $row["CodSetor"]
                ,"NomSetor" => $row["NomSetor"]
                ,"CorSetor" => $row["CorSetor"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"Status" => $row["Status"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id_base"], $_POST["codSala"]);
?>