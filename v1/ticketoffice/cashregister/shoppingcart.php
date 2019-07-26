<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id) {
        $query = "EXEC pr_ticketoffice_shoppingcart_result ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        $isValid = false;
        foreach ($result as &$row) {
            $aux = array("id_apresentacao"=>$row["id_apresentacao"]
                    ,"indice"=>$row["indice"]
                    ,"id_event"=>$row["id_event"]
                    ,"id_base"=>$row["id_base"]
                    ,"amount"=>$row["amount"]
                    ,"amount_discount"=>$row["amount_discount"]
                    ,"amount_topay"=>$row["amount_topay"]
                    ,"created"=>$row["created"]
                    ,"quantity"=>$row["quantity"]
                    ,"id_payment_type"=>$row["id_payment_type"]
                    ,"id_ticket_type"=>$row["id_ticket_type"]
                    ,"NomObjeto"=>$row["NomObjeto"]
                    ,"NomSetor"=>$row["NomSetor"]
                    ,"PerDesconto"=>str_replace('.00', '', (string)$row["PerDesconto"])
                    ,"PerDescontoTipBilhete"=>str_replace('.00', '', (string)$row["PerDescontoTipBilhete"])
                    ,"TipBilhete"=>$row["TipBilhete"]
                    ,"StaCadeira"=>$row["StaCadeira"]
                    ,"amountSubTotalSector"=>$row["amountSubTotalSector"]
                    ,"amountSubTotalTicket"=>$row["amountSubTotalTicket"]
                    ,"isFixedAmount"=>$row["isFixedAmount"]
                    ,"in_obriga_cpf"=>$row["in_obriga_cpf"]
                    ,"in_obriga_cartao"=>$row["in_obriga_cartao"]
                    ,"valid"=>$row["valid"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id_base"], $_REQUEST["id"]);
?>