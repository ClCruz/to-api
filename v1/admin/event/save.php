<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($api, $id_evento, $description, $address,$meta_description, $meta_keyword, $id_genre, $imageChanged, $imageBase64) {
        //sleep(5);
        $query = "EXEC pr_admin_event_save ?, ?, ?, ?, ?, ?, ?";
        //die("aaa.".print_r(db_param($startAt),true));
        $params = array($api, $id_evento, $description, $address, $meta_description, $meta_keyword, $id_genre);
        $result = db_exec($query, $params);

        foreach ($result as &$row) {
            $json = array("success"=>$row["success"] == "1" || $row["success"] == 1
                        ,"msg"=>$row["msg"]);
        }

        $imagelog = "";

        if ($imageChanged || $imageChanged == 1 || $imageChanged == "1") {
            $imagelog = $imagelog."altered image|";
            if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64, $type)) {
                $imagelog = $imagelog."recovering info|";
                $imageBase64 = substr($imageBase64, strpos($imageBase64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                $img = base64_decode($imageBase64);
                $imagelog = $imagelog."looking for directory: ".'/var/www/media/evento/'.$id_evento.'|';
                if (!file_exists('/var/www/media/evento/'.$id_evento)) {
                    $imagelog = $imagelog."folder not exist|";
                    mkdir('/var/www/media/evento/'.$id_evento, 0777, true);
                    $imagelog = $imagelog."created folder|";
                }
                
                $imagelog = $imagelog."looking for image: ".'/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName().'|';
                if (file_exists('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName())) {
                    $imagelog = $imagelog."image exist|";
                    unlink('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName());
                    $imagelog = $imagelog."deleted image|";
                }

                $imagelog = $imagelog."saving|";
                file_put_contents('/var/www/media/evento/'.$id_evento.'/'.getDefaultCardImageName(), $img);
                $imagelog = $imagelog."saved";
            }
            $json["msg"] = $imagelog;
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["apikey"], $_POST["id"], $_POST["description"], $_POST["address"], $_POST["meta_description"], $_POST["meta_keyword"], $_POST["id_genre"], $_POST["imageChanged"], $_POST["base64"]);
?>