<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   
   function splitResp($value, $which) {
       $ret = array();
   
       if ($value === null || $value === '')
           return $ret;
   
       $aux1 = explode(":", $value);
       return $aux1[$which];
   }
   function get($id_base, $key) {
      $query = "EXEC pr_accounting ?";
      // die("dd".json_encode($codVenda));
      $params = array($key);
      $result = db_exec($query, $params, $id_base);
  
      $json = array();
      foreach ($result as &$row) {    
          $json[] = array(
              "local"=>$row["local"]
              ,"weekdayname"=>$row["weekdayname"]
              ,"weekdayfull"=>$row["weekdayfull"]
              ,"event"=>$row["event"]
              ,"responsible"=>$row["responsible"]
              ,"responsibleDoc"=>$row["responsibleDoc"]
              ,"responsibleAddress"=>$row["responsibleAddress"]
              ,"number"=>$row["number"]
              ,"presentation_number"=>$row["presentation_number"]
              ,"presentation_date"=>$row["presentation_date"]
              ,"presentation_hour"=>$row["presentation_hour"]
              ,"sector"=>$row["sector"]
              ,"totalizer_all"=>$row["totalizer_all"]
              ,"totalizer_notsold"=>$row["totalizer_notsold"]
              ,"totalizer_free"=>$row["totalizer_free"]
              ,"totalizer_paid"=>$row["totalizer_paid"]
              ,"totalizer_paid_and_free"=>$row["totalizer_paid_and_free"]
              ,"NomSetor"=>$row["NomSetor"]
              ,"TipBilhete"=>$row["TipBilhete"]
              ,"sold"=>$row["sold"]
              ,"refund"=>$row["refund"]
              ,"ValPagto"=>$row["ValPagto"]
              ,"ValPagtoformatted"=>$row["ValPagtoformatted"]
              ,"soldamount"=>$row["soldamount"]
              ,"soldamountformatted"=>$row["soldamountformatted"]
              ,"occupancyrate"=>$row["occupancyrate"]
              ,"total_refund"=>$row["total_refund"]
              ,"total_sold"=>$row["total_sold"]
              ,"total_soldamount"=>$row["total_soldamount"]
              ,"total_soldamountformatted"=>$row["total_soldamountformatted"]
              ,"date"=>$row["date"]
          );
      }
  
      logme();
  
      return $json;
  }
   function getdebs($id_base, $key) {
       $query = "EXEC pr_accounting_debits ?";
       // die("dd".json_encode($codVenda));
       $params = array($key);
       $result = db_exec($query, $params, $id_base);
   
       $json = array();
       foreach ($result as &$row) {    
           $json[] = array(
               "CodTipDebBordero"=>$row["CodTipDebBordero"]
               ,"DebBordero"=>$row["DebBordero"]
               ,"PerDesconto"=>$row["PerDesconto"]
               ,"PerDescontoformatted"=>$row["PerDescontoformatted"]
               ,"TipValor"=>$row["TipValor"]
               ,"amount"=>$row["amount"]
               ,"amountformatted"=>$row["amountformatted"]
               ,"total_onlydeb"=>$row["total_onlydeb"]
               ,"total_onlydebformatted"=>$row["total_onlydebformatted"]
               ,"total_amount"=>$row["total_amount"]
               ,"total_amountformatted"=>$row["total_amountformatted"]
           );
       }
   
       logme();
   
       return $json;
   }
   function getpayments($id_base, $key) {
      $query = "EXEC pr_accounting_payment ?";
      // die("dd".json_encode($codVenda));
      $params = array($key);
      $result = db_exec($query, $params, $id_base);
  
      $json = array();
      foreach ($result as &$row) {    
          $json[] = array(
              "CodForPagto"=>$row["CodForPagto"]
               ,"ForPagto"=>$row["ForPagto"]
               ,"taxa_administrativa"=>$row["taxa_administrativa"]
               ,"taxa_administrativa_formatted"=>$row["taxa_administrativa_formatted"]
               ,"sold"=>$row["sold"]
               ,"percentage"=>$row["percentage"]
               ,"percentage_formatted"=>$row["percentage_formatted"]
               ,"soldamount"=>$row["soldamount"]
               ,"soldamount_formatted"=>$row["soldamount_formatted"]
               ,"discount"=>$row["discount"]
               ,"discount_formatted"=>$row["discount_formatted"]
               ,"total"=>$row["total"]
               ,"total_formatted"=>$row["total_formatted"]
               ,"PrzRepasseDias"=>$row["PrzRepasseDias"]
               ,"transfer_date"=>$row["transfer_date"]
               ,"sold_total"=>$row["sold_total"]
               ,"sold_total_formatted"=>$row["sold_total_formatted"]
               ,"percentage_total"=>$row["percentage_total"]
               ,"percentage_total_formatted"=>$row["percentage_total_formatted"]
               ,"soldamount_total"=>$row["soldamount_total"]
               ,"soldamount_total_formatted"=>$row["soldamount_total_formatted"]
               ,"discount_total"=>$row["discount_total"]
               ,"discount_total_formatted"=>$row["discount_total_formatted"]
               ,"total_total"=>$row["total_total"]
               ,"total_total_formatted"=>$row["total_total_formatted"]
          
          );
      }
  
      logme();
  
      return $json;
   }
   
   $obj = get($_REQUEST["id_base"], $_REQUEST["id"]);
   $objDeb = getdebs($_REQUEST["id_base"], $_REQUEST["id"]);
   $objPayment = getpayments($_REQUEST["id_base"], $_REQUEST["id"]);
   //die(json_encode($obj));
   //die(json_encode($objDeb));
   // die(json_encode($objPayment));

   $dontbreakline = $_REQUEST["dontbreakline"] != null && $_REQUEST["dontbreakline"] != '';
   $dontclose = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   $dontprint = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   $logo = getDefaultMediaHost()."/logos/".get_uniquename_by_apikey('').".png";

   if ($_REQUEST["exportto"]=="sheet") {
      header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
      header("Content-Disposition: attachment; filename=bordero.xls");  //File name extension was wrong
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
   }
   if ($_REQUEST["exportto"] == "pdf") {
      header('Content-Type: application/pdf');
      header('Content-disposition: inline; filename="bordero.pdf"');
      header('Cache-Control: public, must-revalidate, max-age=0');
      header('Pragma: public');
      header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
      header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
      readfile('bordero.pdf'); 
   }

?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title></title>
      <style>
         .pagebreak { page-break-after: always; } 
         .principal {
            width: 650px;
            font-family: tahoma,verdana,arial;
         }
         .logo img {
            max-width: 60px;
         }
         .right-side-header {
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left: 15px;
            padding-right: 15px;
            text-align: center;
            width: 282px;
            float: right;
            background-color: #e0e0e0;
            /* border: solid 1px red; */
         }
         .right-side-header span {
            display: block;
            font-weight: bold;
            text-transform: uppercase;
         }
         .printonly_lines_values {
            border-bottom: solid 1px #e0e0e0;
         }
         .right-side-header span:first-child {
            font-size: 18px;
         }
         .right-side-header span:last-child {
            font-size: 14px;
         }
         .left-side-header {
            /* border: solid 1px green; */
         }
         .label-info {
            font-size: 10px;
            font-weight: bold;
         }
         .value-info {
            font-size: 10px;
         }
         .grid1 {
            font-size: 14px;
            background-color: #e0e0e0;
         }
         .grid2 {
            margin-top: 10px;
            font-size: 14px;
            background-color: #e0e0e0;
         }
         .grid3 {
            margin-top: 10px;
            font-size: 14px;
            background-color: #e0e0e0;
         }
         .total_discount {
            font-size: 10px;
            text-transform: uppercase;
            text-align: right;
            font-weight: bold;
         }
         .disclaimer {
            background-color: #fff;
            font-size: 11px;
         }
         .signature {
            background-color: #fff;
            font-size: 8px;
         }
         .signaturetable {
            border: none; 
            width: 390px;
            padding-top: 56px;
         }
         .signaturetable_line {
            font-size: 10px;
         }
      </style>
   </head>
   <body>
   <table class="principal">
      <tr>
         <td class="left-side-header">
            <div class="logo">
               <img src="<?php echo $logo ?>" />
            </div>
         </td>  
         <td class="right-side-header">
            <span class="right-side-header-first">Borderô de Vendas</span>
            <span>Contabilização dos Ingressos</span>
         </td>
      </tr>
   </table>
   <table class="principal">
      <tr style="line-height: 10px;">
         <td>
            <span class="label-info">Local:</span>
         </td> 
         <td>
            <span class="value-info"><?php echo $obj[0]["local"] ?></span>
         </td>
         <td>
            <span class="label-info">Borderô nº: </span>
         </td>
         <td>
            <span class="value-info"><?php echo $obj[0]["number"] ?></span>
         </td>
      </tr>
      <tr style="line-height: 10px;">
         <td>
            <span class="label-info">Evento:</span>
         </td> 
         <td>
            <span class="value-info"><?php echo $obj[0]["event"] ?></span>
         </td>
         <td>
            <span class="label-info">Apresentação nº: </span>
         </td>
         <td>
            <span class="value-info"><?php echo $obj[0]["presentation_number"] ?></span>
         </td>
      </tr>
      <tr style="line-height: 10px;">
         <td>
            <span class="label-info">Responsável:</span>
         </td> 
         <td>
            <span class="value-info"><?php echo $obj[0]["responsible"] ?></span>
         </td>
         <td>
            <span class="label-info">Data e Horário: </span>
         </td>
         <td>
            <span class="value-info"><?php echo $obj[0]["presentation_date"] ?> / <?php echo $obj[0]["presentation_hour"] ?></span>
         </td>
      </tr>
      <tr style="line-height: 10px;">
         <td>
            <span class="label-info">CNPJ/CPF:</span>
         </td> 
         <td>
            <span class="value-info"><?php echo $obj[0]["responsibleDoc"] ?></span>
         </td>
         <td>
            <span class="label-info">Dia: </span>
         </td>
         <td>
            <span class="value-info"><?php echo $obj[0]["weekdayfull"] ?></span>
         </td>
      </tr>
      <tr style="line-height: 10px;">
         <td>
            <span class="label-info">Endereço:</span>
         </td> 
         <td>
            <span class="value-info"><?php echo $obj[0]["responsibleAddress"] ?></span>
         </td>
         <td>
            <span class="label-info">Setor: </span>
         </td>
         <td>
            <span class="value-info"><?php echo $obj[0]["sector"] ?></span>
         </td>
      </tr>
   </table>
   <table class="principal">
      <tr style="line-height: 10px;">
         <td style="text-align:center">
            <span class="label-info">Lotação/Capacidade :: </span>
            <span class="value-info"><?php echo $obj[0]["totalizer_all"] ?></span>
         </td>
      </tr>
   </table>
   <table class="principal">
      <tr style="line-height: 10px;">
         <td style="text-align:center">
            <span class="label-info">Ingressos não vendidos :: </span>
            <span class="value-info"><?php echo $obj[0]["totalizer_notsold"] ?></span>
         </td>
         <td style="text-align:center">
            <span class="label-info">Público convidado :: </span>
            <span class="value-info"><?php echo $obj[0]["totalizer_free"] ?></span>
         </td>
         <td style="text-align:center">
            <span class="label-info">Público pagante :: </span>
            <span class="value-info"><?php echo $obj[0]["totalizer_paid"] ?></span>
         </td>
         <td style="text-align:center">
            <span class="label-info">Público total :: </span>
            <span class="value-info"><?php echo $obj[0]["totalizer_paid_and_free"] ?></span>
         </td>
      </tr>
   </table>
   <table class="principal grid1">
         <tr>
            <td colspan="6" style="text-align:center;font-weight: bold;font-size: 14px;">1 - Vendas Borderô</td>
         </tr>
         <tr style="font-weight: bold;font-size: 10px">
            <td style="text-align:left">Setor</td>
            <td style="text-align:left">Tipo de Ingressos</td>
            <td style="text-align:right">Qtde Estornados</td>
            <td style="text-align:right">Qtde Vendidas</td>
            <td style="text-align:right">Preço</td>
            <td style="text-align:right">Sub Total</td>
         </tr>
         <?php foreach ($obj as &$row) {?>
            <tr style="font-size: 11px;">
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["NomSetor"] ?></td>
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["TipBilhete"] ?></td>
               <td class="printonly_lines_values" style="text-align:right">
                  <?php echo ($row["refund"]==0) ? "-" : $row["refund"]; ?>
               </td>
               <td class="printonly_lines_values" style="text-align:right">
                  <?php echo ($row["sold"]==0) ? "-" : $row["sold"]; ?>
               </td>
               <td class="printonly_lines_values" style="text-align:right">
                  <?php echo ($row["ValPagto"]==0) ? "-" : "R$ ".$row["ValPagtoformatted"]; ?>
               </td>
               <td class="printonly_lines_values" style="text-align:right">
                  <?php echo ($row["soldamount"]==0) ? "-" : "R$ ".$row["soldamountformatted"]; ?>
               </td>
            </tr>
         <?php } ?>
         <tr>
            <td colspan="2" style="background-color:#ffff;font-weight:bold;font-size:15px;">
               <span>Taxa de ocupação:</span>
               <span><?php echo $obj[0]["occupancyrate"]; ?>%</span>
            </td>
            <td style="font-size: 10px;font-weight:bold;text-align:right;">
               <?php echo $obj[0]["total_refund"]; ?>
            </td>
            <td style="font-size: 10px;font-weight:bold;text-align:right;">
               <?php echo $obj[0]["total_sold"]; ?>
            </td>
            <td style="font-size: 10px;font-weight:bold;text-align:right;">
               
            </td>
            <td style="font-size: 10px;font-weight:bold;text-align:right;">
               R$ <?php echo $obj[0]["total_soldamountformatted"]; ?>
            </td>
         </tr>
   </table>
   <div class="pagebreak"></div>
   <table class="principal grid2">
      <tr>
         <td colspan="3" style="text-align:center;font-weight: bold;font-size: 14px;">2 - Descontos Borderô</td>
      </tr>
      <tr style="font-weight: bold;font-size: 10px">
         <td style="text-align:left">Tipo de Débito</td>
         <td style="text-align:right">% ou R$ Fixo</td>
         <td style="text-align:right">Valor</td>
      </tr>         
      <?php foreach ($objDeb as &$row) {?>
         <tr style="font-size: 11px;">
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["DebBordero"] ?></td>
            <td class="printonly_lines_values" style="text-align:right">
               <?php echo $row["TipValor"] == 'V' ? "R$ " : "" ?>
               <?php echo $row["PerDescontoformatted"]; ?>
               <?php echo $row["TipValor"] == 'P' ? " %" : "" ?>
            </td>
            <td class="printonly_lines_values" style="text-align:right">
               R$ <?php echo $row["amountformatted"]; ?>
            </td>
         </tr>
         <?php } ?>
         <tr>
            <td class="signature">assinaturas dos responsáveis, <?php echo $obj[0]["date"] ?></td>
            <td colspan="2" class="total_discount">Total descontos</td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount printonly_lines_values">
               R$
               <span class="debs_amount">
               <?php echo $objDeb[0]["total_onlydebformatted"] ?>
               </span>
            </td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount">&nbsp;</td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount">Vendas - descontos</td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount printonly_lines_values">
               R$
               <span class="debs_amount">
               <?php echo $objDeb[0]["total_amountformatted"] ?>
               </span>
            </td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount">&nbsp;</td>
         </tr>
         <tr>
            <td class="signature">&nbsp;</td>
            <td colspan="2" class="total_discount">&nbsp;</td>
         </tr>
         <tr>
            <td class="signature">
               <table class="signaturetable">
                  <tr>
                     <td class="signaturetable_line">____________________</td>
                     <td style="padding-left: 22px;" class="signaturetable_line">____________________</td>
                     <td style="padding-left: 22px;" class="signaturetable_line">____________________</td>
                  </tr>
                  <tr>
                     <td style="text-align: center;">BILHETERIA</td>
                     <td style="text-align: center;padding-left: 22px;">LOCAL</td>
                     <td style="text-align: center;padding-left: 22px;">PRODUÇÃO</td>
                  </tr>
               </table>

            </td>
            <td colspan="2" class="total_discount">&nbsp;</td>
         </tr>
         <tr>
            <td colspan="3" class="disclaimer">
               O Borderô de vendas assinados pelas partes envolvidas, dará a plena quitação dos valores pagos em dinheiro no momento do fechamento, portanto, confira atentamente os valores recebidos em dinheiro, vales/recibos de saques e comprovantes de depósito. Os valores vendidos através dos cartões de crédito e débito serão repassados aos favorecidos de acordo com os prazos firmados através do contrato prestação de serviços assinado pelas partes.
            </td>
         </tr>
   </table>
   <table class="principal grid3">
   <tr>
         <td colspan="8" style="text-align:center;font-weight: bold;font-size: 14px;">3 - DETALHAMENTO POR FORMA DE PAGAMENTO</td>
      </tr>
      <tr>
         <td colspan="8" style="text-align:center;font-weight: bold;font-size: 14px;">(apenas para conferência de valores e quantidades)</td>
      </tr>
      <tr style="font-weight: bold;font-size: 8px">
         <td style="text-align:left">Tipo de forma de pagamento</td>
         <td style="text-align:right">%</td>
         <td style="text-align:right">Qtde de Transações</td>
         <td style="text-align:right">Valores brutos</td>
         <td style="text-align:right">Taxa</td>
         <td style="text-align:right">Desconto Taxa</td>
         <td style="text-align:right">Repasses</td>
         <td style="text-align:right">Data de repasse</td>
      </tr>         
      <?php foreach ($objPayment as &$row) {?>
         <tr style="font-size: 9px;">
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["ForPagto"] ?></td>
            <td class="printonly_lines_values" style="text-align:right"><?php echo $row["percentage_formatted"] ?></td>
            <td class="printonly_lines_values" style="text-align:right"><?php echo $row["sold"] ?></td>
            <td class="printonly_lines_values" style="text-align:right">R$ <?php echo $row["soldamount_formatted"] ?></td>
            <td class="printonly_lines_values" style="text-align:right"><?php echo $row["taxa_administrativa_formatted"] ?></td>
            <td class="printonly_lines_values" style="text-align:right">R$ <?php echo $row["discount_formatted"] ?></td>
            <td class="printonly_lines_values" style="text-align:right">R$ <?php echo $row["total_formatted"] ?></td>
            <td class="printonly_lines_values" style="text-align:right"><?php echo $row["transfer_date"] ?></td>     
         </tr>
         <?php } ?>
         <tr style="font-size: 9px;">
            <td class="printonly_lines_values" style="text-align:left; font-weight: bold;">
               Total:
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               <?php echo $objPayment[0]["percentage_total_formatted"] ?>
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               <?php echo $objPayment[0]["sold_total"] ?>
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               R$ <?php echo $objPayment[0]["soldamount_total_formatted"] ?>
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               R$ <?php echo $objPayment[0]["discount_total_formatted"] ?>
            </td>
            <td class="printonly_lines_values" style="text-align:right; font-weight: bold;">
               R$ <?php echo $objPayment[0]["total_total_formatted"] ?>
            </td>
         </tr>
   </table>
      <script lang="javascript">
         <?php 
            if ($dontprint == false) {
         ?>
               setTimeout(function() { 
                 window.print();
               }, 1000);
         <?php
            }
         ?>
      </script>
   </body>
</html>
