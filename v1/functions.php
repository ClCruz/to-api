<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/mobiledetect/Mobile_Detect.php");

$profilerPerfTimer = array();
$performance = true;
$log = true;

function getDefaultCardImageName() {
    return "card.jpg";
}
function getDefaultMap() {
    return "https://media.tixs.me/palco.png";
}
function getDefaultMediaHost() {
    //return "http://localhost:1003";
    return "https://media.tixs.me";
}
function purchaseMinutesToExpireReservation() {
    return 15;
}
function createTimer($name, $info) {
    global $profilerPerfTimer;
    $before = microtime(true);
    $aux = array("name"=>$name, "info"=>$info, "type"=>"start", "timer"=>$before);
    array_push($profilerPerfTimer,$aux);
}
function performance() {
    global $profilerPerfTimer;
    global $performance;

    if (!$performance)
        return;

    $query = "EXEC pr_performance_trace ?, ?, ?";
    $params = array(json_encode($profilerPerfTimer), json_encode($_REQUEST), json_encode($_POST));
    $result = db_exec($query, $params);
}
function sendonemail($from, $fromName, $to, $toName, $subject, $msg) {	
    if (getwhitelabelemail()["config"]["type"] != "api") {
        return false;
    }
    $apiuser = getwhitelabelemail()["config"]["api"]["login"];
	$apikey = getwhitelabelemail()["config"]["api"]["apikey"];
	$url = getwhitelabelemail()["config"]["api"]["uri"];
	$fields = array(
		'api_user' => urlencode($apiuser),
		'api_key' => urlencode($apikey),
		'from' => urlencode($from),
		'fromname' => urlencode($fromName),
		'to' => urlencode($to),
		'toname' => urlencode($toName),
		'subject' => urlencode($subject),
		'html' => urlencode($msg),
	);

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);
	//close connection
	curl_close($ch);

	return $result;
}
function logme() {
    global $log;

    if (!$log)
        return;

    $uri = $_SERVER["REQUEST_URI"];
    $file = $_SERVER["PHP_SELF"];
    $start = $_SERVER["REQUEST_TIME"];
    $agent = $_SERVER["HTTP_USER_AGENT"];
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    $ip2 = $_SERVER['REMOTE_ADDR'];
    $host = $_SERVER['HTTP_HOST'];

    $end = time();

    $duration = $end-$start;
    $hours = (int)($duration/60/60);
    $minutes = (int)($duration/60)-$hours*60;
    $seconds = (int)$duration-$hours*60*60-$minutes*60;

    $detect = new Mobile_Detect;
    $ismobile = $detect->isMobile();
    $isandroid = $detect->isAndroidOS();
    $isios = $detect->isiOS();

    $query = "EXEC pr_logme ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?";
    $params = array($uri, $file, json_encode($_REQUEST), json_encode($_POST), $start,$end, $seconds, $ismobile, $isandroid, $isios, json_encode($agent), json_encode($ip), json_encode($ip2),$host);
    $result = db_exec($query, $params);
}
function modifyDate($value) {
    if ($value!=null && $value!='') {
        $aux = explode("/", $value);
        $ret = $aux[2].'/'.$aux[1].'/'.$aux[0];
        return $ret;
    }
    return null;
}
?>