<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function getCities($api = null) {
        //die("teste: ".getwhitelabeldb()["host"]);
        $query = "EXEC pr_getcitiesforcards ?";
        $params = array($api);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
          $img = $row["img"] == '' ? '' : getDefaultMediaHost().$row["img"]."?".randomintbydate();
          $img_extra = $row["img_extra"] == '' ? '' : getDefaultMediaHost().$row["img_extra"]."?".randomintbydate();
          $json[] = array(
              "ds_municipio" => $row["ds_municipio"]
              ,"img" => $img
              ,"img_extra" => $img_extra
            );
        }
        //createTimer("getCities","Loop ended...");

        echo json_encode($json);
        logme();
        //performance();
        die();    
    }
//splitBadge('CompreIngressos|/badge/ci.png,ItauCard|/badge/teatro.png');
getCities($_REQUEST["apikey"]);

?>