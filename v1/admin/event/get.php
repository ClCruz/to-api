<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $id_evento, $id_base) {
        //sleep(5);
        $query = "EXEC pr_admin_event_get ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($id_user, $id_evento);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $imageOriginalURI = getDefaultMediaHost().str_replace("{default_ori}",getOriginalCardImageName(),str_replace("{id}",$row["id_evento"],$row["imageoriginal"]))."?".randomintbydate();
            $imageBigURI = getDefaultMediaHost().str_replace("{default_big}",getBigCardImageName(),str_replace("{id}",$row["id_evento"],$row["cardbigimage"]))."?".randomintbydate();
            $imageURI = getDefaultMediaHost().str_replace("{default_card}",getDefaultCardImageName(),str_replace("{id}",$row["id_evento"],$row["cardimage"]))."?".randomintbydate();

            $json = array(
                "CodPeca" => $row["CodPeca"]
                ,"id_produtor" => $row["id_produtor"]
                ,"id_base" => $row["id_base"]
                ,"uri" => $row["uri"]
                ,"urifull" => getwhitelabelURI_home($row["uri"])
                ,"NomPeca" => $row["NomPeca"]
                ,"CodTipPeca" => $row["CodTipPeca"]
                ,"id_genre" => $row["id_genre"]
                ,"TemDurPeca" => $row["TemDurPeca"]
                ,"amountMax" => $row["amountMax"]
                ,"amountMin" => $row["amountMin"]
                ,"CenPeca" => $row["CenPeca"]
                ,"id_local_evento" => $row["id_local_evento"]
                ,"showonline" => $row["showonline"]
                ,"hasshowyet" => $row["hasshowyet"]
                ,"id_municipio" => $row["id_municipio"]
                ,"id_estado" => $row["id_estado"]
                ,"ValIngresso" => $row["ValIngresso"]
                ,"description" => $row["description"]
                ,"meta_description" => $row["meta_description"]
                ,"meta_keyword" => $row["meta_keyword"]
                ,"showInBanner" => $row["showInBanner"]
                ,"bannerDescription" => $row["bannerDescription"]
                ,"opening_time" => $row["opening_time"]
                ,"insurance_policy" => $row["insurance_policy"]
                ,"QtIngrPorPedido" => $row["QtIngrPorPedido"]
                ,"qt_ingressos_por_cpf" => $row["qt_ingressos_por_cpf"]
                ,"in_obriga_cpf" => $row["in_obriga_cpf"]
                ,"ticketoffice_askemail" => $row["ticketoffice_askemail"]
                ,"DatIniPeca" => $row["DatIniPeca"]
                ,"DatFinPeca" => $row["DatFinPeca"]
                ,"hasPresentantion" => $row["hasPresentantion"]
                ,"imageURIOriginal" => $imageOriginalURI
                ,"imageURIBanner" => $imageBigURI
                ,"imageURICard" => $imageURI,
                "free_installments" => $row["free_installments"],
                "max_installments" => $row["max_installments"],
                "interest_rate" => $row["interest_rate"],
                "ticketoffice_ticketmodel" => $row["ticketoffice_ticketmodel"],
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["id_user"], $_REQUEST["id_evento"], $_REQUEST["id_base"]);
?>