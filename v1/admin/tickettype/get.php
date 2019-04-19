<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id,$id_base) {
        //sleep(5);
        $query = "EXEC pr_tickettype_get ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $imageURI = "";
            $imageURIOriginal = "";
            $imageURI = getDefaultMediaHost()."/tickettype/".$row["uniquename"]."/".$row["CodTipBilhete"].".png";
            $imageURIOriginal = getDefaultMediaHost()."/ori_tickettype/".$row["uniquename"]."/".$row["CodTipBilhete"].".png";

            $in_dom = $row["in_dom"];
            $in_qua = $row["in_qua"];
            $in_qui = $row["in_qui"];
            $in_sab = $row["in_sab"];
            $in_seg = $row["in_seg"];
            $in_sex = $row["in_sex"];
            $in_ter = $row["in_ter"];

            if ($in_dom == 0 && $in_seg == 0 && $in_ter == 0 && $in_qua == 0 && $in_qui == 0 && $in_sex == 0 && $in_sab == 0) {
                $in_dom = 1;
                $in_qua = 1;
                $in_qui = 1;
                $in_sab = 1;
                $in_seg = 1;
                $in_sex = 1;
                $in_ter = 1;
            }

            $json = array(
                "CodTipBilhete" => $row["CodTipBilhete"]
                ,"imageURI" => $imageURI
                ,"imageURIOriginal" => $imageURIOriginal
                ,"uniquename" => $row["uniquename"]
                ,"allowticketoffice" => $row["allowticketoffice"]
                ,"allowweb" => $row["allowweb"]
                ,"CobraComs" => $row["CobraComs"]
                ,"CotaMeiaEstudante" => $row["CotaMeiaEstudante"]
                ,"description" => $row["description"]
                ,"ds_nome_site" => $row["ds_nome_site"]
                ,"hasImage" => $row["hasImage"]
                ,"id_promocao_controle" => $row["id_promocao_controle"]
                ,"Img1Promocao" => $row["Img1Promocao"]
                ,"Img2Promocao" => $row["Img2Promocao"]
                ,"ImpDSBilhDest" => $row["ImpDSBilhDest"]
                ,"ImpVlIngresso" => $row["ImpVlIngresso"]
                ,"in_dom" => $in_dom
                ,"in_qua" => $in_qua
                ,"in_qui" => $in_qui
                ,"in_sab" => $in_sab
                ,"in_seg" => $in_seg
                ,"in_sex" => $in_sex
                ,"in_ter" => $in_ter
                ,"in_hot_site" => $row["in_hot_site"]
                ,"in_venda_site" => $row["in_venda_site"]
                ,"InPacote" => $row["InPacote"]
                ,"isAllotment" => $row["isAllotment"]
                ,"isFixed" => $row["isFixed"]
                ,"isHalf" => $row["isHalf"]
                ,"isDiscount" => $row["isDiscount"]
                ,"isOld" => $row["isOld"]
                ,"isPlus" => $row["isPlus"]
                ,"isPrincipal" => $row["isPrincipal"]
                ,"nameTicketOffice" => $row["nameTicketOffice"]
                ,"nameWeb" => $row["nameWeb"]
                ,"PerDesconto" => $row["PerDesconto"]
                ,"QtdVendaPorLote" => $row["QtdVendaPorLote"]
                ,"StaCalculoMeiaEstudante" => $row["StaCalculoMeiaEstudante"]
                ,"StaCalculoPorSala" => $row["StaCalculoPorSala"]
                ,"StaTipBilhete" => $row["StaTipBilhete"]
                ,"StaTipBilhMeia" => $row["StaTipBilhMeia"]
                ,"StaTipBilhMeiaEstudante" => $row["StaTipBilhMeiaEstudante"]
                ,"TipBilhete" => $row["TipBilhete"]
                ,"TipCaixa" => $row["TipCaixa"]
                ,"vl_preco_fixo" => $row["vl_preco_fixo"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

    get($_POST["id"], $_POST["id_base"]);
?>