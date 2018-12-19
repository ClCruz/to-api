<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id, $name, $active, $dateStart, $dateEnd, $domain) {
        $dateStart = modifyDate($dateStart);
        $dateEnd = modifyDate($dateEnd);

        //sleep(5);
        if ($id == '')
        {
            $query = "EXEC pr_partner_save NULL, ?, ?, ?, ?, ?";
            $params = array($name, $active, $dateStart, db_param($dateEnd), $domain);
        }
        else {
            $query = "EXEC pr_partner_save ?, ?, ?, ?, ?, ?";
            $params = array(db_param($id), $name, $active, $dateStart, db_param($dateEnd), $domain);
        }
        //die("aaa.".print_r(db_param($startAt),true));
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id"], $_POST["name"], $_POST["active"], $_POST["dateStart"], $_POST["dateEnd"], $_POST["domain"]);
?>