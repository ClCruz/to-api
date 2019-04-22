<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/php-image-resize/ImageResize.php");
    use \Gumlet\ImageResize;


    function savegitexec($id_partner,$started) {
        $query = "EXEC pr_admin_partner_whitelabel_git_save ?,?";
        $params = array($id_partner,$started);
        // $result = db_exec($query, $params);
    }

    function get($id_user
                ,$id_partner,$json_ga,$json_meta_description,$json_meta_keywords
                ,$json_template,$json_info_title,$json_info_description
                ,$json_info_cnpj,$json_info_companyaddress,$json_info_companyname,$scss_colors_primary
                ,$scss_colors_secondary,$changedImage, $imagebase64, $generate) {
        isuservalidordie($id_user);
        $query = "EXEC pr_admin_partner_whitelabelcontent_save ?,?,?,?,?,?,?,?,?,?,?,?";
        $params = array($id_partner,$json_ga,$json_meta_description,$json_meta_keywords
        ,$json_template,$json_info_title,$json_info_description
        ,$json_info_cnpj, $json_info_companyaddress,$json_info_companyname,$scss_colors_primary
        ,$scss_colors_secondary);
        $result = db_exec($query, $params);

        $imagelog = "";
        $doimage = false;

        if (((string)$changedImage)=="true" || ((string)$changedImage)=="1") {
            $doimage = true;
        }

        if ($doimage) {      
            $aux = getinfofromdb($id_partner,"");

            $imagelog =  $imagelog."altered image|";
            if (preg_match('/^data:image\/(\w+);base64,/', $imagebase64, $type)) {
                $imagelog = $imagelog."recovering info|";
                $imagebase64 = substr($imagebase64, strpos($imagebase64, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                $img = base64_decode($imagebase64);
                
                $imagelog = $imagelog."looking for image: ".'/var/www/media/logo/logo-'.$aux["uniquename"].'.'.$type;

                if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.'.$type)) {
                    $imagelog = $imagelog."image exist|";
                    unlink('/var/www/media/logos/logo-'.$aux["uniquename"].'.'.$type);
                    $imagelog = $imagelog."deleted image|";
                }
                $imagelog = $imagelog."saving|";
                file_put_contents('/var/www/media/logos/logo-'.$aux["uniquename"].'.'.$type, $img);
                $imagelog = $imagelog."saved";
            }
        }
        else {
            $type = "png";
            if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.png')) {
                $type = "png";
            }
            if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.jpg')) {
                $type = "jpg";
            }
            if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.jpeg')) {
                $type = "jpeg";
            }
            if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.svg')) {
                $type = "svg";
            }
            if (file_exists('/var/www/media/logos/logo-'.$aux["uniquename"].'.gif')) {
                $type = "gif";
            }
        }

        if ($generate > 0) {
            savegitexec($id_partner, 1);
            $do_site = $generate == 1 || $generate == 2;
            $do_legacy = $generate == 1 || $generate == 4;
            $do_api = $generate == 1 || $generate == 3;
            $do_admin = $generate == 1 || $generate == 5;
            doall($id_partner, $do_site, $do_legacy, $do_api, $do_admin, $type);
            savegitexec($id_partner, 0);
        }

        $json = array("success"=>true
                    ,"msg"=>'Processo executado');

        echo json_encode($json);
        logme();
        die();    
    }

get($_POST["id_user"],$_POST["id_partner"],$_POST["json_ga"]
,$_POST["json_meta_description"],$_POST["json_meta_keywords"],$_POST["json_template"]
,$_POST["json_info_title"],$_POST["json_info_description"],$_POST["json_info_cnpj"]
,$_POST["json_info_companyaddress"],$_POST["json_info_companyname"],$_POST["scss_colors_primary"]
,$_POST["scss_colors_secondary"],$_POST["changedImage"],$_POST["imagebase64"]
,$_POST["generate"]);
?>