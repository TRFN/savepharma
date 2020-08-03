<?php
    function ctrl_logout($ctx){
        $sessao = new sessoes("contas-painel",true);
        $sessao->logout();
        header("Location: /painel/login");
    }
?>
