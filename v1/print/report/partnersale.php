<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

   use Spipu\Html2Pdf\Html2Pdf;
   
   function get($comission, $dtinit, $dtend) {
      $uniquename = gethost();
      $uniquename = "sazarte";
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
              ,"ds_tipo_bilhete"=>$row["ds_tipo_bilhete"]
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
   // ob_start();
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title></title>
      <style>
         .pagebreak { page-break-after: always; } 
         .principal {
            
         }
         .printonly_lines_values {
            border-bottom: solid 1px #e0e0e0;
         }
         .grid1 {
            font-size: 13px;
            
         }
         .grid2 {
            margin-top: 10px;
            font-size: 13px;
            
         }

      </style>
   </head>
   <body>
   <table class="principal grid1">
         <tr style="">
            <td style="text-align:left">Data venda</td>
            <td style="text-align:left">Evento</td>
            <td style="text-align:left">Data/Hora</td>
            <td style="text-align:left">Pagamento</td>
            <td style="text-align:right">Valor venda</td>
            <td style="text-align:right">%</td>
            <td style="text-align:right">Comissão</td>
         </tr>
         <?php foreach ($obj as &$row) {?>
            <tr style="font-size: 13px;">
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["dt_pedido_venda"] ?></td>
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["ds_evento"] ?></td>
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["dt_apresentacao"].' '.$row["hr_apresentacao"] ?></td>
               <td class="printonly_lines_values" style="text-align:left"><?php echo $row["ds_meio_pagamento"] ?></td>
               <td class="printonly_lines_values" style="text-align:right">R$ <?php echo $row["vl_total_pedido_venda"] ?></td>
               <td class="printonly_lines_values" style="text-align:right"><?php echo $row["comission"] ?></td>
               <td class="printonly_lines_values" style="text-align:right">R$ <?php echo $row["comission_amount_formatted"] ?></td>
            </tr>
         <?php } ?>
      <tr>
         <td style="text-align:left;font-size: 13px;" colspan="3">
            <span class="label-info">Total de venda :: </span>
            <span class="value-info">R$ <?php echo $obj[0]["total_formatted"] ?></span>
         </td>
         <td style="text-align:right;font-size: 13px;" colspan="4">
            <span class="label-info">Total da comissão :: </span>
            <span class="value-info">R$ <?php echo $obj[0]["total_comission_formatted"] ?></span>
         </td>
      </tr>
   </table>
   </body>
</html>
<?php 
// $content = ob_get_clean();

// $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(15, 5, 15, 5));
//     $html2pdf->pdf->SetDisplayMode('fullpage');
//     $html2pdf->writeHTML($content);
//     $html2pdf->output("printsale.pdf");
//die("dd");  
//echo "oiii";
//die($content);

?>