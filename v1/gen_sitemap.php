<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($api) {
        $query = "EXEC pr_generate_sitemap ?";
        $params = array($api);
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
    $myfile = fopen("/var/www/site/sitemap.xml", "w") or die("Unable to open file!");
    $txt = get($_REQUEST["apikey"]);
    fwrite($myfile, $txt);
    fclose($myfile);
    echo "generated sitemap.";
    die("");
?>
