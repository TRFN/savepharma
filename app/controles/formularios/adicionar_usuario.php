<?php
    function ctrl_formularios_adicionar_usuario($ctx){
        $ctx->regVar("input-nome","");
        $ctx->regVar("input-email","");
        $ctx->regVar("input-senha","");
        $ctx->regVar("input-senhaconf","");
        $ctx->regVar("input-nivelacesso","gerente");
    }
?>
