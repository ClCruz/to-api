<?php
   require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
   require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Metzli/autoload.php');

   function logprint() {
      // die("oi");
      $id_base = $_REQUEST["id_base"];
      $codVenda = $_REQUEST["codVenda"];
      $loggedid = $_REQUEST["loggedid"];
      $id_pedido_venda = $_REQUEST["id_pedido_venda"];
      $ip = "";
      if (array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
          $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      }  

      if ($loggedid == '') {
         $loggedid = '00000000-0000-0000-0000-000000000000';
      }
      if ($codVenda == '' && $id_pedido_venda == '') {
         $codVenda = $_REQUEST["id"];
      }
      if ($id_base != '' && $codVenda != '') {
         $query = "EXEC pr_print_ticket_add_bycodvenda ?,?,?";
         $params = array($codVenda,$loggedid,$ip);
         db_exec($query, $params, $id_base);  
      }
      else {
         if ($id_pedido_venda != '') {
            $query = "EXEC pr_print_ticket_add_byid_pedido_venda ?,?,?";
            $params = array($id_pedido_venda,$loggedid,$ip);
            db_exec($query, $params);  
         }
      }
      
   }
   function checkisok($codVenda) {
      $query = "EXEC pr_print_ticket_check ?";
      $params = array($codVenda);
      $result = db_exec($query, $params);  
      $isok = 1;
      foreach ($result as &$row) {      
         $isok = $row["isok"];
      }
      if ($isok == 0) {

         logme();
         echo "Não foi possível imprimir o ingresso.";
         die();
      }
   }

   function tellmethemodel($id_base, $codVenda) {
      $ret = "model001";
      $query = "EXEC pr_ticketoffice_getmodelbysell ?";
      $params = array($codVenda);
      $result = db_exec($query, $params, $id_base);  
      foreach ($result as &$row) {      
         $ret = $row["ticketoffice_ticketmodel"];
      }

      logprint();
      logme();
      return $ret;
  }
  
  checkisok($_REQUEST["id"]);
  
  $model = tellmethemodel($_REQUEST["id_base"], $_REQUEST["id"]);
  

  require_once($_SERVER['DOCUMENT_ROOT']."/v1/print/tickets/".$model.".php");
?>