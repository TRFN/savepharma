<?php
    function ctrl_regras_editar($ctx){

        $estabelecimentos = array("null" => "Não especificado");
        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento!=="0"):
                $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
            endif;
        }

        $qtdregra = array("Sem quantidade disponível", "1 unit. disponível");

        for($i = 2; $i < 100; $i++){
            $qtdregra[$i] = "{$i} units. disponíveis";
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
        $ctx->regVarStrict("input-textonotafiscal", "Abrir ou baixar <strong>nota fiscal</strong> atual");
        $ctx->regVarStrict("attr-validade", json_encode($attr["validade"]));
        $ctx->regVarStrict("input-prazo", "");
        $ctx->regVarStrict("attr-prazo", json_encode($attr["prazo"]));
        $ctx->regVarStrict("input-marca", "");
        $ctx->regVarStrict("input-quantidade", "1");
        $ctx->regVarStrict("input-vinculo", "null");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Modificar Produto");
        $ctx->regVarStrict("qtdregra", json_encode($qtdregra));
        $ctx->regVarStrict("estabelecimentos", json_encode($estabelecimentos));
        $ctx->regVarStrict("painel-titulo", "Dados do regra");
        $ctx->regVarStrict("painel-icone", "shopping-cart");

        $existe = false;

        if(isset($ctx->urlParams[4])):
            foreach($ctx->regras->ler() as $regraId=>$regra){
                if((int)$regraId == (int)$ctx->urlParams[4] && $regra !== "0"){
                    unset($regra["id"]);
                    if(!isset($regra["notafiscal"])){
                        $ctx->regVarStrict("input-textonotafiscal", "&nbsp;");
                    }
                    foreach($regra as $chave => $valor){
                        if(($chave == "prazo" || $chave == "validade") && !empty($valor) && strlen($valor)==10){
                            $d = explode("/", $valor);
                            $attr[$chave]["currentDate"] = "{$d[2]}-{$d[1]}-{$d[0]}";
                            $ctx->regVarStrict("attr-{$chave}", json_encode($attr[$chave]));
                        } else $ctx->regVarStrict("input-{$chave}",$valor);
                    }
                    $existe = true;
                    break;
                }
            }
        endif;

        if(!$existe){
            header("Location: /painel/regras/" . ($ctx->sessao->conexao()->nivelacesso == "admin"?"gerir":"adicionar"));
        }

        if(isset($ctx->urlParams[5]) && $ctx->urlParams[5]=="apagar"){
            $ctx->regras->escrever((int)$ctx->urlParams[4], "0");
            $ctx->regras->gravar();
            header("Location: /painel/regras/gerir/apagado");
        }

        if(isset($_POST["nome"])){
            foreach($_POST as $chave=>$valor){
                if(($chave == "prazo" || $chave == "validade") && !empty($valor) && strlen($valor)==10){
                    $d = explode("/", $valor);
                    $attr[$chave]["currentDate"] = "{$d[2]}-{$d[1]}-{$d[0]}";
                    $ctx->regVarStrict("attr-{$chave}", json_encode($attr[$chave]));
                } else $ctx->regVarStrict("input-{$chave}",$valor);
            }

            $erro = -1;
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
                $erro = "É obrigatório especificar qual estabelecimento está disponibiizando o regra.";
                $ctx->regVarStrict("input-error","#vinculo");
            }

            else {

                $regra = $ctx->regras->ler((string)$ctx->urlParams[4]);

                $vinculo = isset($_POST["vinculo"])?$_POST["vinculo"]:-1;
                unset($_POST["vinculo"]);

                foreach($_POST as $chave => $valor){
                    $regra[$chave] = $valor;
                }

                if((int)$vinculo != -1 && $ctx->sessao->conexao()->nivelacesso == "admin"):
                    $regra["vinculo"] = $vinculo;
                else:
                    $regra["vinculo"] = $ctx->sessao->conexao()->vinculo;
                endif;

                $nf_id = "{$regra["vinculo"]}/".sha1(uniqid());

                while($ctx->uploader->existe("nfs/{$nf_id}")){
                    $nf_id = "{$regra["vinculo"]}/".sha1(uniqid());
                }

                $ctx->uploader->ler("notafiscal");
                $ctx->uploader->ext(array("png","pdf","jpg","jpeg"));
                $ctx->uploader->id("nfs/{$nf_id}");

                if($ctx->uploader->valido()){

                    if(isset($regra["notafiscal"]) && $ctx->uploader->existe("nfs/{$regra["notafiscal"]}")){
                        unlink($ctx->uploader->dados("nfs/{$regra["notafiscal"]}")->arquivo);
                    }

                    $regra["notafiscal"] = $nf_id;
                    $ctx->regVarStrict("input-notafiscal", $nf_id);
                    $ctx->uploader->upload();

                }

                $ctx->regras->escrever((string)$ctx->urlParams[4], $regra);
                $ctx->regras->gravar();

                // print_r($regra);
                // exit;

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"O regra foi modificado!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true});
                ');
            }

            if($erro !== -1){
                $ctx->regVarStrict("mensagem-erro", "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;{$erro}");
                $ctx->regVarStrict("textosubmit", "<i class='fa fa-refresh'></i>&nbsp;Tentar Novamente");
                // $ctx->regVarStrict("mensagem-aviso", '
                //     swal("\n","'.$erro.'","error");
                // ');
            }
        }
    }
?>
