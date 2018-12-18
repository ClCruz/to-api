<?php
//die("dd");
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