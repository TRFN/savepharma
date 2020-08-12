<?php
    function ctrl_painel($ctx){
        $ctx->sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");

        if(!$ctx->sessao->conectado()){
            header("Location: /painel/login");
            exit();
        }

        $ctx->regVar("email-conectado", $ctx->sessao->conexao()->email);
        $ctx->regVar("meuid", $ctx->sessao->conexao()->id);
        $ctx->regVarPersistent("tipo-acesso", $ctx->sessao->conexao()->nivelacesso);
    }
?>
