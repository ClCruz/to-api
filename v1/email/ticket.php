<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    
    function ticket($code) {
        $info = getcodvenda($code);

        $_REQUEST["id_base"] = $info["id_base"];
        $_REQUEST["id"] = $info["codVenda"];
        $_REQUEST["indice"] = '';
        $_REQUEST["dontbreakline"] = 'true';

        require_once($_SERVER['DOCUMENT_ROOT']."/v1/print/ticket.php");
//        logme();
//        die();
    }
    function getcodvenda($code) {
        $query = "EXEC pr_ticketoffice_email_ticket_print_setseen ?";
        $params = array($code);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id"=>$row["id"]
                ,"codVenda"=>$row["codVenda"]
                ,"id_base"=>$row["id_base"]
            );
        }

        logme();

        return $json;
    }
    
    ticket($_REQUEST["code"]);
?>