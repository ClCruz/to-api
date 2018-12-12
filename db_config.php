<?php
require_once($_SERVER['DOCUMENT_ROOT']."/v1/config/whitelabel.php");
require_once($_SERVER['DOCUMENT_ROOT']."/db_configdatabases.php");

function cnn($id = null) {    
	$host = getwhitelabeldb()["host"];
	$port = getwhitelabeldb()["port"];
    $database = getDatabaseName($id);
    $user = getwhitelabeldb()["user"];
	$pass = getwhitelabeldb()["pass"];

	$more = array("UID" => $user, "PWD" => $pass, "Database" => $database,'CharacterSet' => 'UTF-8');
	$cnn = sqlsrv_connect($host.','.$port, $more);
    cnnCheckConnectionError($cnn);
    return $cnn;
}

function cnnCheckConnectionError($conn) {
	if ($conn === false) {
		$ret = array("error"=>true, "message"=>"Connection fail.");
		die(json_encode($ret));   
		//die( print_r( sqlsrv_errors(), true));
   }
}
function cnnCheckExecutionError($stmt) {
	if ($stmt === false) {
		if( ($errors = sqlsrv_errors() ) != null) {
			$helper = "";
			foreach( $errors as $error ) {
				$helper = "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				$helper .= "code: ".$error[ 'code']."<br />";
				$helper .= "message: ".$error[ 'message']."<br />";
			}
			$ret = array("error"=>true, "message"=>"Execution fail. " . $helper);
			die(json_encode($ret));   	
		}
	}
}
?>