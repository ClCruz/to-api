<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");

    function get() {

        $dir = '/var/www/gitauto/';
        git_clone_scaffolder($dir);
        git_clone_toapi($dir);
        git_clone_tosite($dir);
        git_clone_tolegacy($dir);

        $json = array("success"=>true
                    ,"msg"=>'Processo executado');

        echo json_encode($json);
        logme();
        die();    
    }

get();
?>