<?php
    function ctrl_estabelecimentos_editar($ctx){
        # MAIN FAKE

        $estabelecimentos = array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        );

        # END FAKE

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-senha","");
        $ctx->regVarStrict("input-senhaconf","");
        $ctx->regVarStrict("input-nivelacesso","gerente");
        $ctx->regVarStrict("input-vinculo",array_keys($estabelecimentos)[0]);
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Alterar Dados");
        $ctx->regVarStrict("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVarStrict("mensagem-erro", "");
        $ctx->regVarStrict("painel-titulo", "Dados da conta");
        $ctx->regVarStrict("painel-icone", "user");


        $existe = false;

        if(isset($ctx->urlParams[4])):
            foreach( $ctx->sessao->listar_contas() as $conta ){
                if((int)$conta["id"] == (int)$ctx->urlParams[4]){
                    unset($conta["id"]);
                    foreach($conta as $chave => $valor){
                        $ctx->regVarStrict("input-{$chave}",$valor);
                    }
                    $existe = true;
                    break;
                }
            }
        endif;

        if(!$existe){
            header("Location: /painel/contas/gerir");
        }
        if(isset($ctx->urlParams[5]) && $ctx->urlParams[5]=="apagar"){
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

                $ctx->sessao->alterar_dado($_POST, (string)$ctx->urlParams[4]);

                if(isset($_POST["senha"])): unset($_POST["senha"]); endif;

                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }

                $ctx->regVarStrict("mensagem-aviso", '
                    swal("\n","Conta modificada com sucesso!","success");
                    history.pushState(null, null, "/painel/contas/gerir/u/'.$ctx->urlParams[4].'/");
                ');
            }
        }
    }
?>
