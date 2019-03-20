<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

    function get_purchase_email_b2b($id_pedido_venda) {
        $query = "EXEC pr_purchase_info_email_b2b ?,?";
        $params = array($id_pedido_venda, gethost());
        $result = db_exec($query, $params, getbasefromorder($id_pedido_venda));
        
        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "id_pedido_venda" => $row["id_pedido_venda"],
                "CodVenda" => $row["CodVenda"],
                "sell_email" => $row["sell_email"],
                "send_sell_email" => $row["send_sell_email"],
                "buyer_name" => $row["buyer_name"],
                "buyer_document" => documentformatBR($row["buyer_document"]),
                "buyer_email" => $row["buyer_email"],
                "event" => $row["event"],
                "event_fulldate" => $row["event_fulldate"],
                "event_room" => $row["event_room"],
                "tickettype" => $row["tickettype"],
                "amount" => $row["amount"],
                "service_charge" => $row["service_charge"],
                "amount_topay" => $row["amount_topay"],
                "purchase_amount" => $row["purchase_amount"],
                "paymenttype" => $row["paymenttype"],
                "installment" => $row["installment"],
                "buyer_cellphone" => $row["buyer_cellphone"],
                "buyer_phone" => $row["buyer_phone"],
            );
        }
        // die(json_encode($json));

        return $json;
    }
    
    function setHtml_purchase_email_b2b($obj) { 
        $templatefolder = $_SERVER['DOCUMENT_ROOT'].$templatefolder = getwhitelabelobj()["templates"]["emails"]["folder"];

        $replacement = getonlyReplacement(gethost());
        
        $wlsite = "";
        $wlsitewithwww = "";
        $wlsitelogomedia = "";
        $wluniquename = "";
        $wlsitewithoutwww = "";

        
        foreach($replacement as $tocheck){
            if (strpos("__wl-site__", $tocheck["from"])  !== false) {
                $wlsite = $tocheck["to"];
            }
            if (strpos("__wl-sitewithwww__", $tocheck["from"])  !== false) {
                $wlsitewithwww = $tocheck["to"];
            }
            if (strpos("__wl-site-logo-media__", $tocheck["from"])  !== false) {
                $wlsitelogomedia = $tocheck["to"];
            }
            if (strpos("__wl-uniquename__", $tocheck["from"])  !== false) {
                $wluniquename = $tocheck["to"];
            }
            if (strpos("__wl-sitewithoutwww__", $tocheck["from"])  !== false) {
                $wlsitewithoutwww = $tocheck["to"];
            }
        }
        // die(json_encode($wlsitewithwww));

        $loader = new Twig_Loader_Filesystem($templatefolder);
        $twig = new Twig_Environment($loader);
        $htmlname = "purchase_b2b.html";

        return $twig->render($htmlname, [
                                            "purchases" => $obj,
                                            "wlsite" => $wlsite,
                                            "wlsitewithwww" => $wlsitewithwww,
                                            "wlsitelogomedia" => $wlsitelogomedia,
                                            "wluniquename" => $wluniquename,
                                            "wlsitewithoutwww" => $wlsitewithoutwww,
                                            "id_pedido_venda" => $obj[0]["id_pedido_venda"],
                                            "CodVenda" => $obj[0]["CodVenda"],
                                            "buyer_name" => $obj[0]["buyer_name"],
                                            "buyer_document" => $obj[0]["buyer_document"],
                                            "buyer_email" => $obj[0]["buyer_email"],
                                            "buyer_cellphone" => $obj[0]["buyer_cellphone"],
                                            "buyer_phone" => $obj[0]["buyer_phone"],
                                            "event" => $obj[0]["event"],
                                            "event_fulldate" => $obj[0]["event_fulldate"],
                                            "event_room" => $obj[0]["event_room"],
                                            "purchase_amount" => $obj[0]["purchase_amount"],
                                            "paymenttype" => $obj[0]["paymenttype"],
                                            "installment" => $obj[0]["installment"],
                                        ] );
    }

    function make_purchase_email_b2b($id_pedido_venda) {
        
        $obj = get_purchase_email_b2b($id_pedido_venda);
// die(json_encode($obj[0]));
        if ($obj[0]["send_sell_email"] == 0) {
            return;
        }

        $html = setHtml_purchase_email_b2b($obj);

        $to = $obj[0]["sell_email"];
        $toName = "TICKETOFFICE";

        $from = getwhitelabelemail()["noreply"]["email"];
        $fromName = getwhitelabelemail()["noreply"]["from"];

        $subject = "VENDA REALIZADA - ".$id_pedido_venda;

        $msg = $html;

        // die($msg);
        // die(json_encode(array($from, $fromName, $to, $toName, $subject, $msg)));
        sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
        logme();
    }
//    make_purchase_email_b2b($_REQUEST["id"]);
?>