<?php
    function ctrl_painel($ctx){
        $ctx->sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");

        if(!$ctx->sessao->conectado()){
            header("Location: /painel/login");
            exit();
        }

        // Parametros de personalização

        $ctx->regVar("titulo-logo", "SavePharma");
        $ctx->regVar("icone-logo", "hospital-o");

        // Gestão da conexão

        $ctx->regVar("input-error", "_none_");
        $ctx->regVar("debug", "");
        $ctx->regVar("email-conectado", $ctx->sessao->conexao()->email);
        $ctx->regVar("meuid", $ctx->sessao->conexao()->id);
        $ctx->regVarPersistent("menu-carregamento", "%menu_{$tipo_acesso}%");
        $ctx->regVarPersistent("tipo-acesso", $ctx->sessao->conexao()->nivelacesso);
    }
?>
