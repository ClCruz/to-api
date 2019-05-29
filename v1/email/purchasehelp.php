<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/mail_functions.php");

    function generate_email_print_code($id_pedido_venda, $codVenda, $id_base) {
        if ($id_pedido_venda == null)
            $id_pedido_venda = 0;
        if ($codVenda == null)
            $codVenda = '';
        if ($id_base == null)
            $id_base = 0;
        

        $query = "EXEC pr_generate_email_ticket_print ?, ?, ?";
        $params = array($codVenda, $id_base, $id_pedido_venda);
        $result = db_exec($query, $params);
        
        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "code" => $row["code"],
            );
        }

        return $json;
    }

    function get_purchase_email($id_pedido_venda) {
        $query = "EXEC pr_purchase_info_email ?";
        $params = array($id_pedido_venda);
        $result = db_exec($query, $params);
        
        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "buyer_name" => $row["buyer_name"],
                "buyer_email" => $row["buyer_email"],
                "buyer_document" => $row["buyer_document"],
                "voucher_id" => $row["voucher_id"],
                "voucher_method" => $row["delivery_method"],
                "voucher_code" => $row["voucher_code"],
                "voucher_event_image" => getDefaultMediaHost(). str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "voucher_event_link" => getwhitelabelURI_home($row["uri"]),
                "voucher_event_name" => $row["voucher_event_name"],
                "voucher_local_name" => $row["voucher_local_name"],
                "voucher_event_city" => $row["voucher_event_city"],
                "voucher_event_state" => $row["voucher_event_state"],
                "voucher_event_tickettype" => $row["voucher_event_tickettype"],
                "voucher_event_date" => $row["voucher_event_date"],
                "voucher_event_hour" => $row["voucher_event_hour"],
                "voucher_event_amount" => $row["voucher_event_amount"],
                "voucher_event_service" => $row["voucher_event_service"],
                "voucher_event_amount_total" => $row["voucher_event_amount_total"],
                "voucher_event_service_total" => $row["voucher_event_service_total"],
                "voucher_event_value_total" => $row["voucher_event_value_total"],
                "voucher_event_installment" => $row["nr_parcelas_pgto"],
                "voucher_printcodehas" => $row["printcodehas"],
                "voucher_linkold" => getwhitelabelURI_legacy("/comprar/reimprimirEmail.php?pedido=".$row["voucher_id"]),
                "voucher_link" => getwhitelabelURI_api("/v1/email/ticket?code=".$row["printcode"]),
            );
        }
        //die(json_encode($json[1]["voucher_event_amount"]));

        return $json;
    }
    function get_purchase_email_ticketoffice($codVenda, $id_base) {
        //die(json_encode(getwhitelabelobj(),JSON_PRETTY_PRINT));
        $query = "EXEC pr_purchase_info_email_ticketoffice ?";
        $params = array($codVenda);
        $result = db_exec($query, $params, $id_base);
        
        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "buyer_name" => $row["buyer_name"],
                "buyer_email" => $row["buyer_email"],
                "buyer_document" => $row["buyer_document"],
                "voucher_id" => $row["voucher_id"],
                "voucher_method" => "e-ticket",
                "voucher_code" => $row["voucher_code"],
                "voucher_event_image" => getDefaultMediaHost(). str_replace("{id}", $row["id_evento"],str_replace("{default_card}", getDefaultCardImageName(),$row["cardimage"])),
                "voucher_event_link" => getwhitelabelURI_home($row["uri"]),
                "voucher_event_name" => $row["voucher_event_name"],
                "voucher_local_name" => $row["voucher_local_name"],
                "voucher_event_city" => $row["voucher_event_city"],
                "voucher_event_state" => $row["voucher_event_state"],
                "voucher_event_tickettype" => $row["voucher_event_tickettype"],
                "voucher_event_date" => $row["voucher_event_date"],
                "voucher_event_hour" => $row["voucher_event_hour"],
                "voucher_event_amount" => $row["voucher_event_amount"],
                "voucher_event_service" => $row["voucher_event_service"],
                "voucher_event_amount_total" => $row["voucher_event_amount_total"],
                "voucher_event_service_total" => $row["voucher_event_service_total"],
                "voucher_event_value_total" => $row["voucher_event_value_total"],
                "voucher_printcodehas" => $row["printcodehas"],
                "voucher_event_installment" => 1,
                "voucher_linkold" => getwhitelabelURI_legacy("/comprar/reimprimirEmail.php?pedido=".$row["voucher_id"]),
                "voucher_link" => getwhitelabelURI_api("/v1/email/ticket?code=".$row["printcode"]),
            );
        }
        //die(json_encode($json[1]["voucher_event_amount"]));

        return $json;
    }
    function setHtml_email_boleto($name,$link) { 
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
        $htmlname = "buyer_boleto.html";
        return $twig->render($htmlname, [
                                            "wlsite" => $wlsite,
                                            "wlsitewithwww" => $wlsitewithwww,
                                            "wlsitelogomedia" => $wlsitelogomedia,
                                            "wluniquename" => $wluniquename,
                                            "wlsitewithoutwww" => $wlsitewithoutwww,
                                            "user_name" => $name,
                                            "boleto_link" => $link,// getwhitelabelURI_home("/resetpass/".$code),
                                        ] );
    }
    function setHtml_purchase_email($obj, $isgift, $vouchername,$voucheremail, $type) { 
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
        if ($type == "web") {
            $htmlname = "buyer";
            if ($isgift) {
                $htmlname = "gift";
            }
            if ($obj[0]["voucher_method"] == "physical-ticket") {
                $htmlname.= "_delivery";
            }
            $htmlname.=".html";
        }
        else {
            $htmlname = "ticketoffice_buyer.html";
        }
        return $twig->render($htmlname, [
                                            "purchases" => $obj,
                                            "wlsite" => $wlsite,
                                            "wlsitewithwww" => $wlsitewithwww,
                                            "wlsitelogomedia" => $wlsitelogomedia,
                                            "wluniquename" => $wluniquename,
                                            "wlsitewithoutwww" => $wlsitewithoutwww,
                                            "gift_name" => $vouchername == null ? "" : $vouchername,
                                            "git_email" => $voucheremail == null ? "": $voucheremail,
                                            "buyer_name" => $obj[0]["buyer_name"],
                                            "buyer_email" => $obj[0]["buyer_email"],
                                            "buyer_document" => $obj[0]["buyer_document"],
                                            "voucher_event_installment" => $obj[0]["voucher_event_installment"],
                                            "voucher_id" => $obj[0]["voucher_id"],
                                            "voucher_code" => $obj[0]["voucher_code"],
                                            "voucher_event_amount_total" => $obj[0]["voucher_event_amount_total"],
                                            "voucher_event_service_total" => $obj[0]["voucher_event_service_total"],
                                            "voucher_event_value_total" => $obj[0]["voucher_event_value_total"],
                                            "voucher_link" => ($obj[0]["voucher_printcodehas"] == 1 ? $obj[0]["voucher_link"] : $obj[0]["voucher_linkold"]), 
                                        ] );
    }

    function make_purchase_boleto_email($id_pedido_venda, $link) {//, $barcode, $expiredate) {
        $obj = get_purchase_email($id_pedido_venda);

       $to = $obj[0]["buyer_email"];
       $toName = $obj[0]["buyer_name"];
        // $to = "blcoccaro@gmail.com";// $obj[0]["buyer_email"];
        // $toName = "blcoccaro";//$obj[0]["buyer_name"];

        $html = setHtml_email_boleto($toName, $link);

        $from = getwhitelabelemail()["noreply"]["email"];
        $fromName = getwhitelabelemail()["noreply"]["from"];

        $subject = "Boleto para pagamento";
        $msg = $html;

        sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
        logme();
    }

    function make_purchase_email($id_pedido_venda, $vouchername,$voucheremail,$forceemail) {
        if ($vouchername == null && $voucheremail != null) {
            $vouchername = $voucheremail;
        } 
        
        $obj = get_purchase_email($id_pedido_venda);
        $html = setHtml_purchase_email($obj, $voucheremail != null, $vouchername,$voucheremail, 'web');

        $to = $obj[0]["buyer_email"];
        $toName = $obj[0]["buyer_name"];

        if ($forceemail!= "") {
            $to = $forceemail;
            $toName = $forceemail;
        }


        $from = getwhitelabelemail()["noreply"]["email"];
        $fromName = getwhitelabelemail()["noreply"]["from"];

        $subject = "Agradecemos sua compra";
        if ($voucheremail != null) {
            $to = $voucheremail;
            $toName = $vouchername;
            $subject = "Você acaba de ser presenteado por um amigo!";
        }

        $msg = $html;

        sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
        logme();
    }
    function make_purchase_email_ticketoffice($codVenda, $id_base, $email) {        
        $obj = get_purchase_email_ticketoffice($codVenda, $id_base);
        // die(json_encode($obj,JSON_PRETTY_PRINT));
        $html = setHtml_purchase_email($obj, false, '','', 'ticketoffice');

        $to = $email;
        $toName = "Venda pela Bilheteria";

        $from = getwhitelabelemail()["noreply"]["email"];
        $fromName = getwhitelabelemail()["noreply"]["from"];

        $subject = "Agradecemos sua compra";

        $msg = $html;

        sendToAPI($from, $fromName, $to, $toName, $subject, $msg);
        logme();
    }
?>