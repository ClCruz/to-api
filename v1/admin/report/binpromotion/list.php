<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_user, $startdate, $enddate) {
        isuservalidordie($id_user);

        $startdate = $startdate." 00:00:00";
        $enddate = $enddate." 23:59:59";

        $query = "EXEC pr_bin_promotion_list ?, ?";
        $params = array($startdate, $enddate);
        $result = db_exec($query, $params);
        $json = array();

        foreach ($result as &$row) {
            $json[] = array(
                "buyer"=> $row["buyer"],
                "buyer_document"=> documentformatBR($row["buyer_document"]),
                "bin"=> $row["bin"],
                "sellid"=> $row["sellid"],
                "created_at"=> $row["created_at"],
                "sellfrom"=> $row["sellfrom"],
                "sellamount"=> $row["sellamount"],
                "sponsor"=> $row["sponsor"],
                "selltotal"=> $row["selltotal"],
                "sellcount"=> $row["sellcount"],
                "sellavg"=> $row["sellavg"]
            );
        }

        echo json_encode($json);
        logme();
        die();    
    }

get($_REQUEST["loggedId"],$_REQUEST["start"], $_REQUEST["end"]);
?>