<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/sms/helper.php");


    sms_response($_REQUEST["codigosms"], $_REQUEST["status"]);
?>