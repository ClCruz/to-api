<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;

    function get(
        $id
        ,$id_base
        ,$nameWeb
        ,$nameTicketOffice
        ,$description
        ,$in_dom
        ,$in_seg
        ,$in_ter
        ,$in_qua
        ,$in_qui
        ,$in_sex
        ,$in_sab
        ,$allowweb
        ,$allowticketoffice
        ,$isPrincipal
        ,$isDiscount
        ,$isHalf
        ,$isFixed
        ,$isPlus
        ,$isAllotment
        ,$vl_preco_fixo
        ,$PerDesconto
        ,$CotaMeiaEstudante
        ,$StaCalculoMeiaEstudante
        ,$QtdVendaPorLote
        ,$StaTipBilhete
        ,$saveimage
        ,$imagebase64
        ) {
            
        $TipCaixa = 'A';
        $TipBilhete = $nameWeb;
        $StaTipBilhMeiaEstudante = $isHalf == 1 ? 'S' : 'N';
        $StaTipBilhMeia = $isHalf == 1 ? 'S' : 'N';
        $hasImage = 0;
        $in_venda_site = 1;
        
        //die(json_encode($_POST));

        $query = "EXEC pr_tickettype_save ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($id
        ,$id_base
        ,$nameWeb
        ,$nameTicketOffice
        ,$description
        ,$in_dom
        ,$in_seg
        ,$in_ter
        ,$in_qua
        ,$in_qui
        ,$in_sex
        ,$in_sab
        ,$allowweb
        ,$allowticketoffice
        ,$isPrincipal
        ,$isDiscount
        ,$isHalf
        ,$isFixed
        ,$isPlus
        ,$isAllotment
        ,$vl_preco_fixo
        ,$PerDesconto
        ,$CotaMeiaEstudante
        ,$StaCalculoMeiaEstudante
        ,$QtdVendaPorLote
        ,$StaTipBilhete
        ,$TipCaixa
        ,$TipBilhete
        ,$StaTipBilhMeiaEstudante
        ,$StaTipBilhMeia
        ,$in_venda_site
        );
        $result = db_exec($query, $params, $id_base);

        $directoryname = "";

        foreach ($result as &$row) {
            $directoryname = $row["directoryname"];
            $id = $row["id"];
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]
                        ,"imagelog"=> "");
        }

        $imagelog = "";

        if ($json["success"]) {
            if ($saveimage == 1) {      
                $imagelog = $imagelog."altered image|";
                if (preg_match('/^data:image\/(\w+);base64,/', $imagebase64, $type)) {
                    $imagelog = $imagelog."recovering info|";
                    $imagebase64 = substr($imagebase64, strpos($imagebase64, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, gif
                    $img = base64_decode($imagebase64);
                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/tickettype/'.$directoryname.'|';
                    if (!file_exists('/var/www/media/tickettype/'.$directoryname)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/tickettype/'.$directoryname, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }
                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/ori_tickettype/'.$directoryname.'|';
                    if (!file_exists('/var/www/media/ori_tickettype/'.$directoryname)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/ori_tickettype/'.$directoryname, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }

                    $filename = $id.".png";                    

                    $imagelog = $imagelog."looking for image: ".'/var/www/media/ori_tickettype/'.$directoryname.'/'.$filename.'|';
                    if (file_exists('/var/www/media/ori_tickettype/'.$directoryname.'/'.$filename)) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/ori_tickettype/'.$directoryname.'/'.$filename);
                        $imagelog = $imagelog."deleted image|";
                    }    

                    $imagelog = $imagelog."looking for image: ".'/var/www/media/tickettype/'.$directoryname.'/'.$filename.'|';
                    if (file_exists('/var/www/media/tickettype/'.$directoryname.'/'.$filename)) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/tickettype/'.$directoryname.'/'.$filename);
                        $imagelog = $imagelog."deleted image|";
                    }    

                    $imagelog = $imagelog."saving|";
                    file_put_contents('/var/www/media/ori_tickettype/'.$directoryname.'/'.$filename, $img);
                    $imagelog = $imagelog."saved";

                    $imageResizer = new ImageResize('/var/www/media/ori_tickettype/'.$directoryname.'/'.$filename);
                    $imageResizer->resize(600,314, $allow_enlarge = true);
                    $imageResizer->save('/var/www/media/tickettype/'.$directoryname.'/'.$filename);
                }
                $json["imagelog"] = $imagelog;

                $query = "EXEC pr_tickettype_image ?,?";
                $params = array($id, '1');
                db_exec($query, $params, $id_base);
            }
            else {
                $query = "EXEC pr_tickettype_image ?,?";
                $params = array($id, '0');
                db_exec($query, $params, $id_base);
            }
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id"]
    ,$_POST["id_base"]
    ,$_POST["nameWeb"]
    ,$_POST["nameTicketOffice"]
    ,$_POST["description"]
    ,$_POST["in_dom"]
    ,$_POST["in_seg"]
    ,$_POST["in_ter"]
    ,$_POST["in_qua"]
    ,$_POST["in_qui"]
    ,$_POST["in_sex"]
    ,$_POST["in_sab"]
    ,$_POST["allowweb"]
    ,$_POST["allowticketoffice"]
    ,$_POST["isPrincipal"]
    ,$_POST["isDiscount"]
    ,$_POST["isHalf"]
    ,$_POST["isFixed"]
    ,$_POST["isPlus"]
    ,$_POST["isAllotment"]
    ,$_POST["vl_preco_fixo"]
    ,$_POST["PerDesconto"]
    ,$_POST["CotaMeiaEstudante"]
    ,$_POST["StaCalculoMeiaEstudante"]
    ,$_POST["QtdVendaPorLote"]
    ,$_POST["StaTipBilhete"]
    ,$_POST["saveimage"]
    ,$_POST["imagebase64"]
);
?>