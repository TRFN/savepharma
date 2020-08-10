<?php
    function ctrl_contas_adicionar($ctx){
        # MAIN FAKE

        $estabelecimentos = array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        );

        # END FAKE

        $ctx->regVar("input-nome","");
        $ctx->regVar("input-email","");
        $ctx->regVar("input-senha","");
        $ctx->regVar("input-senhaconf","");
        $ctx->regVar("input-nivelacesso","gerente");
        $ctx->regVar("input-vinculo",array_keys($estabelecimentos)[0]);
        $ctx->regVar("textosubmit", "<i class='fa fa-check'></i>&nbsp;Cadastrar");
        $ctx->regVar("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVar("mensagem-erro", "");
        $ctx->regVar("painel-titulo", "Dados da conta");
        $ctx->regVar("painel-icone", "user");

        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O nome é obrigatório.");
                $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                $ctx->regVar("input-error","#nome");
            }

            elseif(empty($dados->email)){
                $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O email é obrigatório.");
                $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                $ctx->regVar("input-error","#email");
            }

            elseif(empty($dados->senha)){
                $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;A senha é obrigatória.");
                $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                $ctx->regVar("input-error","#senha");
            }

            elseif($dados->senha !== $dados->senhaconf){
                $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;As senhas não conferem! Insira novamente a confirmação da senha.");
                $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                $ctx->regVar("input-senhaconf","");
                $ctx->regVar("input-error","#senhaconf");
            } else {
                unset($_POST["senhaconf"]);
                $ctx->sessao->criar_conta($_POST);
                $ctx->regVar("mensagem-aviso", '
                    swal("\n","O usuário foi adicionado com sucesso!","success");
                ');
            }
        }
    }
?>
