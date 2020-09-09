<?php
    function ctrl_relatorios($ctx){

        // MODELO:
        //      "id" => ID Unica
        //      "produto" => Produto
        //      "regra" => Regra
        //      "por" => Quem Disponibilizou
        //      "para" => Quem Adquiriu
        //      "pontos1" => Pontos relativos a quem disponibilizou
        //      "pontos2" => Pontos relativos a quem adquiriu
        //      "quantidade" => Quantidade
        
        foreach($ctx->relatorios->ler() as $dadoID => $dado){
            // Leitura do relatório
        }
    }
?>
