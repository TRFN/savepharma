<?php
    function ctrl_regras_adicionar($ctx){

        // $estabelecimentos = array("null" => "Não especificado");
        // foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
        //     if($estabelecimento!=="0"):
        //         $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
        //     endif;
        // }
        //
        // $qtdregra = array("Sem quantidade disponível", "1 unit. disponível");
        //
        // for($i = 2; $i < 100; $i++){
        //     $qtdregra[$i] = "{$i} units. disponíveis";
        // }
        //
        // $mes = (string)(($m=((int)date("m") + 1)>12?(int)date("m")-11:(int)date("m") + 1)<10?"0{$m}":"{$m}");
        // $ano = ((int)date("m") + 1) > 12 ? (string)((int)date("Y") + 1) : date("Y");
        //
        // $attr = array();
        //
        // $attr["validade"] = array(
        //     "minDate" => "{$ano}-{$mes}-".date("d")
        // );
        //
        // $attr["prazo"] = array(
        //     "minDate" => "{$ano}-{$mes}-".date("d"),
        //     "maxDate" => (string)((int)$ano+1)."-{$mes}-".date("d")
        // );

        $ctx->regVarStrict("input-nome", "");
        $ctx->regVarStrict("input-ativa", "0");
        $ctx->regVarStrict("input-condicao", "0");
        $ctx->regVarStrict("input-mes", "1");
        $ctx->regVarStrict("input-dia", "0");
        $ctx->regVarStrict("input-acao1", "1");
        $ctx->regVarStrict("input-dado1", "100");
        $ctx->regVarStrict("input-acao2", "0");
        $ctx->regVarStrict("input-dado2", "100");
        $ctx->regVarStrict("textosubmit", "<i class='fa fa-plus'></i>&nbsp;Criar Regra");

        $ctx->regVarStrict("painel-titulo", "Dados da nova regra");
        $ctx->regVarStrict("painel-icone", "gavel");

        // $ctx->regVarStrict("scriptextra","$('#validade').attr('min','{$_1_mes_a_frente}');");

        $erro = -1;

        if(isset($_POST["nome"])){
            $dados = (object)$_POST;

            if(empty($dados->nome)){
                $erro = "O nome é obrigatório.";
                $ctx->regVarStrict("input-error","#nome");
            } else {
                $regra = array();
                $regra["id"] = (string)(count($ctx->regras->ler()));

                foreach($_POST as $chave => $valor){
                    $regra[$chave] = $valor;
                }

                $ctx->regras->escrever((string)$regra["id"], $regra);
                $ctx->regras->gravar();

                // print_r($regra);
                // exit;

                $ctx->regVarStrict("mensagem-aviso", '
                    swal({title:"\n",text:"A regra foi criada com sucesso!",type:"success",showCancelButton:false,confirmButtonClass:"btn-primary",confirmButtonText:"OK",closeOnConfirm:true},function(){window.top.location.href="/painel/regras/gerir/id/' . (string)$regra["id"] . '"});
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
