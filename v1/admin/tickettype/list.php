<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($text, $id_base, $currentPage, $perPage) {
        //sleep(5);
        $query = "EXEC pr_tickettype_list ?,?,?";
        $params = array($text, $currentPage, $perPage);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodTipBilhete" => $row["CodTipBilhete"]
                ,"TipBilhete" => $row["TipBilhete"]
                ,"isFixed" => $row["isFixed"]
                ,"isPrincipal" => $row["isPrincipal"]
                ,"isHalf" => $row["isHalf"]
                ,"isPlus" => $row["isPlus"]
                ,"isDiscount" => $row["isDiscount"]
                ,"isAllotment" => $row["isAllotment"]
                ,"StaTipBilhete" => $row["StaTipBilhete"]
                ,"allowweb" => $row["allowweb"]
                ,"allowticketoffice" => $row["allowticketoffice"]
                ,"allowapi" => $row["allowapi"]
                ,"id_base" => $id_base
                ,"totalCount" => $row["totalCount"]
                ,"currentPage" => $row["currentPage"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["text"], $_POST["id_base"], $_REQUEST["__currentPage"], $_REQUEST["__perPage"]);
?>