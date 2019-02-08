<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/email/purchasehelp.php");
    // G64FGOBFHO
    //http://localhost:2002/v1/email/purchase.php?id_user=&codVenda=G64FGOBFHO&id_pedido_venda=&email=blcoccaro@gmail.com
    function get($id_user, $id_base, $codVenda, $id_pedido_venda, $email) {
//        isuservalidordie($id_user);
// die("dd".json_encode($codVenda));
        if ($codVenda == null || $codVenda == "") {

        }
        else {
            generate_email_print_code(0, $codVenda, $id_base);
            make_purchase_email_ticketoffice($codVenda, $id_base, $email);
        }
        
        echo json_encode(array("success"=>true, "msg"=>"E-mail enviado."));
        die("");
    }
    get($_POST["id_user"], $_POST["id_base"], $_POST["codVenda"],$_POST["id_pedido_venda"],$_POST["email"]);
    //get($_REQUEST["id_user"],$_REQUEST["id_base"], $_REQUEST["codVenda"],$_REQUEST["id_pedido_venda"],$_REQUEST["email"]);
?>