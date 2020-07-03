<?php function layout($ctx){ ?>
	<?php
        $u = $ctx->sessao->usuario();
        $contasnomes = array();
        $remedios = array();
        foreach( $ctx->sessao->listar() as $conta ){
            $contasnomes[(int)$conta["id"]] = $conta["nome"];
        }
        $estabelecimentos = array();
        foreach( $ctx->sessao->listar() as $conta ){
            if((int)$conta["tipo"] == 1){
                $estabelecimentos[] = (int)$conta["id"];
            }
        }
        foreach(todosremedios($estabelecimentos) as $grupo){
            foreach($grupo["data"] as $remedio){
                $remedios[(int)$remedio["id"]] = $remedio["nome"];
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
													<i class="fa fa-plus"></i> Novo<span class="hidden-sm hidden-xs"> Medicamento</span>
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
													<th style="width: 100px;max-width: 20%;" class="hidden-xs">Origem</th>
													<th style="width: 100px;max-width: 15%;">Medicamento</th>
													<th style="width: 100px;max-width: 15%;" class="hidden-xs">Valor</th>
													<th class="text-center" style="width: 100px;max-width: 15%;">Status</th>
												</tr>
											</thead>
											<tbody>
												<template><tr class="medicamento%id%">
													<td class="text-left" data-translate='{"origem":<?=json_encode($contasnomes);?>, "metodo":"vetor"}'>%origem%</td>
													<td class="text-left hidden-xs" data-translate='{"origem":<?=json_encode($remedios);?>, "metodo":"vetor"}'>%medicamento%</td>
													<td class="text-left hidden-xs">%valor%</td>
													<!-- <td class="text-left hidden-xs" data-translate='{"origem":["Administrador", "Estabelecimento", "Farmaceutico"], "metodo":"vetor"}'>%tipo%</td> -->
													<td class="text-center">
                                                        <a style="padding: 4px 8px; font-size: 16px; margin: 0px 1vw;" class="btn btn-primary btn-sm"><span style="font-size: 10px;" data-translate='{"origem":["Medicação Enviada","Recebi a medicação","Medicação Devolvida","Recebi a devolução"], "metodo":"vetor"}'>%status%</span></a>
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
                <?php if(!isset($_POST["ajax"])): ?>
                <?php $ctx->dinamizar->inserir("footer",$ctx); ?>
            </body>
        </html>
    <?php endif; ?>
<?php } ?>
