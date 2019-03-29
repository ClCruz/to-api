<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

function teste($name,$code) { 
    $templatefolder = $_SERVER['DOCUMENT_ROOT'].$templatefolder = getwhitelabelobj()["templates"]["emails"]["folder"];

    $replacement = getonlyReplacement(gethost());
    
    $wlsite = "";
    $wlsitewithwww = "";
    $wlsitelogomedia = "";
    $wluniquename = "";
    $wlsitewithoutwww = "";

    
    foreach($replacement as $tocheck){
        if (strpos("__wl-site__", $tocheck["from"])  !== false) {
            $wlsite = $tocheck["to"];
        }
        if (strpos("__wl-sitewithwww__", $tocheck["from"])  !== false) {
            $wlsitewithwww = $tocheck["to"];
        }
        if (strpos("__wl-site-logo-media__", $tocheck["from"])  !== false) {
            $wlsitelogomedia = $tocheck["to"];
        }
        if (strpos("__wl-uniquename__", $tocheck["from"])  !== false) {
            $wluniquename = $tocheck["to"];
        }
        if (strpos("__wl-sitewithoutwww__", $tocheck["from"])  !== false) {
            $wlsitewithoutwww = $tocheck["to"];
        }
    }
    // die(json_encode($wlsitewithwww));

    $loader = new Twig_Loader_Filesystem($templatefolder);
    $twig = new Twig_Environment($loader);
    $htmlname = "resetpass.html";
    return $twig->render($htmlname, [
                                        "wlsite" => $wlsite,
                                        "wlsitewithwww" => $wlsitewithwww,
                                        "wlsitelogomedia" => $wlsitelogomedia,
                                        "wluniquename" => $wluniquename,
                                        "wlsitewithoutwww" => $wlsitewithoutwww,
                                        "user_name" => $name,
                                        "reset_link" => getwhitelabelURI_home("/resetpass/".$code),
                                    ] );
}

echo mailToSMTP("noreply@aecloja.com.br", "noreply", "producao@amigosecia.com.br", "teste", "enviado via smtp", teste("Matt Murdock", "KKKDD"));

echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";

echo "----------------------------------------";
echo "<br />";

echo mailToAPI("noreply@aecloja.com.br", "noreply", "producao@amigosecia.com.br", "teste", "enviado via api", teste("Matt Murdock", "KKKDD"));

echo sendToAPI("noreply@aecloja.com.br", "noreply", "producao@amigosecia.com.br", "teste", "teste default", teste("Matt Murdock", "KKKDD"));

die("final.");


die("oi");
$aux = $_REQUEST["json"];

$passwordHash = hash('ripemd160', '123');
die("teste: ".$passwordHash);

echo json_encode($_SERVER);
echo "<br /><br /><br /><br />";

die("teste:".json_encode($_REQUEST));

$obj = json_decode($aux, true);

$last = null;

foreach ($obj as &$row) {
    $taux = "";
    if ($last != null) {
        $duration = $row["timer"]-$last;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;
        $taux = $seconds." seconds";
    }
    echo "<br />Name: ".$row["name"]." - info:".$row["info"]." - ".$taux;
    $last = $row["timer"];
}
die("");
?>