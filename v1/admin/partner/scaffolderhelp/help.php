<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

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
            );
        }

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

    function getReplacement($db) {
        $domain = $db["domain"];
        $domainwithwww = $domain;
        $domainwithoutwww = strpos($domain, 'www') === 0 ? str_replace("www.", "", $domain) : $domain;
        $domainwithprotocol = "https://".$domain;
        $apiURI = "https://api.".$domainwithoutwww;
        $legacyURI = "https://compra.".$domainwithoutwww;
        $pinpadURI = "http://localhost:7001/api";
        $logo = "https://media.tixs.me/logos/logo-".$db["uniquename"].".jpg";
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
        $ret[] = array("from"=>"__wl-site-logo__", "to"=>$logo);
        $ret[] = array("from"=>"__wl-db-user__", "to"=>$db_user);
        $ret[] = array("from"=>"__wl-db-pass__", "to"=>$db_pass);
        $ret[] = array("from"=>"__wl-db-ip__", "to"=>$db_host);
        $ret[] = array("from"=>"__wl-db-port__", "to"=>$db_port);
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
    function execshell($dir, $cmd, $doecho) {
        if ($dir!='') {
            $cmd = "cd $dir &&".$cmd;
        }

        if ($doecho) {
            echo shell_exec($cmd);
        }
        else {
            shell_exec($cmd);
        }
    }
    function git_clone($dir,$who) {
        $urltoclone = "";

        switch ($who) {
            case "scaffolder":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/scaffolder.git";
            break;
            case "to-site":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/to-site.git";
            break;
            case "to-api":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/to-api.git";
            break;
            case "to-legacy":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/to-legacy.git";
            break;
        }

        execshell($dir, "git clone $urltoclone", false);
    }
    function git_config($dir) {
        execshell($dir, 'git config user.email "blcoccaro@gmail.com" && git config push.default simple', false);
        //execshell($dir, 'git config user.email "blcoccaro+ticketoffice@gmail.com" && git config user.name "ticketofficedeploy"', false);
    }
    function git_pull($dir) {
        execshell($dir, "git pull", false);
    }
    function git_reset($dir) {
        execshell($dir, "git clean -fd && git reset --hard && git clean -fd", false);
    }
    function git_gotomaster($dir) {
        git_reset($dir);
        execshell($dir, "git checkout master", false);
        git_reset($dir);
    }
    function git_createbranch_push($dir, $name, $idexec) {
        git_config($dir);
        $branchname = "wl_".$name."_".$idexec;
        execshell($dir, 'git checkout -b '.$branchname.' && git add -A . && git commit -m "'.date("d-m-Y h:i:s").' <> [[automatically generated]] - '.$name.'" && git push '.$branchname, false);
        execshell($dir, 'git push -u origin '.$branchname, false);
    }
    function git_mergetomaster($dir, $name, $idexec) {
        $branchname = "wl_".$name."_".$idexec;
        git_config($dir);
        execshell($dir, 'git checkout master && git merge -q -m "'.date("d-m-Y h:i:s").' <> [[automatically generated]] - '.$name.'" --no-edit '.$branchname.' && git push', false);
    }

    function git_clone_scaffolder($dir) {
        if (!file_exists($dir."scaffolder")) {
            git_clone($dir, 'scaffolder');
        }
        else {
            git_gotomaster($dir."scaffolder");
        }
    }
    function git_clone_toapi($dir) {
        if (!file_exists($dir."to-api")) {
            git_clone($dir, 'to-api');
        }
        else {
            git_gotomaster($dir."to-api");
        }
    }
    function git_clone_tosite($dir) {
        if (!file_exists($dir."to-site")) {
            git_clone($dir, 'to-site');
        }
        else {
            git_gotomaster($dir."to-site");
        }
    }
    function git_clone_tolegacy($dir) {
        if (!file_exists($dir."to-legacy")) {
            git_clone($dir, 'to-legacy');
        }
        else {
            git_gotomaster($dir."to-legacy");
        }
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
        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
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

        execshell($dir."scaffolder/to-api/", "rsync -rR * ".$dir."to-api/");

        replaceindomain($db, $replacement, $dir."to-api/jsons/domains.json");         

        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
    }
    function do_legacy($db, $replacement, $idexec) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tolegacy($dir);
        $currentgit = $dir."to-legacy";

        execshell($dir."scaffolder/to-legacy/", "rsync -rR * ".$dir."to-legacy/");

        replaceindomain($db, $replacement, $dir."to-legacy/jsons/domains.json");         

        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
    }
    function do_site($db, $replacement, $idexec) {
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tosite($dir);
        $currentgit = $dir."to-site";

        execshell($dir."scaffolder/to-site/", "rsync -rR * ".$dir."to-site/");

        replaceindomain($db, $replacement, $dir."to-site/src/jsons/domains.json");         

        git_createbranch_push($currentgit, $db["uniquename"], $idexec);
        git_mergetomaster($currentgit, $db["uniquename"], $idexec);
    }
    function doall($id) {
        $idexec = date("Ymdhis");
        $db = getinfofromdb($id);
        $replacement = getReplacement($db);

        doScaffolder($db, $replacement, $idexec);
       // do_toapi($db, $replacement, $idexec);
       // do_legacy($db, $replacement, $idexec);
        do_site($db, $replacement, $idexec);

        echo json_encode(array("success"=>true, "msg"=>"Criado com sucesso."));
        logme();
        die();
    }

    //doall("1a0fdc45-e934-4c9e-bf4c-8fc8c11474a1");
?>