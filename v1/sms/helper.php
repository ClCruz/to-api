<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function sms_sendnow($id_cliente, $id_to_admin_user, $type, $cellphone, $content) {
        $id = sms_insert($id_cliente, $id_to_admin_user, $type, $cellphone, $content);
        sms_sendToAPI($cellphone, $content, $id);
        return $id;
    }

    function sms_sendToAPI($cellphone, $content, $id) {	

        try {
            $metodo = "envio";
            $usuario = "leonel.costa@ticketoffice.com.br";
            $pass = "397982";

            $url = 'http://www.iagentesms.com.br/webservices/http.php';
            $fields = array(
                'metodo' => urlencode($metodo),
                'usuario' => urlencode($usuario),
                'senha' => urlencode($pass),
                'celular' => urlencode($cellphone),
                'mensagem' => urlencode($content),
                'codigosms' => urlencode($id),
            );
            $fields_string = "";
            echo json_encode($fields);
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

            echo json_encode($result);
        
            return $result;
    
        } catch (Exception $e) {
            return "";
    
        }
    
    }


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