<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

   //use Spipu\Html2Pdf\Html2Pdf;
   $mpdf = new \Mpdf\Mpdf();
   
   function get($comission, $dtinit, $dtend) {
      $uniquename = get_uniquename_by_apikey('');
      // $uniquename = "sazarte";
      // die($uniquename);
      $query = "EXEC pr_report_partnersale ?,?,?,?";
      // die("dd".json_encode($codVenda));
      $params = array($comission,$dtinit,$dtend,$uniquename);
      $result = db_exec($query, $params);
  
      $json = array();
      foreach ($result as &$row) {    
          $json[] = array(
              "id_pedido_venda"=>$row["id_pedido_venda"]
              ,"total"=>$row["total"]
              ,"total_formatted"=>$row["total_formatted"]
              ,"total_comission"=>$row["total_comission"]
              ,"total_comission_formatted"=>$row["total_comission_formatted"]
              ,"dt_pedido_venda"=>$row["dt_pedido_venda"]
              ,"ds_evento"=>$row["ds_evento"]
              ,"uri"=>$row["uri"]
              ,"dt_apresentacao"=>$row["dt_apresentacao"]
              ,"hr_apresentacao"=>$row["hr_apresentacao"]
              ,"vl_total_pedido_venda"=>$row["vl_total_pedido_venda"]
              ,"ds_meio_pagamento"=>$row["ds_meio_pagamento"]
              ,"comission"=>$row["comission"]
              ,"comission_amount"=>$row["comission_amount"]
              ,"comission_amount_formatted"=>$row["comission_amount_formatted"]
              ,"client_name"=>$row["client_name"]
              ,"cd_cpf"=>$row["cd_cpf"]
              ,"cd_email_login"=>$row["cd_email_login"]
              ,"dt_nascimento"=>$row["dt_nascimento"]
              ,"nr_parcelas_pgto"=>$row["nr_parcelas_pgto"]
              ,"isInstallment"=>$row["isInstallment"]
              ,"host"=>$row["host"]
          );
      }
  
      logme();
  
      return $json;
  }
   $obj = get($_REQUEST["amount"], $_REQUEST["init"], $_REQUEST["end"]);
   // die(json_encode($obj));

   $uniquename = get_uniquename_by_apikey('');
   // die($uniquename);
   $wl_obj = getwhitelabelobjforced($uniquename);
   
   // info companyName  CNPJ
   $logo = getDefaultMediaHost()."/logos/logo-".$uniquename.".png";

   ob_start();
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
<div class="logo" style="text-align: center;">
   <img style="max-width:60px;" src="<?php echo $logo ?>" />
</div>
<br />
<div style="text-align: center;font-size:12px; width:100%;">
   <p class="pmarginzero"><?php echo $wl_obj["info"]["companyName"] ?></p>
   <p class="pmarginzero"><?php echo $wl_obj["info"]["CNPJ"] ?></p>
   <p style="font-weight: bold; text-transform: uppercase">Relatório de vendas por parceiro</p>
</div>
</htmlpageheader>
<htmlpageheader name="header_otherpages" style="display:none;">
</htmlpageheader>

<htmlpagefooter name="footer">
   <div style="text-align: center;font-size:12px; width:100%;">
      <p class="pmarginzero"><?php echo "Emitido em " .date("d/m/Y")." - ".date("H:i:s") ?></p>
      <p class="pmarginzero">{PAGENO}/{nbpg}</p>
   </div>
</htmlpagefooter>

<table class="grid" style="padding-top:130px;">
   <tr>
      <td colspan="6" class="left bold">
         <p class="pmarginzero">Parâmetros</p>
      </td>
   </tr>
   <tr>
      <td colspan="6" class="left">
         <p class="pmarginzero"><b>Data inicio:</b> <?php echo modifyDateUStoBR($_REQUEST["init"]) ?></p>
         <p class="pmarginzero"><b>Data fim:</b> <?php echo modifyDateUStoBR($_REQUEST["end"]) ?></p>
         <p class="pmarginzero"><b>% Comissão:</b> <?php echo $_REQUEST["amount"] ?></p>
      </td>
   </tr>
   <tr>
      <td colspan="6" class="left">
         <p class="pmarginzero"></p>
      </td>
   </tr>
</table>
   <table class="grid" repeat_header="1">
         <thead>
            <tr style="margin-bottom: 5px;border: 1px solid #e3e3e3;">
               <td style="width: 7%;font-weight: bold;" class="left">ID</td>
               <td style="width: 35%;font-weight: bold;" class="left">Evento</td>
               <td style="width: 12%;font-weight: bold;" class="left">Data venda</td>
               <td style="width: 12%;font-weight: bold;" class="left">Pagamento</td>
               <td style="font-weight: bold;" class="right">Valor venda</td>
               <td style="font-weight: bold;" class="right">Comissão</td>
            </tr>
         </thead>
         <tbody>
         <?php foreach ($obj as &$row) {?>
            <tr class="content">
               <td class="printonly_lines_values left" style="font-size:10px;"><?php echo $row["id_pedido_venda"] ?></td>
               <td class="printonly_lines_values left"><?php echo $row["ds_evento"] ?>
               <br />
               <span style="font-size:10px;">
               <?php echo $row["dt_apresentacao"] ?> - <?php echo $row["hr_apresentacao"] ?>
               </span>
               </td>
               <td class="printonly_lines_values left"><?php echo $row["dt_pedido_venda"] ?></td>
               <td class="printonly_lines_values left"><?php echo $row["ds_meio_pagamento"] ?></td>
               <td class="printonly_lines_values right">R$ <?php echo $row["vl_total_pedido_venda"] ?></td>
               <td class="printonly_lines_values right">R$ <?php echo $row["comission_amount_formatted"] ?>
               <br />
               <span style="font-size:10px;">
               <?php echo $row["comission"] ?>%
               </span>
            </td>
            </tr>
         <?php } ?>
         </tbody>
         <tr>
            <td class="left fs13" colspan="3">
               <span class="label-info" style="font-weight: bold;">Total de venda: </span>
               <span class="value-info">R$ <?php echo $obj[0]["total_formatted"] ?></span>
            </td>
            <td class="right fs13" colspan="4">
               <span class="label-info" style="font-weight: bold;">Total da comissão: </span>
               <span class="value-info">R$ <?php echo $obj[0]["total_comission_formatted"] ?></span>
            </td>
         </tr>
   </table>
</page>  
<?php 
$content = ob_get_clean();

// ob_start();
// require_once($_SERVER['DOCUMENT_ROOT']."/v1/print/report/partnersale.header.php");
// $header = ob_get_clean();

// ob_start();
// require_once($_SERVER['DOCUMENT_ROOT']."/v1/print/report/partnersale.footer.php");
// $footer = ob_get_clean();

// die($header);

// $mpdf->SetHTMLHeader($header);
// $mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($content);
$mpdf->Output();
// $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(15, 5, 15, 5));
//     $html2pdf->pdf->SetDisplayMode('fullpage');
//     $html2pdf->writeHTML($content);
//     $html2pdf->output("printsale.pdf");
//die("dd");  
//echo "oiii";
//die($content);

?>