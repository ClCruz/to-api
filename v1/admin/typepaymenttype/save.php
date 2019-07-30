<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function get(
        $id_base
        ,$CodTipForPagto
        ,$ClassifPagtoSAP
        ,$StaTipForPagto
        ,$TipForPagto
        ) {
            
        $StaTipForPagto = $StaTipForPagto == 1 ? 'A' : 'I';
        

        $query = "EXEC pr_typepaymenttype_save ?,?,?,?";
        $params = array(
            $CodTipForPagto
            ,$ClassifPagtoSAP
            ,$StaTipForPagto
            ,$TipForPagto
        );

        $result = db_exec($query, $params, $id_base);

        $directoryname = "";

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id_base"]
    ,$_POST["CodTipForPagto"]
    ,$_POST["ClassifPagtoSAP"]
    ,$_POST["StaTipForPagto"]
    ,$_POST["TipForPagto"]
);
?>