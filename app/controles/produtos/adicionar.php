<?php
    function ctrl_produtos_adicionar($ctx){

        $estabelecimentos = array("null" => "Não especificado");
        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento!=="0"):
                $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
            endif;
        }

        $qtdproduto = array("Sem quantidade disponível", "1 unit. disponível");

        for($i = 2; $i < 100; $i++){
            $qtdproduto[$i] = "{$i} units. disponíveis";
        }

        $mes = (string)(($m=((int)date("m") + 1)>12?(int)date("m")-11:(int)date("m") + 1)<10?"0{$m}":"{$m}");
        $ano = ((int)date("m") + 1) > 12 ? (string)((int)date("Y") + 1) : date("Y");

        $attr_validade = array(
            "minDate" => "{$ano}-{$mes}-".date("d")
        );

        $attr_prazo = array(
            "minDate" => "{$ano}-{$mes}-".date("d"),
            "maxDate" => (string)((int)$ano+1)."-{$mes}-".date("d")            
        );

        $ctx->regVarStrict("input-nome", "");
        $ctx->regVarStrict("input-lote", "");
        $ctx->regVarStrict("input-validade", "");
        $ctx->regVarStrict("attr-validade", json_encode($attr_validade));
        $ctx->regVarStrict("input-prazo", "");
        $ctx->regVarStrict("attr-prazo", json_encode($attr_prazo));
        $ctx->regVarStrict("input-marca", "");
        $ctx->regVarStrict("input-quantidade", "1");
        $ctx->regVarStrict("input-vinculo", "null");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-plus'></i>&nbsp;Cadastrar Produto");
        $ctx->regVarStrict("qtdproduto", json_encode($qtdproduto));
        $ctx->regVarStrict("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVarStrict("painel-titulo", "Dados do produto");
        $ctx->regVarStrict("painel-icone", "shopping-cart");

        // $ctx->regVarStrict("scriptextra","$('#validade').attr('min','{$_1_mes_a_frente}');");



        $erro = -1;

        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $erro = "O nome é obrigatório.";
                $ctx->regVarStrict("input-error","#nome");
            }

            elseif(empty($dados->lote)){
                $erro = "O lote é obrigatório.";
                $ctx->regVarStrict("input-error","#lote");
            }

            elseif(empty($dados->validade)){
                $erro = "A validade é obrigatório.";
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
                $produto = array();
                $produto["id"] = (string)(count($ctx->produtos->ler()));
                $produto["pontos"] = "100";
                $vinculo = isset($_POST["vinculo"])?$_POST["vinculo"]:-1;
                unset($_POST["vinculo"]);

                foreach($_POST as $chave => $valor){
                    $produto[$chave] = $valor;
                }

                $alteracoes = (array(
                    "vinculo" => (string)$ctx->produtos->escrever((string)$produto["id"], $produto)
                ));

                if((int)$vinculo != -1 && $ctx->sessao->conexao()->nivelacesso == "admin"):
                    $ctx->sessao->alterar_dado($alteracoes, (string)($vinculo));
                elseif($ctx->sessao->conexao()->nivelacesso == "gerente"):
                     $ctx->sessao->alterar_dado($alteracoes);
                endif;

                $ctx->produtos->gravar();

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"O produto foi cadastrado com sucesso!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true},function(){window.top.location.href="/painel/produtos/gerir/id/' . (string)$produto["id"] . '"});
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
