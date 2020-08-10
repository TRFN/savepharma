<?php
    function ctrl_estabelecimentos_listar($ctx){
        # MAIN FAKE

        $estabelecimentos = array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        );

        # END FAKE

        $dados = array();

        foreach($ctx->sessao->listar_contas() as $conta){
            $dado = array($conta["nome"],$conta["email"]);
            switch($conta["nivelacesso"]){
                case "admin": $dado[] = "Administrador"; break;
                case "gerente": $dado[] = "Gerente/Proprietário"; break;
                default: $dado[] = "Indefinido"; break;
            }
            $dado[] = '
                <a href="/painel/contas/gerir/u/'.$conta["id"].'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                    &nbsp;
                <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse usuário?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/contas/gerir/u/'.$conta["id"].'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
            ';

            $dados[] = $dado;
        }

        if($ctx->urlParams[3]=="apagado"){
            $ctx->regVar("mensagem-aviso", '
                swal("\n","O usuário foi apagado.","success");
                history.pushState(null, null, "/painel/contas/gerir/");
            ');
        }

        $ctx->regVar("tabela", "tContas");

        $ctx->regVar("painel-titulo", "Contas de usuário");
        $ctx->regVar("painel-icone", "users");

        $ctx->regVar("tabela", "tContas");
        $ctx->regVar("qtdRes", min(25,count($dados)));
        $ctx->regVar("dados", json_encode($dados));
        $ctx->regVar("titulos", json_encode(array(
            array("title"=>"Nome"),
            array("title"=>"Email"),
            array("title"=>"Tipo"),
            array("title"=>"Ações")
        )));

    }

?>
