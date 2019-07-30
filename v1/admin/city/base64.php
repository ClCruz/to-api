<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$type) {
        $path = '';

        switch ($type) {
            case "normal":
            case "first":
            case "1":
                $path = '/var/www/media/city/'.$id.'/'.getDefaultCityImageName();
            break;
            case "extra":
            case "second":
            case "2":
                $path = '/var/www/media/city/'.$id.'/'.getDefaultCityExtraImageName();
            break;
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


