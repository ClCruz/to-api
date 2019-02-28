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
   function limittext($text, $len = 36) {
       return substr($text, 0, $len);
   }
   function limittextandforce($text, $len = 36) {
        return substr($text, 0, $len);
   }
   function get($id_base, $codVenda, $indice) {
       $query = "EXEC pr_print_ticket ?, ?, ?";
       // die("dd".json_encode($codVenda));
       $params = array($codVenda, $indice, gethost());
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
               ,"eventRespName"=>$row["productor_name"]
               ,"eventRespDoc"=>$row["productor_document"]
               ,"eventRespAddress"=>$row["productor_address"]
               ,"user"=>$row["user"]
               ,"countTicket"=>$row["countTicket"]
               ,"purchase_date"=>$row["purchase_date"]
               ,"print_date"=>$row["print_date"]                    
               ,"domain"=>$row["domain"]             
               ,"amount_topay"=>$row["amount_topay"]                      
               ,"howMany"=>$row["howMany"]           
               ,"IngressoNumerado"=>$row["IngressoNumerado"]
           );
       }
   
       logme();
   
       return $json;
   }
   $obj = get($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["indice"]);
   
   $dontbreakline = $_REQUEST["dontbreakline"] != null && $_REQUEST["dontbreakline"] != '';
   $dontclose = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   $dontprint = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   ?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title></title>
      <style>
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
         .fakejumpline {
         visibility: hidden;
         }
         .image {
         width: 90px;
         height: 90px;
         position: relative;
         }
         .qrcode {
         padding-left: 10px;
         width: 110px;
         height: 110px;
         position: relative;
         }
         .container{
         display:flex;
         }
         .fixed{
         width: 90px;
         height: 90px;
         }
         .flex-item{
         flex-grow: 1;
         padding-left: 23px;
         }
         .printbase {
         font-size: 14px;
         /* font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace;  */
         font-family: "Lucida Console";
         line-height: 12px;
         text-transform: uppercase;
         width: 309px;
         }
         .printbase div {
         padding-bottom: 2px;
         }
         .printbase .printname {
         font-weight: bold;
         font-size: 16px;
         line-height: 14px;
         }
         .printbase .printdomain {
         font-weight: bold;
         font-size: 18px;
         }
         .printbase .printday {
         font-weight: bold;
         font-size: 13px;
         }
         .printbase .printvalue {
         font-weight: bold;
         }
         .printbase .printright {
         text-align: right;
         }
         .printbase .printqtd {
         font-size: 10px;
         }
         .printfirstpart {
         text-align: center;
         }
         .printsecondpart {
         text-align: center;
         font-size: 10px;
         padding-left: 10px;
         }
         .center {
         width: 50%;
         }
         .givemesomespace {
         padding-top: 15px;
         }
         .givemesomespaceless {
         padding-top: 5px;
         }
         .givemesomespacemore {
         padding-top: 25px;
         }
         .forcetwolines {
            min-height:24px;
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
      <?php if ($dontbreakline) {
         echo "";
         }
         else {
         echo "<div class='pagebreak'></div>";
         }
         ?>
      <?php } ?>
      <div>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart center">
               <img src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 55px; filter: invert(100%); text-align: center; margin: 0 auto; display: flex;" />
               <div class="forcetwolines"><?php echo limittext(getwhitelabelobj()["info"]["companyName"],62) ?></div>
               <div class="">CNPJ: <?php echo getwhitelabelobj()["info"]["CNPJ"] ?></div>
               <div class="">Site: <?php echo limittext($row["domain"],32); ?></div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class=""><?php echo limittext($row["local"]); ?></div>
               <div class="forcetwolines"><?php echo limittextandforce($row["address"],98); ?></div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class="printname forcetwolines"><?php echo limittext($row["name"],80) ?></div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class="printday">
                  <span class=""><?php echo limittext($row["weekdayName"]) ?></span>
                  <span class=""><?php echo limittext($row["day"]."-".$row["monthName"]."-".$row["year"]) ?></span>
                  <span class=""><?php echo limittext($row["hour"]) ?></span>
               </div>
               <div class="">Abertura: <span class="printvalue"><?php echo limittext($row["opening_time"]) ?></span></div>
               <div class="">
                  <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"],10) ?></span></span>
                  <span class="">Fileira: <span class="printvalue"><?php echo limittext($row["seatRow"],5) ?></span></span>
                  <span class="">Assento: <span class="printvalue"><?php echo limittext($row["seatName"],5) ?></span></span>
               </div>
               <div class="">
                  <span class="">Bilhete: <span class="printvalue"><?php echo limittext($row["ticket"],7) ?></span></span>
                  <span class="">ID: <span class="printvalue"><?php echo limittext($row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"],26) ?></span></span>
               </div>
               <div class="">&nbsp;</div>
               <div class="givemesomespaceless">
                  <span class="">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"],33) ?></span></span>
               </div>
               <div class="">
                  <span class="">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
               </div>
               <div class="">&nbsp;</div>
               <div class="">
                  <span class="">Pagamento: 
                  <span class="printvalue">
                  <?php echo limittext($row["paymentType"],10) ?>
                  <?php if ($row["transaction"] != "") {?>
                  <span class="">(<?php echo $row["transaction"] ?>)</span>
                  <?php }?>
                  </span>
                  </span>
               </div>
               <div class="">Valor: <span class="printvalue"><?php echo limittext($row["amount_topay"]) ?></span></div>
               <div class="">&nbsp;</div>
               <div class="">
                  <span class="">
                  Apol. Seg.: <span class="printvalue"><?php echo limittext($row["insurance_policy"],35) ?></span>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="">
                  <span class="">
                  Responsável: <span class="printvalue"><?php echo limittext($row["eventRespName"],34) ?></span>
                  </span>
               </div>
               <div class="">
                  <span class="">
                  CNPJ/CPF: <span class="printvalue"><?php echo documentformatBR($row["eventRespDoc"]) ?></span>
                  </span>
               </div>
               <div class="forcetwolines">
                    <span class=""><?php echo limittextandforce($row["user"]." V:".$row["purchase_date"]." IM:".$row["print_date"],500) ?>
                  </span>

               </div>
               <div class="printright">
                  <span class="printqtd">
                  Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
            </div>
         </div>
         <?php //."12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789" ?>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart center">
            <img src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 55px; filter: invert(100%); text-align: center; margin: 0 auto; display: flex;" />
            </div>
         </div>
         <div class="container givemesomespace">
            <div class="fixed"><img class="qrcode" style="<?php echo ($count == 1 || $count == "1") ? "" : "" ?>" src="data:image/png;base64,<?php echo $row["qrcode"]?>" alt="image 1" width="96" height="48"/></div>
            <div class="flex-item">
               <div class="printbase printsecondpart">
                  <div class="printname forcetwolines"><?php echo limittext($row["name"],50) ?></div>
                  <div class="">&nbsp;</div>
                  <div class="printday">
                     <span class=""><?php echo $row["weekdayName"] ?></span>
                     <span class=""><?php echo $row["day"]."-".$row["monthName"]."-".$row["year"] ?></span>
                     <span class=""><?php echo $row["hour"] ?></span>
                  </div>
                  <div class="">&nbsp;</div>
                  <div class="">
                     <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"],8) ?></span></span>
                     <span class="">Fileira: <span class="printvalue"><?php echo limittext($row["seatRow"],5) ?></span></span>
                     <span class="">Assento: <span class="printvalue"><?php echo limittext($row["seatName"],5) ?></span></span>
                  </div>
                  <div class="">
                     <span class="">Bilhete: <span class="printvalue"><?php echo limittext($row["ticket"],25) ?></span></span>
                  </div>
                  <div class="">
                     <span class="">ID: <span class="printvalue"><?php echo $row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"] ?></span></span>
                  </div>
                  <div class="">
                     <span class="">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"],36) ?></span></span>
                  </div>
                  <div class="">
                     <span class="">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
                  </div>
                  <div class="">
                    <span class="">Vendido por: <?php echo limittextandforce($row["user"],35) ?>
                    </span>
                  </div>
                  <div class="">
                    <span class="forcetwolines"><?php echo limittextandforce(" V:".$row["purchase_date"]." IM:".$row["print_date"],80) ?>
                    </span>
                  </div>
                  <div class="">&nbsp;</div>
                  <div class="">&nbsp;</div>
                  <div class="">&nbsp;</div>
                  <div class="printright">
                     <span class="printqtd">
                     Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                     </span>
                  </div>
               </div>
            </div>
         </div>
         <div class="">&nbsp;</div>
         <div class="">&nbsp;</div>
         <div class="">&nbsp;</div>
         <div class="container" style="width: 858px;">
            <div class="printbase printsecondpart center">
               <img src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 55px; filter: invert(100%); text-align: center; margin: 0 auto; display: flex;" />
               <div class="printname"><?php echo limittext($row["name"],30) ?></div>
               <div class="printday">
                  <span class=""><?php echo $row["weekdayName"] ?></span>
                  <span class=""><?php echo $row["day"]."-".$row["monthName"]."-".$row["year"] ?></span>
                  <span class=""><?php echo $row["hour"] ?></span>
               </div>
               <div class="">
                  <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"],8) ?></span></span>
                  <span class="">Fileira: <span class="printvalue"><?php echo limittext($row["seatRow"],5) ?></span></span>
                  <span class="">Assento: <span class="printvalue"><?php echo limittext($row["seatName"],5) ?></span></span>
               </div>
               <div class="">
                  <span class="">Bilhete: <span class="printvalue"><?php echo limittext($row["ticket"],25) ?></span></span>
               </div>
               <div class="">
                  <span class="">ID: <span class="printvalue"><?php echo $row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"] ?></span></span>
               </div>
               <div class="">
                  <span class="">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"],36) ?></span></span>
               </div>
               <div class="">
                  <span class="">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
               </div>
               <div class="">
                  <span class="">Vendido por: <?php echo limittextandforce($row["user"],35) ?>
                  </span>
               </div>
               <div class="">
                  <span class="forcetwolines"><?php echo limittextandforce(" V:".$row["purchase_date"]." IM:".$row["print_date"],80) ?>
                  </span>
               </div>
               <div class="printright">
                  <span class="printqtd">
                  Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      <div class="pagebreak"></div>
      <script lang="javascript">
         <?php 
            if ($dontprint == false) {
               //echo "window.print();";
            }
            if ($dontclose == false) {
               // echo "window.close();";
            }
            ?>
      </script>
   </body>
</html>