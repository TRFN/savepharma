<?php
    function ctrl_estabelecimentos_adicionar($ctx){
        foreach($ctx->sessao->listar_contas() as $conta){
            if($conta["nivelacesso"] == "gerente"){
                $gerentes[$conta["id"]] = $conta["nome"];
            }
        }

        $gerentes["-1"] = "Não especificado (em branco)";

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-pontos","100");
        $ctx->regVarStrict("input-telefone","");
        $ctx->regVarStrict("input-endereco","");
        $ctx->regVarStrict("input-web","");
        $ctx->regVarStrict("input-vinculo", "-1");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-plus'></i>&nbsp;Criar Estabelecimento");
        $ctx->regVarStrict("gerentes", json_encode($gerentes));
        $ctx->regVarStrict("painel-titulo", "Dados do estabelecimento");
        $ctx->regVarStrict("painel-icone", "shopping-basket");

        $erro = -1;

        if(isset($_POST["nome"])){
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

            else {
                // Ação de criar estabelecimento

                $ctx->regVarStrict("mensagem-aviso", '
                    swal("\n","O estabelecimento foi criado com sucesso!","success");
                ');
            }

            if($erro !== -1){
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;{$erro}");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                // $ctx->regVarStrict("mensagem-aviso", '
                //     swal("\n","'.$erro.'","error");
                // ');
            }
        }
    }
?>
