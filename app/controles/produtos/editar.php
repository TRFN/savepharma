<?php
    function ctrl_estabelecimentos_editar($ctx){
        if($ctx->sessao->conexao()->nivelacesso == "farmaceutico"){
            header("Location: /painel/home/");
            exit;
        }

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-pontos","100");
        $ctx->regVarStrict("input-telefone","");
        $ctx->regVarStrict("input-cnpj","");
        $ctx->regVarStrict("input-endereco","");
        $ctx->regVarStrict("input-web","");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Modificar Estabelecimento");
        $ctx->regVarStrict("painel-titulo", "Dados do estabelecimento");
        $ctx->regVarStrict("painel-icone", "shopping-basket");

        $existe = false;

        if(isset($ctx->urlParams[4])):
            foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
                if((int)$estabelecimentoId == (int)$ctx->urlParams[4] && $estabelecimento !== "0"){
                    $idEstabelecimento = $estabelecimentoId;
                    unset($estabelecimento["id"]);
                    foreach($estabelecimento as $chave => $valor){
                        $ctx->regVarStrict("input-{$chave}",$valor);
                    }
                    $existe = true;
                    break;
                }
            }
        endif;

        if(!$existe){
            header("Location: /painel/estabelecimentos/" . ($ctx->sessao->conexao()->nivelacesso == "admin"?"gerir":"adicionar"));
        }

        $ctx->regVarStrict("input-vinculo", "null");

        $gerentes["null"] = "Não especificado (em branco)";

        foreach($ctx->sessao->listar_contas() as $conta){
            $idConta = $conta["id"];
            if($conta["nivelacesso"] == "gerente"){
                $gerentes["{$idConta}"] = $conta["nome"];
                if((int)$conta["vinculo"] === $idEstabelecimento && (string)$conta["vinculo"] !== "null"){
                    $ctx->regVarStrict("input-vinculo", "{$idConta}");
                }
            }
        }

        $ctx->regVarStrict("gerentes", json_encode($gerentes));

        if(isset($ctx->urlParams[5]) && $ctx->urlParams[5]=="apagar"){
            $ctx->estabelecimentos->escrever((int)$ctx->urlParams[4], "0");
            $ctx->estabelecimentos->gravar();
            header("Location: /painel/estabelecimentos/gerir/apagado");
        }

        if(isset($_POST["nome"])){
            $erro = -1;

            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $erro = "O nome é obrigatório.";
                $ctx->regVarStrict("input-error","#nome");
            }

            elseif(empty($dados->email)){
                $erro = "O email é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->telefone)){
                $erro = "O telefone é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->endereco)){
                $erro = "O endereço é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->cnpj)){
                $erro = "O CNPJ é obrigatório.";
                $ctx->regVarStrict("input-error","#cnpj");
            }

            else {
                $dados = $ctx->estabelecimentos->ler((int)$ctx->urlParams[4])[0];

                if($ctx->sessao->conexao()->nivelacesso == "admin"){
                    $vinculo = $_POST["vinculo"];
                    $ctx->regVarStrict("input-vinculo", $vinculo);
                    unset($_POST["vinculo"]);

                    foreach($ctx->sessao->listar_contas() as $idConta => $conta){
                        if($conta["nivelacesso"] == "gerente" && (string)$conta["vinculo"] == $idEstabelecimento){
                            $ctx->sessao->alterar_dado(array("vinculo" => "null"), (int)$idConta);
                        }
                    }

                    if((string)$vinculo !== "null"):
                        $ctx->sessao->alterar_dado(array("vinculo" => (string)$idEstabelecimento), (int)$vinculo);
                    endif;
                }

                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                    $dados[$chave] = $valor;
                }

                $ctx->estabelecimentos->escrever((int)$ctx->urlParams[4], $dados);
                $ctx->estabelecimentos->gravar();

                $ctx->regVarStrict("mensagem-aviso", '
                    swal("\n","Estabelecimento modificado com sucesso!","success");
                ');
            }

            if($erro !== -1){
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;{$erro}");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
            }
        }
    }
?>
