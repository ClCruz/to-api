<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");


    function get($id_user) {
        isuservalidordie($id_user);
        resetall();
    }

get($_POST["id_user"]);
?>