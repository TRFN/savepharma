<?php function layout($ctx){ ?>
	<?php $u = $ctx->sessao->usuario(); ?>
    <?php if(!isset($_POST["ajax"])): ?>
        <!DOCTYPE html>
        <html class="no-js" lang="">
            <?php $ctx->dinamizar->inserir("head",$ctx); ?>
            <body class="contact-page">
                <?php $ctx->dinamizar->inserir("menu",$ctx); endif; ?>
                <section class="contact space paginas" id="pag_medicamentos">
                    <div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 col-lg-6 col-lg-offset-3">
							<h2 style="color: #1178f7;">Medicamentos</h2>
							<p>Nesta página você pode selecionar um medicamento, modificar ou cadastrar um novo. Tenha muita atenção a adição de medicamentos, bem como na gestão do que for estabelecido.</p>
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
											<a class="btn btn-success text-center dinamizar" href="/adicionar_medicamento">
												<span>
													<i class="fa fa-plus"></i> Novo<span class="hidden-sm hidden-xs"> Medicamento</span>
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
													<th style="width: 100px;max-width: 20%;">Nome</th>
													<th style="width: 100px;max-width: 20%;" class="hidden-xs">Marca</th>
													<th style="width: 100px;max-width: 15%;" class="hidden-xs">Validade</th>
													<th style="width: 100px;max-width: 15%;" class="hidden-xs">Prazo</th>
													<th class="text-center hidden-xs hidden-sm" style="width: 100px;max-width: 15%;">Disponível</th>
													<th class="text-center" style="width: 150px;max-width: 20%;">Ações</th>
												</tr>
											</thead>
											<tbody>
												<template><tr class="medicamento%id%">
													<td class="text-left">%nome%</td>
													<td class="text-left hidden-xs">%marca%</td>
													<td class="text-left hidden-xs">%validade%</td>
													<td class="text-left hidden-xs">%prazo%</td>
													<!-- <td class="text-left hidden-xs" data-translate='{"origem":["Administrador", "Estabelecimento", "Farmaceutico"], "metodo":"vetor"}'>%tipo%</td> -->
													<td class="text-center hidden-xs hidden-sm" data-translate='{"origem":"ativo", "metodo":"chave", "id":"%id%"}'>%ativo%</td>
													<td class="text-center"><a style="padding: 4px 8px; font-size: 16px; margin: 0px 1vw;" class="btn rounded btn-primary btn-sm dinamizar" href="/editar_medicamento/id:%id%" onclick="_forms.set('editar_medicamento', '', {ativo: '%ativo%', nome: '%nome%', id: '%id%', marca:'%marca%', validade:'%validade%', prazo:'%prazo%' });$('html, body').stop().animate({scrollTop:0}, 400, 'swing')" title='Editar Medicamento'><i class="la la-edit"></i></a>&nbsp;
													<a style="padding: 4px 8px; font-size: 16px; margin: 0px .7vw;" class="btn btn-danger rounded btn-sm" href="javascript:;" onclick="return msgbox('Confirme sua ação', 'Deseja mesmo apagar essa medicamento? Essa ação não pode ser desfeita.', 'error', function(){$.post(location.href, {apagar:%id%}, function(){$('#pag_medicamentos .medicamento%id%').fadeOut().promise().done(function(){update(false);msgbox('O medicamento foi removido!');})})})&&false;" title="Apagar"><i class="la la-trash"></i></a></td>
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
