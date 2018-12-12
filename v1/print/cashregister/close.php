<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");
    
    function get($id_base, $id) {
        $query = "EXEC pr_cashregister_closed_list ?, ?";
        $params = array($id, $id_base);
        $result = db_exec($query, $params);

        $json = array();

        foreach ($result as &$row) {
            $aux = array("login"=>$row["login"]
            ,"name"=>$row["name"]
            ,"id"=>$row["id"]
            ,"ds_nome_teatro"=>$row["ds_nome_teatro"]
            ,"ds_nome_base_sql"=>$row["ds_nome_base_sql"]
            ,"id_base"=>$row["id_base"]
            ,"id_ticketoffice_user"=>$row["id_ticketoffice_user"]
            ,"TipForPagto"=>$row["TipForPagto"]
            ,"codTipForPagto"=>$row["codTipForPagto"]
            ,"created"=>$row["created"]
            ,"close_date"=>$row["close_date"]
            ,"amount"=>$row["amount"]
            ,"amountDeclared"=>$row["amountDeclared"]
            ,"diff"=>$row["diff"]
            ,"amountTotal"=>$row["amountTotal"]
            ,"amountDeclaredTotal"=>$row["amountDeclaredTotal"]
            ,"diffTotal"=>$row["diffTotal"]);

            array_push($json,$aux);
        }

        echo json_encode($json);
        logme();

        return $json;
    }
    $obj = get($_REQUEST["id_base"], $_REQUEST["id"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <style>
        .left {
            font-size: 8px;
        }
        .middle {
            font-size: 9px;
            line-height: 9px;
        }
        .right {
            font-size: 8px;

        }
        .table {
        }

        .value {
            font-weight: bold;
        }
        .block {
            display:block;
        }

        .freetext {
            text-transform: uppercase;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .local {
            text-transform: uppercase;
            text-align: center;
        }
        .address {
        }
        .name {
            font-weight: bold;
        }
        .name.middle {
            font-size: 14px;
            padding-bottom: 1px;
            padding-top: 1px;   
            text-align: center;
        }
        .divDay {
            text-align: center;
        }
        .weekdayName {
            text-transform: uppercase;
            font-weight: bold;
        }
        .hour {
            font-weight: bold;
        }
        .day {
            text-transform: uppercase;
            font-weight: bold;
        }
        .opening_time {
            text-align: center;
        }
        .portao {
            display: none;
        }
        .roomName {
            text-transform: uppercase;
            text-align: center;
        }
        .seatRow {
            text-transform: uppercase;
        }
        .seatName {
            text-transform: uppercase;
        }
        .ticketIdentity {
        }
        .ticket {
            text-align: center;
        }        
        .ticket2 {
            font-size: 10px;
        }        
        .buyer {
        }
        .buyerDoc {
        }
        .payment {
            text-transform: uppercase;
        }
        .transaction {
        }
        .insurance_policy {
        }
        .eventRespName {
            text-transform: uppercase;
        }
        .eventRespDoc {
        }
        .eventRespAddress {
        }
        .user {
            text-transform: uppercase;
        }
        .countTicket {
        }
        .purchase_date {
        }
        .print_date {
        }
        .image {
            width: 70px;
            height: 70px;
        }
        .table {
            margin-left: 0px;
        }
        .tdleft {
            display:none;
            text-align: center;
            border-right: 1px solid #cdd0d4;
            border-bottom: 2px dotted #cdd0d4;
        }
        .tdmiddle {
            text-align: left;
            /*border-right: 1px solid #cdd0d4;*/
            /*border-bottom: 2px dotted #cdd0d4;*/
        }
        .tdright {
            display:none;
            text-align: center;
            border-bottom: 2px dotted #cdd0d4;
        }
        .dotted {
            
        }
        .dotted2 {
            
        }
        .lineheight {
            line-height: 2px;
        }
        .pagebreak { page-break-after: always; } 
        .cut {
            line-height: 0px;
            padding-bottom: 1px;
        }
        .cutoutside {
            line-height: 0px;
            padding-bottom: 15px;
            padding-left: 9px;
        }
        .noimage {
            padding-left:49px;
        }
    </style>
</head>
<body>
<?php 
$count = 0;
?>
<?php foreach ($obj as &$row) {?>
    <?php $count = $count +1; ?>
    <?php if ($count != 1) { ?>
    <div class="pagebreak"></div>
    <?php } ?>
    <table class="table dotted">
        <tr>
            <td class="tdmiddle">
                <div class="freetext">**** FECHAMENTO DE CAIXA ****</div>
                <div class="login middle"><?php echo $row["login"] ?></div>
                <div class="name middle"><?php echo $row["name"] ?></div>
                <div class="date divDay"><?php echo $row["date"] ?></div>
                <div class="roomName middle">Setor: <span class="value"><?php echo $row["roomName"] ?></span></div>
                <div class="middle">
                    <span class="seatRow middle">Fileira: <span class="value"><?php echo $row["seatRow"] ?></span></span>
                    <span class="seatName middle"> | Assento: <span class="value"><?php echo $row["seatName"] ?></span></span>
                </div>
                <div class="ticketIdentity middle">Código: <span class="value"><?php echo $row["reservationCode"]."-".$row["seatIndice"] ?></span></div>
                <div class="middle">
                    <span class="buyer middle">Reservado para: <span class="value"><?php echo $row["buyer"] ?></span></span>
                    <span class="buyerDoc middle">CPF/CNPJ: <span class="value"><?php echo $row["buyerDoc"] ?></span></span>
                </div>
                <div class="block middle"></div>
                <div class="middle">
                    <span class="countTicket middle">Qtde: <span class="value"><?php echo $row["howMany"] ?></span></span>
                    <span class="user middle"> - <span class="value"><?php echo $row["user"] ?></span></span>
                    <span class="print_date middle"> IM:<span class="value"><?php echo $row["print_date"] ?></span></span>
                </div>
            </td>
        </tr>
    </table>
<?php } ?>
<div class="pagebreak"></div>
<script lang="javascript">
    window.print();
    window.close();
</script>
</body>
</html>