<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

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
            case "to-database":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/to-database.git";
            break;
            case "to-admin":
                $urltoclone = "https://blcoccaro:JweUqRJFkSqdKTjvyzct@bitbucket.org/intuitisolucoestecnologicas/to-admin.git";
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
    function git_dodeploy($dir) {
        git_config($dir);
        execshell($dir, 'git add -A dodeploy && git commit -m "'.date("d-m-Y h:i:s").' <> [[automatically generated]] - dodeploy" && git push', false);
    }
    function git_push_tomaster($dir) {
        git_config($dir);
        execshell($dir, 'git add -A . && git commit -m "'.date("d-m-Y h:i:s").' <> [[automatically generated]] - dodeploy" && git push', false);
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
    function git_clone_todatabase($dir) {
        if (!file_exists($dir."to-database")) {
            git_clone($dir, 'to-database');
        }
        else {
            git_gotomaster($dir."to-database");
        }
    }
    function git_clone_toadmin($dir) {
        if (!file_exists($dir."to-admin")) {
            git_clone($dir, 'to-admin');
        }
        else {
            git_gotomaster($dir."to-admin");
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
?>