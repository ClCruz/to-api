<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

    $mpdf = new \Mpdf\Mpdf();

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
    function urn($eventName, $localName, $ticketName, $price, $date, $time, $sectorName, $seat) {
        ?>
<tbody align="left" style="margin-bottom: 5px; width:140px;">
            <tbody>
                <tr>
                    <td style="width:130px;">
                        <table style="width:130px;">
                            <tbody><tr>
                                <td style="width:122px; background-color: #EEEEEE; padding:2px;">
                                    <p style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:12px;margin:0;padding:0;">
                                        <?php echo $eventName ?><br>
                                        <?php echo $localName ?><br>
                                        <?php echo $ticketName ?> - <?php echo $price ?><br>
                                        <?php echo $date ?> <?php echo $time ?><br>
                                        <?php echo $sectorName ?> <?php echo $seat ?>
                                    </p>
                                    <p style="font-family:Arial,Verdana;font-size:10px;font-weight:normal;color:#000000;line-height:12px;margin:0;padding:0;text-transform:uppercase;float:right;">
                                        urna
                                    </p>
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                    <td style="width:10px;">
                        <div style="width:0;height:80px;border-right:2px dashed #EEEEEE;"></div>
                    </td>
                </tr>
            </tbody>
        </tbody>
        <?php
    }
    
    function getobj($id_base, $codVenda, $indice) {

        if ($id_base == null || $id_base == '') {
            $id_base = get_id_base_by_codvenda($codVenda);
        }

        $query = "EXEC pr_print_ticket2 ?,?,?,?";
        $params = array($codVenda, $indice, gethost(),getwhitelabelobj()["apikey"]);
        $result = db_exec($query, $params, $id_base);

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
                ,"insurance_policy"=>$row["insurance_policy"]
                ,"opening_time"=>$row["opening_time"]
                ,"eventResp"=>$row["eventResp"]
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

    // die(json_encode($obj));
    // foreach ($obj as &$row) {
        // urn($row["name"], $row["local"], $row["ticket"], $row["price_formatted"], $row["day"]."/".$row["month"]."/".$row["year"], $row["hour"], $row["sectorName"], $row["seatNameFull"]);
    // }
?>
<html>
<head>
      <style>
         @page :first {
            size: auto;
            header: html_header_first;
            footer: html_footer;
         }

         @page {
            size: auto;
            header: html_header_otherpages;
            footer: html_footer;
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

<body>
<htmlpageheader name="header_first" style="display:none;padding-bottom:150px;">
</htmlpageheader>
<htmlpageheader name="header_otherpages" style="display:none;">
</htmlpageheader>

<htmlpagefooter name="footer">
</htmlpagefooter>

<?php
$row = $obj[0];
//foreach ($obj as &$row) {
?>
<div style="">
    <div style="width:10px;">
        <span style="font-size:11px;">
            <?php echo $row["name"] ?>
        </span>
    </div>
</div>
   
<?php
 //urn($row["name"], $row["local"], $row["ticket"], $row["price_formatted"], $row["day"]."/".$row["month"]."/".$row["year"], $row["hour"], $row["sectorName"], $row["seatNameFull"]);
//}
echo "oi";
?>

</body>  
<?php 
$content = ob_get_clean();
// die($content);

$mpdf->WriteHTML($content);
$mpdf->Output();
die();
?>