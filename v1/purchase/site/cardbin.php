<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/purchase/site/helper.php");

    function validateBIN($id_purchase, $id_client, $type, $id_session, $bin) {
        traceme($id_purchase, "Validate BIN - Start", json_encode(array("id_client"=>$id_client, "type"=> $type, "id_session"=> $id_session, "bin"=> $bin)),0);
        $ret = null;
        $ret = array("success"=>true, "msg"=>"");
        switch ($type) {
            case "itau":
              //$ret = validateItau($id_client, $id_session, $bin);  
            break;
            default:
            break;
        }
        traceme($id_purchase, "Validate BIN - END", json_encode(array("id_client"=>$id_client, "type"=> $type, "id_session"=> $id_session, "bin"=> $bin)),0);

        return $ret;
    }

    
    function validateItau($id_client, $id_session, $bin) {

        $basesandreserva = getbases4purchasedistinct($id_session);

        $validated = array();
        $hasError = false;
        $errorMsg = "";

        traceme($id_purchase, "Validate BIN - looping in bases", json_encode(array("id_client"=>$id_client, "id_session"=> $id_session ,"bin"=> $bin)),0);
        foreach ($basesandreserva as &$row) {
            $query = "EXEC pr_purchase_bin_validate ?, ?";
            $params = array($bin, $id_session);
            $result = db_exec($query, $params, $row["id_base"]);
            foreach ($result as &$row2) {
                if ($row2["success"] == 0 && $hasError == false) {
                    $hasError = true;
                    $errorMsg = $row2["msg"];
                }

                $validated[] = array("id_base"=>$row2["id_base"]
                                    ,"success"=>$row2["success"]
                                    ,"msg"=>$row2["msg"]);
            }
        }
        traceme($id_purchase, "Validate BIN - looping result", json_encode($validated),0);

        return array("success"=>$hasError == false, "msg"=>$errorMsg);
    }
?>