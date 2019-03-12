<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");


    function sms_insert($id_cliente, $id_to_admin_user, $type, $cellphone, $content) {
        if ($id_to_admin_user == null) { 
            $id_to_admin_user = "00000000-0000-0000-0000-000000000000";
        }
        $query = "EXEC pr_sms_add ?,?,?,?, 'notsent', 0, NULL, ?";
        $params = array($id_cliente, $id_to_admin_user, $type, $cellphone, $content);
        $result = db_exec($query, $params);

        $id = -1;

        foreach ($result as &$row) {
            $id = $row["id"];
        }

        return $id;
    }

    function sms_sent($id) {
        sms_update($id, 'sent');
    }

    function sms_update($id, $status) {
        $query = "EXEC pr_sms_update ?,?";
        $params = array($id, $status);
        db_exec($query, $params);
    }

    function sms_response($id, $status, $data) {
        $query = "EXEC pr_sms_response ?,?,?";
        $params = array($id, $status, $data);
        db_exec($query, $params);
    }

?>