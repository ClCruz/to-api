<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;
    
    function execute($apikey
    ,$id_base
    ,$id_produtor
    ,$id_to_admin_user
    ,$CodPeca
    ,$NomPeca
    ,$CodTipPeca
    ,$TemDurPeca
    ,$CenPeca
    ,$id_local_evento
    ,$description
    ,$meta_description
    ,$meta_keyword
    ,$opening_time
    ,$insurance_policy
    ,$showInBanner
    ,$bannerDescription
    ,$QtIngrPorPedido
    ,$in_obriga_cpf
    ,$qt_ingressos_por_cpf
    ,$imagechanged
    ,$imagebase64
    ) {
        //die("aqui: $imagechanged / $imagebase64");
        //sleep(5);
        $query = "EXEC pr_event_save ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($apikey
        ,$id_produtor
        ,$id_to_admin_user
        ,$CodPeca
        ,$NomPeca
        ,$CodTipPeca
        ,$TemDurPeca
        ,$CenPeca
        ,$id_local_evento
        ,$description
        ,$meta_description
        ,$meta_keyword
        ,$opening_time
        ,$insurance_policy
        ,$showInBanner == "" ? "0" : $showInBanner
        ,$bannerDescription
        ,$QtIngrPorPedido
        ,$in_obriga_cpf == "" ? "0" : $in_obriga_cpf
        ,$qt_ingressos_por_cpf);

        //die("aqui".json_encode($params));
        $result = db_exec($query, $params, $id_base);

        $id_evento = 0;

        foreach ($result as &$row) {
            $id_evento = $row["id_evento"];
        }

        $json = array("success"=>($id_evento != 0)
        ,"msg"=>"Salvo com sucesso");

        $imagelog = "";


        if ($imagechanged || $imagechanged == 1 || $imagechanged == "1") {      
            $imagelog = $imagelog."altered image|";
            if (preg_match('/^data:image\/(\w+);base64,/', $imagebase64, $type)) {
                $imagelog = $imagelog."recovering info|";
                $imagebase64 = substr($imagebase64, strpos($imagebase64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                $img = base64_decode($imagebase64);
                $imagelog = $imagelog."looking for directory: ".'/var/www/media/ori/'.$id_evento.'|';
                if (!file_exists('/var/www/media/ori/'.$id_evento)) {
                    $imagelog = $imagelog."folder not exist|";
                    mkdir('/var/www/media/ori/'.$id_evento, 0777, true);
                    $imagelog = $imagelog."created folder|";
                }
                $imagelog = $imagelog."looking for directory: ".'/var/www/media/evento/'.$id_evento.'|';
                if (!file_exists('/var/www/media/evento/'.$id_evento)) {
                    $imagelog = $imagelog."folder not exist|";
                    mkdir('/var/www/media/evento/'.$id_evento, 0777, true);
                    $imagelog = $imagelog."created folder|";
                }
                
                $imagelog = $imagelog."looking for image: ".'/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName().'|';
                if (file_exists('/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName())) {
                    $imagelog = $imagelog."image exist|";
                    unlink('/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName());
                    $imagelog = $imagelog."deleted image|";
                }
                if (file_exists('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName())) {
                    $imagelog = $imagelog."image exist|";
                    unlink('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName());
                    $imagelog = $imagelog."deleted image|";
                }
                if (file_exists('/var/www/media/evento/'.$id_evento.'/'.getBigCardImageName())) {
                    $imagelog = $imagelog."image exist|";
                    unlink('/var/www/media/evento/'.$id_evento.'/'.getBigCardImageName());
                    $imagelog = $imagelog."deleted image|";
                }

                $imagelog = $imagelog."saving|";
                file_put_contents('/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName(), $img);
                $imagelog = $imagelog."saved";
                //file_put_contents('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName(), $img);
//                file_put_contents('/var/www/media/evento/'.$id_evento.'/'.getBigCardImageName(), $img);
                                
                $imageResizer = new ImageResize('/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName());
                $imageResizer->resize(600,314, $allow_enlarge = true);
                $imageResizer->save('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName());

                $imageResizer = new \Gumlet\ImageResize('/var/www/media/ori/'.$id_evento.'/'.getOriginalCardImageName());
                $imageResizer->resize(816, 459, $allow_enlarge = true);
                $imageResizer->save('/var/www/media/evento/'.$id_evento.'/'.getBigCardImageName());
            }
            $json["msg"] = $imagelog;
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["apikey"]
,$_POST["id_base"]
,$_POST["id_produtor"]
,$_POST["id_to_admin_user"]
,$_POST["CodPeca"]
,$_POST["NomPeca"]
,$_POST["CodTipPeca"]
,$_POST["TemDurPeca"]
,$_POST["CenPeca"]
,$_POST["id_local_evento"]
,$_POST["description"]
,$_POST["meta_description"]
,$_POST["meta_keyword"]
,$_POST["opening_time"]
,$_POST["insurance_policy"]
,$_POST["showInBanner"]
,$_POST["bannerDescription"]
,$_POST["QtIngrPorPedido"]
,$_POST["in_obriga_cpf"]
,$_POST["qt_ingressos_por_cpf"]
,$_POST["imagechanged"]
,$_POST["imagebase64"]);
?>