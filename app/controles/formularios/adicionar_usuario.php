<?php
    function ctrl_formularios_adicionar_usuario($ctx){
        $ctx->regVar("input-nome","");
        $ctx->regVar("input-email","");
        $ctx->regVar("input-senha","");
        $ctx->regVar("input-senhaconf","");
        $ctx->regVar("input-nivelacesso","gerente");
        $ctx->regVar("input-vinculo","rede");
        $ctx->regVar("textosubmit", "<i class='fa fa-check'></i>&nbsp;Cadastrar");
        $ctx->regVar("estabelecimentos", json_encode(array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        )));
    }
?>
