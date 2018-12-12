<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function open($id_base, $id) {

        $query = "EXEC pr_currentCashRegister ?";
        $params = array($id);
        $result = db_exec($query, $params, $id_base);

        $isOpen = false;
        
        foreach ($result as &$row) {
            $isOpen = $row["opened"] == 1 || $row["opened"] == "1";
        }

        if ($isOpen)
        {
            $json = array("success"=>true, "alreadyOpen"=>true);
            echo json_encode($json);    
        }
        else {
            $query = "EXEC pr_cashregister ?";
            $params = array($id);
            $result = db_exec($query, $params, $id_base);
    
            $json = array("success"=>true, "alreadyOpen"=>false);
    
            echo json_encode($json);    
        }
        logme();
        die();    
    }
open($_REQUEST["id_base"],$_POST["id"]);
?>