<?php
    function ctrl_formularios_editar_usuario($ctx){
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
        $ctx->regVar("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Alterar Dados");
        $ctx->regVar("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVar("mensagem-erro", "");

        foreach( $ctx->sessao->listar_contas() as $conta ){
            if($conta["id"] == (int)$ctx->urlParams[4]){
                unset($conta["id"]);
                foreach($conta as $chave => $valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                break;
            }
        }

        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(!empty($dados->senha) && $dados->senha !== $dados->senhaconf){
                $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;As senhas não conferem! Insira novamente a confirmação da senha.");
                $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
                $ctx->regVar("input-senhaconf","");
                $ctx->regVar("input-error","#senhaconf");
            } else {
                if(empty($dados->senha)){
                    unset($_POST["senha"]);
                }
                unset($_POST["senhaconf"]);

                $ctx->sessao->alterar_dado($_POST, (string)$ctx->urlParams[4]);

                if(isset($_POST["senha"])): unset($_POST["senha"]); endif;

                foreach($_POST as $chave=>$valor){
                    $ctx->regVar("input-{$chave}",$valor);
                }
            }
        }
    }
?>
