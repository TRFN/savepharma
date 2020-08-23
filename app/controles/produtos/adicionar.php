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

        $attr = array();

        $attr["validade"] = array(
            "minDate" => "{$ano}-{$mes}-".date("d")
        );

        $attr["prazo"] = array(
            "minDate" => "{$ano}-{$mes}-".date("d"),
            "maxDate" => (string)((int)$ano+1)."-{$mes}-".date("d")
        );

        $ctx->regVarStrict("input-nome", "");
        $ctx->regVarStrict("input-lote", "");
        $ctx->regVarStrict("input-validade", "");
        $ctx->regVarStrict("input-notafiscal", "");
        $ctx->regVarStrict("input-textonotafiscal", "&nbsp;");
        $ctx->regVarStrict("attr-validade", json_encode($attr["validade"]));
        $ctx->regVarStrict("input-prazo", "");
        $ctx->regVarStrict("attr-prazo", json_encode($attr["prazo"]));
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
                $erro = "A validade é obrigatória.";
                $ctx->regVarStrict("input-error","#validade");
            }

            elseif(empty($dados->marca)){
                $erro = "A marca é obrigatória.";
                $ctx->regVarStrict("input-error","#marca");
            }

            elseif(empty($dados->prazo)){
                $erro = "O Prazo para devolução é obrigatório.";
                $ctx->regVarStrict("input-error","#prazo");
            }

            elseif(isset($dados->vinculo)&&$dados->vinculo=="null"&&$ctx->sessao->conexao()->nivelacesso == "admin"){
                $erro = "É obrigatório especificar qual estabelecimento está disponibiizando o produto.";
                $ctx->regVarStrict("input-error","#vinculo");
            }

            else {
                $produto = array();
                $produto["id"] = (string)(count($ctx->produtos->ler()));

                $vinculo = isset($_POST["vinculo"])?$_POST["vinculo"]:-1;
                unset($_POST["vinculo"]);

                foreach($_POST as $chave => $valor){
                    $produto[$chave] = $valor;
                }

                if((int)$vinculo != -1 && $ctx->sessao->conexao()->nivelacesso == "admin"):
                    $produto["vinculo"] = $vinculo;
                else:
                    $produto["vinculo"] = $ctx->sessao->conexao()->vinculo;
                endif;

                $nf_id = "id_{$produto["vinculo"]}/".sha1(uniqid());

                while($ctx->uploader->existe("nfs/{$nf_id}")){
                    $nf_id = "id_{$produto["vinculo"]}/".sha1(uniqid());
                }



                $ctx->uploader->ler("notafiscal");
                $ctx->uploader->ext(array("png","pdf","jpg","jpeg"));
                $ctx->uploader->id("nfs/{$nf_id}");

                if($ctx->uploader->valido()){
                    $produto["notafiscal"] = $nf_id;
                    $ctx->regVarStrict("input-notafiscal", $nf_id);
                    $ctx->uploader->upload();

                }

                $ctx->produtos->escrever((string)$produto["id"], $produto);
                $ctx->produtos->gravar();

                // print_r($produto);
                // exit;

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"O produto foi cadastrado com sucesso!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true},function(){window.top.location.href="/painel/produtos/gerir/id/' . (string)$produto["id"] . '"});
                ');
            }

            if($erro !== -1){
                foreach($_POST as $chave=>$valor){
                    if(($chave == "prazo" || $chave == "validade") && !empty($valor) && strlen($valor)==10){
                        $d = explode("/", $valor);
                        $attr[$chave]["currentDate"] = "{$d[2]}-{$d[1]}-{$d[0]}";
                        $ctx->regVarStrict("attr-{$chave}", json_encode($attr[$chave]));
                    } else $ctx->regVarStrict("input-{$chave}",$valor);
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
