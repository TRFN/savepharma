<?php

    // class registro {
    //     private $db = null;
    //     public $debug = false;
    //
    //     function __construct($nome = null, $debug = true){
    //         if($nome !== null){
    //             $this->definir()
    //         }
    //     }
    //
    //     public function definir($nome){
    //         $this->db = new database($id,$debug?-1:md5($id));
    //     }
    // }

    function ctrl_painel($ctx){
        $ctx->sessao = new sessoes("contas-painel", $ctx->app->versao == "desenvolvimento");

        // $ctx->sessao->alterar_dado(array("vinculo"=>"null"));

        $ctx->estabelecimentos = new database("estabelecimentos",-1);

        if(!$ctx->sessao->conectado()){
            header("Location: /painel/login");
            exit();
        }

        $ctx->regVar("email-conectado", $ctx->sessao->conexao()->email);
        $ctx->regVar("meuid", $ctx->sessao->conexao()->id);
        $ctx->regVarPersistent("tipo-acesso", $ctx->sessao->conexao()->nivelacesso);

        $ctx->app->aviso_criar_estabelecimento = true &&  ( // DEBUG: Testes,sempre será False; Runtime, será True.
            $ctx->sessao->conexao()->nivelacesso == "gerente" &&
            (!isset($ctx->sessao->conexao()->vinculo) || $ctx->sessao->conexao()->vinculo == "null")
        );

        if($ctx->app->aviso_criar_estabelecimento && $ctx->urlParams[1] == "contas"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal({title:"\n",text:"Primeiro cadastre seu estabelecimento\nVocê será redirecionado.",type:"error",showCancelButton:false,confirmButtonClass:"btn-danger",confirmButtonText:"Entendido",closeOnConfirm:true},function(){window.top.location.href="/painel/estabelecimentos/adicionar"});
            ');
        }

        if($ctx->sessao->conexao()->nivelacesso == "gerente"){
            $ctx->regVar("id-estabelecimento", $ctx->app->aviso_criar_estabelecimento ? "adicionar":"gerir/id/{$ctx->sessao->conexao()->vinculo}");
        }
    }
?>
