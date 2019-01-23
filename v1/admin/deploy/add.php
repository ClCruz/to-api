<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/git_functions.php");

    function getdatabases() {
        $query = "EXEC pr_admin_partner_database_list";
        $params = array();
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            array_push($json, $row["uniquename"]);
        }

        return $json;
    }

    function deploy_database($id_user) {
        isuservalidordie($id_user);
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_todatabase($dir);
        $currentgit = $dir."to-database";
        $jsonFile = $currentgit."/jsons/database.json";

        $aux = json_decode(file_get_contents($jsonFile), true);
        $fromdb = getdatabases();        

        if (sizeof($fromdb) != sizeof($aux["names"])) {
            $isdiff = true;
        }

        if (!$isdiff) {
            foreach ($fromdb as &$db) {
                $found = false;
                foreach ($aux["names"] as &$memory) {
                    if ($db == $memory) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $isdiff = true;
                    break;
                }
            }
        }
        if (!$isdiff) {
            foreach ($aux["names"] as &$memory) {
                $found = false;
                foreach ($fromdb as &$db) {
                    if ($db == $memory) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $isdiff = true;
                    break;
                }
            }
        }
        if ($isdiff) {
            $aux["names"] = $fromdb;
            $content = json_encode($aux,JSON_PRETTY_PRINT);

            file_put_contents($jsonFile, $content);
        }
        
        execshell($currentgit, "echo >> dodeploy", false);
        execshell($currentgit, 'echo "'.date("Ymdhis").'" >> dodeploy', false);

        //production
        if ($isdiff) {
            git_push_tomaster($currentgit);
        }
        else {
            git_dodeploy($currentgit);
        }
        //dev
        //git_reset($currentgit);
    }
    function replaceindomain($db, $replacement, $jsonFile) {
        $site = str_replace("https://", "", getFromReplacement($replacement, "__wl-sitewithwww__"));
        $api = str_replace("https://", "", getFromReplacement($replacement, "__wl-siteapi__"));
        $legacy = str_replace("https://", "", getFromReplacement($replacement, "__wl-sitecompra__"));

        $aux[$site] = $db["uniquename"];
        $aux[$api] = $db["uniquename"];
        $aux[$legacy] = $db["uniquename"];

        $content = json_encode($aux,JSON_PRETTY_PRINT);

        file_put_contents($jsonFile, $content);
    }


    function deploy_toapi($id_user) {
        isuservalidordie($id_user);
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_toapi($dir);
        $currentgit = $dir."to-api";

        execshell($currentgit, "echo >> dodeploy", false);
        execshell($currentgit, 'echo "'.date("Ymdhis").'" >> dodeploy', false);

        //production
        git_dodeploy($currentgit);
        //dev
        //git_reset($currentgit);
    }
    function deploy_legacy($id_user) {
        isuservalidordie($id_user);
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tolegacy($dir);
        $currentgit = $dir."to-legacy";

        execshell($currentgit, "echo >> dodeploy", false);
        execshell($currentgit, 'echo "'.date("Ymdhis").'" >> dodeploy',false);

        //production
        git_dodeploy($currentgit);
        //dev
        //git_reset($currentgit);
    }
    function deploy_site($id_user) {
        isuservalidordie($id_user);
        $doecho = false;
        $dir = '/var/www/gitauto/';
        git_clone_tosite($dir);
        $currentgit = $dir."to-site";

        execshell($currentgit, "echo >> dodeploy", false);
        execshell($currentgit, 'echo "'.date("Ymdhis").'" >> dodeploy', false);

        //production
        git_dodeploy($currentgit);
        //dev
        //git_reset($currentgit);
    }

    function dodeploy($who, $id_user) {
        switch ($who) {
            case 1: 
                deploy_database($id_user);
            break;
            case 2:
                deploy_site($id_user);
            break;
            case 3:
                deploy_toapi($id_user);
            break;
            case 4:
                deploy_legacy($id_user);
            break;
            case 5:
                //deploy_admin($id_user);
            break;
        }

        $json = array();

        $json = array("success"=>true
                    ,"msg"=>"Deploy realizado com sucesso");

        echo json_encode($json);
        logme();
        die();    
  }
dodeploy($_POST["who"], $_POST["id_user"]);
?>