<?php function layout($ctx){ ?>
	<?php
        $u = $ctx->sessao->usuario();
        $contasnomes = array();
        $remedios = array();
        $nomesremedios = array();

        foreach( $ctx->sessao->listar() as $conta ){
            $contasnomes[(int)$conta["id"]] = $conta["nome"];
        }
        $estabelecimentos = array();
        foreach( $ctx->sessao->listar() as $conta ){
            if((int)$conta["tipo"] == 1){
                $estabelecimentos[] = (int)$conta["id"];
            }
        }
		// exit(print_r($estabelecimentos,true));
        foreach(todosremedios($estabelecimentos) as $grupo){
            foreach($grupo["data"] as $remedio){
                if(!isset($remedios[(int)$grupo["vinculo"]])){
					$remedios[(int)$grupo["vinculo"]] = [];
				}
				$remedios[(int)$grupo["vinculo"]][(int)$remedio["id"]] = count($nomesremedios);
				$nomesremedios[] = $remedio["nome"];
            }
        }
    ?>
    <?php if(!isset($_POST["ajax"])): ?>
        <!DOCTYPE html>
        <html class="no-js" lang="">
            <?php $ctx->dinamizar->inserir("head",$ctx); ?>
            <body class="contact-page">
                <?php $ctx->dinamizar->inserir("menu",$ctx); endif; ?>
                <section class="contact space paginas" id="pag_relatorios">
					<div class="row">
						<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 col-lg-8 col-lg-offset-2">
							<h2 style="color: #1178f7;">Relatório Resumido</h2>
							<?php function badge($cor, $icone, $style, $titulo, $classe, $txt="",$size="3"){ ?>
							<div style="padding: 16px;" class="col-md-4 col-sm-6 col-xs-12 col-lg-3 col-xl-3">
								<center>
									<fieldset style="min-height: 240px;border: 2px solid;color: <?=$cor;?>;border-radius: 8px;">
										<legend style="background-color: transparent;width: <?=(string)((int)$size*22);?>px;border: 0px solid;border-radius: 8px;color: <?=$cor;?>;">
											<i class="<?=$style;?> <?=$style;?>-<?=$icone;?> <?=$style;?>-<?=$size;?>x"></i>
										</legend>
										<h2 style="color: <?=$cor;?>; font-family: monospace;"><?=$titulo;?></h2>
										<div style="color: <?=$cor;?>; font-family: monospace; font-size: 28px;">
											<span id="<?=$classe;?>"><i class="la fa-spin la-refresh"></i></span><?=$txt;?>
										</div>
									</fieldset>
								</center>
							</div>
						<?php } ?>

						<?php badge("#22aa55", "plug",       "la", "Ganhou",    "ganhou", "&nbsp;pts<br /><br /><br />"); ?>
						<?php badge("#cc2211", "legal",      "la", "Gastou",    "perdeu", "&nbsp;pts<br /><br /><br />"); ?>
						<?php badge("#1178f7", "level-up",   "la", "Emprestou", "emprestou",    "<br /><small>Produto(s)</small><br /><br />"); ?>
						<?php badge("purple",  "level-down", "la", "Pegou",     "pegou",        "<br /><small>Produto(s)</small><br /><br />"); ?>

						</div>
					</div>
                    <div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
							<h2 style="color: #1178f7;">Transações</h2>
							<p>Nesta area, você pode visualizar as transações realizadas, bem como gerir os pedidos e visualizar o progresso.</p>
						</div>
                        <div style="padding-left: 0px;padding-right: 0px;" class="panel panel-primary col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-xl-6 col-xl-offset-3">
							<div class="panel-heading">
								&nbsp;
							</div>
							<div class="panel-body">
								<div>
									<div class="row align-items-center">
										<?php pesquisar_tabela(); ?>
										<div class="col-md-2 col-sm-8 col-xs-6"></div>
										<div class="col-md-4 col-sm-4 col-xs-6 text-center">
											<!--a class="btn btn-success text-center dinamizar" href="/adicionar_medicamento">
												<span>
													<i class="fa fa-plus"></i> Novo<span class="hidden-sm hidden-xs"> Produto</span>
												</span>
											</a-->
										</div>
									</div>
								</div>
								<br />
								<div>
									<div class="table-responsive">
										<table class="table table-hover table-borderless table-sm">
											<thead class="thead-dark">
												<tr>
													<th style="width: 100px;max-width: 20%;" class="hidden-xs">Estabelecimento</th>
													<th style="width: 100px;max-width: 20%;">Produto</th>
													<th style="width: 100px;max-width: 10%;" class="text-left">Pontos</th>
													<th style="width: 100px;max-width: 15%;" class="text-left hidden-xs">Operação</th>
													<th class="text-center" style="width: 100px;max-width: 35%;">Mudar Status</th>
												</tr>
											</thead>
											<tbody>
												<template><tr class="medicamento%id%">
													<td class="text-left hidden-xs" data-translate='{"origem":<?=json_encode($contasnomes);?>, "metodo":"vetor"}'>%origem%</td>
													<td class="text-left" data-translate='{"origem":<?=json_encode($nomesremedios);?>, "metodo":"vetor"}'>%medicamento%</td>
													<td class="text-left"><i class="fa fa-%dir%"></i>&nbsp;%valor% pts</td>
													<td class="text-left hidden-xs"><span><i class="fa fa-%dir% text-center" style="padding: 7px 8px; background-color: %cor%; border-radius: 100%; display: inline-block; color: #fff; margin: 4px;"></i>&nbsp;<span class="text-center" style="font-size: 12px; font-weight: bold;">%textostatus%</span></td>
													<td class="text-center">
														<form method="post">
															<input type="hidden" name="est1" value="%est1%" />
															<input type="hidden" name="est2" value="%est2%" />
															<input type="hidden" name="status" value="%status%" />

															<button type=submit style="padding: 4px 8px; font-size: 16px; margin: 0px 1vw;" class="btn btn-%button% btn-sm"><span style="font-size: 10px; font-weight: bold;" data-translate='{"origem":["Enviado","Recebido","Devolvida","Devolução","Excluir"], "metodo":"vetor"}'>%status%</span></button>
														</form>
                                                    </td>
												</tr></template>
												<td>&bull;&bull;&bull;</td>
												<td>&bull;&bull;&bull;</td>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="panel-footer">
								&nbsp;
							</div>
						</div>
					</div>
                </section>
				<script>
					window.idsremedios = <?=json_encode($remedios);?>;
				</script>
                <?php if(!isset($_POST["ajax"])): ?>
                <?php $ctx->dinamizar->inserir("footer",$ctx); ?>
            </body>
        </html>
    <?php endif; ?>
<?php } ?>
