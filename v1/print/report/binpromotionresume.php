
<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   
   function get($id_user, $startdate, $enddate) {
      isuservalidordie($id_user);

      $startdate = $startdate." 00:00:00";
      $enddate = $enddate." 23:59:59";

      $query = "EXEC pr_bin_promotion_list ?, ?";
      $params = array($startdate, $enddate);
      $result = db_exec($query, $params);
      $json = array();

      foreach ($result as &$row) {
            $json[] = array(
               "buyer"=> $row["buyer"],
               "buyer_document"=> documentformatBR($row["buyer_document"]),
               "bin"=> $row["bin"],
               "sellid"=> $row["sellid"],
               "created_at"=> $row["created_at"],
               "sellfrom"=> $row["sellfrom"],
               "sellamount"=> $row["sellamount"],
               "selltotal"=> $row["selltotal"]
            );
      }

      logme();
      return $json;
   }   
   $obj = get($_REQUEST["loggedId"], $_REQUEST["start"], $_REQUEST["end"]);
   // die(json_encode($obj));

   $dontbreakline = $_REQUEST["dontbreakline"] != null && $_REQUEST["dontbreakline"] != '';
   $dontclose = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   $dontprint = $_REQUEST["dontclose"] != null && $_REQUEST["dontclose"] != '';
   $logo = getDefaultMediaHost()."/logos/logo-".get_uniquename_by_apikey('').".png";

   if ($_REQUEST["exportto"]=="sheet") {
      header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
      header("Content-Disposition: attachment; filename=binpromotion.xls");  //File name extension was wrong
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
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
            /* background-color: #e0e0e0; */
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
            <span class="right-side-header-first">Promoção por BIN</span>
         </td>
      </tr>
   </table>
   <table class="principal">
      <tr style="line-height: 10px;">
         <td style="text-align:center">
            <span class="label-info">Ingressos não vendidos :: </span>
            <span class="value-info"><?php echo $obj[0]["selltotal"] ?></span>
         </td>
      </tr>
   </table>
   <table class="principal grid1">
         <tr style="font-weight: bold;font-size: 10px">
            <td style="text-align:left">Data da venda</td>
            <td style="text-align:left">Comprador Documento</td>
            <td style="text-align:right">BIN do Cartão</td>
            <td style="text-align:right">Valor venda</td>
         </tr>
         <?php foreach ($obj as &$row) {?>
            <tr style="font-size: 11px;">
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["created_at"] ?></td>
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["buyer_document"] ?></td>
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["bin"] ?></td>
            <td class="printonly_lines_values" style="text-align:left"><?php echo $row["sellamount"] ?></td>
            </tr>
         <?php } ?>
   </table>
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
