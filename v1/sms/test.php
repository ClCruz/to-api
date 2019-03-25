<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/sms/helper.php");


    die(sms_sendnow(30, null, 'login', '5513996736933', 'localhost codigo 12548'));
?>