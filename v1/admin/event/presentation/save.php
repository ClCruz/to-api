<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;
    
    function execute($apikey
    ,$data
    ) {

       // echo json_encode($json);
       // logme();
        die("oi".json_encode($data));    
    }

execute($_REQUEST["apikey"]
,$_POST["data"]);
?>