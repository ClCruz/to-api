<?php
require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

echo sendToSMTP("noreply@ciadeingressos.com", "noreply", "blcoccaro@gmail.com", "blcoccaro", "this is smtp", "Bacon ipsum dolor amet venison pig chicken, sirloin kevin porchetta tri-tip pork t-bone salami pork loin pastrami ham boudin. Pig boudin pork, venison spare ribs strip steak hamburger jowl ribeye meatloaf ball tip shankle short ribs bacon. Hamburger kielbasa pastrami t-bone, meatball tenderloin chuck pork. Tri-tip salami short ribs landjaeger shankle ground round. Pork loin cow ham hock pancetta, alcatra t-bone drumstick short loin pig ball tip.");

echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";

echo "----------------------------------------";
echo "<br />";

echo sendToAPI("noreply@ciadeingressos.com", "noreply", "blcoccaro@gmail.com", "blcoccaro", "this is api", "Bacon ipsum dolor amet venison pig chicken, sirloin kevin porchetta tri-tip pork t-bone salami pork loin pastrami ham boudin. Pig boudin pork, venison spare ribs strip steak hamburger jowl ribeye meatloaf ball tip shankle short ribs bacon. Hamburger kielbasa pastrami t-bone, meatball tenderloin chuck pork. Tri-tip salami short ribs landjaeger shankle ground round. Pork loin cow ham hock pancetta, alcatra t-bone drumstick short loin pig ball tip.");

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