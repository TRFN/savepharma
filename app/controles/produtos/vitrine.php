<?php
    function ctrl_produtos_vitrine($ctx){
        $dados = array();

        $estabelecimentos = array("null" => "Não especificado");
        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento!=="0"):
                $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
            endif;
        }

        foreach($ctx->produtos->ler() as $produtoId=>$produto){
            if(
                f_datas::diferenca(date("d/m/Y"), $produto["validade"]) > f_datas::diferenca(date("d/m/Y"), $produto["prazo"])
                &&
                f_datas::diferenca(date("d/m/Y"), $produto["prazo"]) > 6
                &&
                f_datas::diferenca(date("d/m/Y"), $produto["validade"]) > 31
            ){
                foreach($ctx->regras->ler() as $regraId=>$regra){
                    if($regra != "0" && (int)$regra["ativa"] == 1){
                        if(!(bool)(
                            f_datas::diferenca( // Diferença entre dias
                                f_datas::somar( // Soma a uma data
                                    -1, // Data atual
                                    (int)$regra["dia"], // Dias
                                    (int)$regra["mes"], // Meses
                                    0 // Anos
                                ), $produto["validade"] // Validade Produto
                            )
                        ) == (bool)$regra["condicao"]){
                            $pontos = $regra["dado2"];
                            $transacao = array((string)$regraId, (string)$produto["vinculo"], (string)$produtoId, $ctx->sessao->conexao()->vinculo, (string)$ctx->sessao->conexao()->id);
                        }
                    }
                }

                $quantidade = (int)$produto["quantidade"];
                if($quantidade == 0):
                    $quantidade = "Nenhum / Indisponível";
                elseif($quantidade == 1):
                    $quantidade = "01 Unidade";
                else:
                    $quantidade = $quantidade < 10 ? "0{$quantidade} Unidades":"{$quantidade} Unidades";
                endif;

                $acoes = '<div style="clear: both;"><a href="/painel/home/' . substr(sha1(json_encode($transacao)),10,8) . '!' . implode(":", $transacao) . '" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> PEGAR EMPRESTADO</a>';

                $acoes .= "<a href='/painel/notasfiscais/{$produto["notafiscal"]}/download' class='btn btn-success btn-block' target=_blank><i class='fa fa-file fa-fw'></i> NOTA FISCAL</a></div>";

                $dado = array(
                    "<div style='float: left; text-align: left'><strong>Nome:</strong> {$produto["nome"]}" .
                    "<br /><strong>Autor:</strong> {$estabelecimentos[(string)$produto["vinculo"]]}" .
                    "<br /><strong>Validade:</strong> {$produto["validade"]}</div>",
                    "<div style='float: left; text-align: left'><strong>Prazo:</strong> {$produto["prazo"]}" .
                    "<br /><strong>Quantidade:</strong> {$quantidade}" .
                    "<br /><strong>Valor:</strong> {$pontos} <small>pts</small></div>",
                    $acoes
                );

                // $dado[] = '
                //     <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse produto?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/produtos/gerir/id/'.$produtoId.'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                // ';

                $dados[] = $dado;
            }
        }

        // exit((bool)f_datas::diferenca(date("d/m/Y"),"03/09/2020"));
        // exit(f_datas::somar(-1,10,2,0));

        if($ctx->urlParams[3]=="comprar"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal("\n","O produto foi apagado.","success");
                history.pushState(null, null, "/painel/produtos/gerir/");
            ');
        }

        $ctx->regVarStrict("tabela", "tProdutos");

        $ctx->regVarStrict("painel-titulo", "Produtos Disponíveis");
        $ctx->regVarStrict("painel-icone", "asterisk");

        $ctx->regVarStrict("qtdRes", min(25,count($dados)));
        $ctx->regVarStrict("dados", json_encode($dados));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"Nome | Autor | Validade"),
            array("title"=>"Prazo | Quantidade | Valor"),
            array("title"=>"Ações")
        )));
    }
?>
