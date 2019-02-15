<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');

    use Metzli\Encoder\Encoder;
    use Metzli\Renderer\PngRenderer;
    
    function get($id_base, $codVenda, $indice) {

        if ($id_base == null || $id_base == '') {
            $id_base = get_id_base_by_codvenda($codVenda);
        }

        $query = "EXEC pr_print_ticket ?, ?";
        $params = array($codVenda, $indice);
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
                ,"user"=>$row["user"]
                ,"countTicket"=>$row["countTicket"]
                ,"purchase_date"=>$row["purchase_date"]
                ,"print_date"=>$row["print_date"]                                
                ,"howMany"=>$row["howMany"]           
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }
    get($_REQUEST["id_base"], $_REQUEST["codVenda"], $_REQUEST["indice"]);
?>
