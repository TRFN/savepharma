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
                $meuproduto = (string)$produto["vinculo"] == (string)$ctx->sessao->conexao()->vinculo;
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
                            if($meuproduto):
                                $pontos = $regra["dado1"];
                            else:
                                $pontos = $regra["dado2"];
                            endif;
                            $transacao = array(
                                (string)$regraId,
                                (string)$produto["vinculo"],
                                (string)$produtoId,
                                $ctx->sessao->conexao()->vinculo,
                                (string)$ctx->sessao->conexao()->id
                            );
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
                if($meuproduto):
                    $acoes = '<div style="clear: both;"><a href="/painel/produtos/gerir/id/' . $produtoId . '" class="btn btn-primary btn-block"><i class="fa fa-edit"></i> MODIFICAR PRODUTO</a>';
                else:
                    $acoes = '<div style="clear: both;"><a href="/painel/home/' . (transacao::criar($transacao)) . '" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> PEGAR EMPRESTADO</a>';
                endif;
                $acoes .= "<a href='/painel/notasfiscais/{$produto["notafiscal"]}/download' class='btn btn-success btn-block' target=_blank><i class='fa fa-file fa-fw'></i> NOTA FISCAL</a></div>";

                $dado = array(
                    "<div style='float: left; text-align: left'><strong>Nome:</strong> {$produto["nome"]}" .
                    "<br /><strong>Autor: </strong> " . ($meuproduto ? "Estabelecimento que sou {$ctx->sessao->conexao()->nivelacesso}":$estabelecimentos[(string)$produto["vinculo"]]) .
                    "<br /><strong>Validade:</strong> {$produto["validade"]}</div>",
                    "<div style='float: left; text-align: left'><strong>Prazo:</strong> {$produto["prazo"]}" .
                    "<br /><strong>Quantidade:</strong> {$quantidade}" .
                    "<br /><strong>Pontos " . ($meuproduto?"a ganhar":"a pagar") . ":</strong> {$pontos} <small>pts</small></div>",
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

        if(!empty($ctx->urlParams[2])){
            if(is_array($conteudo=transacao::ler($ctx->urlParams[2]))){
                foreach($ctx->produtos->ler() as $produtoId=>$produto){
                    if((int)$produtoId == (int)$transacao[2] && $produto !== "0"){
                        $_SESSION["transacao"] = $conteudo;

                        $qtdmax = $produto["quantidade"];

                        $ctx->regVarStrict("mensagem-aviso",'msg_confirmacao(["","Você tem certeza que deseja\nadquirir esse produto?\n\n>> ' . $produto["nome"] . ' <<","info","Sim, eu tenho","info"],()=>{window.prodqtd="1"; swal({html: true,confirmButtonText: "PEGAR EMPRESTADO",title: "\n",text: "<h2>Especifique a quantidade</h2><br /><label>Quantidade: <input type=number class=form-control style=\'width: 120px;\' min=1 value=1
                            min=\'1\' max=\'' . $produto["quantidade"] . '\' autofocus onchange=\'if(parseInt(this.value)<1 || this.value.length==0){this.value=1} else if(parseInt(this.value) > ' . $qtdmax . '){this.value=' . $qtdmax . ';}
                            window.prodqtd=String(this.value);\'
                        /></label>",type: "info"}, function(){location.href="/painel/home/finalizar-transacao/"+prodqtd;})});');
                    }
                }
            } elseif($ctx->urlParams[2] == "finalizar-transacao" && is_array($_SESSION["transacao"])){
                if($ctx->sessao->conexao()->vinculo=="null"){
                    if($ctx->sessao->conexao()->nivelacesso == "gerente"):
                        $ctx->regVarStrict("mensagem-aviso",'swal({title: "\n",text: "Você não pode fazer essa operação\nporque você não está vinculado a um estabelecimento.",type: "error"}, function(){location.href="/painel/estabelecimentos/adicionar"});');
                    elseif($ctx->sessao->conexao()->nivelacesso == "farmaceutico"):
                        $ctx->regVarStrict("mensagem-aviso",'swal({title: "\n",text: "Peça para o gerente configurar\nsua conta corretamente.",type: "warning"}, function(){location.href="/painel/home"});');
                    endif;
                    $_SESSION["transacao"] = null;
                } else {
                    if($ctx->sessao->conexao()->nivelacesso == "admin"):
                        $ctx->regVarStrict("mensagem-aviso",'swal({title: "\n",text: "Você e um administrador do sistema, logo, \nnão pode realizar essa operação.",type: "info"}, function(){location.href="/painel/home"});');
                    elseif((string)$produto["vinculo"] == (string)$ctx->sessao->conexao()->vinculo):
                        $ctx->regVarStrict("mensagem-aviso",'swal({title: "\n",text: "Você não pode adquirir\nseu próprio produto.",type: "info"}, function(){location.href="/painel/home"});');
                    else:
                        $transacao = $_SESSION["transacao"];
                        $_SESSION["transacao"] = null;

                        foreach($ctx->produtos->ler() as $produtoId=>$produto){
                            if((int)$produtoId == (int)$transacao[2] && $produto !== "0"){
                                $produto["quantidade"] -= (int)$ctx->urlParams[3];
                                foreach($ctx->regras->ler() as $regraId=>$regra){
                                    if($regra != "0" && (int)$regra["ativa"] == 1 && (int)$regraId == (int)$transacao[0]){
                                        $por  = $ctx->estabelecimentos->ler($transacao[1]);
                                        $para = $ctx->estabelecimentos->ler($transacao[3]);

                                        $por["pontos"]  += ($pontos1=(((int)$regra["dado1"]) * ((bool)(int)$regra["acao1"]?1:-1) * (int)$ctx->urlParams[3]));
                                        $para["pontos"] += ($pontos2=(((int)$regra["dado2"]) * ((bool)(int)$regra["acao2"]?1:-1) * (int)$ctx->urlParams[3]));

                                        if($por["pontos"] < 0){$por["pontos"] = 0;}

                                        if($para["pontos"] < 0){
                                            $ctx->regVarStrict("mensagem-aviso",'swal({title: "\n",text: "Você não tem saldo suficiente\npara realizar essa operação.",type: "info"}, function(){location.href="/painel/home/"});');
                                        } else {
                                            $relatorio = array(
                                                "id" => (string)(count($ctx->relatorios->ler())), // ID Unica
                                                "produto" => $produto, // Produto
                                                "regra" => $regra, // Regra
                                                "por" => $por, // Quem Disponibilizou
                                                "para" => $para, // Quem Adquiriu
                                                "pontos1" => $pontos1, // Pontos relativos a quem disponibilizou
                                                "pontos2" => $pontos2, // Pontos relativos a quem adquiriu
                                                "quantidade" => (int)$ctx->urlParams[3] // Quantidade
                                            );

                                            $ctx->estabelecimentos->escrever($transacao[1], $por);
                                            $ctx->estabelecimentos->escrever($transacao[3], $para);
                                            $ctx->produtos->escrever($produtoId, $produto);
                                            $ctx->relatorios->escrever($relatorio["id"], $relatorio);
                                            $ctx->estabelecimentos->gravar();
                                            $ctx->produtos->gravar();
                                            $ctx->relatorios->gravar();

                                            $ctx->regVarStrict("mensagem-aviso",'swal({html: true,title: "\n",confirmButtonText: "FECHAR" ,text: "Operação concluída!<br /><br /><a href=\'/painel/relatorios/ver/'.$relatorio["id"].'\' class=\'btn btn-info btn-lg\'><i class=\'fa fa-text\'></i> Ver Comprovante</a>",type: "success"},function(){location.href="/painel/home/"});');
                                        }
                                    }
                                }
                            }
                        }
                    endif;
                }
            } else {
                header("Location: ./../");
            }
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
