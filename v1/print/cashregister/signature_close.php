<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');

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
   function getTextType($value) { 
         $ret = $value;
         switch ($value) { 
             case "add":
                 $ret = "Venda";
             break;
             case "refund":
                 $ret = "Estorno";
             break;
             case "withdraw":
                 $ret = "Saque";
             break;
             case "cashdepositopen":
                 $ret = "-";
             break;
             case "cashdeposit":
                 $ret = "-";
             break;
             case "diff":
                 $ret = "-";
             break;
         }
         return $ret;
   }
   function get($id) {
       $query = "EXEC pr_ticketoffice_cashregister_closed_info ?";
       // die("dd".json_encode($codVenda));
       $params = array($id);
       $result = db_exec($query, $params);
   
       $json = array();
       foreach ($result as &$row) {
           $json[] = array(
               "id"=>$row["id"]
               ,"amount"=>$row["amount"]
               ,"qtd"=>$row["qtd"]
               ,"id_base"=>$row["id_base"]
               ,"ds_nome_base_sql"=>$row["ds_nome_base_sql"]
               ,"ds_nome_teatro"=>$row["ds_nome_teatro"]
               ,"type"=>$row["type"]
               ,"typeText"=>getTextType($row["type"])
               ,"desc"=>$row["desc"]
               ,"codForPagto"=>$row["codForPagto"]
               ,"id_evento"=>$row["id_evento"]
               ,"ds_evento"=>$row["ds_evento"]
               ,"nameMoviment"=>$row["nameMoviment"]
               ,"orderby"=>$row["orderby"]
               ,"closed"=>$row["closed"]
               ,"id_ticketoffice_user_closed"=>$row["id_ticketoffice_user_closed"]
               ,"justification_closed"=>$row["justification_closed"]
               ,"nameClose"=>$row["nameClose"]
               ,"amountbyevent"=>$row["amountbyevent"]
               ,"amountbybase"=>$row["amountbybase"]
               ,"qtdbyevent"=>$row["qtdbyevent"]
               ,"qtdbybase"=>$row["qtdbybase"]
               ,"howmanyevents"=>$row["howmanyevents"]
               ,"howmanytypebyevents"=>$row["howmanytypebyevents"]
               ,"howmanypaymenttypebyevents"=>$row["howmanypaymenttypebyevents"]
               ,"cashOpenDate"=>$row["cashOpenDate"]
               ,"loginClose"=>$row["loginClose"]
               ,"hasDiff"=>$row["hasDiff"]
           );
       }
   
       logme();
   
       return $json;
   }
   $obj = get($_REQUEST["id"]);
   
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
         .printbase {
         font-size: 10px;
         /* font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace;  */
         font-family: "Lucida Console";
         line-height: 12px;
         text-transform: uppercase;
         width: 309px;
         }
         .printbase div {
         padding-bottom: 2px;
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
         .alert-info {
            text-align: center;
         }
         .table .result td {
            border-bottom: 1px solid black;
         }
         .table .head td {
            border-bottom: 1px dotted black;
            font-weight: bold;
         }
         .table .footer td {
            font-weight: bold;
         }
      </style>
   </head>
   <body>
   <div class="form-row printbase">
      <img src="<?php echo getwhitelabelobj()["logo"] ?>" style="height: 55px; text-align: center; margin: 0 auto; display: flex;" />
      <div role="alert" aria-live="polite" aria-atomic="true" class="alert alert-info">
         <!---->
         Informações de fechamento
         <br>
         Data de abertura: <?php echo $obj[0]["cashOpenDate"] ?>
         <br>
         Data de fechamento: <?php echo $obj[0]["closed"] ?>
         <br> 
         Fechamento realizado por: <?php echo $obj[0]["nameClose"] ?> (<?php echo $obj[0]["loginClose"] ?>)
         <br>
         Teve diferença? <span><?php echo ($obj[0]["hasDiff"] == 1 ? "Sim" : "Não" ) ?></span><!----><!---->
      </div>
   <table class="table" style="">
         <tr class="head">
            <td>Evento</td>
            <td>Tipo</td>
            <td>Forma</td>
            <td>Qtde.</td>
            <td style="width:47px;">Valor</td>
         </tr>
      <?php foreach ($obj as &$row) {?>
         <tr class="result">
            <td><?php echo $row["ds_evento"] ?></td>
            <td><?php echo $row["typeText"] ?></td>
            <td><?php echo $row["desc"] ?></td>
            <td><?php echo $row["qtd"] ?></td>
            <td><?php echo $row["amount"] ?></td>
         </tr>
      <?php }?>
      <tr class="footer">
         <td colspan="5" style="text-align:center;">Total: <?php echo $row["amountbybase"] ?></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style=""></td>
      </tr>
      <tr>
         <td colspan="5" style="text-align:right;">__________________________________</td>
      </tr>
      <tr>
         <td colspan="5" style="text-align:right;">Assinatura</td>
      </tr>
   </table>
</div>
      <div class="pagebreak"></div>
      <script lang="javascript">
         <?php 
            if ($dontprint == false) {
               echo "window.print();";
            }
            if ($dontclose == false) {
               echo "window.close();";
            }
            ?>
      </script>
   </body>
</html>