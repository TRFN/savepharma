<?php
    function ctrl_contas_adicionar($ctx){
        # MAIN FAKE
        $estabelecimentos["null"] = "Não especificado";
        $estabelecimentos["araujo"] = "Araujo Farmacia";

        # END FAKE

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-senha","");
        $ctx->regVarStrict("input-senhaconf","");
        $ctx->regVarStrict("input-nivelacesso","gerente");
        $ctx->regVarStrict("input-vinculo", array_keys($estabelecimentos)[0]);
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-check'></i>&nbsp;Cadastrar");
        $ctx->regVarStrict("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVarStrict("mensagem-erro", "");
        $ctx->regVarStrict("painel-titulo", "Dados da conta");
        $ctx->regVarStrict("painel-icone", "user");




        if(isset($_POST["nome"]) && !$ctx->app->aviso_criar_estabelecimento){
            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O nome é obrigatório.");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("input-error","#nome");
            }

            elseif(empty($dados->email)){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O email é obrigatório.");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->senha)){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;A senha é obrigatória.");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("input-error","#senha");
            }

            elseif($dados->senha !== $dados->senhaconf){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;As senhas não conferem! Insira novamente a confirmação da senha.");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("input-senhaconf","");
                $ctx->regVarStrict("input-error","#senhaconf");
            } else {
                unset($_POST["senhaconf"]);
                $criar = $_POST;
                if($ctx->sessao->conexao()->nivelacesso == "farmaceutico"){
                    $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;Desculpe, mas você não possue permissão para criar contas.");
                } elseif($ctx->sessao->conexao()->nivelacesso == "gerente") {
                    $criar["nivelacesso"] = "farmaceutico";
                    $criar["vinculo"] = $ctx->sessao->conexao()->vinculo;
                }
                $ctx->sessao->criar_conta($criar);
                $ctx->regVarStrict("mensagem-aviso", '
                    swal("\n","O usuário foi adicionado com sucesso!","success");
                ');
            }
        }
    }
?>
