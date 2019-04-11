<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$type) {
        $path = '';

        switch ($type) {
            case "ori":
            case "original":
                $path = '/var/www/media/ori/'.$id.'/original.jpg';
            break;
            case "card":
                $path = '/var/www/media/evento/'.$id.'/card.jpg';
            break;
            case "banner":
            case "big":
                $path = '/var/www/media/evento/'.$id.'/big.jpg';
            break;
        }

        $filetype = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $filetype . ';base64,' . base64_encode($data);
        $json = array("code"=>$base64);
        echo json_encode($json);
        logme();
        //performance();
        die();    
    }

get($_POST["id"],$_POST["type"]);
?>


