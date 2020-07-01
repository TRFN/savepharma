<?php
    function remedios_vitrine($ctx){
        ?><div class="row" id="vitrine" style="min-height: 579px;">
            <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
                <h2 style="color: #1178f7;">Medicamentos Disponíveis</h2>
                <p>Visualize todos os medicamentos que você pode adquirir a partir dos seus pontos.</p>
                <br />
            </div>
            <div style="clear: both;"></div>
            <div class="col-md-6 col-sm-2 col-xs-1">&nbsp;</div>
            <div class="col-md-6 col-sm-8 col-xs-10 text-left">
                <div class="col-md-4 col-sm-12 col-xs-12 text-right" style="display: inline-block;vertical-align: super;float: none;">
                    <strong><i class="fa fa-search"></i>&nbsp;Pesquisar</strong>
                </div>
                <div style="display: inline-block;vertical-align: middle;float: none;" class="col-md-7 col-sm-12 col-xs-12">
                    <input class="form-control" style="outline: 0;border-radius: 0; border: 2px solid #1178f7;"
                    onkeyup='
                        var pesquisa = this.value.toLowerCase().split(/[^a-zà-úç0-9 ]/).join("").split(" ");
                        achoualgum = false;
                        $("#vitrine .remedio").each(function(){
                            if(pesquisa.length==0){
                                return $(this).show();
                            }
                            conteudo = (this.innerText.split(/[^A-zà-úç0-9 ]/).join("")).toLowerCase();
                            achou = true;
                            for( i in pesquisa ){
                                achou = (achou && conteudo.split(pesquisa[i]).length>1);
                            }
                            if(achou && !achoualgum){achoualgum=true;}
                            $(this)[achou?"show":"hide"]();
                        });
                        $("#vitrine .nao-encontrado")[achoualgum?"hide":"show"]();
                    ' placeholder="Insira algum termo de pesquisa" type="text" />
                </div>
            </div>
            <div style="clear: both;"></div>
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-4 col-lg-offset-4 nao-encontrado" style="display:none; font-size: 18px;">
                <br />
                <p style="color: #1178f7; font-weight: bolder;">Medicamento não localizado</p>
                <p>Desculpe, mas o medicamento solicitado não pôde ser encontrado. Tente pesquisar novamente utilizando outros termos.</p>
                <br />
            </div>
        <?php
        $estabelecimentos = array();
        foreach( $ctx->sessao->listar() as $conta ){
            if((int)$conta["tipo"] == 1 && (int)$conta["id"] != (int)$ctx->sessao->uid()){
                $estabelecimentos[] = (int)$conta["id"];
            }
        }
        foreach(todosremedios($estabelecimentos) as $grupo){
            if(isset($grupo["data"])){
                foreach($grupo["data"] as $remedio){
                    $valido = (
                        ($exp1=(int)difData(date("Y-m-d"),$remedio["validade"])>=30) &&
                        ($exp2=(int)difData(date("Y-m-d"),$remedio["prazo"]) > 0) &&
                        ($exp3=$remedio["ativo"]=="sim")
                    );
                    ?>
                    <!-- Remedio: <?=$remedio["nome"];?><br /> -->
                    <div class="col-md-3 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-8 col-xs-offset-2 remedio">
                        <div style="color: #000; border: 2px solid #1178f7; padding: 4px 12px; background-color: #eee; border-radius: 8px;">
                            <span style="font-size: 14px; font-weight: bold;  padding: 6px 24px;background-color: #1178f7; color: #fff;"><?=$remedio["nome"];?></span>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <?php
                                if(!$valido):
                                $borrar = 5;
                            ?><div style="-webkit-filter: blur(<?=$borrar;?>px);-moz-filter: blur(<?=$borrar;?>px);-o-filter: blur(<?=$borrar;?>px);-ms-filter: blur(<?=$borrar;?>px);filter: blur(<?=$borrar;?>px);"><?php endif; ?>
                            <p style="font-size: 12px;">
                                <small>
                                    Vence em: <?php $date = new DateTime($remedio["validade"]); echo $date->format('d/m/Y');?>
                                </small>
                            </p>
                            <p style="font-size: 14px;">
                                <small>
                                    Devolução até: <?php $date = new DateTime($remedio["prazo"]); echo $date->format('d/m/Y');?>
                                </small>
                            </p>
                            <p style="font-size: 14px;">
                                <small>
                                    <?php $preco = abs((int)$remedio["preco"]); ?>
                                    <?=(int)$remedio["preco"]<0?"Você pagará {$preco} pontos":"Você ganha {$preco} pontos";?>
                                </small>
                            </p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <?php
                                if($valido):
                            ?><a href="javascript:;" onclick='
                                    msgbox("Você confirma essa transação?","Você deseja prosseguir com a aquisição desta medicação? Confirmando, <?=$preco;?> pontos serão <?=(int)$remedio["preco"]<0?"debitados do":"adicionados ao";?> seu estabelecimento", "question", function(){processar_transacao("<?=base64_encode((string)$grupo["vinculo"]);?>-<?=base64_encode((string)$remedio["id"]);?>")});
                                ' class="btn btn-success">Adquirir</a>
                            <?php elseif(!$exp3): ?>
                            </div><a href="javascript:;" class="btn btn-default" disabled>Indisponível</a>
                                <p><small class="text-muted" style="font-size: 11px;">* <strong>Motivo</strong>: Medicamento desativado pelo estabelecimento.</small></p>
                            <?php elseif(!$exp1 && $exp2): ?>
                            </div><a href="javascript:;" class="btn btn-default" disabled>Indisponível</a>
                                <p><small class="text-muted" style="font-size: 11px;">* <strong>Motivo</strong>: Medicamento com menos de 1 mês para vencimento.</small></p>
                            <?php else: ?>
                                </div><a href="javascript:;" class="btn btn-default" disabled>Indisponível</a>
                                <p><small class="text-muted" style="font-size: 11px;">* <strong>Motivo</strong>: Medicamento com prazo de devolução excedido.</small></p>
                            <?php endif; ?>
                        </div>
                    </div>


                    <?php
                }
            }
        }
        ?></div><?php
    }
?>
