<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($key, $api) {
        $query = "EXEC pr_event_meta ?";
        $params = array($key);
        $result = db_exec($query, $params);

        $codPeca = 0;
        $id_evento = 0;
        $codPeca = 0;

        foreach ($result as &$row) {
            $id_evento = $row["id_evento"];
            $id_base = $row["id_base"];
            $codPeca = $row["CodPeca"];
        }
        //die("ddd".print_r($result,true));
        if ($id_base == 0 && $codPeca == 0) {
            echo json_encode(array("error"=>true, "msg"=>"Não foi possível achar o evento."));    
            die();
        }

        $query = "EXEC pr_event_bybase ?";
        $params = array($codPeca);
        $result = db_exec($query, $params, $id_base);
        //die("oi".print_r($result,true));

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "CodPeca" => $row["CodPeca"],
                "NomPeca" => $row["NomPeca"],
                "ds_evento" => $row["ds_evento"],
                "CodTipPeca" => $row["CodTipPeca"],
                "TipPeca" => $row["TipPeca"],
                "CenPeca" => parentalRating($row["CenPeca"]),
                "ds_local_evento" => $row["ds_local_evento"],
                "ds_nome_teatro" => $row["ds_nome_teatro"],
                "address" => $row["address"],
                "valores" => $row["valores"],
                "id_evento" => $row["id_evento"],
                "description" => $row["description"],
                "cardimage" => $row["cardimage"],
                "id_base" => $row["id_base"],
                "meta_keyword" => $row["meta_keyword"],
                "meta_description" => $row["meta_description"],
                "img" => getDefaultMediaHost() . str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
            );
        }

        logme();

        return $json;
    }
    function parentalRating($value) {
        $ret = "";

        if ($value<10) {
            $ret = "L";
        }
        else if ($value<12) {
            $ret = "10";
        }
        else if ($value<14) {
            $ret = "12";
        }
        else if ($value<16) {
            $ret = "14";
        }
        else if ($value<18) {
            $ret = "16";
        }
        else {
            $ret = "18";
        }

        return $ret;
    }
    $obj = get($_REQUEST["id"]);
    $title = getwhitelabel("title");
    $appName = getwhitelabel("appName");
    $name = getwhitelabel("appName");
    $host = getwhitelabel("uri")."/evento/";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo $obj["NomPeca"]." - ".$obj["ds_local_evento"]." - ".$obj["TipPeca"].' | '.$title?></title>
    
    <meta name="application-name" content="<?php echo $appName?>" />
    <meta name="description" content="<?php echo $obj["meta_description"]?>" />
    <meta name="keywords" content="<?php echo $obj["meta_keyword"]?>" />

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo $obj["NomPeca"]?>">
    <meta itemprop="description" content="<?php echo $obj["meta_description"]?>">


    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $obj["NomPeca"]?>">
    <meta name="twitter:description" content="<?php echo $obj["meta_description"]?>">
    <meta name="twitter:image" content="<?php echo $obj["img"]?>">
    <meta name="twitter:image:alt" content="<?php echo $obj["NomPeca"]?>">

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $obj["NomPeca"]?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $host.$_REQUEST["id"]?>" />
    <meta property="og:description" content="<?php echo $obj["meta_description"]?>" />
    <meta property="og:site_name" content="<?php echo $name?>" />

    <meta property="og:image" content="<?php echo $obj["img"]?>" />
    <!--meta property="og:image:secure_url" content="https://secure.example.com/ogp.jpg" /--> 
    <meta property="og:image:type" content="image/jpeg" /> 
    <meta property="og:image:alt" content="<?php echo $obj["NomPeca"]?>" />
    <!--meta property="og:image:secure_url" content=" echo $obj["img"]" /-->

</head>
<body>
    
</body>
</html>