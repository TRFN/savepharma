<?php
    function ctrl_estabelecimentos_listar($ctx){
        $dados = array();

        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento !== "0"){
                $dado = array($estabelecimento["nome"],$estabelecimento["telefone"]);
                $dado[] = '
                    <a href="/painel/estabelecimentos/gerir/id/'.$estabelecimentoId.'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                        &nbsp;
                    <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse estabelecimento?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/estabelecimentos/gerir/id/'.$estabelecimentoId.'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                ';

                $dados[] = $dado;
            }
        }

        if($ctx->urlParams[3]=="apagado"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal("\n","O estabelecimento foi apagado.","success");
                history.pushState(null, null, "/painel/estabelecimentos/gerir/");
            ');
        }

        $ctx->regVarStrict("tabela", "tEstabelecimentos");

        $ctx->regVarStrict("painel-titulo", "Lista de Estabelecimentos Cadastrados");
        $ctx->regVarStrict("painel-icone", "shopping-basket");

        $ctx->regVarStrict("qtdRes", min(25,count($dados)));
        $ctx->regVarStrict("dados", json_encode($dados));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"Nome"),
            array("title"=>"Telefone"),
            array("title"=>"Ações")
        )));

    }

?>
