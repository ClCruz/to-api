<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function send($subject,$name,$email,$content,$id) {
        $newContent = "E-mail: ".$email;
        $newContent .= "<br />Conteúdo: ".$content;
        $newContent .= "<br />Número do pedido: ".$id;
        $newContent .= "<br />Nome: ".$name;

        sendonemail(getwhitelabelemail()["noreply"]["email"]
        , getwhitelabelemail()["noreply"]["from"]
        , getwhitelabelemail()["formto"]["email"]
        , getwhitelabelemail()["formto"]["from"]
        , gethost()." - Atendimento Site - ".$subject
        , $newContent
        );
        echo json_encode(array("success"=>true));
        logme();
        //performance();
        die();    
    }
send($_POST["subject"],$_POST["name"],$_POST["email"],$_POST["content"],$_POST["id"]);

?>