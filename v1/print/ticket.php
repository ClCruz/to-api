<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');

   function tellmethemodel($id_base, $codVenda) {
      $ret = "model001";
      $query = "EXEC pr_ticketoffice_getmodelbysell ?";
      $params = array($codVenda);
      $result = db_exec($query, $params, $id_base);  
      foreach ($result as &$row) {      
         $ret = $row["ticketoffice_ticketmodel"];
      }
      logme();
      return $ret;
  }
  $model = tellmethemodel($_REQUEST["id_base"], $_REQUEST["id"]);

  require_once($_SERVER['DOCUMENT_ROOT']."/v1/print/tickets/".$model.".php");
?>