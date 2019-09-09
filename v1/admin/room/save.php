<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function get(
        $id_base
        ,$CodSala
        ,$NomSala
        ,$NomRedSala
        ,$nameonsite
        ,$IngressoNumerado
        ,$id_local_evento
        ,$StaSala
        ) {
            
        $StaSala = $StaSala == 1 ? 'A' : 'I';

        $query = "EXEC pr_room_save ?,?,?,?,?,?,?";
        $params = array(
            $CodSala
            ,$NomSala
            ,$NomRedSala
            ,$nameonsite
            ,$IngressoNumerado
            ,$id_local_evento
            ,$StaSala
        );

        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]
                        ,"id"=>$row["id"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id_base"]
    ,$_POST["CodSala"]
    ,$_POST["NomSala"]
    ,$_POST["NomRedSala"]
    ,$_POST["nameonsite"]
    ,$_POST["IngressoNumerado"]
    ,$_POST["id_local_evento"]
    ,$_POST["StaSala"]
);
?>