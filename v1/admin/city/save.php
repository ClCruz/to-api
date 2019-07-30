<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");
    
    use \Gumlet\ImageResize;
    // die($_POST["minAmount"]*100);
    
    function execute($id_municipio
    ,$id_estado
    ,$ds_municipio
    ,$imagechanged
    ,$imagebase64
    ,$imagechanged_extra
    ,$imagebase64_extra
    ) {

        $query = "EXEC pr_city_save ?,?,?";
        $params = array($id_municipio
        ,$ds_municipio
        ,$id_estado
        );

        $result = db_exec($query, $params);

        $id_municipio = 0;
        $msg = "Falha ao salvar.";

        foreach ($result as &$row) {
            $id_municipio = $row["id"];
            $msg = $row["msg"];
        }

        $json = array("success"=>true,"id_municipio"=>$id_municipio,"msg"=>"Salvo com sucesso");

        if ($id_municipio == 0) {
            $json = array("success"=>false
            ,"id_municipio"=>0
            ,"msg"=>$msg);
        }


        $imagelog = "";

        $savedimg = false;
        $savedimg_extra = false;
        $url_img = '';
        $url_img_extra = '';

        if ($json["success"]) {
            if ($imagechanged || $imagechanged == 1 || $imagechanged == "1") {      
                $imagelog = $imagelog."altered image|";
                if (preg_match('/^data:image\/(\w+);base64,/', $imagebase64, $type)) {
                    $imagelog = $imagelog."recovering info|";
                    $imagebase64 = substr($imagebase64, strpos($imagebase64, ',') + 1);

                    $type = strtolower($type[1]); // jpg, png, gif
                    $img = base64_decode($imagebase64);

                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/city/'.$id_municipio.'|';
                    if (!file_exists('/var/www/media/city/evento/'.$id_municipio)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/city/'.$id_municipio, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }
                    
                    $imagelog = $imagelog."looking for image: ".'/var/www/media/city/'.$id_municipio.'/'.getDefaultCityImageName().'|';
                    if (file_exists('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityImageName())) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityImageName());
                        $imagelog = $imagelog."deleted image|";
                    }
    
                    $imagelog = $imagelog."saving|";
                    file_put_contents('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityImageName(), $img);
                    $imagelog = $imagelog."saved";
                    $savedimg = true;
                    $url_img = '/city/'.$id_municipio.'/'.getDefaultCityImageName();
                }
            }
            if ($imagechanged_extra || $imagechanged_extra == 1 || $imagechanged_extra == "1") {      
                $imagelog = $imagelog."altered image extra|";
                if (preg_match('/^data:image\/(\w+);base64,/', $imagebase64_extra, $type)) {
                    $imagelog = $imagelog."recovering info|";
                    $imagebase64_extra = substr($imagebase64_extra, strpos($imagebase64_extra, ',') + 1);

                    $type = strtolower($type[1]); // jpg, png, gif
                    $img = base64_decode($imagebase64_extra);

                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/city/'.$id_municipio.'|';
                    if (!file_exists('/var/www/media/city/evento/'.$id_municipio)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/city/'.$id_municipio, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }
                    
                    $imagelog = $imagelog."looking for image: ".'/var/www/media/city/'.$id_municipio.'/'.getDefaultCityExtraImageName().'|';
                    if (file_exists('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityExtraImageName())) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityExtraImageName());
                        $imagelog = $imagelog."deleted image|";
                    }
    
                    $imagelog = $imagelog."saving|";
                    file_put_contents('/var/www/media/city/'.$id_municipio.'/'.getDefaultCityExtraImageName(), $img);
                    $imagelog = $imagelog."saved";
                    $savedimg_extra = true;
                    $url_img_extra = '/city/'.$id_municipio.'/'.getDefaultCityExtraImageName();
                }
            }
            $json["msg"] = $imagelog;
        }

        if ($savedimg || $savedimg_extra) {
            
            $query = "EXEC pr_city_update_img ?,?,?";
            $params = array($id_municipio
            ,$url_img
            ,$url_img_extra
            );
    
            $result = db_exec($query, $params);
        }

        echo json_encode($json);
        logme();
        die();    
    }

execute($_REQUEST["id_municipio"]
,$_POST["id_estado"]
,$_POST["ds_municipio"]
,$_POST["imagechanged"]
,$_POST["imagebase64"]
,$_POST["imagechanged_extra"]
,$_POST["imagebase64_extra"]
);
?>