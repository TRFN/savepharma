<?php function layout($ctx){ ?>
	<?php $u = $ctx->sessao->usuario(); ?>
    <?php if(!isset($_POST["ajax"])): ?>
        <!DOCTYPE html>
        <html class="no-js" lang="">
            <?php $ctx->dinamizar->inserir("head",$ctx); ?>
            <body class="contact-page">
                <?php $ctx->dinamizar->inserir("menu",$ctx); endif; ?>
                <section class="contact space paginas" id="pag_gestaopontos">
                    <div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
							<h2 style="color: #1178f7;">Gestão de Pontos</h2>
							<p>Nesta página você pode selecionar uma regra, modificar ou criar uma nova. Tenha muita atenção a adição de regras, bem como na gestão do que for estabelecido. As regras interferem diretamente na pontuação dos pontos.</p>
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
											<a class="btn btn-success text-center dinamizar" href="/adicionar_regra">
												<span>
													<i class="fa fa-plus"></i> Nova<span class="hidden-sm hidden-xs"> Regra</span>
												</span>
											</a>
										</div>
									</div>
								</div>
								<br />
								<div>
									<div class="table-responsive">
										<table class="table table-hover table-borderless table-sm">
											<thead class="thead-dark">
												<tr>
													<th style="width: 100px;max-width: 50%;">Titulo</th>
													<th class="text-center hidden-xs hidden-sm" style="width: 100px;max-width: 25%;">Ativa</th>
													<th class="text-center" style="width: 150px;max-width: 25%;">Ações</th>
												</tr>
											</thead>
											<tbody>
												<template><tr class="regra%id%">
													<td class="text-left">%titulo%</td>
													<td class="text-center hidden-xs hidden-sm" data-translate='{"origem":"ativo", "metodo":"chave", "id":"%id%"}'>%ativo%</td>
													<td class="text-center">
														<a
														style="padding: 4px 8px; font-size: 16px; margin: 0px 1vw;"
														class="btn rounded btn-primary btn-sm dinamizar"
														href="/editar_regra/id:%id%" onclick="setTimeout(function(){
														_forms.set('editar_regra', '', {
															id:'%id%',
															ativo: '%ativo%',
															titulo: '%titulo%',
															meses: '%meses%',
															dias: '%dias%',
															prazo: '%prazo%',
															pontos1: '%pontos1%',
															pontos2: '%pontos2%',
															reacao1: '%reacao1%',
															reacao2: '%reacao2%'
														});},500);
														$('html, body').stop().animate({scrollTop:0}, 400, 'swing')" title='Editar Regra'><i class="la la-edit"></i></a>&nbsp;
													<a style="padding: 4px 8px; font-size: 16px; margin: 0px .7vw;" class="btn btn-danger rounded btn-sm" href="javascript:;" onclick="return msgbox('Confirme sua ação', 'Deseja mesmo apagar essa regra? Essa ação não pode ser desfeita.', 'error', function(){$.post(location.href, {apagar:'%id%'}, function(){$('#pag_gestaopontos .regra%id%').fadeOut().promise().done(function(){update(false);msgbox('A regra foi removida!');})})})&&false;" title="Apagar"><i class="la la-trash"></i></a></td>
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
