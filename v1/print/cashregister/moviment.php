<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/v1/api_include.php");

    function get($id_base, $id, $date, $codMovimento) {
        $query = "EXEC pr_movimentCashRegister ?, ?, ?";
        $params = array($id, $date, $codMovimento);
        $result = db_exec($query, $params, $id_base);

        $json = array();

        foreach ($result as &$row) {
            //die("aaa".print_r($row["Saldo"],true));
            $aux = array("id"=>$row["id"]
            ,"CodCaixa"=>$row["CodCaixa"]
            ,"CodMovimento"=>$row["CodMovimento"]
            ,"DatApresentacao"=>$row["DatApresentacao"]
            ,"DatHorApresentacao"=>$row["DatHorApresentacao"]
            ,"DatMovimento"=>$row["DatMovimento"]
            ,"HorSessao"=>$row["HorSessao"]
            ,"IdOperacao"=>$row["IdOperacao"]
            ,"NomPeca"=>$row["NomPeca"]
            ,"Operacao"=>$row["Operacao"]
            ,"Qtde"=>$row["Qtde"]
            ,"Tipo"=>$row["Tipo"]
            ,"TipSaque"=>$row["TipSaque"]
            ,"Valor"=>$row["Valor"]
            ,"ValorInt"=>$row["ValorInt"]);
            array_push($json,$aux);
        }

        logme();
        return $json;  
    }
    
    $obj = get($_REQUEST["id_base"], $_REQUEST["id"], $_REQUEST["date"], $_REQUEST["codMovimento"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <style>
        .fontSmall {
            font-size: 7px;
        }
        .fontBig {
            font-size: 9px;
            line-height: 9px;
        }
        .fontNormal {
            font-size: 8px;

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
        .lineheight {
            line-height: 2px;
        }
        .pagebreak { page-break-after: always; } 
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
                <div class="freetext">**** Caixa movimentação ****</div>

                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Operação</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Detalhe</th>
                                <th scope="col">Qtde.</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in sells" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="sells.length">{{item.Operacao}}</td>
                                <td v-if="!isEqualLastName(index, sells)" :rowspan="howManyNomPeca(item.NomPeca, sells)">{{item.NomPeca}}</td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="sells.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(sells)}}</td>
                                <td :class="sumValor(sells)<0 ? 'red' : 'green'">{{sumValor(sells) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in sells_reservation" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="sells_reservation.length">{{item.Operacao}}</td>
                                <td v-if="!isEqualLastName(index, sells_reservation)" :rowspan="howManyNomPeca(item.NomPeca, sells_reservation)">{{item.NomPeca}}</td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="sells_reservation.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(sells_reservation)}}</td>
                                <td :class="sumValor(sells_reservation)<0 ? 'red' : 'green'">{{sumValor(sells_reservation) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in sells_halfComplement" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="sells_halfComplement.length">{{item.Operacao}}</td>
                                <td v-if="!isEqualLastName(index, sells_halfComplement)" :rowspan="howManyNomPeca(item.NomPeca, sells_halfComplement)">{{item.NomPeca}}</td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="sells_halfComplement.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(sells_halfComplement)}}</td>
                                <td :class="sumValor(sells_halfComplement)<0 ? 'red' : 'green'">{{sumValor(sells_halfComplement) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in sells_refund" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="sells_refund.length">{{item.Operacao}}</td>
                                <td v-if="!isEqualLastName(index, sells_refund)" :rowspan="howManyNomPeca(item.NomPeca, sells_refund)">{{item.NomPeca}}</td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="sells_refund.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(sells_refund)}}</td>
                                <td :class="sumValor(sells_refund)<0 ? 'red' : 'green'">{{sumValor(sells_refund) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in withdraw" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="withdraw.length">{{item.Operacao}}</td>
                                <td v-if="index==0" :rowspan="withdraw.length"></td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="withdraw.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(withdraw)}}</td>
                                <td :class="sumValor(withdraw)<0 ? 'red' : 'green'">{{sumValor(withdraw) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in crClose" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="crClose.length">{{item.Operacao}}</td>
                                <td v-if="index==0" :rowspan="crClose.length"></td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="crClose.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(crClose)}}</td>
                                <td :class="sumValor(crClose)<0 ? 'red' : 'green'">{{sumValor(crClose) | money}}</td>
                            </tr>
                            <tr v-for="(item, index) in diff" v-bind:key="item.id">
                                <td v-if="index==0" :rowspan="diff.length">{{item.Operacao}}</td>
                                <td v-if="index==0" :rowspan="diff.length"></td>
                                <td>{{item.Tipo}}</td>
                                <td>{{item.Qtde}}</td>
                                <td>{{item.ValorInt | money}}</td>
                            </tr>
                            <tr v-if="diff.length > 0">
                                <td colspan="3">Total Parcial</td>
                                <td>{{sumQtde(diff)}}</td>
                                <td :class="sumValor(diff)<0 ? 'red' : 'green'">{{sumValor(diff) | money}}</td>
                            </tr>
                            <tr v-if="grids.movs.items.length > 0">
                                <td colspan="3">Total Geral</td>
                                <td>{{sumQtde(grids.movs.items)}}</td>
                                <td :class="sumValor(grids.movs.items)<0 ? 'red' : 'green'">{{sumValor(grids.movs.items) | money}}</td>
                            </tr>
                        </tbody>
                    </table>
            </td>
        </tr>
    </table>
<?php } ?>
<div class="pagebreak"></div>
<script lang="javascript">
    //window.print();
    //window.close();
</script>
</body>
</html>