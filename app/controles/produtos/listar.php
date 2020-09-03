<?php
    function ctrl_produtos_listar($ctx){
        $dados = array();

        $estabelecimentos = array("null" => "Não especificado");
        foreach($ctx->estabelecimentos->ler() as $estabelecimentoId=>$estabelecimento){
            if($estabelecimento!=="0"):
                $estabelecimentos[(string)$estabelecimentoId] = $estabelecimento["nome"];
            endif;
        }

        foreach($ctx->produtos->ler() as $produtoId=>$produto){
            if($produto !== "0" && ($ctx->sessao->conexao()->nivelacesso == "admin" || (int)$produto["vinculo"] == $ctx->sessao->conexao()->vinculo)){
                $quantidade = (int)$produto["quantidade"];
                if($quantidade == 0):
                    $quantidade = "Nenhum / Indisponível";
                elseif($quantidade == 1):
                    $quantidade = "01 Unidade";
                else:
                    $quantidade = $quantidade < 10 ? "0{$quantidade} Unidades":"{$quantidade} Unidades";
                endif;
                $nf = "<a href='/painel/notasfiscais/{$produto["notafiscal"]}' class='btn btn-success btn-circle' target=_blank><i class='fa fa-file-o fa-fw'></i></a>
                    &nbsp;
                <a href='/painel/notasfiscais/{$produto["notafiscal"]}/download' class='btn btn-info btn-circle' target=_blank><i class='fa fa-download fa-fw'></i></a>";
                $dado = $ctx->sessao->conexao()->nivelacesso == "admin"
                    ? array($produto["nome"],$produto["validade"],$estabelecimentos[(String)$produto["vinculo"]],$nf)
                    : array($produto["nome"],$produto["validade"],$quantidade,$nf);
                $dado[] = '
                    <a href="/painel/produtos/gerir/id/'.$produtoId.'" class="btn btn-primary btn-circle"><i class="fa fa-pencil"></i></a>
                        &nbsp;
                    <a href="javascript:;" onclick=\'msg_confirmacao(["","Você tem certeza que deseja\napagar esse produto?\n\nEssa ação é irreversível.","warning","Sim, eu tenho","danger"],()=>{location.href="/painel/produtos/gerir/id/'.$produtoId.'/apagar/"})\' class="btn btn-danger btn-circle"><i class="fa fa-trash-o"></i></a>
                ';

                $dados[] = $dado;
            }
        }

        if($ctx->urlParams[3]=="apagado"){
            $ctx->regVarStrict("mensagem-aviso", '
                swal("\n","O produto foi apagado.","success");
                history.pushState(null, null, "/painel/produtos/gerir/");
            ');
        }

        $ctx->regVarStrict("tabela", "tProdutos");

        $ctx->regVarStrict("painel-titulo", "Lista de Produtos");
        $ctx->regVarStrict("painel-icone", "shopping-cart");

        $ctx->regVarStrict("qtdRes", min(25,count($dados)));
        $ctx->regVarStrict("dados", json_encode($dados));
        $ctx->regVarStrict("titulos", json_encode(array(
            array("title"=>"Nome"),
            array("title"=>"Validade"),
            array("title"=>($ctx->sessao->conexao()->nivelacesso == "admin"?"Disponibilizado por":"Quantidade")),
            array("title"=>"Nota Fiscal"),
            array("title"=>"Ações")
        )));

    }

?>
