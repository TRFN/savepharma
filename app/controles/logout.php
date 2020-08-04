<?php
    function ctrl_logout($ctx){
        $sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");
        $sessao->logout();
        header("Location: /painel/login");
    }
?>
