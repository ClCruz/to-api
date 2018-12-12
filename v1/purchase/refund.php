<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/config/pagarme.php");

    function call($id_base, $id_ticketoffice_user, $codVenda, $all, $indiceList, $dogateway) {
        $query = "EXEC pr_refund ?, ?, ?, ?";
        $params = array($id_ticketoffice_user, $codVenda, $all, $indiceList);
        $result = db_exec($query, $params, $id_base);

        $aux = array();
        foreach ($result as &$row) {
            $aux = array("key"=>$row["key"]
            ,"amount"=>$row["amount"]);

            //if ($dogateway == 1)
                refundPagarme($aux["key"], $aux["amount"]);
            
            array_push($json,$aux);
        }

        $json = array("success"=>true);

        echo json_encode($json);
        logme();
        die();    
    }

    function refundPagarme($id, $amount) {
        $conf = getConfigPagarme();

        $url = $conf["apiURI"]."transactions/".$id."/refund";

        $post_data = "";

        if ($amount == 0)
        {
            $data = array("api_key" => $conf["apikey"]);
            $post_data = "{ \"api_key\":\"".$conf["apikey"]."\" }";
        }
        else {
            $data = array("api_key" => $conf["apikey"], "amount" => $amount);
            $post_data = json_encode($data);     
        }

        $ch = curl_init($url); 
        //curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($post_data))                                                                       
        );             
        $response = curl_exec($ch);
        //$errno = curl_errno($ch);

        $aux = json_decode($response);

        curl_close($ch);

        return isset($aux["refunded_amount"]);
    }
//    refundPagarme($_REQUEST["id"], $_REQUEST["amount"]);
call($_REQUEST["id_base"],$_REQUEST["id_ticketoffice_user"], $_REQUEST["codVenda"], $_REQUEST["all"], $_REQUEST["indiceList"], $_REQUEST["dogateway"]);
?>