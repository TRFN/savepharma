<?php
    # INICIO DA APLICAÇÃO #

    require_once("controles/motor.php");
    $ctx = new motor(false);

    # INICIALIZAÇÃO DOS CONTROLES PRINCIPAIS #

    $ctx->partes(array(
        "database" => (new database("database")),
        "dinamizar" => (new dinamizar()),
        "ResizeImage" => (new resimg())
    ));

    # DEFINIÇÃO DAS PÁGINAS DE NAVEGAÇÃO #

    $ctx->adcpag("login");

    $ctx->adcpag("home");

    $ctx->adcpag("adicionar_perfil");
    $ctx->adcpag("editar_perfil");
    $ctx->adcpag("usuarios");

    $ctx->adcpag("adicionar_regra");
    $ctx->adcpag("editar_regra");
    $ctx->adcpag("gestaopontos");

    $ctx->adcpag("adicionar_medicamento");
    $ctx->adcpag("editar_medicamento");
    $ctx->adcpag("medicamentos");

    $ctx->adcpag("relatorios");

    # DEFINIÇÃO DE PARÂMETROS GLOBAIS DINÂMICOS

    $ctx->dinamizar->definir("head");
    $ctx->dinamizar->definir("footer");
    $ctx->dinamizar->definir("menu");
    $ctx->dinamizar->definir("remedios_vitrine");

    # FORMULARIOS

    $ctx->dinamizar->definir("f_perfil");
    $ctx->dinamizar->definir("f_regras");
    $ctx->dinamizar->definir("f_medicamentos");

    # CONTROLES ADICIONAIS #

    # $ctx->adcres("controle","otimizar-pagina",array("home","login"));
    $ctx->adcres("controle","processos");

    # RENDERIZAÇÃO DO MOTOR #

    $ctx->renderizar();
