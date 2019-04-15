<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function printtoxml($item, $key, $xml)
    {
        //$child = $xml->addChild("item",$key);
        $child = $xml->addChild('item');
        array_walk($item, 'printtoxml2', $child);
    }
    function printtoxml2($item, $key, $xml)
    {
        $xml->addAttribute($key, $item);
        //$xml->addChild($key,$item);
    }

    function execute($data) {
        //die(json_encode($data));
        $xml = new SimpleXMLElement('<root/>');
        array_walk($data, 'printtoxml', $xml);
        //array_walk_recursive($data, array ($xml, 'addChild'));
        //print $xml->asXML();
        //die();

        $query = "EXEC pr_admin_presentation_add ?";
        $params = array($xml->asXML());
        //die("aaa.".print_r(db_param($startAt),true));
        $result = db_exec($query, $params);

        $json = array("success"=>true);
        foreach ($result as &$row) {
            // $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
            //             ,"msg"=>$row["msg"]);
        }

        echo json_encode($json);
        logme();
        die();      
    }

execute($_POST["data"]);
?>