<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/mobiledetect/Mobile_Detect.php");

$profilerPerfTimer = array();
$performance = true;
$log = true;

function getDefaultCardImageName() {
    return "card.jpg";
}
function getOriginalCardImageName() {
    return "original.jpg";
}
function getBigCardImageName() {
    return "big.jpg";
}
function getDefaultMap() {
    return "https://media.tixs.me/palco.png";
}
function getDefaultMediaHost() {
    //return "http://localhost:2003";
    return "https://media.tixs.me";
}
function purchaseMinutesToExpireReservation() {
    return 15;
}
function stopIfApiNotPresent() {
    if (!array_key_exists("apikey", $_REQUEST) && !array_key_exists("apikey", $_POST)) {
        die(json_encode(array("error"=>true, "msg"=>"api key is not present")));
    }
}
function stopIfApiNotExist() {
    stopIfApiNotPresent();
    $query = "EXEC pr_apikey ?";
    $api = $_REQUEST["apikey"];
    if ($api == "" || $api == null) {
        $api = $_POST["apikey"];
    }
    if ($api == "")
        die(json_encode(array("error"=>true, "msg"=>"api key is not present")));

    $params = array($api);
    $result = db_exec($query, $params);

    $json = array("error"=>true, "msg"=> "api key is not valid.");

    foreach ($result as &$row) {
        if ($row["success"] == true) {
            return;
        }
    }
    die(json_encode($json));
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
function get_id_base_from_id_evento($id_evento) {
    $query = "EXEC pr_admin_event_getbase ??";
    $params = array($id_evento);
    $result = db_exec($query, $params);

    $id_base = 0;
    foreach ($result as &$row) {
        $id_base = $row["id_base"];
    }

    return $id_base;
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
    $ip = "";

    if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }

    $ip2 = $_SERVER['REMOTE_ADDR'];
    $host = $_SERVER['HTTP_HOST'];


    $HTTP_ORIGIN = '';
    if (array_key_exists("HTTP_ORIGIN", $_SERVER)) {
        $HTTP_ORIGIN = $_SERVER['HTTP_ORIGIN'];
    }
    $HTTP_REFERER = '';
    if (array_key_exists("HTTP_REFERER", $_SERVER)) {
        $HTTP_REFERER = $_SERVER['HTTP_REFERER'];
    }

    $end = time();

    $duration = $end-$start;
    $hours = (int)($duration/60/60);
    $minutes = (int)($duration/60)-$hours*60;
    $seconds = (int)$duration-$hours*60*60-$minutes*60;

    $detect = new Mobile_Detect;
    $ismobile = $detect->isMobile();
    $isandroid = $detect->isAndroidOS();
    $isios = $detect->isiOS();

    $query = "EXEC pr_logme ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?";
    $params = array($uri, $file, json_encode($_REQUEST), json_encode($_POST), $start,$end, $seconds, $ismobile, $isandroid, $isios, json_encode($agent), json_encode($ip), json_encode($ip2),$host, $HTTP_ORIGIN, $HTTP_REFERER);
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
function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++)
    {
        if($mask[$i] == '#')
        {
            if(isset($val[$k]))
            $maskared .= $val[$k++];
        }
        else
        {
            if(isset($mask[$i]))
            $maskared .= $mask[$i];
        }
    }
    return $maskared;
}
function isuservalidordie($id) {
    if (isuservalid($id) == false) {
        $json = array("success"=>false
                    ,"msg"=>"Login invalido.");
        echo json_encode($json);
        logme();
        die();    
    }
}
function isuservalid($id) {
    if ($id == '' || $id == null)
        return false;

    $query = "EXEC pr_admin_user_isvalid ?";
    $params = array($id);
    $result = db_exec($query, $params);
    $json = array();
    foreach ($result as &$row) {
        $json = array(
            "hasuser" => $row["hasuser"]
            ,"valid" => $row["valid"]
        );
    }
    return $json["valid"] == 1 && $json["hasuser"] == 1;
}
function userrevalid($id) {
    if ($id == '' || $id == null)
        return;

    $query = "EXEC pr_admin_user_revalid ?";
    $params = array($id);
    $result = db_exec($query, $params);
}
?>