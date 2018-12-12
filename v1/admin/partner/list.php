<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get() {
        //sleep(5);
        $query = "EXEC pr_partner_list";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array();
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id" => $row["id"]
                ,"created" => $row["created"]
                ,"key" => $row["key"]
                ,"key_test" => $row["key_test"]
                ,"name" => $row["name"]
                ,"active" => $row["active"]
                ,"dateStart" => $row["dateStart"]
                ,"dateEnd" => $row["dateEnd"]
                ,"showKey" => false
                ,"showKeyTest"=> false
                ,"domain" => $row["domain"]
                ,"text"=>$row["name"].($row["active"]==1 ? '' : ' (Inativo)')
                ,"value"=>$row["id"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get();
?>