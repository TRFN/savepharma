<?php
    function ctrl_login($ctx){
        $sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");

        // $sessao->criar_conta(array(
        //     "email" => "tulio.nasc95@gmail.com",
        //     "senha" => "12345"
        // ));

        if(!isset($_POST["email"])){
            $ctx->regVar("mensagem-erro","");
            $ctx->regVar("email","");
        } else {
            if(!$sessao->login($_POST)){
                $ctx->regVar("mensagem-erro",'<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Usuario e/ou senha incorreto(s). Tente novamente.');
                $ctx->regVar("email",$_POST["email"]);
            } else {
                header("Location: /painel/home/");
            }
        }
    }
?>
