<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

    function execute_bordero($loggedId, $pass, $email, $id_evento, $date, $hour) {
        $user = getinfo_user($loggedId);
        $event = getinfo($id_evento);
        $bordero = getid($loggedId, $id_evento, $date, $hour, $pass);
        $event_name = $event["ds_evento"];

        $user_name = $user["name"];
        $event_name = $event["ds_evento"];
        $event_date = $date;
        $event_hour = $hour;
        $show_pass = $pass != '' && isset($pass);
        $event_pass = $pass;

        $link = "v1/print/report/ac?code=".$bordero["id"];

        $event_link = getwhitelabelURI_api($link);

        if ($email != '') {
            $html = setHtml_email($user_name,$event_name,$event_date,$event_hour,$show_pass,$event_pass,$event_link);

            $to = $email;
            $toName = $email;
    
            $from = getwhitelabelemail()["noreply"]["email"];
            $fromName = getwhitelabelemail()["noreply"]["from"];
    
            $subject = "Borderô - ".$event_name;
    
            $msg = $html;
    
            sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
        }
        
        return $event_link;
    }

    function setHtml_email($user_name,$event_name,$event_date,$event_hour,$show_pass,$event_pass,$event_link) { 
        $templatefolder = $_SERVER['DOCUMENT_ROOT'].$templatefolder = getwhitelabelobj()["templates"]["emails"]["folder"];

        $replacement = getonlyReplacement(gethost());
        
        $wlsite = "";
        $wlsitewithwww = "";
        $wlsitelogomedia = "";
        $wluniquename = "";
        $wlsitewithoutwww = "";

        
        foreach($replacement as $tocheck){
            if (strpos("__wl-site__", $tocheck["from"])  !== false) {
                $wlsite = $tocheck["to"];
            }
            if (strpos("__wl-sitewithwww__", $tocheck["from"])  !== false) {
                $wlsitewithwww = $tocheck["to"];
            }
            if (strpos("__wl-site-logo-media__", $tocheck["from"])  !== false) {
                $wlsitelogomedia = $tocheck["to"];
            }
            if (strpos("__wl-uniquename__", $tocheck["from"])  !== false) {
                $wluniquename = $tocheck["to"];
            }
            if (strpos("__wl-sitewithoutwww__", $tocheck["from"])  !== false) {
                $wlsitewithoutwww = $tocheck["to"];
            }
        }
        // die(json_encode($wlsitewithwww));

        $loader = new Twig_Loader_Filesystem($templatefolder);
        $twig = new Twig_Environment($loader);

        $htmlname = "link_bordero.html";
        return $twig->render($htmlname, [
                                            "wlsite" => $wlsite,
                                            "wlsitewithwww" => $wlsitewithwww,
                                            "wlsitelogomedia" => $wlsitelogomedia,
                                            "wluniquename" => $wluniquename,
                                            "user_name" => $user_name,
                                            "event_name" => $event_name,
                                            "event_date" => $event_date,
                                            "event_hour" => $event_hour,
                                            "show_pass" => $show_pass,
                                            "event_pass" => $event_pass,
                                            "event_link" => $event_link
                                        ] );
    }
    
    function getinfo($id_evento) {
        $query = "EXEC pr_event_get_simple ?";
        $params = array($id_evento);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array("ds_evento"=>$row["ds_evento"]);
        }

        return $json;
    }
    function getinfo_user($loggedId) {
        $query = "EXEC pr_to_admin_user_get ?";
        $params = array($loggedId);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $json = array("name"=>$row["name"]);
        }

        return $json;
    }

    function getid($loggedId, $id_evento, $date, $hour, $pass) {
        $dateex = explode("/", $date);
        $date2 = $dateex[2]."-".$dateex[1]."-".$dateex[0];
        $query = "EXEC pr_accounting_key_add ?, ?, ?, ?, ?";
        $params = array($loggedId, $id_evento, $date2, $hour, $pass);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array("id"=>$row["id"]);
        }
        return $json;
    }

    function get($loggedId, $pass, $email, $id_evento, $date, $hour) {

        $link = execute_bordero($loggedId, $pass, $email, $id_evento, $date, $hour);

        $json = array("link"=>$link);

        echo json_encode($json);
        logme();
        die(); 
    }

get($_POST["loggedId"], $_POST["pass"], $_POST["email"], $_POST["id_evento"], $_POST["date"], $_POST["hour"]);
?>