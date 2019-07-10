<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;


    // function generate_email_print_code($id_pedido_venda, $codVenda, $id_base) {
    //     if ($id_pedido_venda == null)
    //         $id_pedido_venda = 0;
    //     if ($codVenda == null)
    //         $codVenda = '';
    //     if ($id_base == null)
    //         $id_base = 0;
        

    //     $query = "EXEC pr_generate_email_ticket_print ?, ?, ?";
    //     $params = array($codVenda, $id_base, $id_pedido_venda);
    //     $result = db_exec($query, $params);
        
    //     $json = array();
    //     foreach ($result as &$row) {
    //         $json = array(
    //             "code" => $row["code"],
    //         );
    //     }

    //     return $json;
    // }
    // die(json_encode(generate_email_print_code(1932, 'F6IGAOAICB', 213)));
    // die("");
    function getbycode($code) {
        $query = "EXEC pr_ticketoffice_email_ticket_print_setseen ?";
        $params = array($code);
        $result = db_exec($query, $params);

        $json = array("found"=>0);
        foreach ($result as &$row) {
            if ($row["id"] != null) {
                $json = array(
                    "found"=>1
                    ,"id"=>$row["id"]
                    ,"codVenda"=>$row["codVenda"]
                    ,"id_base"=>$row["id_base"]
                    ,"id_pedido_venda"=>$row["id_pedido_venda"]
                );
            }
        }
        return $json;
    }
    function urn($count, $eventName, $localName, $ticketName, $price, $date, $time, $sectorName, $seat) {
        ?>
            <td style="width:130px;">
                <table style="width:130px;">
                    <tbody><tr>
                        <td style="width:122px; background-color: #EEEEEE; padding:2px;">
                            <p style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:12px;margin:0px;padding:0px;">
                                <?php echo $eventName ?><br>
                                <?php echo $localName ?><br>
                                <?php echo $ticketName ?> - <?php echo $price ?><br>
                                <?php echo $date ?> <?php echo $time ?><br>
                                <?php echo $sectorName ?> <?php echo $seat ?>
                            </p>
                        </td>
                    </tr>
                </tbody></table>
            </td>
        <?php
    }
    
    function getobj($id_base, $codVenda, $indice) {
        
        if ($id_base == null || $id_base == '') {
            $id_base = get_id_base_by_codvenda($codVenda);
        }
        
        $query = "EXEC pr_print_ticket2 ?,?,?,?";
        $params = array($codVenda, $indice, gethost(),getwhitelabelobj()["apikey"]);
        // die("oi: ".json_encode($params));
        $result = db_exec($query, $params, $id_base);

        // die($id_base);

        $json = array();
        foreach ($result as &$row) {
            $code = $row['barcode'];

            $code = Encoder::encode($code);
            $renderer = new PngRenderer();
            $render = $renderer->render($code);

            $png = imagecreatefromstring($renderer->render($code));
            ob_start();
            imagepng($png);
            $imagedata = ob_get_contents();
            ob_end_clean();
            imagedestroy($png);
        

            $json[] = array(
                "id"=>$row["id"]
                ,"qrcode"=>base64_encode($imagedata)
                ,"local"=>$row["local"]
                ,"address"=>$row["address"]
                ,"name"=>$row["name"]
                ,"weekday"=>$row["weekday"]
                ,"weekdayName"=>$row["weekdayName"]
                ,"hour"=>$row["hour"]
                ,"month"=>$row["month"]
                ,"monthName"=>$row["monthName"]
                ,"monthNameAbb"=>$row["monthNameAbb"]
                ,"day"=>$row["day"]
                ,"year"=>$row["year"]
                ,"roomName"=>$row["roomName"]
                ,"roomNameOther"=>$row["roomNameOther"]
                ,"seatNameFull"=>$row["seatNameFull"]
                ,"sectorName"=>$row["sectorName"]
                ,"seatRow"=>$row["seatRow"]
                ,"seatName"=>$row["seatName"]
                ,"seatIndice"=>$row["seatIndice"]
                ,"purchaseCode"=>$row["purchaseCode"]
                ,"purchaseCodeInt"=>$row["purchaseCodeInt"]
                ,"ticket"=>$row["ticket"]
                ,"payment"=>$row["payment"]
                ,"paymentType"=>$row["paymentType"]
                ,"transaction"=>$row["transaction"]
                ,"buyer"=>$row["buyer"]
                ,"buyerDoc"=>documentformatBR($row["buyerDoc"])
                ,"buyerEmail"=>$row["buyerEmail"]
                ,"insurance_policy"=>$row["insurance_policy"]
                ,"opening_time"=>$row["opening_time"]
                ,"user"=>$row["user"]
                ,"countTicket"=>$row["countTicket"]
                ,"purchase_date"=>$row["purchase_date"]
                ,"print_date"=>$row["print_date"]         
                ,"domain"=>$row["domain"]                       
                ,"howMany"=>$row["howMany"]
                ,"show_partner_info"=>$row["show_partner_info"]
                ,"id_partner"=>$row["id_partner"]
                ,"name_site"=>$row["name_site"]
                ,"price"=>$row["price"]
                ,"price_formatted"=>$row["price_formatted"]
                ,"unique_sell_code"=>$row["unique_sell_code"]
                ,"price_total"=>$row["price_total"]
                ,"count_total"=>$row["count_total"]
                ,"price_total_formatted"=>$row["price_total_formatted"]
                ,"buyer_bin_card"=>$row["buyer_bin_card"]
            );
        }

        return $json;

    }
    $code = $_REQUEST["code"];
    if ($code == '') {
        die("Falha na execução. ERR.01");
    }

    $primary_info = getbycode($code);
    //die(json_encode($primary_info));
    if ($primary_info["found"] == 0) {
        die("Falha na execução. ERR.02");
    }

    $obj = getobj($primary_info["id_base"], $primary_info["codVenda"], '');
    $uniquename = gethost();
    $logo = getDefaultMediaHost()."/logos/logo-".$uniquename.".png";

    // die(json_encode($obj));
    // foreach ($obj as &$row) {
    //     urn($row["name"], $row["local"], $row["ticket"], $row["price_formatted"], $row["day"]."/".$row["month"]."/".$row["year"], $row["hour"], $row["sectorName"], $row["seatNameFull"]);
    // }
?>
<html>
<head>
      <style>
         @page :first {
            size: auto;
            /* header: html_header_first; */
            /* footer: html_footer; */
         }

         @page {
            size: auto;
            /* header: html_header_otherpages; */
            /* footer: html_footer; */
         }
         .principal {
            
         }
         .logo img {
            max-width: 60px;
         }
         .teste {
            border: 1px solid #e3e3e3;
         }

         .printonly_lines_values {
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 5px;
            padding-top: 5px;
         }
         .left {
            text-align:left;
         }
         .right {
            text-align:right;
         }
         .center {
            text-align:center;
         }
         .bold {
            font-weight: bold;
         }
         .fs13 {
            font-size: 13px;
         }

         .grid {
            font-size: 13px;
            position: relative;
            width:100%;
            
         }
         .grid .header {
            font-weight: bold; 
            margin-bottom: 5px;
         }
         .grid .content {
            font-size: 11px;
         }
         .grid2 {
            margin-top: 10px;
            font-size: 13px;
            
         }
         .pb10 {
            padding-bottom: 10px;
         }
         .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
         }
         .pmarginzero {
            margin: 2px;
         }

      </style>
</head>

<body style="max-width: 960px">
<?php 
$count = 0;
$first = true;
$total = count($obj);
foreach ($obj as &$row) {
    $count=$count+1;
    if ($count > 5) {
        $count = 1;
    }
    if ($count == 1) {
        if ($first == false) {
            echo "</tr></tbody></table>";
        }
        echo "<table><tbody align='left' style='margin-bottom: 1px; width:140px;'><tr>";
    }
    urn($row["name"], $row["local"], $row["ticket"], $row["price_formatted"], $row["day"]."/".$row["month"]."/".$row["year"], $row["hour"], $row["sectorName"], $row["seatNameFull"]);
    $first = false;
} 
echo "</tr></tbody></table>";
?>
    <div>
        <div style="font-weight: bold;font-size:20px;" class="left">Aqui está o seu voucher (e-ticket)</div>
        <span>
            <img style="float:right;max-width:200px;" src="<?php echo $logo ?>" />
        </span>
        <span style="font-size:8px">É obrigatório a apresentação deste voucher na bilheteria, balcão ou diretamente no controle de acesso do local.</span>
        <br /><br /><br />
        <div style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:16px;margin:0px;padding:0px;">
        Olá <span style="font-size:11px;font-weight:bold;"><?php echo $obj[0]["buyer"] ?></span>, obrigado pela compra.<br>
        <b>Confira abaixo as informações</b> da sua compra.
        </div>
        <div style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:16px;margin:0px;padding:0px;">
            > <b>Nome:</b> <?php echo $obj[0]["buyer"]; ?><br />
            > <b>CPF/CNPJ/Passport:</b> <?php echo $obj[0]["buyerDoc"]; ?><br />
            > <b>E-mail:</b> <?php echo $obj[0]["buyerEmail"]; ?>
        </div>
        <?php if ($obj[0]["show_partner_info"] == 1) {
        ?>
            <div style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:16px;margin:0px;padding:0px;">
                > <b>Compra realizada em:</b> <?php echo getwhitelabelobj()["uri"] ?>
            </div>
        <?php
        }
        ?>
        <br />
        <div style="height:1px;border-bottom:1px solid #EEEEEE;width:600px;"></div>
        <div style="font-family:Arial,Verdana;font-size:14px;font-weight:bold;color:#000000;line-height:16px;margin:0px;padding:0px;text-transform:uppercase;">
            LOCALIZADOR Nº <?php echo $obj[0]["purchaseCodeInt"]; ?>
        </div>
    </div>
    <table>
        <?php
        foreach ($obj as &$row) {
            // urn(, $row["local"], $row["ticket"], $row["price_formatted"], $row["day"]."/".$row["month"]."/".$row["year"], $row["hour"], $row["sectorName"], $row["seatNameFull"]);
        ?>
            <tr>
                <td>
                <table width="400" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td height="3"></td>
                                </tr>
                                <tr>
                                    <td width="72" align="left" valign="middle">                                    
                                        <img src="data:image/png;base64,<?php echo $row["qrcode"]?>" style="margin:0 20px 0 0;">
                                    </td>
                                    <td width="328" valign="middle">
                                      <div style="width:324px;border:2px solid #EEEEEE;">
                                            <table width="324" border="0" cellpadding="4" cellspacing="0">
                                                <tbody><tr>
                                                    <td width="208" valign="middle">
                                                        <p style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:15px;margin:0;padding:0;text-transform:uppercase;">
                                                            <b><?php echo $row["name"] ?></b><br>
                                                            <?php echo $row["local"] ?><br>
                                                            <?php echo $row["ticket"] ?> - R$ <?php echo $row["price_formatted"] ?><br>
                                                            <?php echo $row["sectorName"] ?> - <?php echo $row["seatNameFull"] ?><br>
                                                            <span style="font-size:15px;line-height:16px;"><?php echo $row["day"]."/".$row["month"]."/".$row["year"] ?> <?php echo $row["hour"] ?></span>
                                                        </p>
                                                    </td>
                                                    <td width="100" align="center" valign="middle">
                                                        <p style="font-family:Arial,Verdana;font-size:13px;font-weight:bold;color:#000000;line-height:18px;margin:0;padding:0;text-transform:uppercase;">
                                                            <?php echo $row["sectorName"] ?>
                                                        </p>
                                                        <p style="font-family:Arial,Verdana;font-size:19px;font-weight:bold;color:#000000;line-height:22px;margin:0;padding:0;text-transform:uppercase;">
                                                            <?php echo $row["seatNameFull"] ?>
                                                        </p>
                                                        <p style="font-size: 10px; font-family: Arial, Verdana; margin-top: 2px;">
                                                            <?php echo $row["purchaseCode"] ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </td>
            </tr>

        <?php
        }
        ?>
    </table>
    <?php if (count($obj)) {
    ?>
    <table style="width: 600px;">
        <tr>
            <td style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:15px;margin:0;padding:0;text-transform:uppercase;">Total de vouchers: <?php echo $obj[0]["count_total"] ?></td>
            <td style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:15px;margin:0;padding:0;text-transform:uppercase;">Total do pedido: R$ <?php echo $obj[0]["price_total_formatted"] ?></td>
        </tr>
    </table>
    <br />
    <table width="560" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td>
                        <table width="560" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td width="150" valign="top">
                                    <p style="font-family:Arial,Verdana;font-size:9px;font-weight:bold;color:#000000;line-height:14px;margin:0;padding:0;text-transform:uppercase;">
                                        DADOS DO PEDIDO
                                    </p>
                                    <p style="font-family:Arial,Verdana;font-size:9px;font-weight:normal;color:#000000;line-height:14px;margin:0;padding:0;">
                                        <b>Localizador:</b> <?php echo $obj[0]["purchaseCodeInt"] ?>1935<br>
                                        <b>Data e hora de venda:</b><br>
                                        <?php echo $obj[0]["purchase_date"] ?><br>
                                        <b>Data e hora de impressão:</b><br>
                                        <?php echo $obj[0]["print_date"] ?><br>
                                        <b>Login de usuário:</b><br>
                                        <?php echo $obj[0]["buyerEmail"] ?><br>
                                        <b>Total de pagamento:</b><br>
                                        <?php echo $obj[0]["price_total_formatted"] ?><br>
                                        <b>Meio de pagamento:</b><br>
                                        <?php echo $obj[0]["paymentType"] ?><br>
                                        <b>Nr. do Cartão da Compra</b><br>
                                        <?php echo $obj[0]["buyer_bin_card"] ?>
                                    </p>
                                </td>
                                <td width="10"></td>
                                <td width="400" valign="top">
                                  <p style="font-family:Arial,Verdana;font-size:8px;font-weight:bold;color:#000000;line-height:14px;margin:0;padding:0;text-transform:uppercase;">
                                        OBSERVAÇÕES IMPORTANTES.
                                    </p>
                                    <p style="font-family:Arial,Verdana;font-size:8px;font-weight:normal;color:#000000;line-height:14px;margin:0;padding:0;">
                                        - O evento  começa rigorosamente no horário marcado. Não haverá troca de voucher ou devoluções em caso de atraso de qualquer natureza. Seja pontual, poderá não ser permitida a entrada após o início do espetáculo.<br>
                                        - A taxa de serviço e os vouchers que forem adquiridos e pagos através desse canal não poderão ser devolvidos,
                                        trocado ou cancelados depois que a compra for efetuada pelo cliente e o pagamento confirmado pela
                                        instituição financeira.<br>
                                        - É obrigatório <b>apresentar um documento de identificação pessoal e o cartão de crédito utilizado na compra (no caso da compra feita por cartão de crédito)</b> na entrada do evento. De acordo com a política de segurança das operadoras de crédito, essa conferência se faz necessária visto que as transações via internet não são autenticadas com sua senha de usuário.<br>
                                        
                                        - No caso de <b>meia-entrada</b> ou <b>promoção</b> é obrigatório a apresentação de documento que comprove o
                                        benefício no momento da retirada dos vouchers e na entrada do local.<br>
                                    </p>
                                    <br>
                                    
                                </td>
                          </tr>
                        </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td height="8"></td>
                </tr>
            </tbody></table>
    <?php } ?>
</body>  
<?php 


// $content = ob_get_clean();
// //ob_end_clean();
// die($content);

// //$mpdf = new \Mpdf\Mpdf();
// $mpdf = new \Mpdf\Mpdf([
//     'debug' => true,
//     'allow_output_buffering' => true
// ]);
// $mpdf->WriteHTML($content);
// $mpdf->Output();
// die();

?>