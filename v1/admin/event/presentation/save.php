<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;
    
    function execute($apikey
    ,$id_base
    ,$id_to_admin_user
    ,$id_evento
    ) {

       // echo json_encode($json);
       // logme();
        die("oi");    
    }

execute($_REQUEST["apikey"]
,$_POST["id_base"]
,$_POST["id_to_admin_user"]
,$_POST["id_evento"]);
?>