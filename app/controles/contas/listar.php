<?php
    function ctrl_contas_listar($ctx){
        # MAIN FAKE

        $estabelecimentos = array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        );

        # END FAKE

        $dados = array();

        foreach($ctx->sessao->listar_contas() as $conta){
            if(
                (string)$conta["id"] != (string)$ctx->sessao->conexao()->id &&
                (
                    $ctx->sessao->conexao()->nivelacesso=="admin" ||
                    (
                        $ctx->sessao->conexao()->nivelacesso=="gerente" &&
                        $conta["nivelacesso"] == "farmaceutico" &&
                        isset($conta["vinculo"]) &&
                        (string)$conta["vinculo"] == (string)$ctx->sessao->conexao()->vinculo
                    )
                )
            ){
                $dado = array($conta["nome"],$conta["email"]);
                switch($conta["nivelacesso"]){
                    case "admin": $dado[] = "Administrador"; break;
                    case "gerente": $dado[] = "Gerente/Proprietário"; break;
                    case "farmaceutico": $dado[] = "Farmaceutico"; break;
                    default: $dado[] = $conta["nivelacesso"]; break;
                }
                $dado[] = '
                    <a href="/painel/contas/gerir/u/'.$conta["id"].'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                        &nbsp;
                    <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse usuário?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/contas/gerir/u/'.$conta["id"].'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                ';

                $dados[] = $dado;
            }
        }

        if($ctx->urlParams[3]=="apagado"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal("\n","O usuário foi apagado.","success");
                history.pushState(null, null, "/painel/contas/gerir/");
            ');
        }

        $ctx->regVarStrict("tabela", "tContas");

        $ctx->regVarStrict("painel-titulo", "Contas de usuário");
        $ctx->regVarStrict("painel-icone", "users");

        $ctx->regVarStrict("tabela", "tContas");
        $ctx->regVarStrict("qtdRes", min(25,count($dados)));
        $ctx->regVarStrict("dados", $ctx->app->aviso_criar_estabelecimento?"[]":json_encode($dados));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"Nome"),
            array("title"=>"Email"),
            array("title"=>"Tipo"),
            array("title"=>"Ações")
        )));

    }

?>
