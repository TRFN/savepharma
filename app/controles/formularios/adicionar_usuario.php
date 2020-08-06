<?php
    function ctrl_formularios_adicionar_usuario($ctx){
        $estabelecimentos = array(
            "araujo" => "Araujo Farmacia",
            "rede" => "Rede Farmacia"
        );

        $ctx->regVar("input-nome","");
        $ctx->regVar("input-email","");
        $ctx->regVar("input-senha","");
        $ctx->regVar("input-senhaconf","");
        $ctx->regVar("input-nivelacesso","gerente");
        $ctx->regVar("input-vinculo",array_keys($estabelecimentos)[0]);
        $ctx->regVar("textosubmit", "<i class='fa fa-check'></i>&nbsp;Cadastrar");
        $ctx->regVar("estabelecimentos", json_encode($estabelecimentos));
    }
?>
