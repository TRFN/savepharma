<?php
    function ctrl_regras_listar($ctx){
        $dados = array();

        foreach($ctx->regras->ler() as $regraId=>$regra){
            if($regra != "0"){
                $dado = array(
                    $regra["nome"],
                    ((int)$regra["ativa"] == 1 ? "Está ativa" : "Desabilitada"),
                    ((int)$regra["acao1"] == 1 ? "Ganha" : "Perde") . " {$regra["dado1"]} <small>pts</small>",
                    ((int)$regra["acao2"] == 1 ? "Ganha" : "Perde") . " {$regra["dado2"]} <small>pts</small>"
                );

                $dado[] = '
                    <a href="/painel/regras/gerir/id/'.$regraId.'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                        &nbsp;
                    <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse regra?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/regras/gerir/id/'.$regraId.'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                ';

                $dados[] = $dado;
            }
        }

        if($ctx->urlParams[3]=="apagado"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal("\n","O regra foi apagado.","success");
                history.pushState(null, null, "/painel/regras/gerir/");
            ');
        }

        $ctx->regVarStrict("tabela", "tRegras");

        $ctx->regVarStrict("painel-titulo", "Conjunto de Regras dos Pontos");
        $ctx->regVarStrict("painel-icone", "gavel");

        $ctx->regVarStrict("qtdRes", min(25,count($dados)));
        $ctx->regVarStrict("dados", json_encode($dados));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"Nome"),
            array("title"=>"Status"),
            array("title"=>"Quem anunciou"),
            array("title"=>"Quem adquiriu"),
            array("title"=>"Ações")
        )));

    }

?>
