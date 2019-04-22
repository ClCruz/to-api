<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($local,$id) {

        $query = "EXEC pr_dbname";
        $params = array();
        $result = db_exec($query, $params, $local);

        $directoryname = "";

        foreach ($result as &$row) {
            $directoryname = $row["uniquename"];
        }


        $path = '/var/www/media/tickettype/'.$directoryname.'/'.$id.'.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $json = array("code"=>$base64);
        echo json_encode($json);
        logme();
        //performance();
        die();    
    }

get($_POST["local"],$_POST["id"]);
?>


