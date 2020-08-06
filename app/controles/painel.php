<?php
    function ctrl_painel($ctx){
        $sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");

        if(!$sessao->conectado()){
            header("Location: /painel/login");
            exit();
        }

        // Parametros de personalização

        $ctx->regVar("titulo-logo", "SavePharma");
        $ctx->regVar("icone-logo", "hospital-o");

        // Gestão da conexão

        $tipo_acesso = "gerente";

        $ctx->regVar("email-conectado", $sessao->conexao()->email);
        $ctx->regVarPersistent("menu-carregamento", "%menu_{$tipo_acesso}%");
        $ctx->regVarPersistent("tipo-acesso",$tipo_acesso);
    }
?>
