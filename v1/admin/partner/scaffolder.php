<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user
                ,$id_partner,$json_meta_description,$json_meta_keywords
                ,$json_template,$json_info_title,$json_info_description
                ,$json_info_cnpj,$json_info_companyname,$scss_colors_primary
                ,$scss_colors_secondary) {
        $query = "EXEC pr_admin_partner_whitelabelcontent_save ?,?,?,?,?,?,?,?,?,?";
        $params = array($id_partner,$json_meta_description,$json_meta_keywords
        ,$json_template,$json_info_title,$json_info_description
        ,$json_info_cnpj,$json_info_companyname,$scss_colors_primary
        ,$scss_colors_secondary);
        $result = db_exec($query, $params);

        $json = array("success"=>true
                    ,"msg"=>'Processo executado');

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_user"],
$_POST["id_partner"], $_POST["json_meta_description"], $_POST["json_meta_keywords"],
$_POST["json_template"], $_POST["json_info_title"], $_POST["json_info_description"],
$_POST["json_info_cnpj"], $_POST["json_info_companyname"],$_POST["scss_colors_primary"]
,$_POST["scss_colors_secondary"]);
?>