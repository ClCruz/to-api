<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

    function set($email) {
        $query = "EXEC pr_user_resetpass ?, ?";
        $params = array($email, gethost());
        $result = db_exec($query, $params);

        $json = array();
        $name = "";
        $email = "";
        $token = "";

        foreach ($result as &$row) {
            $json = array(
                "success"=>$row["success"]
                ,"showerror"=>$row["showerror"]
                ,"msg"=>$row["msg"]
            );
            $token = $row["token"];
            $name = $row["name"];
            $email = $row["email"];
        }
// die("oi".json_encode($email));
        if ($json["success"] == 1 && $json["showerror"] == 0)
        {
            $json["msg"] = "Caso o e-mail exista em nossa base, iremos enviar um e-mail contendo as instruções para resetar a senha.";

            $html = setHtml_email($name, $token);

            $to = $email;
            $toName = $name;
    
            $from = getwhitelabelemail()["noreply"]["email"];
            $fromName = getwhitelabelemail()["noreply"]["from"];
    
            $subject = "Você pediu para alterar sua senha?";
    
            $msg = $html;
    
            //$teste = 
            sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
            //die(json_encode($teste));
        }

        echo json_encode($json);
        logme();
        die();     
    }
    function setHtml_email($name,$code) { 
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
        $htmlname = "resetpass.html";
        return $twig->render($htmlname, [
                                            "wlsite" => $wlsite,
                                            "wlsitewithwww" => $wlsitewithwww,
                                            "wlsitelogomedia" => $wlsitelogomedia,
                                            "wluniquename" => $wluniquename,
                                            "wlsitewithoutwww" => $wlsitewithoutwww,
                                            "user_name" => $name,
                                            "reset_link" => getwhitelabelURI_home("/resetpass/".$code),
                                        ] );
    }
    
set($_POST["email"]);
?>