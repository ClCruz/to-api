<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;

    function get($api,$id_produtor,$id_to_admin_user
                ,$CodPeca,$NomPeca,$CodTipPeca
                ,$TemDurPeca,$CenPeca,$id_local_evento
                ,$ValIngresso,$description,$address
                ,$meta_description,$meta_keyword,$showInBanner
                ,$bannerDescription,$QtIngrPorPedido,$in_obriga_cpf
                ,$qt_ingressos_por_cpf, $imageChanged, $imageBase64) {

        //sleep(5);
        $cont = true;
        $query = "EXEC pr_event_save ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api, $id_evento, $description, $address, $meta_description, $meta_keyword, $id_genre, $showInBanner, $bannerDescription);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);

            if ($row["success"] != "1" && $row["success"] != 1) {
                $cont = false;
            }
        }

        $imagelog = "";

        if (($cont == true) && ($imageChanged || $imageChanged == 1 || $imageChanged == "1")) {            

            $imagelog = $imagelog."altered image|";
            if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64, $type)) {
                $imagelog = $imagelog."recovering info|";
                $imageBase64 = substr($imageBase64, strpos($imageBase64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                $img = base64_decode($imageBase64);
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

get($_REQUEST["api"],$_REQUEST["id_produtor"],$_REQUEST["id_to_admin_user"]
,$_REQUEST["CodPeca"],$_REQUEST["NomPeca"],$_REQUEST["CodTipPeca"]
,$_REQUEST["TemDurPeca"],$_REQUEST["CenPeca"],$_REQUEST["id_local_evento"]
,$_REQUEST["ValIngresso"],$_REQUEST["description"],$_REQUEST["address"]
,$_REQUEST["meta_description"],$_REQUEST["meta_keyword"],$_REQUEST["showInBanner"]
,$_REQUEST["bannerDescription"],$_REQUEST["QtIngrPorPedido"],$_REQUEST["in_obriga_cpf"]
,$_REQUEST["qt_ingressos_por_cpf"], $_POST["imageChanged"], $_POST["base64"]);


?>