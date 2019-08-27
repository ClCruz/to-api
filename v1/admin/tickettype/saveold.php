<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;

    function get(
        $id
        ,$id_base
        ,$allpartner
        ) {
            
        $query = "EXEC pr_tickettype_save_old ?,?,?";
        $params = array($id
        ,$id_base
        ,$allpartner
        );
        $result = db_exec($query, $params, $id_base);

        foreach ($result as &$row) {
            $id = $row["id"];
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]
                        ,"imagelog"=> "");
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id"]
    ,$_POST["id_base"]
    ,$_POST["allpartner"]
);
?>