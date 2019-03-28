<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");

    use \Gumlet\ImageResize;



    function get(
        $id,$id_partner,$isactive
        ,$startdate,$enddate,$title
        ,$content,$link,$type
        ,$imagebase64,$campaign,$name
        ,$priority,$index,$saveimage
    ) {
        if ($id == "") {
            $id = '00000000-0000-0000-0000-000000000000';
        }

        $startdate = modifyDateWithHour($startdate);
        $enddate = modifyDateWithHour($enddate, "23:59");

        $query = "EXEC pr_ad_save ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($id,$id_partner,$isactive
        ,$startdate,$enddate,$title
        ,$content,$link,$type
        ,$campaign,$name,$priority
        ,$index);
        $result = db_exec($query, $params);

        $directoryname = "";

        foreach ($result as &$row) {
            $directoryname = $row["directoryname"];
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
                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/discovery/'.$directoryname.'|';
                    if (!file_exists('/var/www/media/discovery/'.$directoryname)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/discovery/'.$directoryname, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }
                    $imagelog = $imagelog."looking for directory: ".'/var/www/media/ori_discovery/'.$directoryname.'|';
                    if (!file_exists('/var/www/media/ori_discovery/'.$directoryname)) {
                        $imagelog = $imagelog."folder not exist|";
                        mkdir('/var/www/media/ori_discovery/'.$directoryname, 0777, true);
                        $imagelog = $imagelog."created folder|";
                    }

                    $filename = getDefaultCardAdImageName();
                    if ($type=="banner") {
                        $filename = getDefaultBannerAdImageName();
                    }
                    

                    $imagelog = $imagelog."looking for image: ".'/var/www/media/ori_discovery/'.$directoryname.'/'.$filename.'|';
                    if (file_exists('/var/www/media/ori_discovery/'.$directoryname.'/'.$filename)) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/ori_discovery/'.$directoryname.'/'.$filename);
                        $imagelog = $imagelog."deleted image|";
                    }    

                    $imagelog = $imagelog."looking for image: ".'/var/www/media/discovery/'.$directoryname.'/'.$filename.'|';
                    if (file_exists('/var/www/media/discovery/'.$directoryname.'/'.$filename)) {
                        $imagelog = $imagelog."image exist|";
                        unlink('/var/www/media/discovery/'.$directoryname.'/'.$filename);
                        $imagelog = $imagelog."deleted image|";
                    }    

                    $imagelog = $imagelog."saving|";
                    file_put_contents('/var/www/media/ori_discovery/'.$directoryname.'/'.$filename, $img);
                    $imagelog = $imagelog."saved";

                    $imageResizer = new ImageResize('/var/www/media/ori_discovery/'.$directoryname.'/'.$filename);
                    if ($type == "card") {
                        $imageResizer->resize(600,314, $allow_enlarge = true);
                    }
                    else
                    {
                        $imageResizer->resize(1280, 180, $allow_enlarge = true);
                    }
                    $imageResizer->save('/var/www/media/discovery/'.$directoryname.'/'.$filename);
                }
                $json["imagelog"] = $imagelog;
            }
        }

        echo json_encode($json);
        logme();
        die();    
    }

get(
    $_POST["id"],$_POST["id_partner"],$_POST["isactive"]
    ,$_POST["startdate"],$_POST["enddate"],$_POST["title"]
    ,$_POST["content"],$_POST["link"],$_POST["type"]
    ,$_POST["image"],$_POST["campaign"],$_POST["name"]
    ,$_POST["priority"],$_POST["index"],$_POST["saveimage"]
);
?>