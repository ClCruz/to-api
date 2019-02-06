<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($uniquename) {
        $query = "EXEC pr_generate_sitemap ?";
        $params = array($uniquename);
        $result = db_exec($query, $params);

        $text = "";

        foreach ($result as &$row) {
            if ($text != "")
                $text .= PHP_EOL;
            $text .= $row["result"];
        }
        logme();

        return $text;
    }
    $uniquename = $_REQUEST["uniquename"];
    touch("/var/www/site/sitemap".$uniquename.".xml");
    $myfile = fopen("/var/www/site/sitemap".$uniquename.".xml", "w") or die("Unable to open file!");
    $txt = get($uniquename);
    fwrite($myfile, $txt);
    fclose($myfile);
    echo "generated sitemap.";
    die("");
?>
