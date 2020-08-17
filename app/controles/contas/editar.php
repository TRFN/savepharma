<?php
    function ctrl_contas_editar($ctx){
        $estabelecimentos = array("null" => "Não especificado");
        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento!=="0"):
                $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
            endif;
        }

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-senha","");
        $ctx->regVarStrict("input-senhaconf","");
        $ctx->regVarStrict("input-nivelacesso","gerente");
        $ctx->regVarStrict("input-vinculo", "null");
        $ctx->regVarStrict("input-cpf","");
        $ctx->regVarStrict("input-crf","");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Alterar Dados");
        $ctx->regVarStrict("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVarStrict("mensagem-erro", "");
        $ctx->regVarStrict("painel-titulo", "Dados da conta");
        $ctx->regVarStrict("painel-icone", "user");

        $existe = false;

        if(isset($ctx->urlParams[4])):
            foreach( $ctx->sessao->listar_contas() as $conta ){
                if(
                    $ctx->sessao->conexao()->nivelacesso !== "farmaceutico" &&
                    (int)$conta["id"] == (int)$ctx->urlParams[4] &&
                    (string)$conta["id"] != (string)$ctx->sessao->conexao()->id &&
                    (
                        $ctx->sessao->conexao()->nivelacesso=="admin" ||
                        (
                            $ctx->sessao->conexao()->nivelacesso=="gerente" &&
                            $conta["nivelacesso"] == "farmaceutico" &&
                            isset($conta["vinculo"]) &&
                            (string)$conta["vinculo"] == (string)$ctx->sessao->conexao()->vinculo
                        )
                    )
                ){
                    unset($conta["id"]);
                    foreach($conta as $chave => $valor){
                        $ctx->regVarStrict("input-{$chave}",$valor);
                    }
                    $existe = true;
                    break;
                }
            }
        endif;

        if($ctx->urlParams[1] == "meus-dados"):
            $conta = $ctx->sessao->conexao();
            unset($conta->id);
            unset($conta->senha);
            foreach($conta as $chave => $valor){
                $ctx->regVarStrict("input-{$chave}",$valor);
            }
            $existe = true;
        endif;

        if(!$existe){
            header("Location: /painel/" . ($ctx->sessao->conexao()->nivelacesso == "farmaceutico" ? "":"contas/gerir"));
        }
        if(isset($ctx->urlParams[5]) && $ctx->urlParams[5]=="apagar" && $ctx->sessao->conexao()->nivelacesso !== "farmaceutico"){
            $ctx->sessao->apagar_conta((int)$ctx->urlParams[4]);
            header("Location: /painel/contas/gerir/apagado");
        }
        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(!empty($dados->senha) && $dados->senha !== $dados->senhaconf){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;As senhas não conferem! Insira novamente a confirmação da senha.");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("input-senhaconf","");
                $ctx->regVarStrict("input-error","#senhaconf");
            } else {
                if(empty($dados->senha)){
                    unset($_POST["senha"]);
                }
                unset($_POST["senhaconf"]);

                $ctx->sessao->alterar_dado($_POST, (string)($ctx->urlParams[1] == "meus-dados" ? $ctx->sessao->conexao()->id:$ctx->urlParams[4]));

                if(isset($_POST["senha"])): unset($_POST["senha"]); endif;

                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }

                $ctx->regVarStrict("mensagem-aviso", '
                    swal("\n","Conta modificada com sucesso!","success");
                ');
            }
        }
    }
?>
