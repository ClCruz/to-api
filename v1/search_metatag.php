<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    $id = $_REQUEST["id"];
    $params = explode("/",$id);


    $title = getwhitelabel("title");
    $appName = getwhitelabel("appName");
    $name = getwhitelabel("appName");
    $host = getwhitelabel("uri");
    $description = getwhitelabel("meta_description");
    $keyword = getwhitelabel("meta_keywords");
    $logo = getwhitelabel("logo");
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
    <!--meta property="og:image:secure_url" content="https://secure.example.com/ogp.jpg" /--> 
    <meta property="og:image:type" content="image/jpeg" /> 
    <meta property="og:image:alt" content="<?php echo $name?>" />
    <!--meta property="og:image:secure_url" content=" echo $obj["img"]" /-->

</head>
<body>
    
</body>
</html>