<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    $count = 0;

    function get($uniquename) {
        global $count;
        $query = "EXEC pr_generate_sitemap ?";
        $params = array($uniquename);
        $result = db_exec($query, $params);

        $text = "";


        $count = 0;
        foreach ($result as &$row) {
            $count = $count+1;
            if ($text != "")
                $text .= PHP_EOL;
            $text .= $row["result"];
        }
        // logme();

        return $text;
    }
    $ret = array("total"=>$count, "success"=> 1, "msg"=>"");
    $uniquename = $_REQUEST["uniquename"];
    touch("/var/www/site/sitemap_".$uniquename.".xml");
    $myfile = fopen("/var/www/site/sitemap_".$uniquename.".xml", "w") or $ret = array("total"=>$count, "success"=> 0, "msg"=> "Unable to open file!");
    if ($ret["success"] == 1) {
        $txt = get($uniquename);
        fwrite($myfile, $txt);
        fclose($myfile);
        $ret = array("total"=>$count, "success"=> 1, "msg"=>"created.");
        //echo "generated ".((string)$count)." sitemap.";
        //die("");
    
    }
    echo json_encode($ret);
    die();
?>
