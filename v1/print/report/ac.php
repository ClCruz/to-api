<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/v1/api_include.php");

function getaccountingkey($code) {
   $query = "EXEC pr_accounting_key_get ?";
   $params = array($code);
   $result = db_exec($query, $params);

   $json = array();
   foreach ($result as &$row) {    
       $json = array(
           "password"=>$row["password"]
           ,"used"=>$row["used"]
           ,"id_evento"=>$row["id_evento"]
       );
   }

   logme();

   return $json;
}

$code = $_REQUEST["code"];
$obj = getaccountingkey($code);
$pass = $_POST["pass"];
$pass = $pass == null ? "" : $pass;
$passok = $pass === $obj["password"];
$submitted = !empty($_POST);
$id_base = get_id_base_from_id_evento($obj["id_evento"]);
// die(json_encode($pass));
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <title></title>
   <link href="<?php echo getwhitelabelURI_home('/assets/css/dashboard.css') ?>" rel="stylesheet" />
</head>

<body>
<?php if ($obj["password"]!='' && $passok == false && $submitted == true) {
?>
<div class="alert alert-danger" role="alert">
                          Senha não confere.
                        </div>
<?php
   } ?>

   <br />

   <?php if ($obj["password"]!='' && $passok == false) {
?>
<form action="" method="post">
<div class="card-body">
      <div class="row">
         <div class="col-md-6 col-lg-4">
            <div class="form-group">
               <label class="form-label">Senha</label>
               <div class="input-group">
                  <input type="password" class="form-control" name="pass" id="pass" placeholder="Digite a senha de acesso" aria-autocomplete="list">
                  <span class="input-group-append">
                  <button type="submit" class="btn btn-primary" type="button">Ver!</button>
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>
   </form>
<?php
   } ?>


<?php 
//die(json_encode($_SERVER['DOCUMENT_ROOT'] . "/v1/print/report/accounting.php?id_base=".$id_base."&id=".$code));
if ($passok == true) {
   $_REQUEST["id"] = $code;
   $_REQUEST["id_base"] = $id_base;
   include_once($_SERVER['DOCUMENT_ROOT'] . "/v1/print/report/accounting.php");
}
?>
<br />
<br />
</body>

</html>