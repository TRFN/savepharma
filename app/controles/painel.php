<?php
    function ctrl_painel($ctx){
        $sessao = new sessoes("contas-painel",true);
        if(!$sessao->conectado()){
            header("Location: /painel/login");
            exit();
        }
        $ctx->regVar("email-conectado", $sessao->conexao()->email);
    }
?>
