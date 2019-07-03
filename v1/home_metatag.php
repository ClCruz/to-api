<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");

    function get($uniquename) {
        $query = "EXEC pr_generate_link_for_home ?";
        $params = array($uniquename);
        $result = db_exec($query, $params);

        $text = "";

        foreach ($result as &$row) {
            if ($text != "")
                $text .= "<br />";
            $text .= $row["result"];
        }
        logme();

        return $text;
    }

    $id = $_REQUEST["id"];
    $params = explode("/",$id);

    $uniquename = getuniquefromdomainforced($_REQUEST["host"]);
    $replacement = getonlyReplacement($uniquename);
    $logo = "";
    $fb_appid = "";

    foreach($replacement as $tocheck){
        if (strpos("__wl-site-logo-media-facebook__", $tocheck["from"])  !== false) {
            $logo = $tocheck["to"];
        }
        if (strpos("__wl-fb_appid__", $tocheck["from"])  !== false) {
            $fb_appid = $tocheck["to"];
        }
    }

    $title = getwhitelabelobjforced($uniquename)["info"]["title"];
    $appName = getwhitelabelobjforced($uniquename)["host"];
    $name = getwhitelabelobjforced($uniquename)["host"];
    $host = getwhitelabelobjforced($uniquename)["uri"];
    $description = getwhitelabelobjforced($uniquename)["meta"]["description"];
    $keyword = getwhitelabelobjforced($uniquename)["meta"]["keywords"];
    $width = "493";
    $height = "498";
    logme();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php $title?></title>
    
    <meta name="application-name" content="<?php echo $appName?>" />
    <meta name="description" content="<?php echo $description?>" />
    <meta name="keywords" content="<?php echo $keyword?>" />
<?php if ($fb_appid!='') {
    echo '<meta property="fb:app_id" content="'.$fb_appid.'" />';
} ?>
    

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo $name?>">
    <meta itemprop="description" content="<?php echo $description?>">


    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $name?>">
    <meta name="twitter:description" content="<?php echo $description?>">
    <meta name="twitter:image" content="<?php echo $logo?>">
    <meta name="twitter:image:alt" content="<?php echo $name?>">

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $name?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $host?>" />
    <meta property="og:description" content="<?php echo $description?>" />
    <meta property="og:site_name" content="<?php echo $name?>" />

    <meta property="og:image" content="<?php echo $logo?>" />
    <!--meta property="og:image:secure_url" content=" echo $obj["img"]" /-->

</head>
<body>
    <h1><?php echo $title ?></h1>
    <h2><?php echo $description?></h2>
    <?php echo get($uniquename) ?>
</body>
</html>