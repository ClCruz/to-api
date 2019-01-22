<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id) {
        //sleep(5);
        $query = "EXEC pr_admin_partner_get_wl ?";
        $params = array($id);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "id" => $row["id"]
                ,"name" => $row["name"]
                ,"uniquename" => $row["uniquename"]
                ,"domain"=> $row["domain"]
                ,"databaseOK"=> $row["databaseOK"]
                ,"userOK"=> $row["userOK"]
                ,"databaseStatus"=> $row["databaseStatus"]
                ,"userStatus"=> $row["userStatus"]
                ,"json_ga"=>$row["json_ga"]
                ,"json_meta_description"=> $row["json_meta_description"]
                ,"json_meta_keywords"=> $row["json_meta_keywords"]
                ,"json_template"=> $row["json_template"]
                ,"json_info_title"=> $row["json_info_title"]
                ,"json_info_description"=> $row["json_info_description"]
                ,"json_info_cnpj"=> $row["json_info_cnpj"]
                ,"json_info_companyaddress"=> $row["json_info_companyaddress"]
                ,"json_info_companyname"=> $row["json_info_companyname"]
                ,"scss_colors_primary"=> $row["scss_colors_primary"]
                ,"scss_colors_secondary"=> $row["scss_colors_secondary"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_REQUEST["id"]);
?>