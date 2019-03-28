<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$type) {

        $path = '/var/www/media/discovery/'.$id.'/'.$type.'.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $json = array("code"=>$base64);
        echo json_encode($json);
        logme();
        //performance();
        die();    
    }

get($_POST["id"],$_POST["type"]);
?>


