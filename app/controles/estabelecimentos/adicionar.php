<?php
    function ctrl_estabelecimentos_adicionar($ctx){
        if($ctx->sessao->conexao()->nivelacesso == "farmaceutico"){
            header("Location: /painel/home/");
            exit;
        }

        if($ctx->sessao->conexao()->nivelacesso != "admin" && $ctx->sessao->conexao()->vinculo != "null"){
            header("Location: /painel/estabelecimentos/gerir/id/{$ctx->sessao->conexao()->vinculo}");
        }

        foreach($ctx->sessao->listar_contas() as $conta){
            if($conta["nivelacesso"] == "gerente"){
                $gerentes[$conta["id"]] = $conta["nome"];
            }
        }

        $gerentes["-1"] = "Não especificado (em branco)";

        $ctx->regVarStrict("input-nome","");
        $ctx->regVarStrict("input-email","");
        $ctx->regVarStrict("input-pontos","100");
        $ctx->regVarStrict("input-telefone","");
        $ctx->regVarStrict("input-cnpj","");
        $ctx->regVarStrict("input-endereco","");
        $ctx->regVarStrict("input-web","");
        $ctx->regVarStrict("input-vinculo", "-1");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-plus'></i>&nbsp;Criar Estabelecimento");
        $ctx->regVarStrict("gerentes", json_encode($gerentes));
        $ctx->regVarStrict("painel-titulo", "Dados do estabelecimento");
        $ctx->regVarStrict("painel-icone", "shopping-basket");

        $erro = -1;

        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $erro = "O nome é obrigatório.";
                $ctx->regVarStrict("input-error","#nome");
            }

            elseif(empty($dados->email)){
                $erro = "O email é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->telefone)){
                $erro = "O telefone é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->endereco)){
                $erro = "O endereço é obrigatório.";
                $ctx->regVarStrict("input-error","#email");
            }

            elseif(empty($dados->cnpj)){
                $erro = "O CNPJ é obrigatório.";
                $ctx->regVarStrict("input-error","#cnpj");
            }

            else {
                $estabelecimento = array();
                $estabelecimento["id"] = (string)(count($ctx->estabelecimentos->ler()));
                $estabelecimento["pontos"] = "100";
                $vinculo = isset($_POST["vinculo"])?$_POST["vinculo"]:-1;
                unset($_POST["vinculo"]);

                foreach($_POST as $chave => $valor){
                    $estabelecimento[$chave] = $valor;
                }

                $alteracoes = (array(
                    "vinculo" => (string)$ctx->estabelecimentos->escrever((string)$estabelecimento["id"], $estabelecimento)
                ));

                if((int)$vinculo != -1 && $ctx->sessao->conexao()->nivelacesso == "admin"):
                    $ctx->sessao->alterar_dado($alteracoes, (string)($vinculo));
                elseif($ctx->sessao->conexao()->nivelacesso == "gerente"):
                     $ctx->sessao->alterar_dado($alteracoes);
                endif;

                $ctx->estabelecimentos->gravar();

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"O estabelecimento foi cadastrado com sucesso!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true},function(){window.top.location.href="/painel/estabelecimentos/gerir/id/' . (string)$estabelecimento["id"] . '"});
                ');
            }

            if($erro !== -1){
                foreach($_POST as $chave=>$valor){
                    $ctx->regVarStrict("input-{$chave}",$valor);
                }
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;{$erro}");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                // $ctx->regVarStrict("mensagem-aviso", '
                //     swal("\n","'.$erro.'","error");
                // ');
            }
        }
    }
?>
