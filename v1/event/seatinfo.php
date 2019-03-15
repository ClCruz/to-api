<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id_apresentacao, $indice, $loggedid) {
        $query = "EXEC pr_seat_info ?, ?, ?";
        $params = array($id_apresentacao, $indice, $loggedid);
        $result = db_exec($query, $params, $id_base);

        $json = array();
        foreach ($result as &$row) {
            $json = array(
                "Indice" => $row["Indice"],
                "NomSala" => $row["NomSala"],
                "isBought" => $row["isBought"],
                "isReserved" => $row["isReserved"],
                "isOpen" => $row["isOpen"],
                "isSelected" => $row["isSelected"],
                "isWeb" => $row["isWeb"],
                "isTicketOffice" => $row["isTicketOffice"],
                "NomObjeto" => $row["NomObjeto"],
                "NomSetor" => $row["NomSetor"],
                "TipBilhete" => $row["TipBilhete"],
                "ForPagto" => $row["ForPagto"],
                "Nome" => $row["Nome"],
                "CPF" => documentformatBR($row["CPF"]),
                "DDD" => $row["DDD"],
                "Telefone" => $row["Telefone"],
                "StaCadeira" => $row["StaCadeira"],
                "id_pedido_venda" => $row["id_pedido_venda"],
                "CodVenda" => $row["CodVenda"],
                "login" => $row["login"],
                "CodReserva" => $row["CodReserva"]
            );
        }

        echo json_encode($json);
        logme();

        die();    
    }

get($_POST["id_base"],$_POST["id_apresentacao"],$_POST["indice"],$_POST["loggedid"]);

?>