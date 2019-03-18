<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id_apresentacao, $id) {
        $query = "EXEC pr_seats ?, ?";
        $params = array($id_apresentacao, $id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "Indice" => $row["Indice"],
                "NomObjeto" => $row["NomObjeto"],
                "ClasseObj" => $row["ClasseObj"],
                "CodSetor" => $row["CodSetor"],
                "NomSetor" => $row["NomSetor"],
                "PosXSite" => $row["PosXSite"],
                "PosYSite" => $row["PosYSite"],
                "PosX" => $row["PosX"],
                "PosY" => $row["PosY"],
                "status" => $row["status"],
                "STACADEIRA" => $row["STACADEIRA"],
                "id_session" => $row["id_session"],
                "imgvisaolugarfoto" => $row["imgvisaolugarfoto"],
                "CodCliente" => $row["CodCliente"],
                "CodReserva" => $row["CodReserva"],
                "codVenda" => $row["codVenda"],
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_REQUEST["id_base"],$_REQUEST["id_apresentacao"],$_REQUEST["id"]);

?>