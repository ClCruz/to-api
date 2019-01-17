<?php

function db_exec($sql, $params = array(), $id_base = null, $cnn = null, $loopthrough = false, $timeout = 30) {
    //die("db_exec.");
    if ($cnn == null)
    {
        $cnn = cnn($id_base);
        $loopthrough = true;
    }
    ini_set("max_execution_time", $timeout);

    $result = sqlsrv_query($cnn, $sql, $params, array("QueryTimeout" => $timeout));

    cnnCheckExecutionError($result);
    //die("db_exec.. ".print_r($result,true));
    if ($loopthrough) {
        $ret = array();
        while ($rs = db_fetch($result)) {
            //die("db_exec.. ".print_r($rs,true));
            array_push($ret,$rs);
            //$ret = array($rs);
        }
        return $ret;
    }
    else {
        return $result;
    }
}

function db_param3($param) {
    if (isset($param) && $param != 'null' && $param != 'NULL') 
        return $param;
    else
        return "";
}
function db_param2($param) {
    if (isset($param)) 
        return $param;
    else
        return "null";
}

function db_paramid($param) {
    if (isset($param) && $param != 'null' && $param != 'NULL' && $param != '') 
        return $param;
    else
        return "00000000-0000-0000-0000-000000000000";
}
function db_param($param) {
    if (isset($param)) 
        if ($param != "null")
            return $param;
        else
            return null;
    else
        return null;
}

function db_fetch($result, $fetchType = SQLSRV_FETCH_BOTH) {
    return sqlsrv_fetch_array($result, $fetchType);
}
?>