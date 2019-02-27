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
       $query = "EXEC pr_ticketoffice_cashregister_get ?";
       // die("dd".json_encode($codVenda));
       $params = array($id);
       $result = db_exec($query, $params);
   
       $json = array();
       foreach ($result as &$row) {
           $json[] = array(
               "id"=>$row["id"]
               ,"amount"=>$row["amount"]
               ,"type"=>$row["type"]
               ,"created"=>$row["created"]
               ,"typeText"=>getTextType($row["type"])
               ,"justification"=>$row["justification"]
               ,"login"=>$row["login"]
               ,"name"=>$row["name"]
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
         <b>Informações de movimentação</b>
         <br>
         <b>Tipo:</b> <?php echo $obj[0]["typeText"] ?>
         <br />
         <b>Justificativa:</b> <?php echo $obj[0]["justification"] ?>
         <br>
         <b>Valor: </b><?php echo $obj[0]["amount"] ?>
         <br> 
         <b>Data: </b><?php echo $obj[0]["created"] ?>

         <br>
         <br />
         <br />
         <br />
         ______________________________________________
         <br />
         <?php echo $obj[0]["name"] ?> (<?php echo $obj[0]["login"] ?>)
      </div>
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