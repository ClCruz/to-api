<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($showto, $principal, $fixed, $half, $plus, $allotment) {
        //sleep(5);
        $query = "EXEC pr_tickettype_select ?,?,?,?,?,?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($showto, $principal, $fixed, $half, $plus, $allotment);
        $result = db_exec($query, $params);

        $json = array();
        foreach ($result as &$row) {
            $json[] = array(
                "CodTipBilhete" => $row["CodTipBilhete"]
                ,"TipBilhete" => $row["TipBilhete"]
                ,"isFixed" => $row["isFixed"]
                ,"isPrincipal" => $row["isPrincipal"]
                ,"isHalf" => $row["isHalf"]
                ,"isPlus" => $row["isPlus"]
                ,"isAllotment" => $row["isAllotment"]
                ,"hasImage" => $row["hasImage"]
                ,"description" => $row["description"]
                ,"name" => $row["name"]
                ,"fixed_amount" => $row["fixed_amount"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"vl_preco_fixo" => $row["vl_preco_fixo"]
                ,"value"=>$row["CodTipBilhete"]
                ,"text"=>$row["name"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }
get($_REQUEST["showto"], $_REQUEST["principal"], $_REQUEST["fixed"], $_REQUEST["half"], $_REQUEST["plus"], $_REQUEST["allotment"]);
?>