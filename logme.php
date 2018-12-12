<?php
$aux = $_REQUEST["json"];

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