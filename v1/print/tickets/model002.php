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
               ,"reprint"=>$row["reprint"]
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
   // $moretextteste = " Bacon ipsum dolor amet cow ball tip corned beef ribeye pancetta andouille pastrami jowl shoulder.";
   // $moretextteste2 = " Baconipsumdolorametcowballtipcornedbeefribeyepancettaandouillepastramijowlshoulder.";
   // $moretextteste = "";
   // $moretextteste2 = "";
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
         .leftdonttouchme {
            padding-left:10px;
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
         width: 110px;
         height: 110px;
         position: relative;
         }
         .logobottom {
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
         .smalltext{
            font-size:10px;
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
         width: 420px;
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
         .imbiggerthanyou {
            font-size: 22px;
            font-weight: bold;
         }
         .printbase .printvalue {
         font-weight: bold;
         }
         .printbase .printright {
         text-align: right;
         }
         .printbase .printqtd {
         font-size: 10px;
         padding-right: 10px;
         }
         .printfirstpart {
         
         }
         .textcenter {
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
         .somethingisbetweenus {
            margin-top: 2px;
            /* border-bottom: black solid 2px;    */
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
         .forcethreelines {
            min-height:36px;
         }

         .top_partnerinfo{
         display: flex;
         }
         .top_partnerinfo_img{
         width: 200px;
         }
         .top_partnerinfo_text{
         flex-grow: 1;
         }
         .theskiesisoutoflimit {
            padding-top:12px;
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
      <div style="border: black solid 2px; padding: 2px 2px 2px 2px;">
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart">
               <div class="top_partnerinfo center">
                  <span>
                     <img src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 55px; filter: invert(100%); margin-top: 40px;margin-right: 10px;" />
                  </span>
                  <span class="textcenter" style="padding-left: 48px;">
                     <div class="forcetwolines theskiesisoutoflimit"><?php echo limittext(getwhitelabelobj()["info"]["companyName"].$moretextteste,50) ?></div>
                     <div class="smalltext">CNPJ: <?php echo getwhitelabelobj()["info"]["CNPJ"] ?></div>
                     <div class="forcetwolines smalltext"><?php echo limittext(getwhitelabelobj()["uri"].$moretextteste2,48); ?></div>
                     <div class="">&nbsp;</div>
                     <div class="forcetwolines"><?php echo limittext($row["local"].$moretextteste,50); ?></div>
                     <div class="forcethreelines"><?php echo limittext($row["address"].$moretextteste,75); ?></div>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="leftdonttouchme printname forcethreelines"><?php echo limittext($row["name"].$moretextteste,81) ?></div>
               <div class="printday leftdonttouchme">
                  <span class=""><?php echo limittext($row["weekdayName"]) ?></span>
                  <span class=""><?php echo limittext($row["day"]."-".$row["monthName"]."-".$row["year"]) ?></span>
                  <span class=""><?php echo limittext($row["hour"]) ?></span>
               </div>
               <div class="leftdonttouchme">Abertura: <span class="printvalue"><?php echo limittext($row["opening_time"].$moretextteste,36) ?></span></div>
               <div class="leftdonttouchme">
                  <span class="">Bilh.e: <span class="printvalue"><?php echo limittext($row["ticket"].$moretextteste,15) ?></span></span>
                  <span class="">- <span class="" style="font-size:12px;"><?php echo limittext($row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"].$moretextteste,25) ?></span></span>
               </div>
               <div class="givemesomespaceless">
                  <span class="leftdonttouchme">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"].$moretextteste,30) ?></span></span>
               </div>
               <div class="">
                  <span class="leftdonttouchme">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
               </div>
               <div class="givemesomespaceless leftdonttouchme">
                  <span class="">Pgto.: 
                  <span class="printvalue">
                  <?php echo limittext($row["paymentType"].$moretextteste,25) ?>
                  <?php if ($row["transaction"] != "") {?>
                  <span class="">(<?php echo limittext($row["transaction"],10) ?>)</span>
                  <?php }?>
                  </span>
                  </span>
               </div>
               <div class="leftdonttouchme">Valor: <span class="printvalue"><?php echo limittext($row["amount_topay"]) ?></span></div>
               <div class="somethingisbetweenus" style="margin-bottom: 12px;"></div>
               <div class="leftdonttouchme imbiggerthanyou" style="text-align: center;">
                  <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"].$moretextteste, 24) ?></span></span>
               </div>
               <div class="leftdonttouchme imbiggerthanyou" style="padding-top:5px;margin-top:10px; text-align: center;">
                  <?php if ($row["IngressoNumerado"] == 1) {?>
                  <span class="">Assento: <span class="printvalue"><?php echo limittext($row["seatRow"]."-".$row["seatName"].$moretextteste,20) ?></span></span>
                  <?php } ?>
                  <?php if ($row["IngressoNumerado"] == 0) {?>
                  <span class="">Assento: <span class="printvalue"><?php echo limittext($row["seatName"].$moretextteste,20) ?></span></span>
                  <?php } ?>
               </div>
               <div class="somethingisbetweenus" style="margin-top: 10px !important;"></div>
               <div class="">&nbsp;</div>
               <div class="leftdonttouchme">
                  <span class="">
                  Apol. Seg.: <span class="printvalue"><?php echo limittext($row["insurance_policy"].$moretextteste,35) ?></span>
                  </span>
               </div>
               <div class="leftdonttouchme">
                  <span class="">
                  Responsável: <span class="printvalue"><?php echo limittext($row["eventRespName"].$moretextteste,34) ?></span>
                  </span>
               </div>
               <div class="leftdonttouchme">
                  <span class="">
                  CNPJ/CPF: <span class="printvalue"><?php echo documentformatBR($row["eventRespDoc"].$moretextteste) ?></span>
                  </span>
               </div>
               <div class="leftdonttouchme forcetwolines">
                    <span class=""><?php echo limittext($row["user"].$moretextteste,12).limittext(" V:".$row["purchase_date"]." IM:".$row["print_date"]) ?>
                  </span>

               </div>
               <div class="">&nbsp;</div>
               <div class="printright">
                  <span class="printqtd">
                     <?php 
                     if ($row["reprint"] > 1) {
                     ?>
                        <span>Reimpressão - </span>
                     <?php
                     }
                     ?>
                  Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
            </div>
         </div>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart">
               <div class="leftdonttouchme printname forcethreelines" style="text-align: center;"><?php echo limittext($row["name"].$moretextteste,81) ?></div>
            </div>
         </div>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart textcenter">
            <img class="qrcode" style="<?php echo ($count == 1 || $count == "1") ? "" : "" ?>" src="data:image/png;base64,<?php echo $row["qrcode"]?>" alt="image 1" width="96" height="48"/>
            </div>
         </div>
         <div class="container" style="width: 858px;padding-top:10px;">
            <div class="printbase printfirstpart">
               <div class="printday leftdonttouchme">
                  <span class=""><?php echo limittext($row["weekdayName"]) ?></span>
                  <span class=""><?php echo limittext($row["day"]."-".$row["monthName"]."-".$row["year"]) ?></span>
                  <span class=""><?php echo limittext($row["hour"]) ?></span>
               </div>
               <div class="leftdonttouchme">
                  <span class="">Bilh.e: <span class="printvalue"><?php echo limittext($row["ticket"].$moretextteste,15) ?></span></span>
                  <span class="">- <span class="" style="font-size:12px;"><?php echo limittext($row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"].$moretextteste,25) ?></span></span>
               </div>
               <div class="givemesomespaceless">
                  <span class="leftdonttouchme">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"].$moretextteste,30) ?></span></span>
               </div>
               <div class="">
                  <span class="leftdonttouchme">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
               </div>
               <div class="givemesomespaceless leftdonttouchme">
                  <span class="">Pgto.: 
                  <span class="printvalue">
                  <?php echo limittext($row["paymentType"].$moretextteste,28) ?>
                  <?php if ($row["transaction"] != "") {?>
                  <span class="">(<?php echo limittext($row["transaction"],10) ?>)</span>
                  <?php }?>
                  </span>
                  </span>
               </div>
               <div class="leftdonttouchme">
                  <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"].$moretextteste, ($row["IngressoNumerado"] == 1 ? 18 : 38)) ?></span></span>
                  <?php if ($row["IngressoNumerado"] == 1) {?>
                  <span class="">| Assento: <span class="printvalue"><?php echo limittext($row["seatRow"]."-".$row["seatName"].$moretextteste,10) ?></span></span>
                  <?php } ?>
               </div>
               <div class="leftdonttouchme forcetwolines">
                    <span class=""><?php echo limittext($row["user"].$moretextteste,12).limittext(" V:".$row["purchase_date"]." IM:".$row["print_date"]) ?>
                  </span>

               </div>
               <div class="printright">
                  <span class="printqtd">
                  <?php 
                     if ($row["reprint"] > 1) {
                     ?>
                        <span>Reimpressão - </span>
                     <?php
                     }
                     ?>
                  Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="">&nbsp;</div>
            </div>
         </div>

         <div class="">&nbsp;</div>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart textcenter">
            <img class="logobottom" src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 65px; filter: invert(100%);" alt="image 1"/>
            </div>
         </div>
         <div class="container" style="width: 858px;margin-top:5px;">
            <div class="printbase printfirstpart">
               <div class="leftdonttouchme printname forcethreelines" style="text-align: center;"><?php echo limittext($row["name"].$moretextteste,81) ?></div>
            </div>
         </div>
         <div class="container" style="width: 858px;">
            <div class="printbase printfirstpart">
               <div class="printday leftdonttouchme">
                  <span class=""><?php echo limittext($row["weekdayName"]) ?></span>
                  <span class=""><?php echo limittext($row["day"]."-".$row["monthName"]."-".$row["year"]) ?></span>
                  <span class=""><?php echo limittext($row["hour"]) ?></span>
               </div>
               <div class="">&nbsp;</div>
               <div class="leftdonttouchme">
                  <span class="">Bilh.e: <span class="printvalue"><?php echo limittext($row["ticket"].$moretextteste,15) ?></span></span>
                  <span class="">- <span class="" style="font-size:12px;"><?php echo limittext($row["purchaseCode"]."-".$row["purchaseCodeInt"]."-".$row["seatIndice"].$moretextteste,25) ?></span></span>
               </div>
               <div class="">&nbsp;</div>
               <div class="givemesomespaceless">
                  <span class="leftdonttouchme">Emitido para: <span class="printvalue"><?php echo limittext($row["buyer"].$moretextteste,30) ?></span></span>
               </div>
               <div class="">
                  <span class="leftdonttouchme">CPF/CNPJ: <span class="printvalue"><?php echo documentformatBR($row["buyerDoc"]) ?></span></span>
               </div>
               <div class="">&nbsp;</div>
               <div class="givemesomespaceless leftdonttouchme">
                  <span class="">Pgto.: 
                  <span class="printvalue">
                  <?php echo limittext($row["paymentType"].$moretextteste,28) ?>
                  <?php if ($row["transaction"] != "") {?>
                  <span class="">(<?php echo limittext($row["transaction"],10) ?>)</span>
                  <?php }?>
                  </span>
                  </span>
               </div>
               <div class="">&nbsp;</div>
               <div class="leftdonttouchme">
                  <span class="">Setor: <span class="printvalue"><?php echo limittext($row["roomName"].$moretextteste, ($row["IngressoNumerado"] == 1 ? 18 : 38)) ?></span></span>
                  <?php if ($row["IngressoNumerado"] == 1) {?>
                  <span class="">| Assento: <span class="printvalue"><?php echo limittext($row["seatRow"]."-".$row["seatName"].$moretextteste,10) ?></span></span>
                  <?php } ?>
               </div>
               <!-- <div class="">&nbsp;</div> -->
               <div class="leftdonttouchme forcetwolines">
                    <span class=""><?php echo limittext($row["user"].$moretextteste,12).limittext(" V:".$row["purchase_date"]." IM:".$row["print_date"]) ?>
                  </span>

               </div>
               <!-- <div class="">&nbsp;</div> -->
               <div class="printright">
                  <span class="printqtd">
                  <?php 
                     if ($row["reprint"] > 1) {
                     ?>
                        <span>Reimpressão - </span>
                     <?php
                     }
                     ?>
                  Qtde: <span class=""><?php echo $row["howMany"] ?></span>
                  </span>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      <!-- <div class="pagebreak"></div> -->
      <script lang="javascript">
         <?php 
            if ($dontprint == false) {
         ?>
               setTimeout(function() { 
                  window.print();
                  window.close();
               }, 1000);
         <?php
            }
         ?>
      </script>
   </body>
</html>