<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    function get($id_user, $id_base, $codVenda, $id_pedido_venda, $email) {
        if ($codVenda == null || $codVenda == "") {

        }
        else {
            $obj = generate_email_print_code(0, $codVenda, $id_base);
            make_purchase_email_ticketoffice($codVenda, $id_base, $email);
        }
        
        echo json_encode(array("success"=>true, "msg"=>"E-mail enviado."));
        die("");
    }
    get($_POST["id_user"], $_POST["id_base"], $_POST["codVenda"],$_POST["id_pedido_venda"],$_POST["email"]);
?>