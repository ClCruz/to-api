<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/git_functions.php");

    function getinfofromdb($id) {
        $query = "EXEC pr_admin_partner_get_wl ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"uniquename" => $row["uniquename"]
                ,"domain"=> $row["domain"]
                ,"databaseOK"=> $row["databaseOK"]
                ,"userOK"=> $row["userOK"]
                ,"databaseStatus"=> $row["databaseStatus"]
                ,"userStatus"=> $row["userStatus"]
                ,"json_ga"=> $row["json_ga"]
                ,"json_meta_description"=> $row["json_meta_description"]
                ,"json_meta_keywords"=> $row["json_meta_keywords"]
                ,"json_template"=> $row["json_template"]
                ,"json_info_title"=> $row["json_info_title"]
                ,"json_info_description"=> $row["json_info_description"]
                ,"json_info_cnpj"=> $row["json_info_cnpj"]
                ,"json_info_companyaddress"=> $row["json_info_companyaddress"]
                ,"json_info_companyname"=> $row["json_info_companyname"]
                ,"scss_colors_primary"=> $row["scss_colors_primary"]
                ,"scss_colors_secondary"=> $row["scss_colors_secondary"]
                ,"apikey"=>$row["apikey"]
                ,"videos"=>array("list"=>array())
            );
        }


        $query = "EXEC pr_admin_partner_whitelabel_videos_list ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $aux = array();
        foreach ($result as &$row) {
            $aux = array(
                "order" => $row["fileorder"]
                ,"type" => $row["filetype"]
                ,"src" => $row["source"]
            );
        }

        $json["videos"]["list"] = $aux;

        return $json;
    }

    function getFromReplacement($replace, $key) {
        foreach($replace as $tocheck){
            if ($tocheck["from"] == $key) {
                return $tocheck["to"];
            }
        }
        return null;
    }

    function getReplacement($db, $logoext) {
        $domain = $db["domain"];
        $domainwithwww = $domain;
        $domainwithoutwww = strpos($domain, 'www') === 0 ? str_replace("www.", "", $domain) : $domain;
        $domainwithprotocol = "https://".$domain;
        $apiURI = "https://api.".$domainwithoutwww;
        $legacyURI = "https://compra.".$domainwithoutwww;
        $pinpadURI = "http://localhost:7001/api";
        $logomedia = "https://media.tixs.me/logos/logo-".$db["uniquename"].".jpg";
        $logo = "/assets/logo-".$db["uniquename"].".".$logoext;
        $db_user = "api.".$db["uniquename"];
        $db_pass = "!".$db["uniquename"]."@api#$";
        $db_host = "172.30.5.3";
        $db_port = "1435";
        
        $ret = array();
        $ret[] = array("from"=>"__whitelabel-name__", "to"=>$db["uniquename"]);
        $ret[] = array("from"=>"__wl-site__", "to"=> $domain);
        $ret[] = array("from"=>"__wl-sitewithwww__", "to"=> $domain);
        $ret[] = array("from"=>"__wl-sitewithoutwww__", "to"=> $domainwithoutwww);
        $ret[] = array("from"=>"__wl-sitewithwwwnoprotocol__", "to"=> $domainwithwww);
        $ret[] = array("from"=>"__wl-template__", "to"=>$db["json_template"]);
        $ret[] = array("from"=>"__wl-ga__", "to"=>$db["json_ga"]);
        $ret[] = array("from"=>"__wl-apikey__", "to"=>$db["apikey"]);
        $ret[] = array("from"=>"__wl-geokey__", "to"=>"AIzaSyCH0_IkJUiB8TLRuIJFvnI-JKLvuKx1zl8");
        $ret[] = array("from"=>"__wl-cnpj__", "to"=>$db["json_info_cnpj"]);
        $ret[] = array("from"=>"__wl-meta-description__", "to"=>$db["json_meta_description"]);
        $ret[] = array("from"=>"__wl-meta-keywords__", "to"=>$db["json_meta_keywords"]);
        $ret[] = array("from"=>"__wl-recaptcha-private__", "to"=>"");
        $ret[] = array("from"=>"__wl-recaptcha-public__", "to"=>"");
        $ret[] = array("from"=>"__wl-title__", "to"=>$db["json_info_title"]);
        $ret[] = array("from"=>"__wl-companyname__", "to"=>$db["json_info_companyname"]);
        $ret[] = array("from"=>"__wl-companyaddress__", "to"=>$db["json_info_companyaddress"]);
        $ret[] = array("from"=>"__wl-uniquename__", "to"=>$db["uniquename"]);
        $ret[] = array("from"=>"__wl-siteapi__", "to"=>$apiURI);
        $ret[] = array("from"=>"__wl-pinpaduri__", "to"=>$pinpadURI);
        $ret[] = array("from"=>"__wl-sitecompra__", "to"=>$legacyURI);
        $ret[] = array("from"=>"__wl-color-primary__", "to"=>$db["scss_colors_primary"]);
        $ret[] = array("from"=>"__wl-color-secondary__", "to"=>$db["scss_colors_secondary"]);
        $ret[] = array("from"=>"__wl-site-logo-media__", "to"=>$logo);
        $ret[] = array("from"=>"__wl-site-logo__", "to"=>$logo);
        $ret[] = array("from"=>"__wl-db-user__", "to"=>$db_user);
        $ret[] = array("from"=>"__wl-db-pass__", "to"=>$db_pass);
        $ret[] = array("from"=>"__wl-db-ip__", "to"=>$db_host);
        $ret[] = array("from"=>"__wl-db-port__", "to"=>$db_port);
        $ret[] = array("from"=>"__wl-logoext__", "to"=>$logoext);
        $ret[] = array("from"=>"__wl-video-list__", "to"=>json_encode($db["videos"]["list"], JSON_PRETTY_PRINT));
        $ret[] = array("from"=>"__wl-gateway-pagarme-api__", "to"=>"ak_live_pcYp3eGXxpOBHqViOLfBQ61NQ4433y");
        $ret[] = array("from"=>"__wl-gateway-pagarme-cryptkey__", "to"=>"ek_live_QSMMW6WD1Bgio5K9aB0IIPL656ctjE");
        return $ret;
    }
    function getDirContents($replace, $dir, &$results = array()){
        $files = array_diff(scandir($dir), array('..', '.', '.git'));
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

            if (is_file($path)) {
                $file_hasreplace = false;
                $file_fromreplace = "";
                $file_toreplace = "";
                $file_newname = "";
                foreach($replace as $tocheck){
                    if (strpos($value, $tocheck["from"])  !== false) {
                        $file_hasreplace = true;
                        $file_fromreplace = $tocheck["from"];
                        $file_toreplace = $tocheck["to"];
                        $file_newname = str_replace($tocheck["from"], $tocheck["to"], $path);
                        break;
                    }
                }
                $results[] = array("name"=>$path
                                    ,"newname"=>$file_newname
                                    ,"isdir"=>false
                                    ,"hasreplace"=> $file_hasreplace
                                    ,"from"=>$file_fromreplace
                                    ,"to"=>$file_toreplace);
            }
            if (is_dir($path)) {
                $dir_hasreplace = false;
                $dir_fromreplace = "";
                $dir_toreplace = "";
                $dir_newname = "";
                foreach($replace as $tocheck){
                    if (strpos($value, $tocheck["from"])  !== false) {
                        $dir_hasreplace = true;
                        $dir_fromreplace = $tocheck["from"];
                        $dir_toreplace = $tocheck["to"];
                        $dir_newname = str_replace($tocheck["from"], $tocheck["to"], $path);
                        break;
                    }
                }
                $results[] = array("name"=>$path
                                    ,"newname"=>$dir_newname
                                    ,"isdir"=>true
                                    ,"hasreplace"=> $dir_hasreplace
                                    ,"from"=>$dir_fromreplace
                                    ,"to"=>$dir_toreplace);


                getDirContents($replace, $path, $results);
            }
        }
    
        return $results;
    }

    function change($file, $replacement, $doecho) {
        $content = file_get_contents($file);
        $hasChanged = false;

        foreach ($replacement as &$row) {
            $count = 0;
            $content = str_replace($row["from"], $row["to"],$content, $count);
            if ($count > 0) {
                $hasChanged = true;
            }
        }
        if ($hasChanged) {
            if ($doecho) echo " changed.";

            file_put_contents($file, $content);
        }
        else {
            if ($doecho) echo " no change.";
        }
    }
    function doScaffolder($db, $replacement, $idexec) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_scaffolder($dir);
        $currentgit = $dir."scaffolder";
  
        $all = getDirContents($replacement, $currentgit);
        foreach ($all as &$row) {
            if ($row["hasreplace"] && $row["isdir"] == 1) {
                if ($doecho) echo "<br />renamed folder ".$row["name"]." to ".$row["newname"];
                rename($row["name"], $row["newname"]);
            }
        }

        $all = getDirContents($replacement, $currentgit);
        foreach ($all as &$row) {
            if ($row["hasreplace"] && $row["isdir"] == 0) {
                if ($doecho) echo "<br />renamed file ".$row["name"]." to ".$row["newname"];
                rename($row["name"], $row["newname"]);
            }
        }

        $all = getDirContents($replacement, $currentgit);
        foreach ($all as &$row) {
            if ($row["isdir"] == 0) {
                if ($doecho) echo "<br />checkin file ".$row["name"];
                change($row["name"], $replacement, $doecho);
            }
        }
        //production
        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        //dev
        // git_reset($currentgit);
    }
    function replaceindomain($db, $replacement, $jsonFile) {
        $aux = json_decode(file_get_contents($jsonFile), true);
        $site = str_replace("https://", "", getFromReplacement($replacement, "__wl-sitewithwww__"));
        $api = str_replace("https://", "", getFromReplacement($replacement, "__wl-siteapi__"));
        $legacy = str_replace("https://", "", getFromReplacement($replacement, "__wl-sitecompra__"));

        $aux[$site] = $db["uniquename"];
        $aux[$api] = $db["uniquename"];
        $aux[$legacy] = $db["uniquename"];

        $content = json_encode($aux,JSON_PRETTY_PRINT);

        file_put_contents($jsonFile, $content);
    }
    function do_toapi($db, $replacement, $idexec) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_toapi($dir);
        $currentgit = $dir."to-api";

        execshell($dir."scaffolder/to-api/", "rsync -rR * ".$dir."to-api/", false);

        replaceindomain($db, $replacement, $dir."to-api/jsons/domains.json");         

        //production
        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
        //dev
        //git_reset($currentgit);
    }
    function do_legacy($db, $replacement, $idexec,$logoext) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tolegacy($dir);
        $currentgit = $dir."to-legacy";

        execshell($dir."scaffolder/to-legacy/", "rsync -rR * ".$dir."to-legacy/", false);
        execshell("/var/www/media/logos/", "rsync -rR logo-".$db["uniquename"].".".$logoext." ".$dir."to-legacy/images/", false);

        replaceindomain($db, $replacement, $dir."to-legacy/jsons/domains.json");         

        //production
        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
        //dev
        //git_reset($currentgit);
    }
    function do_site($db, $replacement, $idexec,$logoext) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tosite($dir);
        $currentgit = $dir."to-site";

        execshell($dir."scaffolder/to-site/", "rsync -rR * ".$dir."to-site/", false);
        execshell("/var/www/media/logos/", "rsync -rR logo-".$db["uniquename"].".".$logoext." ".$dir."to-site/public/assets/", false);

        replaceindomain($db, $replacement, $dir."to-site/src/jsons/domains.json");         

        //production
        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
        //dev
        //git_reset($currentgit);
    }
    function resetall() {
        git_gotomaster("/var/www/gitauto/scaffolder/");
        git_gotomaster("/var/www/gitauto/to-site/");
        git_gotomaster("/var/www/gitauto/to-legacy/");
        git_gotomaster("/var/www/gitauto/to-api/");
        git_gotomaster("/var/www/gitauto/to-database/");

        echo json_encode(array("success"=>true, "msg"=>"Resetado com sucesso."));
        logme();
        die();
    }
    function doall($id, $do_site, $do_legacy, $do_api, $logoext) {
        $idexec = date("Ymdhis");
        $db = getinfofromdb($id);
        $replacement = getReplacement($db,$logoext);

        doScaffolder($db, $replacement, $idexec);
        if ($do_site) {
            do_site($db, $replacement, $idexec, $logoext);
        }
        if ($do_legacy) {
            do_legacy($db, $replacement, $idexec, $logoext);
        }
        if ($do_api) {
            do_toapi($db, $replacement, $idexec);
        }

        echo json_encode(array("success"=>true, "msg"=>"Criado com sucesso."));
        logme();
        die();
    }

    //doall("1a0fdc45-e934-4c9e-bf4c-8fc8c11474a1");
?>