<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($loggedId, $id_base, $id_evento) {
        //sleep(5);
        $query = "EXEC pr_tickettype_event_list ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodPeca" => $row["CodPeca"]
                ,"CodTipBilhete" => $row["CodTipBilhete"]
                ,"DatIniDesconto" => $row["DatIniDesconto"]
                ,"DatFinDesconto" => $row["DatFinDesconto"]
                ,"TipBilhete" => $row["TipBilhete"]
                ,"nameTicketOffice" => $row["nameTicketOffice"]
                ,"nameWeb" => $row["nameWeb"]
                ,"isAllotment" => $row["isAllotment"]
                ,"isDiscount" => $row["isDiscount"]
                ,"isFixed" => $row["isFixed"]
                ,"isHalf" => $row["isHalf"]
                ,"isPlus" => $row["isPlus"]
                ,"isPrincipal" => $row["isPrincipal"]
                ,"allowweb" => $row["allowweb"]
                ,"allowticketoffice" => $row["allowticketoffice"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"vl_preco_fixo" => $row["vl_preco_fixo"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["id_base"],$_REQUEST["id_evento"]);
?>