<?php
    function ctrl_relatorios($ctx){

        // MODELO:
        //      "id" => ID Unica
        //      "produto" => Produto
        //      "regra" => Regra
        //      "por" => Quem Disponibilizou
        //      "para" => Quem Adquiriu
        //      "pontos1" => Pontos relativos a quem disponibilizou
        //      "pontos2" => Pontos relativos a quem adquiriu
        //      "quantidade" => Quantidade

        $dados = array();
        $tdg = array();

        // header("Content-Type: text/plain");
        // print_r($ctx->relatorios->ler());
        // exit;
        //


        // exit(print_r($ctx->urlParams,true));

        if($ctx->urlParams[2] == "t" && ($relatorio=$ctx->relatorios->ler((int)$ctx->urlParams[3])) != null){
            // header("Content-Type: text/plain");
            // print_r($relatorio);
            // exit;
            ?>
            <style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;  width: 95vw; max-width: 800px;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-lqy6{text-align:right;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-dvpl{border-color:inherit;text-align:right;vertical-align:top}
.tg-wrap {margin: 10vh auto; display: block; width: 95vw; max-width: 800px;}
@media screen and (max-width: 767px) {.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>
<div class="tg-wrap">
<center>
    <img src="/images/logo-azul.png" width="40%" /><br /><br />
    <h4>COMPROVANTE DE EMPRESTIMO</h4></center>

    <table class="tg">
<thead>
  <tr>
    <th class="tg-c3ow" colspan="3">SOLICITANTE</th>
    <th class="tg-c3ow" colspan="3">CEDENTE</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="tg-c3ow" colspan="3"><?=$relatorio[0]["para"]["nome"];?></td>
    <td class="tg-c3ow" colspan="3"><?=$relatorio[0]["por"]["nome"];?></td>
  </tr>
  <tr>
    <td class="tg-0pky">DESCRIÇÃO</td>
    <td class="tg-0pky" colspan="5"><?=$relatorio[0]["produto"]["nome"];?></td>
  </tr>
  <tr>
    <td class="tg-dvpl">LOTE</td>
    <td class="tg-c3ow"><?=$relatorio[0]["produto"]["lote"];?></td>
    <td class="tg-dvpl">VALIDADE</td>
    <td class="tg-c3ow"><?=$relatorio[0]["produto"]["validade"];?></td>
    <td class="tg-dvpl">QUANTIDADE</td>
    <td class="tg-baqh"><?=$relatorio[0]["quantidade"];?></td>
  </tr>
  <tr>
    <td class="tg-lqy6">RETIRADA</td>
    <td class="tg-baqh"><?=$relatorio["retirada"];?></td>
    <td class="tg-lqy6">PREVISÃO</td>
    <td class="tg-baqh"><?=$relatorio[0]["produto"]["prazo"];?></td>
    <td class="tg-lqy6">DEVOLUÇÃO</td>
    <td class="tg-baqh"> AINDA NÃO REALIZADA </td>
  </tr>
  <tr>
    <td class="tg-lqy6">PERMUTA</td>
    <td class="tg-baqh"> - </td>
    <td class="tg-lqy6">ATRASO</td>
    <td class="tg-baqh">NÃO</td>
    <td class="tg-lqy6">DEVOLVIDO</td>
    <td class="tg-baqh">NÃO</td>
  </tr>
</tbody>
</table></div>
            <?php
            exit;
        }


        foreach($ctx->relatorios->ler() as $dadoID => $dado){
            if($ctx->sessao->conexao()->nivelacesso == "admin" || (int)$dado["por"]["id"] == (int)$ctx->sessao->conexao()->vinculo || (int)$dado["para"]["id"] == (int)$ctx->sessao->conexao()->vinculo){
                $td = array();
                $td[] = "<h4># $dadoID</h4>";

                $td[] = $dado["produto"]["nome"] . "<br /><small>{$dado["por"]["nome"]}</small>";

                $pontos = array(abs($dado["pontos1"]), abs($dado["pontos2"]));

                $td[] = $ctx->sessao->conexao()->nivelacesso == "admin"
                    ? "<strong>{$dado["por"]["nome"]}</strong> ganhou {$pontos[0]} pts<br /><strong>{$dado["para"]["nome"]}</strong> pagou {$pontos[1]} pts"
                    : (
                        (int)$dado["por"]["id"] == (int)$ctx->sessao->conexao()->vinculo
                            ? "Você ganhou<br />{$pontos[0]} pontos"
                            : (
                                (int)$dado["para"]["id"] == (int)$ctx->sessao->conexao()->vinculo
                                    ? "Você pagou<br />{$pontos[1]} pontos"
                                    : "&ndash;"
                                )
                        );

                $acoes = '<div style="clear: both;">';

                if($ctx->sessao->conexao()->nivelacesso == "admin" || (int)$dado["para"]["id"] == (int)$ctx->sessao->conexao()->vinculo){
                    $status = isset($dado["status"])?(int)$dado["status"]:0;
                    switch($status){
                        case 0:
                            $acoes .= '<a  href="javascript:;" onclick=\'msg_confirmacao(["","Você deseja mesmo declarar que\no produto foi recebido?","warning","Sim, desejo","warning"],()=>{location.href="/painel/relatorios/t/'.$dadoID.'/recebido/"})\' class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> RECEBI O PRODUTO</a>';
                        break;
                        case 1:
                            $acoes .= '<a  href="javascript:;" onclick=\'msg_confirmacao(["","Você deseja mesmo declarar que\no produto foi devolvido?","warning","Sim, desejo","warning"],()=>{location.href="/painel/relatorios/t/'.$dadoID.'/devolvido/"})\' class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> DEVOLVI O PRODUTO</a>';
                        break;
                    }
                }

                $acoes .= "<a href='/painel/relatorios/t/{$dadoID}/ver' class='btn btn-success btn-block' target=_blank><i class='fa fa-file fa-fw'></i> COMPROVANTE</a></div>";

                // $acoes = '
                //     <a href="/painel/regras/gerir/id/'.$regraId.'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                //         &nbsp;
                //     <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse regra?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/regras/gerir/id/'.$regraId.'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                // ';

                $td[] = $acoes;

                $tdg[] = $td;
            }

            $o_dado = (object)$dado;

            // echo $o_dado->por["id"];
            // echo " - ";
            // echo $o_dado->para["id"];
            // echo "<br />";

            if(!isset($dados[$o_dado->por["id"]])){
                $dados[$o_dado->por["id"]] = array((int)$o_dado->quantidade, 0, (int)$o_dado->pontos1, 0);
            } else {
                $dados[$o_dado->por["id"]][0] += (int)$o_dado->quantidade;
                $dados[$o_dado->por["id"]][2] += (int)$o_dado->pontos1;
            }

            if(!isset($dados[$o_dado->para["id"]])){
                $dados[$o_dado->para["id"]] = array(0, (int)$o_dado->quantidade, 0, (int)$o_dado->pontos2);
            } else {
                $dados[$o_dado->para["id"]][1] += (int)$o_dado->quantidade;
                $dados[$o_dado->para["id"]][3] += (int)$o_dado->pontos2;
            }
        }

        $emprestou = 0;
        $pegou = 0;
        $p_entrou = 0;
        $p_saiu = 0;

        if($ctx->sessao->conexao()->nivelacesso == "admin"){
            foreach($dados as $dado){
                $emprestou += $dado[0];
                $pegou += $dado[1];
                $p_entrou += $dado[2];
                $p_saiu += $dado[3];
            }
        } else {
            $emprestou += $dados[$ctx->sessao->conexao()->vinculo][0];
            $pegou += $dados[$ctx->sessao->conexao()->vinculo][1];
            $p_entrou += $dados[$ctx->sessao->conexao()->vinculo][2];
            $p_saiu += $dados[$ctx->sessao->conexao()->vinculo][3];
        }

        $ctx->regVarStrict("emprestou", $emprestou);
        $ctx->regVarStrict("emprestado", $pegou);
        $ctx->regVarStrict("entrada", $p_entrou);
        $ctx->regVarStrict("saida", abs($p_saiu));

        $ctx->regVarStrict("tabela", "tRelatorios");

        $ctx->regVarStrict("painel-titulo", "Transações Realizadas");
        $ctx->regVarStrict("painel-icone", "file");

        $ctx->regVarStrict("qtdRes", min(25,count($tdg)));
        $ctx->regVarStrict("dados", json_encode($tdg));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"ID"),
            array("title"=>"Produto"),
            array("title"=>"Pontos"),
            array("title"=>"Ações")
        )));
    }
?>
