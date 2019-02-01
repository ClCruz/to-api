<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;
    
    function splitResp($value, $which) {
        $ret = array();

        if ($value === null || $value === '')
            return $ret;

        $aux1 = explode(":", $value);
        return $aux1[$which];
    }
    function get($id_base, $codVenda, $indice) {
        $query = "EXEC pr_print_ticket ?, ?";
        $params = array($codVenda, $indice);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {

            $code = $row['barcode'];

            //die("teste".$code);

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
                ,"day"=>$row["day"]
                ,"year"=>$row["year"]
                ,"roomName"=>$row["roomName"]
                ,"roomNameOther"=>$row["roomNameOther"]
                ,"seatNameFull"=>$row["seatNameFull"]
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
                ,"buyerDoc"=>$row["buyerDoc"]
                ,"insurance_policy"=>$row["insurance_policy"]
                ,"opening_time"=>$row["opening_time"]
                ,"eventResp"=>$row["eventResp"]
                ,"eventRespName"=>splitResp($row["eventResp"],0)
                ,"eventRespDoc"=>splitResp($row["eventResp"],1)
                ,"eventRespAddress"=>splitResp($row["eventResp"],2)
                ,"user"=>$row["user"]
                ,"countTicket"=>$row["countTicket"]
                ,"purchase_date"=>$row["purchase_date"]
                ,"print_date"=>$row["print_date"]                                
                ,"howMany"=>$row["howMany"]           
            );
        }

        logme();

        return $json;
    }
    $obj = get($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["indice"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <style>
        body {
            
        }
        .left {
            font-size: 10px;
        }
        .middle {
            font-size: 10px;
            line-height: 10px;
            margin-bottom: 0px;
        }
        .right {
            font-size: 9px;

        }
        .table {
        }

        .value {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .block {
            display:block;
        }


        .local {
            text-transform: uppercase;
            text-align: center;
        }
        .address {
        }
        .name {
            font-weight: bold;
        }
        .name.middle {
            font-size: 14px;
            padding-bottom: 1px;
            padding-top: 1px;   
            text-align: center;
        }
        .divDay {
            text-align: center;
        }
        .weekdayName {
            text-transform: uppercase;
            font-weight: bold;
        }
        .hour {
            font-weight: bold;
        }
        .day {
            text-transform: uppercase;
            font-weight: bold;
        }
        .opening_time {
            text-align: center;
        }
        .portao {
            display: none;
        }
        .roomName {
            text-transform: uppercase;
            text-align: center;
        }
        .seatRow {
            text-transform: uppercase;
        }
        .seatName {
            text-transform: uppercase;
        }
        .ticketIdentity {
        }
        .ticket {
            text-align: center;
        }        
        .ticket2 {
            font-size: 10px;
        }        
        .buyer {
        }
        .buyerDoc {
        }
        .payment {
            text-transform: uppercase;
        }
        .transaction {
        }
        .insurance_policy {
        }
        .eventRespName {
            text-transform: uppercase;
        }
        .eventRespDoc {
        }
        .eventRespAddress {
        }
        .user {
            text-transform: uppercase;
        }
        .countTicket {
        }
        .purchase_date {
        }
        .print_date {
        }
        .image {
            width: 70px;
            height: 70px;
        }
        .table {
            margin-left: 0px;
        }
        .tdleft {
            display:none;
            text-align: center;
            border-right: 1px solid #cdd0d4;
            border-bottom: 2px dotted #cdd0d4;
        }
        .tdmiddle {
            text-align: left;
            /*border-right: 1px solid #cdd0d4;*/
            /*border-bottom: 2px dotted #cdd0d4;*/
        }
        .tdright {
            display:none;
            text-align: center;
            border-bottom: 2px dotted #cdd0d4;
        }
        .dotted {
            
        }
        .dotted2 {
            
        }
        .lineheight {
            line-height: 2px;
        }
        .pagebreak { page-break-after: always; } 
        .cut {
            line-height: 0px;
            padding-bottom: 1px;
        }
        .cutoutside {
            line-height: 0px;
            padding-bottom: 15px;
            padding-left: 9px;
        }
        .noimage {
            padding-left:49px;
        }
        .section {
            margin-top: 3px;
        }
    </style>
</head>
<body>
<?php 
$count = 0;
?>
<?php foreach ($obj as &$row) {?>
    <?php $count = $count +1; ?>
    <?php if ($count != 1) { ?>
    <div class="pagebreak"></div>
    <?php } ?>
    <table class="table dotted">
        <tr>
            <td class="tdmiddle">
                <div class="local middle"><?php echo $row["local"] ?></div>
                <div class="address middle local"><?php echo $row["address"] ?></div>
                <div class="name middle"><?php echo $row["name"] ?></div>
                <div class="middle divDay">
                    <span class="weekdayName middle"><?php echo $row["weekdayName"] ?></span>
                    <span class="hour middle"><?php echo $row["hour"] ?></span>
                    <span class="day middle"><?php echo $row["day"]."-".$row["monthName"]."-".$row["year"] ?></span>
                </div>
                <div class="opening_time middle">Abertura: <span class="value"><?php echo $row["opening_time"] ?></span></div>
                <?php if ($row["opening_time"] != "") {?>
                <?php }?>
                <div class="portao middle"></div>
                <div class="roomName middle ">Setor: <span class="value"><?php echo $row["roomName"] ?></span></div>
                <div class="middle" style="margin: 0 auto; text-align: center;">
                    <span class="seatRow  middle">Fileira: <span class="value"><?php echo $row["seatRow"] ?></span></span>
                    <span class="seatName middle"> | Assento: <span class="value"><?php echo $row["seatName"] ?></span></span>
                </div>
                <div class="ticket middle">Bilhete: <span class="value"><?php echo $row["ticket"] ?></span></div>
                <div class="ticketIdentity local middle">Código: <span class="value"><?php echo $row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"] ?></span></div>
                <div class="middle section">
                    <span class="buyer middle">Emitido para: <span class="value"><?php echo $row["buyer"] ?></span></span>
                    <br>
                    <span class="buyerDoc middle">CPF/CNPJ: <span class="value"><?php echo $row["buyerDoc"] ?></span></span>
                </div>
                <div class="block middle"></div>
                <div class="middle">
                    <span class="payment middle">Pagamento: <span class="value"><?php echo $row["paymentType"] ?></span></span>
                    <?php if ($row["transaction"] != "") {?>
                    <span class="transaction middle"><span class="value">(<?php echo $row["transaction"] ?>)</span></span>
                    <?php }?>
                </div>
                <div class="insurance_policy middle">Apólice de Seguro N: <span class="value"><?php echo $row["insurance_policy"] ?></span></div>
                <?php if ($row["insurance_policy"] != "") {?>
                <?php }?>
                <div class="block middle"></div>
                <div class="middle">
                    <span class="eventRespName middle">Responsável: <span class="value"><?php echo $row["eventRespName"] ?></span></span>
                    <br>
                    <span class="eventRespDoc middle">CNPJ/CPF: <span class="value"><?php echo $row["eventRespDoc"] ?></span></span>
                </div>
                <div class="eventRespAddress middle">End.: <span class="value"><?php echo $row["eventRespAddress"] ?></span></div>
                <div class="middle">
                    <span class="countTicket middle">Qtde: <span class="value"><?php echo $row["howMany"] ?></span></span>
                    <span class="user middle"> - <span class="value"><?php echo $row["user"] ?></span></span>
                    <br>
                    <span class="purchase_date middle"> V:<span class="value"><?php echo $row["purchase_date"] ?></span></span>
                    <span class="print_date middle"> IM:<span class="value"><?php echo $row["print_date"] ?></span></span>
                </div>
                <br>
                <table class="dotted2">
                    <tr>
                        <td><img class="image" src="data:image/png;base64,<?php echo $row["qrcode"]?>" alt="image 1" width="96" height="48"/></td>
                        <td>
                            <table>
                                <tr class="lineheight">
                                    <td><div class="name middle"><?php echo $row["name"] ?></div></td>
                                </tr>
                                <tr class="lineheight">
                                    <td>
                                        <div class="middle divDay">
                                            <span class="weekdayName middle"><?php echo $row["weekdayName"] ?></span>
                                            <span class="hour middle"><?php echo $row["hour"] ?></span>
                                            <span class="day middle"><?php echo $row["day"]."-".$row["monthName"]."-".$row["year"] ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="lineheight">
                                    <td><div class="ticketIdentity middle">Código: <span class="value"><?php echo $row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"] ?></span></div></td>
                                </tr>
                                <tr class="lineheight">
                                    <td><span class="buyerDoc middle">Emitido para CPF/CNPJ: <span class="value"><?php echo $row["buyerDoc"] ?></span></td>
                                </tr>
                                <tr class="lineheight">
                                    <td>
                                        <span class="countTicket middle">Qtde: <span class="value"><?php echo $row["howMany"] ?></span></span>
                                        <span class="user middle"> - <span class="value"><?php echo $row["user"] ?></span></span>
                                        <span class="ticket2 middle"> - <span class="value"><?php echo $row["ticket"] ?></span></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<?php } ?>
<div class="pagebreak"></div>
<script lang="javascript">
    window.print();
    window.close();
</script>
</body>
</html>