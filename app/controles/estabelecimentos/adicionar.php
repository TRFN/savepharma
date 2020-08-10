<?php
    function ctrl_estabelecimentos_adicionar($ctx){
        foreach($ctx->sessao->listar_contas() as $conta){
            if($conta["nivelacesso"] == "gerente"){
                $gerentes[$conta["id"]] = $conta["nome"];
            }
        }

        $gerentes["-1"] = "Não especificado (em branco)";

        $ctx->regVar("input-nome","");
        $ctx->regVar("input-email","");
        $ctx->regVar("input-pontos","100");
        $ctx->regVar("input-telefone","");
        $ctx->regVar("input-endereco","");
        $ctx->regVar("input-web","");
        $ctx->regVar("input-vinculo", "-1");
        $ctx->regVar("textosubmit", "<i class='fa fa-plus'></i>&nbsp;Criar Estabelecimento");
        $ctx->regVar("gerentes", json_encode($gerentes));
        $ctx->regVar("painel-titulo", "Dados do estabelecimento");
        $ctx->regVar("painel-icone", "shopping-basket");

        // if(isset($_POST["nome"])){
        //     $dados = (object)$_POST;
        //
        //     if(empty($dados->nome)){
        //         $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O nome é obrigatório.");
        //         $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
        //         foreach($_POST as $chave=>$valor){
        //             $ctx->regVar("input-{$chave}",$valor);
        //         }
        //         $ctx->regVar("input-error","#nome");
        //     }
        //
        //     elseif(empty($dados->email)){
        //         $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;O email é obrigatório.");
        //         $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
        //         foreach($_POST as $chave=>$valor){
        //             $ctx->regVar("input-{$chave}",$valor);
        //         }
        //         $ctx->regVar("input-error","#email");
        //     }
        //
        //     elseif(empty($dados->senha)){
        //         $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;A senha é obrigatória.");
        //         $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
        //         foreach($_POST as $chave=>$valor){
        //             $ctx->regVar("input-{$chave}",$valor);
        //         }
        //         $ctx->regVar("input-error","#senha");
        //     }
        //
        //     elseif($dados->senha !== $dados->senhaconf){
        //         $ctx->regVar("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;As senhas não conferem! Insira novamente a confirmação da senha.");
        //         $ctx->regVar("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
        //         foreach($_POST as $chave=>$valor){
        //             $ctx->regVar("input-{$chave}",$valor);
        //         }
        //         $ctx->regVar("input-senhaconf","");
        //         $ctx->regVar("input-error","#senhaconf");
        //     } else {
        //         unset($_POST["senhaconf"]);
        //         $ctx->sessao->criar_conta($_POST);
        //         $ctx->regVar("mensagem-aviso", '
        //             swal("\n","O usuário foi adicionado com sucesso!","success");
        //         ');
        //     }
        // }
    }
?>
