<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user,$id,$uniquename,$name,$domain,$dateStart,$dateEnd,$type,$active, $fb_appid, $recaptchaid, $sell_email, $send_sell_email, $ga_id) {
        
        //sleep(5);
        $query = "EXEC pr_admin_partner_save ?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($id_user, db_paramid($id),$uniquename, $name,$domain,$dateStart,$dateEnd,$type,$active, db_param($fb_appid), db_param($recaptchaid),$sell_email, $send_sell_email,$ga_id);
        //die("aaa.".json_encode($params));        
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"]
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_user"],$_POST["id"],$_POST["uniquename"],$_POST["name"],$_POST["domain"],$_POST["dateStart"],$_POST["dateEnd"],$_POST["type"],$_POST["active"],$_POST["fb_appid"], $_POST["recaptchaid"], $_POST["sell_email"], $_POST["send_sell_email"], $_POST["ga_id"]);
?>