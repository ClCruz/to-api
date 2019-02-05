<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");


    function setsession($id_client, $session) {
        $makemine = makeitmine($id_client, $session);
        echo json_encode($makemine);
        logme();
        die();    
        //http://localhost:2002/v1/purchase/site/session?id_client=30&session=thslkr39i6nhon6qgbgs5bnoc2
    }
  //setsession($_POST["id_client"], $_POST["session"]);
  setsession($_REQUEST["id_client"], $_REQUEST["session"]);
?>