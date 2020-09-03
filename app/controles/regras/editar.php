<?php
    function ctrl_regras_editar($ctx){

        $ctx->regVarStrict("input-nome", "");
        $ctx->regVarStrict("input-ativa", "0");
        $ctx->regVarStrict("input-condicao", "0");
        $ctx->regVarStrict("input-mes", "1");
        $ctx->regVarStrict("input-dia", "0");
        $ctx->regVarStrict("input-acao1", "1");
        $ctx->regVarStrict("input-dado1", "100");
        $ctx->regVarStrict("input-acao2", "0");
        $ctx->regVarStrict("input-dado2", "100");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-floppy-o'></i>&nbsp;Modificar Regra");
        $ctx->regVarStrict("painel-titulo", "Dados da regra");
        $ctx->regVarStrict("painel-icone", "gavel");

        $existe = false;

        if(isset($ctx->urlParams[4])):
            foreach($ctx->regras->ler() as $regraId=>$regra){
                if((int)$regraId == (int)$ctx->urlParams[4] && $regra !== "0"){
                    unset($regra["id"]);
                    foreach($regra as $chave => $valor){
                        $ctx->regVarStrict("input-{$chave}",$valor);
                    }
                    $existe = true;
                    break;
                }
            }
        endif;

        if(!$existe){
            header("Location: /painel/regras/gerir");
        }

        if(isset($ctx->urlParams[5]) && $ctx->urlParams[5]=="apagar"){
            $ctx->regras->escrever((int)$ctx->urlParams[4], "0");
            $ctx->regras->gravar();
            header("Location: /painel/regras/gerir/apagado");
        }

        if(isset($_POST["nome"])){
            foreach($_POST as $chave=>$valor){
                $ctx->regVarStrict("input-{$chave}",$valor);
            }

            $erro = -1;
            $dados = (object)$_POST;

            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $erro = "Um título é obrigatório.";
                $ctx->regVarStrict("input-error","#nome");
            } else {
                $regra = array();
                $regra["id"] = (string)$regraId;

                foreach($_POST as $chave => $valor){
                    $regra[$chave] = $valor;
                }

                $ctx->regras->escrever((string)$regraId, $regra);
                $ctx->regras->gravar();

                // print_r($regra);
                // exit;

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"A regra foi modificada com exito!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true},function(){window.top.location.href="/painel/regras/gerir/id/' . (string)$regra["id"] . '"});
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
