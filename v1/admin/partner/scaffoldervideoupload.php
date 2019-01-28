<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/admin/partner/scaffolderhelp/help.php");

    function saveindb($id_partner,$fileorder, $filetype, $source) {
        $query = "EXEC pr_admin_partner_whitelabel_videos_save ?,?,?,?";
        $params = array($id_partner,$fileorder, $filetype, $source);
        $result = db_exec($query, $params);
    }

    function get($id_user, $id_partner, $type) {

        //die(sys_get_temp_dir());
        isuservalidordie($id_user);

        try {
            if (
                !isset($_FILES['uploadedvideo']['error']) ||
                is_array($_FILES['uploadedvideo']['error'])
            ) {
                echo json_encode(array("success"=>false,"msg"=>"Falha no upload."));
                logme();
                die();    
            }
        
            // Check $_FILES['uploadedvideo']['error'] value.
            switch ($_FILES['uploadedvideo']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    echo json_encode(array("success"=>false,"msg"=>"Arquivo não foi enviado."));
                    logme();
                    die();    
                    // throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    echo json_encode(array("success"=>false,"msg"=>"Tamanho do arquivo acima do limite (40mb)."));
                    logme();
                    die();    
                    // throw new RuntimeException('Exceeded filesize limit.');
                default:
                    echo json_encode(array("success"=>false,"msg"=>"Falha no upload, erro desconhecido."));
                    logme();
                    die();    
//                die("Unknown errors.");
                    //throw new RuntimeException('Unknown errors.');
            }
        
            // You should also check filesize here. 
            if ($_FILES['uploadedvideo']['size'] > 40000000) {
                echo json_encode(array("success"=>false,"msg"=>"Tamanho do arquivo acima do limite - 40mb."));
                logme();
                die();    
            //throw new RuntimeException('Exceeded filesize limit.');
            }
        
            // DO NOT TRUST $_FILES['uploadedvideo']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                $finfo->file($_FILES['uploadedvideo']['tmp_name']),
                array(
                    'mp4' => 'video/mp4',
                    'webm' => 'video/webm'
                ),
                true
            )) {
                echo json_encode(array("success"=>false,"msg"=>"Formato inválido (Permitido apenas mp4 e webm)."));
                logme();
                die();    
                //throw new RuntimeException('Invalid file format.');
            }

            $aux = getinfofromdb($id_partner);

            $putlog = "recovering info|";
            //$imagebase64 = substr($imagebase64, strpos($imagebase64, ',') + 1);
            //$type = strtolower($type[1]); // jpg, png, gif
            //$putfile = base64_decode($base64);
            
            $putlog = $putlog."looking for video: ".'/var/www/media/videos/'.$aux["uniquename"].'/'.getVideoFilename().'.'.$type;
    
            $putlog = $putlog."looking for directory: ".'/var/www/media/videos/'.$aux["uniquename"].'|';
            if (!file_exists('/var/www/media/videos/'.$aux["uniquename"])) {
                $putlog = $putlog."folder not exist|";
                mkdir('/var/www/media/videos/'.$aux["uniquename"], 0777, true);
                $putlog = $putlog."created folder|";
            }
    
            if (file_exists('/var/www/media/videos/'.$aux["uniquename"].'/'.getVideoFilename().'.'.$type)) {
                $putlog = $putlog."video exist|";
                unlink('/var/www/media/videos/'.$aux["uniquename"].'/'.getVideoFilename().'.'.$type);
                $putlog = $putlog."deleted video|";
            }
    
            $order = 1;
            if ($type=="webm") {
                $order = 2;
            }
    
            $putlog = $putlog."saving|";
            //file_put_contents('/var/www/media/videos/'.$aux["uniquename"].'/'.getVideoFilename().'.'.$type, $_POST["base64"]);

            if (!move_uploaded_file($_FILES['uploadedvideo']['tmp_name'],'/var/www/media/videos/'.$aux["uniquename"].'/'.getVideoFilename().'.'.$type)) {
                echo json_encode(array("success"=>false,"msg"=>"Falha para mover o arquivo."));
                logme();
                die();    
//                throw new RuntimeException('Failed to move uploaded file.');
            }
            $putlog = $putlog."saved";

            $url = getDefaultMediaHost()."/videos/".$aux["uniquename"].'/'.getVideoFilename().'.'.$type;
    
            saveindb($id_partner, $order, $type, $url);
    
            $json = array("success"=>true
                        ,"msg"=>$putlog);
    
            echo json_encode($json);
            logme();
            die();    
        } catch (RuntimeException $e) {
            echo json_encode(array("success"=>false,"msg"=>$e->getMessage()));
            logme();
            die();    
//            echo $e->getMessage();
//            die("");
        }
    }

get($_POST["id_user"], $_POST["id_partner"], $_POST["type"]);
?>