<?php
require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

function getDatabaseNameFromDB($id) {
	$query = "EXEC pr_base_get ?";
	$params = array($id);
	$result = db_exec($query, $params);

	$name = '';

	foreach ($result as &$row) {
		$name = $row["ds_nome_base_sql"];
	}
	return $name;
}

function getDatabaseName($id_base) {
	$jsonFile = $_SERVER['DOCUMENT_ROOT']."/jsons/bases/bases.json";

	if (!file_exists($jsonFile)) {
		die("Falha de configuração no JSON de bases.");
	}

	$aux = json_decode(file_get_contents($jsonFile), true);

	$ret = '';

    if ($id_base == null) { 
		$ret = $aux["default"];
	}
	else {
		//$id_base_h = (string)$id_base;
		$ret = $aux[$id_base_h];
	}

	if ($ret == '')
	{
		$ret = getDatabaseNameFromDB($id_base);
	}

	return $ret;
}
?>