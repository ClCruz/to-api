<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get() {

        $id = gethost();
        //sleep(5);
        $query = "EXEC pr_partner_getinfo ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "name" => $row["name"]
                ,"isDemo" => $row["isDemo"]
                ,"isTrial" => $row["isTrial"]
                ,"isDev"=> $row["isDev"]
                ,"fb_appid"=> $row["fb_appid"]
                ,"recaptchaid"=> $row["recaptchaid"]
                ,"hasfb"=> $row["hasfb"]
                ,"hasrecaptcha"=> $row["hasrecaptcha"]
                ,"show_partner_info"=>$row["show_partner_info"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get();
?>